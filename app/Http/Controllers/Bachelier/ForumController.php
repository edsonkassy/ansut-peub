<?php

namespace App\Http\Controllers\Bachelier;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumPost;
use App\Models\ForumReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ModerationService;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index()
    {
        $categories = ForumCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Threads épinglés
        $pinnedThreads = ForumThread::with(['user', 'category'])
            ->pinned()
            ->orderBy('last_activity_at', 'desc')
            ->limit(5)
            ->get();

        // Threads récents
        $recentThreads = ForumThread::with(['user', 'category'])
            ->notPinned()
            ->orderBy('last_activity_at', 'desc')
            ->limit(10)
            ->get();

        // Threads populaires
        $popularThreads = ForumThread::with(['user', 'category'])
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        // Statistiques
        $stats = [
            'total_threads' => ForumThread::count(),
            'total_posts' => ForumPost::count(),
            'active_users' => Auth::user()->bachelier()->count(),
            'online_users' => 1 // Simplified for now
        ];

        return view('bachelier.forum.index', compact(
            'categories', 
            'pinnedThreads', 
            'recentThreads', 
            'popularThreads', 
            'stats'
        ));
    }

    public function category(ForumCategory $category)
    {
        $threads = ForumThread::with(['user', 'category'])
            ->where('forum_category_id', $category->id)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('last_activity_at', 'desc')
            ->paginate(20);

        return view('bachelier.forum.category', compact('category', 'threads'));
    }

    // createThread et sa vue ont ete supprimes le 20/08/2026 : la methode
    // n avait aucune route et rendait une vue vide, donc une page blanche.
    // La creation de discussion se fait par la modale de forum/index, qui
    // poste sur storeThread ci-dessous.

    public function storeThread(Request $request)
    {
        $validated = $request->validate([
            'forum_category_id' => 'required|exists:forum_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10'
        ]);

        // Moderation rapide (gpt-5-nano)
        $moderation = app(ModerationService::class)->allowText($validated['title'] . "\n\n" . $validated['content']);
        if (!($moderation['allowed'] ?? true)) {
            $message = "Ce contenu viole notre politique d'utilisation.";
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'category' => $moderation['category'] ?? 'blocked'
                ], 422);
            }
            return back()->with('error', $message)->withInput();
        }

        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title'] . '-' . time());
        $validated['last_activity_at'] = now();

        $thread = ForumThread::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Discussion créée avec succès !',
                'redirect' => route('bachelier.forum.thread', $thread)
            ]);
        }

        return redirect()->route('bachelier.forum.thread', $thread)
            ->with('success', 'Discussion créée avec succès !');
    }

    public function thread(ForumThread $thread)
    {
        $thread->incrementViews();
        
        $thread->load(['user', 'category']);

        $posts = ForumPost::with(['user', 'replies.user'])
            ->where('forum_thread_id', $thread->id)
            ->whereNull('parent_id')
            ->orderBy('created_at')
            ->paginate(10);

        return view('bachelier.forum.thread', compact('thread', 'posts'));
    }

    public function storePost(Request $request, ForumThread $thread)
    {
        if ($thread->is_locked) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette discussion est verrouillée.'
                ], 422);
            }
            return back()->with('error', 'Cette discussion est verrouillée.');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:5',
            'parent_id' => 'nullable|exists:forum_posts,id'
        ]);

        // Moderation rapide (gpt-5-nano)
        $moderation = app(ModerationService::class)->allowText($validated['content']);
        if (!($moderation['allowed'] ?? true)) {
            $message = "Ce contenu viole notre politique d'utilisation.";
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'category' => $moderation['category'] ?? 'blocked'
                ], 422);
            }
            return back()->with('error', $message)->withInput();
        }

        $validated['forum_thread_id'] = $thread->id;
        $validated['user_id'] = Auth::id();

        $post = ForumPost::create($validated);

        // Mettre à jour les compteurs du thread
        $thread->incrementPosts();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Message publié avec succès !',
                'post' => $post->load('user')
            ]);
        }

        return back()->with('success', 'Message publié avec succès !');
    }

    public function editPost(ForumPost $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        return view('bachelier.forum.edit-post', compact('post'));
    }

    public function updatePost(Request $request, ForumPost $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $validated = $request->validate([
            'content' => 'required|string|min:5'
        ]);

        // Moderation rapide (gpt-5-nano)
        $moderation = app(ModerationService::class)->allowText($validated['content']);
        if (!($moderation['allowed'] ?? true)) {
            return redirect()->route('bachelier.forum.thread', $post->thread)
                ->with('error', "Ce contenu viole notre politique d'utilisation.");
        }

        $post->update([
            'content' => $validated['content'],
            'edited_at' => now(),
            'edited_by' => Auth::id()
        ]);

        return redirect()->route('bachelier.forum.thread', $post->thread)
            ->with('success', 'Message modifié avec succès !');
    }

    public function deletePost(ForumPost $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $threadSlug = $post->thread->slug;
        $post->delete();

        return redirect()->route('bachelier.forum.thread', $threadSlug)
            ->with('success', 'Message supprimé avec succès !');
    }

    public function members()
    {
        $bacheliers = \App\Models\Bachelier::with('user')
            ->whereHas('user')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('bachelier.forum.members', compact('bacheliers'));
    }

    public function toggleReaction(Request $request)
    {
        $validated = $request->validate([
            'reactable_type' => 'required|in:App\Models\ForumThread,App\Models\ForumPost',
            'reactable_id' => 'required|integer',
            'type' => 'required|in:like,love,wow,angry,sad'
        ]);

        $userId = Auth::id();
        
        // Vérifier si l'utilisateur a déjà réagi
        $existingReaction = ForumReaction::where([
            'user_id' => $userId,
            'reactable_type' => $validated['reactable_type'],
            'reactable_id' => $validated['reactable_id']
        ])->first();

        if ($existingReaction) {
            if ($existingReaction->type === $validated['type']) {
                // Supprimer la réaction si c'est la même
                $existingReaction->delete();
                $hasReaction = false;
            } else {
                // Changer le type de réaction
                $existingReaction->update(['type' => $validated['type']]);
                $hasReaction = true;
            }
        } else {
            // Créer une nouvelle réaction
            ForumReaction::create([
                'user_id' => $userId,
                'reactable_type' => $validated['reactable_type'],
                'reactable_id' => $validated['reactable_id'],
                'type' => $validated['type']
            ]);
            $hasReaction = true;
        }

        // Récupérer les nouveaux compteurs
        $reactionCounts = ForumReaction::where([
            'reactable_type' => $validated['reactable_type'],
            'reactable_id' => $validated['reactable_id']
        ])->selectRaw('type, count(*) as count')
        ->groupBy('type')
        ->pluck('count', 'type')
        ->toArray();

        return response()->json([
            'success' => true,
            'hasReaction' => $hasReaction,
            'reactionType' => $hasReaction ? $validated['type'] : null,
            'reactionCounts' => $reactionCounts
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category');

        if (empty($query)) {
            return redirect()->route('bachelier.forum.index');
        }

        $threads = ForumThread::with(['user', 'category'])
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->when($category, function($q) use ($category) {
                $q->where('forum_category_id', $category);
            })
            ->orderBy('last_activity_at', 'desc')
            ->paginate(15);

        $categories = ForumCategory::where('is_active', true)->get();

        return view('bachelier.forum.search', compact('threads', 'categories', 'query'));
    }

    public function favorites(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $sort = $request->get('sort', 'recent');

        $favoriteThreads = Auth::user()->favoriteThreads()
            ->with(['thread', 'thread.category', 'thread.user'])
            ->whereHas('thread', function($query) use ($search, $category) {
                if ($search) {
                    $query->where('title', 'like', '%' . $search . '%');
                }
                if ($category) {
                    $query->where('forum_category_id', $category);
                }
            })
            ->join('forum_threads', 'forum_favorites.forum_thread_id', '=', 'forum_threads.id');

        switch ($sort) {
            case 'popular':
                $favoriteThreads->orderBy('forum_threads.views_count', 'desc');
                break;
            case 'replies':
                $favoriteThreads->orderBy('forum_threads.posts_count', 'desc');
                break;
            case 'views':
                $favoriteThreads->orderBy('forum_threads.views_count', 'desc');
                break;
            default:
                $favoriteThreads->orderBy('forum_favorites.created_at', 'desc');
        }
        
        $favoriteThreads->select('forum_favorites.*');

        $favoriteThreads = $favoriteThreads->paginate(15);

        $categories = ForumCategory::where('is_active', true)->get();

        return view('bachelier.forum.favorites', compact('favoriteThreads', 'categories'));
    }

    public function toggleFavorite(ForumThread $thread)
    {
        $user = Auth::user();
        
        $favorite = $user->favoriteThreads()->where('forum_thread_id', $thread->id)->first();
        
        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
        } else {
            $user->favoriteThreads()->create(['forum_thread_id' => $thread->id]);
            $isFavorited = true;
        }

        return response()->json([
            'success' => true,
            'isFavorited' => $isFavorited
        ]);
    }
}