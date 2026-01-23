<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumPost;
use App\Models\ForumFavorite;
use App\Models\ForumReaction;

class ForumController extends Controller
{
    /**
     * Liste des catégories du forum
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories(Request $request)
    {
        $categories = ForumCategory::where('is_active', true)
            ->withCount(['threads' => function($q) {
                $q->where('is_published', true);
            }])
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    }

    /**
     * Liste des discussions (threads)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function threads(Request $request)
    {
        $query = ForumThread::where('is_published', true)
            ->with(['category', 'user.bachelier'])
            ->withCount('posts');

        // Filtres
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'popular') {
            $query->orderBy('views_count', 'desc');
        } elseif ($sortBy === 'active') {
            $query->orderBy('last_activity_at', 'desc');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $threads = $query->paginate($perPage);

        // Ajouter les informations utilisateur
        if ($request->user()) {
            $userId = $request->user()->id;
            $threads->getCollection()->transform(function ($thread) use ($userId) {
                $thread->is_favorited = ForumFavorite::where('user_id', $userId)
                    ->where('thread_id', $thread->id)
                    ->exists();
                return $thread;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $threads
        ], 200);
    }

    /**
     * Détails d'une discussion
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $thread = ForumThread::with([
            'category',
            'user.bachelier',
            'posts' => function($q) {
                $q->orderBy('created_at', 'asc')
                  ->with(['user.bachelier', 'reactions']);
            }
        ])
        ->withCount('posts')
        ->find($id);

        if (!$thread || !$thread->is_published) {
            return response()->json([
                'success' => false,
                'message' => 'Discussion non trouvée'
            ], 404);
        }

        // Incrémenter le compteur de vues
        $thread->increment('views_count');

        // Informations utilisateur
        $userInfo = [];
        if ($request->user()) {
            $userId = $request->user()->id;
            $userInfo = [
                'is_favorited' => ForumFavorite::where('user_id', $userId)
                    ->where('thread_id', $thread->id)
                    ->exists(),
                'user_reactions' => ForumReaction::where('user_id', $userId)
                    ->whereIn('post_id', $thread->posts->pluck('id'))
                    ->get()
                    ->keyBy('post_id')
            ];
        }

        return response()->json([
            'success' => true,
            'data' => array_merge(
                ['thread' => $thread],
                $userInfo
            )
        ], 200);
    }

    /**
     * Créer une nouvelle discussion
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeThread(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:forum_categories,id',
            'title' => 'required|string|min:10|max:255',
            'content' => 'required|string|min:50',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $thread = ForumThread::create([
            'category_id' => $request->category_id,
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'tags' => $request->tags ?? [],
            'is_published' => true,
            'last_activity_at' => now(),
        ]);

        $thread->load(['category', 'user.bachelier']);

        return response()->json([
            'success' => true,
            'message' => 'Discussion créée avec succès',
            'data' => $thread
        ], 201);
    }

    /**
     * Ajouter un post à une discussion
     * 
     * @param Request $request
     * @param int $threadId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePost(Request $request, $threadId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $thread = ForumThread::find($threadId);

        if (!$thread || !$thread->is_published) {
            return response()->json([
                'success' => false,
                'message' => 'Discussion non trouvée'
            ], 404);
        }

        $post = ForumPost::create([
            'thread_id' => $thread->id,
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        // Mettre à jour l'activité du thread
        $thread->update(['last_activity_at' => now()]);

        $post->load(['user.bachelier']);

        return response()->json([
            'success' => true,
            'message' => 'Réponse ajoutée avec succès',
            'data' => $post
        ], 201);
    }

    /**
     * Mettre à jour un post
     * 
     * @param Request $request
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePost(Request $request, $postId)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = ForumPost::find($postId);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post non trouvé'
            ], 404);
        }

        // Vérifier que l'utilisateur est l'auteur
        if ($post->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas la permission de modifier ce post'
            ], 403);
        }

        $post->update([
            'content' => $request->content,
            'is_edited' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post mis à jour avec succès',
            'data' => $post->fresh(['user.bachelier'])
        ], 200);
    }

    /**
     * Supprimer un post
     * 
     * @param Request $request
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePost(Request $request, $postId)
    {
        $post = ForumPost::find($postId);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post non trouvé'
            ], 404);
        }

        // Vérifier que l'utilisateur est l'auteur
        if ($post->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas la permission de supprimer ce post'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post supprimé avec succès'
        ], 200);
    }

    /**
     * Toggle favori sur une discussion
     * 
     * @param Request $request
     * @param int $threadId
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFavorite(Request $request, $threadId)
    {
        $thread = ForumThread::find($threadId);

        if (!$thread) {
            return response()->json([
                'success' => false,
                'message' => 'Discussion non trouvée'
            ], 404);
        }

        $favorite = ForumFavorite::where('user_id', $request->user()->id)
            ->where('thread_id', $thread->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Retiré des favoris';
            $is_favorited = false;
        } else {
            ForumFavorite::create([
                'user_id' => $request->user()->id,
                'thread_id' => $thread->id,
            ]);
            $message = 'Ajouté aux favoris';
            $is_favorited = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'is_favorited' => $is_favorited
            ]
        ], 200);
    }

    /**
     * Ajouter/retirer une réaction à un post
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleReaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:forum_posts,id',
            'type' => 'required|in:like,helpful,insightful',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $reaction = ForumReaction::where('user_id', $request->user()->id)
            ->where('post_id', $request->post_id)
            ->first();

        if ($reaction) {
            if ($reaction->type === $request->type) {
                // Retirer la réaction
                $reaction->delete();
                $message = 'Réaction retirée';
                $hasReaction = false;
            } else {
                // Changer le type de réaction
                $reaction->update(['type' => $request->type]);
                $message = 'Réaction modifiée';
                $hasReaction = true;
            }
        } else {
            ForumReaction::create([
                'user_id' => $request->user()->id,
                'post_id' => $request->post_id,
                'type' => $request->type,
            ]);
            $message = 'Réaction ajoutée';
            $hasReaction = true;
        }

        // Compter les réactions par type pour ce post
        $reactions = ForumReaction::where('post_id', $request->post_id)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'has_reaction' => $hasReaction,
                'reaction_type' => $hasReaction ? $request->type : null,
                'reactions' => $reactions
            ]
        ], 200);
    }

    /**
     * Liste des favoris de l'utilisateur
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function favorites(Request $request)
    {
        $favorites = ForumFavorite::where('user_id', $request->user()->id)
            ->with([
                'thread' => function($q) {
                    $q->where('is_published', true)
                      ->with(['category', 'user.bachelier'])
                      ->withCount('posts');
                }
            ])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $favorites
        ], 200);
    }
}








