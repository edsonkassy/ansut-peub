<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ArticleController extends Controller
{
    /**
     * Afficher la liste des articles
     */
    public function index(Request $request)
    {
        $query = Article::with('auteur');
        
        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }
        
        if ($request->filled('auteur')) {
            $query->where('auteur_id', $request->auteur);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('contenu', 'like', "%{$search}%")
                  ->orWhere('resume', 'like', "%{$search}%");
            });
        }
        
        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);
        
        $articles = $query->paginate(15);
        
        // Statistiques
        $stats = [
            'total' => Article::count(),
            'published' => Article::where('status', 'published')->count(),
            'draft' => Article::where('status', 'draft')->count(),
            'archived' => Article::where('status', 'archived')->count(),
            'featured' => Article::where('featured', true)->count(),
            'total_views' => Article::sum('vues'),
        ];
        
        // Données pour les filtres - Charger les relations pour obtenir les noms
        $auteurs = User::whereHas('articles')
            ->with(['bachelier', 'partenaire'])
            ->get(['id', 'role', 'email'])
            ->map(function ($user) {
                return (object) [
                    'id' => $user->id,
                    'name' => $user->getFullName()
                ];
            });
        $categories = Article::getCategories();
        $statuses = Article::getStatuses();
        
        return view('admin.articles.index', compact(
            'articles', 
            'stats', 
            'auteurs', 
            'categories', 
            'statuses'
        ));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $categories = Article::getCategories();
        $statuses = Article::getStatuses();
        $auteurs = User::where('role', 'admin')
            ->with(['bachelier', 'partenaire'])
            ->get(['id', 'role', 'email'])
            ->map(function ($user) {
                return (object) [
                    'id' => $user->id,
                    'name' => $user->getFullName()
                ];
            });
        
        return view('admin.articles.create', compact('categories', 'statuses', 'auteurs'));
    }
    
    /**
     * Enregistrer un nouvel article
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'resume' => 'nullable|string|max:500',
            'image_principale' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB
            'categorie' => 'required|string|in:' . implode(',', array_keys(Article::getCategories())),
            'tags' => 'nullable|string',
            'auteur_id' => 'required|exists:users,id',
            'status' => 'required|in:' . implode(',', array_keys(Article::getStatuses())),
            'date_publication' => 'nullable|date',
            'meta_description' => 'nullable|string|max:160',
            'temps_lecture' => 'nullable|integer|min:1',
            'featured' => 'boolean',
            'ordre_affichage' => 'nullable|integer|min:0'
        ]);
        
        // Traitement des tags
        if ($validated['tags']) {
            $validated['tags'] = array_filter(array_map('trim', explode(',', $validated['tags'])));
        }
        
        // Gestion de l'image principale
        if ($request->hasFile('image_principale')) {
            $validated['image_principale'] = $this->handleImageUpload($request->file('image_principale'));
        }
        
        // Auto-publication si status published sans date
        if ($validated['status'] === 'published' && !$validated['date_publication']) {
            $validated['date_publication'] = now();
        }
        
        $article = Article::create($validated);
        
        return redirect()->route('admin.articles.show', $article)
            ->with('success', 'Article créé avec succès.');
    }
    
    /**
     * Afficher les détails d'un article
     */
    public function show(Article $article)
    {
        $article->load('auteur');
        
        // Statistiques de l'article
        $stats = [
            'vues_totales' => $article->vues,
            'vues_aujourdhui' => 0, // À implémenter avec un système de stats détaillées
            'vues_cette_semaine' => 0,
            'vues_ce_mois' => 0,
        ];
        
        return view('admin.articles.show', compact('article', 'stats'));
    }
    
    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Article $article)
    {
        $categories = Article::getCategories();
        $statuses = Article::getStatuses();
        $auteurs = User::where('role', 'admin')
            ->with(['bachelier', 'partenaire'])
            ->get(['id', 'role', 'email'])
            ->map(function ($user) {
                return (object) [
                    'id' => $user->id,
                    'name' => $user->getFullName()
                ];
            });
        
        return view('admin.articles.edit', compact('article', 'categories', 'statuses', 'auteurs'));
    }
    
    /**
     * Mettre à jour un article
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'resume' => 'nullable|string|max:500',
            'image_principale' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'categorie' => 'required|string|in:' . implode(',', array_keys(Article::getCategories())),
            'tags' => 'nullable|string',
            'auteur_id' => 'required|exists:users,id',
            'status' => 'required|in:' . implode(',', array_keys(Article::getStatuses())),
            'date_publication' => 'nullable|date',
            'meta_description' => 'nullable|string|max:160',
            'temps_lecture' => 'nullable|integer|min:1',
            'featured' => 'boolean',
            'ordre_affichage' => 'nullable|integer|min:0'
        ]);
        
        // Traitement des tags
        if ($validated['tags']) {
            $validated['tags'] = array_filter(array_map('trim', explode(',', $validated['tags'])));
        }
        
        // Gestion de l'image principale
        if ($request->hasFile('image_principale')) {
            // Supprimer l'ancienne image
            if ($article->image_principale) {
                Storage::disk('public')->delete($article->image_principale);
            }
            $validated['image_principale'] = $this->handleImageUpload($request->file('image_principale'));
        }
        
        // Auto-publication si status published sans date
        if ($validated['status'] === 'published' && !$validated['date_publication'] && $article->status !== 'published') {
            $validated['date_publication'] = now();
        }
        
        $article->update($validated);
        
        return redirect()->route('admin.articles.show', $article)
            ->with('success', 'Article mis à jour avec succès.');
    }
    
    /**
     * Supprimer un article
     */
    public function destroy(Article $article)
    {
        // Supprimer l'image associée
        if ($article->image_principale) {
            Storage::disk('public')->delete($article->image_principale);
        }
        
        $article->delete();
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article supprimé avec succès.');
    }
    
    /**
     * Publier un article
     */
    public function publish(Article $article)
    {
        $article->update([
            'status' => 'published',
            'date_publication' => $article->date_publication ?: now()
        ]);
        
        return back()->with('success', 'Article publié avec succès.');
    }
    
    /**
     * Mettre en brouillon un article
     */
    public function unpublish(Article $article)
    {
        $article->update(['status' => 'draft']);
        
        return back()->with('success', 'Article mis en brouillon.');
    }
    
    /**
     * Basculer le statut featured
     */
    public function toggleFeatured(Article $article)
    {
        $article->update(['featured' => !$article->featured]);
        
        $message = $article->featured ? 'Article mis en avant.' : 'Article retiré de la mise en avant.';
        
        return back()->with('success', $message);
    }
    
    /**
     * Dupliquer un article
     */
    public function duplicate(Article $article)
    {
        $newArticle = $article->replicate();
        $newArticle->titre = $article->titre . ' (Copie)';
        $newArticle->slug = Str::slug($newArticle->titre);
        $newArticle->status = 'draft';
        $newArticle->date_publication = null;
        $newArticle->vues = 0;
        $newArticle->featured = false;
        $newArticle->save();
        
        return redirect()->route('admin.articles.edit', $newArticle)
            ->with('success', 'Article dupliqué avec succès.');
    }
    
    /**
     * Gestion du téléchargement d'image
     */
    private function handleImageUpload($file)
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = 'articles/' . $filename;
        
        // Redimensionner et optimiser l'image
        $image = Image::make($file);
        $image->resize(1200, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Sauvegarder l'image
        Storage::disk('public')->put($path, $image->encode());
        
        return $path;
    }
    
    /**
     * Analytics des articles
     */
    public function analytics()
    {
        $stats = [
            'articles_by_category' => Article::selectRaw('categorie, COUNT(*) as count')
                ->groupBy('categorie')
                ->pluck('count', 'categorie'),
            'articles_by_status' => Article::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status'),
            'most_viewed' => Article::orderBy('vues', 'desc')->limit(10)->get(),
            'recent_articles' => Article::recent()->limit(10)->get(),
            'articles_by_author' => Article::with('auteur')
                ->selectRaw('auteur_id, COUNT(*) as count')
                ->groupBy('auteur_id')
                ->havingRaw('COUNT(*) > 0')
                ->get()
        ];
        
        return view('admin.articles.analytics', compact('stats'));
    }
} 