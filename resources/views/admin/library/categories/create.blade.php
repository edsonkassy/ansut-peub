@extends('layouts.admin')

@section('title', 'Créer une Catégorie - Admin')

@section('page-title', 'Nouvelle Catégorie')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white border border-gray-300 p-6">
        <form action="{{ route('admin.library.categories.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la catégorie</label>
                    <input type="text" name="name" id="name" required 
                           class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror"
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icône (Lucide)</label>
                        <input type="text" name="icon" id="icon" placeholder="ex: book-open"
                               class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                               value="{{ old('icon') }}">
                        <p class="mt-1 text-xs text-gray-500">Nom de l'icône Lucide</p>
                    </div>

                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Couleur</label>
                        <input type="color" name="color" id="color" 
                               class="w-full h-10 border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                               value="{{ old('color', '#265BFF') }}">
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="mr-2 text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="text-sm font-medium text-gray-700">Catégorie active</span>
                    </label>
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t">
                    <a href="{{ route('admin.library.categories.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 transition rounded-md">
                        Annuler
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white hover:bg-primary-700 transition">
                        Créer la catégorie
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection