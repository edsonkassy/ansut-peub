<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\LibraryResource;
use App\Models\LibraryFavorite;
use App\Models\LibraryLike;
use App\Models\LibraryComment;
use App\Models\LibraryDownload;
use App\Models\LibraryCategory;

class LibraryController extends Controller
{
    /**
     * Liste des ressources de la bibliothèque
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = LibraryResource::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->with(['category']);

        // Filtres
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'published_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'popular') {
            $query->withCount('downloads')->orderBy('downloads_count', 'desc');
        } elseif ($sortBy === 'likes') {
            $query->withCount('likes')->orderBy('likes_count', 'desc');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Ajouter les compteurs
        $query->withCount(['likes', 'comments', 'downloads']);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $resources = $query->paginate($perPage);

        // Ajouter les informations utilisateur
        if ($request->user()) {
            $userId = $request->user()->id;
            $resources->getCollection()->transform(function ($resource) use ($userId) {
                $resource->is_favorited = LibraryFavorite::where('user_id', $userId)
                    ->where('resource_id', $resource->id)
                    ->exists();
                $resource->is_liked = LibraryLike::where('user_id', $userId)
                    ->where('resource_id', $resource->id)
                    ->exists();
                return $resource;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $resources
        ], 200);
    }

    /**
     * Détails d'une ressource
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $resource = LibraryResource::with([
            'category',
            'comments' => function($q) {
                $q->where('is_approved', true)
                  ->latest()
                  ->with('user.bachelier');
            }
        ])
        ->withCount(['likes', 'comments', 'downloads'])
        ->find($id);

        if (!$resource || !$resource->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ressource non trouvée'
            ], 404);
        }

        // Informations utilisateur
        $userInfo = [];
        if ($request->user()) {
            $userId = $request->user()->id;
            $userInfo = [
                'is_favorited' => LibraryFavorite::where('user_id', $userId)
                    ->where('resource_id', $resource->id)
                    ->exists(),
                'is_liked' => LibraryLike::where('user_id', $userId)
                    ->where('resource_id', $resource->id)
                    ->exists(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => array_merge(
                ['resource' => $resource],
                $userInfo
            )
        ], 200);
    }

    /**
     * Télécharger une ressource
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function download(Request $request, $id)
    {
        $resource = LibraryResource::find($id);

        if (!$resource || !$resource->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ressource non trouvée'
            ], 404);
        }

        if (!$resource->file_path) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier non disponible'
            ], 404);
        }

        // Enregistrer le téléchargement
        if ($request->user()) {
            LibraryDownload::create([
                'user_id' => $request->user()->id,
                'resource_id' => $resource->id,
            ]);
        }

        $filePath = storage_path('app/public/' . $resource->file_path);
        
        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier introuvable sur le serveur'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'download_url' => asset('storage/' . $resource->file_path),
                'filename' => basename($resource->file_path),
                'file_size' => $resource->file_size,
                'mime_type' => $resource->mime_type
            ]
        ], 200);
    }

    /**
     * Toggle favori
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFavorite(Request $request, $id)
    {
        $resource = LibraryResource::find($id);

        if (!$resource) {
            return response()->json([
                'success' => false,
                'message' => 'Ressource non trouvée'
            ], 404);
        }

        $favorite = LibraryFavorite::where('user_id', $request->user()->id)
            ->where('resource_id', $resource->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Retiré des favoris';
            $is_favorited = false;
        } else {
            LibraryFavorite::create([
                'user_id' => $request->user()->id,
                'resource_id' => $resource->id,
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
     * Toggle like
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleLike(Request $request, $id)
    {
        $resource = LibraryResource::find($id);

        if (!$resource) {
            return response()->json([
                'success' => false,
                'message' => 'Ressource non trouvée'
            ], 404);
        }

        $like = LibraryLike::where('user_id', $request->user()->id)
            ->where('resource_id', $resource->id)
            ->first();

        if ($like) {
            $like->delete();
            $message = 'Like retiré';
            $is_liked = false;
        } else {
            LibraryLike::create([
                'user_id' => $request->user()->id,
                'resource_id' => $resource->id,
            ]);
            $message = 'Ressource likée';
            $is_liked = true;
        }

        $likes_count = LibraryLike::where('resource_id', $resource->id)->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'is_liked' => $is_liked,
                'likes_count' => $likes_count
            ]
        ], 200);
    }

    /**
     * Ajouter un commentaire
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeComment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $resource = LibraryResource::find($id);

        if (!$resource) {
            return response()->json([
                'success' => false,
                'message' => 'Ressource non trouvée'
            ], 404);
        }

        $comment = LibraryComment::create([
            'user_id' => $request->user()->id,
            'resource_id' => $resource->id,
            'content' => $request->content,
            'is_approved' => false, // Nécessite une approbation
        ]);

        $comment->load('user.bachelier');

        return response()->json([
            'success' => true,
            'message' => 'Commentaire ajouté (en attente de modération)',
            'data' => $comment
        ], 201);
    }

    /**
     * Liste des favoris de l'utilisateur
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function favorites(Request $request)
    {
        $favorites = LibraryFavorite::where('user_id', $request->user()->id)
            ->with([
                'resource' => function($q) {
                    $q->where('is_active', true)
                      ->with('category')
                      ->withCount(['likes', 'comments', 'downloads']);
                }
            ])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $favorites
        ], 200);
    }

    /**
     * Liste des catégories
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories(Request $request)
    {
        $categories = LibraryCategory::where('is_active', true)
            ->withCount(['resources' => function($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    }
}








