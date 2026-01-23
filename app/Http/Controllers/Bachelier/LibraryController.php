<?php

namespace App\Http\Controllers\Bachelier;

use App\Http\Controllers\Controller;
use App\Models\LibraryCategory;
use App\Models\LibraryResource;
use App\Models\LibraryFavorite;
use App\Models\LibraryComment;
use App\Models\LibraryLike;
use App\Models\LibraryDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\ModerationService;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $query = LibraryResource::with(['category', 'user'])
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });

        // Filtres
        if ($request->filled('category')) {
            $query->where('library_category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhereJsonContains('tags', $search);
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Tri
        $sortBy = $request->get('sort', 'recent');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'downloads':
                $query->orderBy('downloads_count', 'desc');
                break;
            case 'featured':
                $query->where('is_featured', true)->orderBy('published_at', 'desc');
                break;
            default:
                $query->orderBy('published_at', 'desc');
        }

        $resources = $query->paginate(12);
        $categories = LibraryCategory::where('is_active', true)->withCount('resources')->get();

        // Ressources en vedette
        $featuredResources = LibraryResource::where('is_active', true)
            ->where('is_featured', true)
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->take(4)
            ->get();

        return view('bachelier.library.index', compact('resources', 'categories', 'featuredResources'));
    }

    public function show(LibraryResource $resource)
    {
        if (!$resource->is_active || ($resource->published_at && $resource->published_at > now())) {
            abort(404);
        }

        $resource->incrementViews();
        
        $resource->load(['category', 'user'])
            ->loadCount(['favorites', 'likes', 'downloads']);

        $comments = $resource->comments()
            ->where('is_approved', true)
            ->with(['user', 'replies' => function($query) {
                $query->where('is_approved', true)->with('user');
            }])
            ->latest()
            ->paginate(10);

        // Ressources similaires
        $relatedResources = LibraryResource::where('library_category_id', $resource->library_category_id)
            ->where('id', '!=', $resource->id)
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->take(4)
            ->get();

        $isFavorited = false;
        $isLiked = false;

        if (Auth::check()) {
            $isFavorited = $resource->isFavoritedBy(Auth::user());
            $isLiked = $resource->isLikedBy(Auth::user());
        }

        return view('bachelier.library.show', compact('resource', 'comments', 'relatedResources', 'isFavorited', 'isLiked'));
    }

    public function download(LibraryResource $resource)
    {
        if (!$resource->is_active || !$resource->file_path) {
            abort(404);
        }

        // Enregistrer le téléchargement
        LibraryDownload::create([
            'library_resource_id' => $resource->id,
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        $resource->incrementDownloads();

        $filePath = Storage::disk('public')->path($resource->file_path);
        $fileName = $resource->title . '.' . pathinfo($resource->file_path, PATHINFO_EXTENSION);

        return response()->download($filePath, $fileName);
    }

    public function toggleFavorite(LibraryResource $resource)
    {
        if (!$resource->is_active) {
            return response()->json(['error' => 'Resource not available'], 403);
        }

        $user = Auth::user();
        $favorite = LibraryFavorite::where('user_id', $user->id)
            ->where('library_resource_id', $resource->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
            $message = 'Retiré des favoris';
        } else {
            LibraryFavorite::create([
                'user_id' => $user->id,
                'library_resource_id' => $resource->id
            ]);
            $isFavorited = true;
            $message = 'Ajouté aux favoris';
        }

        return response()->json([
            'isFavorited' => $isFavorited,
            'message' => $message,
            'count' => $resource->favorites()->count()
        ]);
    }

    public function toggleLike(LibraryResource $resource)
    {
        if (!$resource->is_active) {
            return response()->json(['error' => 'Resource not available'], 403);
        }

        $user = Auth::user();
        $like = LibraryLike::where('user_id', $user->id)
            ->where('library_resource_id', $resource->id)
            ->whereNull('likeable_id')
            ->first();

        if ($like) {
            $like->delete();
            $isLiked = false;
            $message = 'Like retiré';
        } else {
            LibraryLike::create([
                'user_id' => $user->id,
                'library_resource_id' => $resource->id
            ]);
            $isLiked = true;
            $message = 'Ressource likée';
        }

        return response()->json([
            'isLiked' => $isLiked,
            'message' => $message,
            'count' => $resource->likes()->count()
        ]);
    }

    public function storeComment(Request $request, LibraryResource $resource)
    {
        if (!$resource->is_active) {
            return back()->with('error', 'Cette ressource n\'est pas disponible.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:library_comments,id'
        ]);

        // Moderation rapide (gpt-5-nano)
        $moderation = app(ModerationService::class)->allowText($validated['content']);
        if (!($moderation['allowed'] ?? true)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Ce contenu viole notre politique d'utilisation.",
                ], 422);
            }
            return back()->with('error', "Ce contenu viole notre politique d'utilisation.")->withInput();
        }

        $comment = LibraryComment::create([
            'library_resource_id' => $resource->id,
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
            'is_approved' => true // Auto-approuvé pour les bacheliers
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Commentaire ajouté avec succès.',
            ]);
        }

        return back()->with('success', 'Commentaire ajouté avec succès.');
    }

    public function toggleCommentLike(LibraryComment $comment)
    {
        $user = Auth::user();
        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $isLiked = false;
            $message = 'Like retiré';
        } else {
            $comment->likes()->create([
                'user_id' => $user->id,
                'library_resource_id' => $comment->library_resource_id
            ]);
            $isLiked = true;
            $message = 'Commentaire liké';
        }

        return response()->json([
            'isLiked' => $isLiked,
            'message' => $message,
            'count' => $comment->likes()->count()
        ]);
    }

    public function favorites()
    {
        $user = Auth::user();
        $favorites = LibraryFavorite::where('user_id', $user->id)
            ->with(['resource' => function($query) {
                $query->where('is_active', true)
                    ->with(['category', 'user']);
            }])
            ->latest()
            ->paginate(12);

        return view('bachelier.library.favorites', compact('favorites'));
    }
}