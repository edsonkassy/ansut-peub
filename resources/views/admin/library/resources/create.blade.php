@extends('layouts.admin')

@section('title', 'Ajouter une Ressource - Admin')

@section('page-title', 'Nouvelle Ressource')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white border border-gray-300 p-6">
        <form action="{{ route('admin.library.resources.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                        <input type="text" name="title" id="title" required 
                               class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('title') border-red-500 @enderror"
                               value="{{ old('title') }}">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="library_category_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                        <select name="library_category_id" id="library_category_id" required
                                class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('library_category_id') border-red-500 @enderror">
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('library_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('library_category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type" id="type" required
                                class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('type') border-red-500 @enderror">
                            <option value="">Sélectionner un type</option>
                            <option value="pdf" {{ old('type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Vidéo</option>
                            <option value="audio" {{ old('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                            <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Document</option>
                            <option value="presentation" {{ old('type') == 'presentation' ? 'selected' : '' }}>Présentation</option>
                            <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
                        <select name="level" id="level"
                                class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Tous les niveaux</option>
                            <option value="debutant" {{ old('level') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                            <option value="intermediaire" {{ old('level') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                            <option value="avance" {{ old('level') == 'avance' ? 'selected' : '' }}>Avancé</option>
                        </select>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Fichier de la ressource</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Fichier local</label>
                            <input type="file" name="file" id="file" 
                                   class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('file') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Max: 100 MB. Formats acceptés: PDF, vidéos, documents, etc.</p>
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">OU</span>
                        </div>

                        <div>
                            <label for="external_url" class="block text-sm font-medium text-gray-700 mb-1">URL externe</label>
                            <input type="url" name="external_url" id="external_url" placeholder="https://..."
                                   class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('external_url') border-red-500 @enderror"
                                   value="{{ old('external_url') }}">
                            <p class="mt-1 text-xs text-gray-500">Pour les vidéos YouTube, documents Google Drive, etc.</p>
                            @error('external_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Image de couverture</label>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500 @error('thumbnail') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Image pour la prévisualisation (Max: 2 MB)</p>
                        @error('thumbnail')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Auteur</label>
                        <input type="text" name="author" id="author" 
                               class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                               value="{{ old('author') }}" placeholder="Nom de l'auteur original">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                        <input type="text" name="tags" id="tags" 
                               class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                               value="{{ old('tags') }}" placeholder="tag1, tag2, tag3">
                        <p class="mt-1 text-xs text-gray-500">Séparez les tags par des virgules</p>
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Durée (pour vidéos/audio)</label>
                        <input type="text" name="duration" id="duration" 
                               class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                               value="{{ old('duration') }}" placeholder="Ex: 45 min">
                    </div>
                </div>

                <div class="flex gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="mr-2 text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="text-sm font-medium text-gray-700">Mettre en avant</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="mr-2 text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="text-sm font-medium text-gray-700">Ressource active</span>
                    </label>
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t">
                    <a href="{{ route('admin.library.resources.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 transition rounded-md">
                        Annuler
                    </a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white hover:bg-primary-700 transition">
                        Ajouter la ressource
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection