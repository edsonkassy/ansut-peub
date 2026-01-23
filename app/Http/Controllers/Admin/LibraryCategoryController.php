<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LibraryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LibraryCategoryController extends Controller
{
    public function index()
    {
        $categories = LibraryCategory::withCount('resources')
            ->orderBy('name')
            ->paginate(10);
            
        return view('admin.library.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.library.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        LibraryCategory::create($validated);

        return redirect()->route('admin.library.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(LibraryCategory $category)
    {
        return view('admin.library.categories.edit', compact('category'));
    }

    public function update(Request $request, LibraryCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.library.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(LibraryCategory $category)
    {
        if ($category->resources()->count() > 0) {
            return redirect()->route('admin.library.categories.index')
                ->with('error', 'Impossible de supprimer une catégorie contenant des ressources.');
        }

        $category->delete();

        return redirect()->route('admin.library.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}