<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Afficher la liste des actualités
     */
    public function index(Request $request)
    {
        $query = Article::published()->with('auteur');
        
        // Filtres par catégorie
        if ($request->filled('categorie')) {
            $query->byCategory($request->categorie);
        }
        
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('contenu', 'like', "%{$search}%")
                  ->orWhere('resume', 'like', "%{$search}%");
            });
        }
        
        // Tri
        $query->recent();
        
        // Articles à la une (featured) en premier
        $featuredArticles = Article::published()->featured()->recent()->limit(3)->get();
        
        // Articles principaux
        $articles = $query->paginate(12);
        
        // Catégories disponibles avec compteurs
        $categories = Article::getCategories();
        $categoriesCounts = Article::published()
            ->selectRaw('categorie, COUNT(*) as count')
            ->groupBy('categorie')
            ->pluck('count', 'categorie');
        
        return view('actualites', compact(
            'articles', 
            'featuredArticles', 
            'categories', 
            'categoriesCounts'
        ));
    }
    
    /**
     * Afficher un article spécifique
     */
    public function show(Article $article)
    {
        // Vérifier que l'article peut être affiché
        if (!$article->canBeViewed()) {
            abort(404);
        }
        
        // Incrémenter les vues
        $article->incrementViews();
        
        // Articles similaires (même catégorie, excluant l'article actuel)
        $articlesLies = Article::published()
            ->byCategory($article->categorie)
            ->where('id', '!=', $article->id)
            ->recent()
            ->limit(3)
            ->get();
        
        // Si pas assez d'articles dans la même catégorie, compléter avec d'autres articles
        if ($articlesLies->count() < 3) {
            $additionalArticles = Article::published()
                ->where('id', '!=', $article->id)
                ->whereNotIn('id', $articlesLies->pluck('id'))
                ->recent()
                ->limit(3 - $articlesLies->count())
                ->get();
            
            $articlesLies = $articlesLies->concat($additionalArticles);
        }
        
        return view('actualite', compact('article', 'articlesLies'));
    }
    
    /**
     * Afficher les articles par catégorie
     */
    public function category($categorie)
    {
        // Vérifier que la catégorie existe
        $categories = Article::getCategories();
        if (!array_key_exists($categorie, $categories)) {
            abort(404);
        }
        
        $query = Article::published()->byCategory($categorie)->with('auteur');
        
        // Articles de la catégorie
        $articles = $query->recent()->paginate(12);
        
        // Articles à la une de cette catégorie
        $featuredArticles = Article::published()
            ->byCategory($categorie)
            ->featured()
            ->recent()
            ->limit(3)
            ->get();
        
        $categoryName = $categories[$categorie];
        
        return view('actualites-categorie', compact(
            'articles', 
            'featuredArticles', 
            'categorie', 
            'categoryName',
            'categories'
        ));
    }
    
    /**
     * Recherche d'articles
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);
        
        $query = $request->q;
        
        $articles = Article::published()
            ->with('auteur')
            ->where(function($q) use ($query) {
                $q->where('titre', 'like', "%{$query}%")
                  ->orWhere('contenu', 'like', "%{$query}%")
                  ->orWhere('resume', 'like', "%{$query}%");
            })
            ->recent()
            ->paginate(12);
        
        return view('actualites-recherche', compact('articles', 'query'));
    }
    
    /**
     * Articles par tag
     */
    public function tag($tag)
    {
        $articles = Article::published()
            ->with('auteur')
            ->whereJsonContains('tags', $tag)
            ->recent()
            ->paginate(12);
        
        return view('actualites-tag', compact('articles', 'tag'));
    }
    
    /**
     * Flux RSS des articles
     */
    public function rss()
    {
        $articles = Article::published()
            ->with('auteur')
            ->recent()
            ->limit(20)
            ->get();
        
        return response()
            ->view('rss.articles', compact('articles'))
            ->header('Content-Type', 'application/rss+xml');
    }
    
    /**
     * API pour obtenir les articles populaires
     */
    public function popular()
    {
        $articles = Article::published()
            ->with('auteur')
            ->popular()
            ->limit(5)
            ->get();
        
        return response()->json([
            'success' => true,
            'articles' => $articles->map(function ($article) {
                return [
                    'id' => $article->id,
                    'titre' => $article->titre,
                    'slug' => $article->slug,
                    'resume' => $article->excerpt,
                    'image_principale' => $article->image_principale,
                    'categorie' => $article->categorie,
                    'auteur' => $article->auteur->name,
                    'date_publication' => $article->date_publication,
                    'vues' => $article->vues,
                    'temps_lecture' => $article->reading_time,
                    'url' => route('actualite', $article->slug)
                ];
            })
        ]);
    }
} 