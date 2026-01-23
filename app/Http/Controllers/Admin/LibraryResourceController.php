<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LibraryCategory;
use App\Models\LibraryResource;
use App\Models\LibraryComment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LibraryResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = LibraryResource::with(['category', 'user'])
            ->withCount(['favorites', 'comments', 'likes', 'downloads']);

        if ($request->filled('category')) {
            $query->where('library_category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $resources = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = LibraryCategory::where('is_active', true)->get();

        return view('admin.library.resources.index', compact('resources', 'categories'));
    }

    public function create()
    {
        $categories = LibraryCategory::where('is_active', true)->get();
        return view('admin.library.resources.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'library_category_id' => 'required|exists:library_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:pdf,video,audio,document,presentation,image,other',
            'file' => 'required_without:external_url|file|max:102400',
            'external_url' => 'required_without:file|url',
            'thumbnail' => 'nullable|image|max:2048',
            'author' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'level' => 'nullable|in:debutant,intermediaire,avance',
            'language' => 'nullable|string|max:10',
            'duration' => 'nullable|string|max:20',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date'
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('library/resources/' . date('Y/m'), 'public');
            $validated['file_path'] = $path;
            $validated['file_size'] = $file->getSize();
            $validated['mime_type'] = $file->getMimeType();
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailPath = $thumbnail->store('library/thumbnails/' . date('Y/m'), 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }

        if (!$request->filled('published_at')) {
            $validated['published_at'] = now();
        }

        LibraryResource::create($validated);

        return redirect()->route('admin.library.resources.index')
            ->with('success', 'Ressource ajoutée avec succès.');
    }

    public function show(LibraryResource $resource)
    {
        $resource->load(['category', 'user', 'comments.user', 'comments.replies'])
            ->loadCount(['favorites', 'likes', 'downloads']);
            
        $comments = $resource->comments()
            ->with(['user', 'replies.user'])
            ->latest()
            ->paginate(10);

        return view('admin.library.resources.show', compact('resource', 'comments'));
    }

    public function edit(LibraryResource $resource)
    {
        $categories = LibraryCategory::where('is_active', true)->get();
        return view('admin.library.resources.edit', compact('resource', 'categories'));
    }

    public function update(Request $request, LibraryResource $resource)
    {
        $validated = $request->validate([
            'library_category_id' => 'required|exists:library_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:pdf,video,audio,document,presentation,image,other',
            'file' => 'nullable|file|max:102400',
            'external_url' => 'nullable|url',
            'thumbnail' => 'nullable|image|max:2048',
            'author' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'level' => 'nullable|in:debutant,intermediaire,avance',
            'language' => 'nullable|string|max:10',
            'duration' => 'nullable|string|max:20',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date'
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('file')) {
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }
            $file = $request->file('file');
            $path = $file->store('library/resources/' . date('Y/m'), 'public');
            $validated['file_path'] = $path;
            $validated['file_size'] = $file->getSize();
            $validated['mime_type'] = $file->getMimeType();
        }

        if ($request->hasFile('thumbnail')) {
            if ($resource->thumbnail) {
                Storage::disk('public')->delete($resource->thumbnail);
            }
            $thumbnail = $request->file('thumbnail');
            $thumbnailPath = $thumbnail->store('library/thumbnails/' . date('Y/m'), 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }

        $resource->update($validated);

        return redirect()->route('admin.library.resources.index')
            ->with('success', 'Ressource mise à jour avec succès.');
    }

    public function destroy(LibraryResource $resource)
    {
        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }
        if ($resource->thumbnail) {
            Storage::disk('public')->delete($resource->thumbnail);
        }

        $resource->delete();

        return redirect()->route('admin.library.resources.index')
            ->with('success', 'Ressource supprimée avec succès.');
    }

    public function toggleComment(LibraryComment $comment)
    {
        $comment->is_approved = !$comment->is_approved;
        $comment->save();

        return back()->with('success', 'Statut du commentaire modifié.');
    }

    public function deleteComment(LibraryComment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Commentaire supprimé.');
    }
}