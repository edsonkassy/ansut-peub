@extends('layouts.admin')

@section('title', 'Créer un Article')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm ">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.articles.index') }}" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Créer un Article</h1>
                        <p class="text-sm text-gray-600">Rédigez un nouvel article pour le blog PEUB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="px-4 sm:px-6 lg:px-8 py-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations de base -->
                <div class="bg-white shadow border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h3 class="text-lg font-medium text-gray-900">Informations de base</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre de l'article <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="titre" 
                                   id="titre"
                                   value="{{ old('titre') }}"
                                   class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('titre') border-red-500 @enderror"
                                   placeholder="Entrez le titre de l'article..."
                                   required>
                            @error('titre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="resume" class="block text-sm font-medium text-gray-700 mb-2">
                                Résumé (optionnel)
                            </label>
                            <textarea name="resume" 
                                      id="resume"
                                      rows="3"
                                      class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('resume') border-red-500 @enderror"
                                      placeholder="Résumé court de l'article...">{{ old('resume') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maximum 500 caractères. Sera utilisé comme excerpt si fourni.</p>
                            @error('resume')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contenu" class="block text-sm font-medium text-gray-700 mb-2">
                                Contenu de l'article <span class="text-red-500">*</span>
                            </label>
                            <textarea name="contenu" 
                                      id="contenu"
                                      rows="20"
                                      class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('contenu') border-red-500 @enderror"
                                      placeholder="Rédigez le contenu de votre article..."
                                      required>{{ old('contenu') }}</textarea>
                            @error('contenu')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Image principale -->
                <div class="bg-white shadow border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h3 class="text-lg font-medium text-gray-900">Image principale</h3>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="image_principale" class="block text-sm font-medium text-gray-700 mb-2">
                                Télécharger une image
                            </label>
                            <input type="file" 
                                   name="image_principale" 
                                   id="image_principale"
                                   accept="image/*"
                                   class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('image_principale') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Formats acceptés: JPG, PNG, WebP. Taille max: 5MB</p>
                            @error('image_principale')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO et méta-données -->
                <div class="bg-white shadow border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h3 class="text-lg font-medium text-gray-900">SEO et méta-données</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta description
                            </label>
                            <textarea name="meta_description" 
                                      id="meta_description"
                                      rows="2"
                                      class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('meta_description') border-red-500 @enderror"
                                      placeholder="Description pour les moteurs de recherche...">{{ old('meta_description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maximum 160 caractères recommandés pour le SEO.</p>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                                Tags
                            </label>
                            <input type="text" 
                                   name="tags" 
                                   id="tags"
                                   value="{{ old('tags') }}"
                                   class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('tags') border-red-500 @enderror"
                                   placeholder="tag1, tag2, tag3...">
                            <p class="mt-1 text-sm text-gray-500">Séparez les tags par des virgules.</p>
                            @error('tags')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="temps_lecture" class="block text-sm font-medium text-gray-700 mb-2">
                                Temps de lecture (minutes)
                            </label>
                            <input type="number" 
                                   name="temps_lecture" 
                                   id="temps_lecture"
                                   min="1"
                                   value="{{ old('temps_lecture') }}"
                                   class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('temps_lecture') border-red-500 @enderror"
                                   placeholder="Laisser vide pour calcul automatique">
                            <p class="mt-1 text-sm text-gray-500">Si non spécifié, sera calculé automatiquement.</p>
                            @error('temps_lecture')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publication -->
                <div class="bg-white shadow border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h3 class="text-lg font-medium text-gray-900">Publication</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Statut <span class="text-red-500">*</span>
                            </label>
                            <select name="status" 
                                    id="status" 
                                    class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('status') border-red-500 @enderror"
                                    required>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', 'draft') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_publication" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de publication
                            </label>
                            <input type="datetime-local" 
                                   name="date_publication" 
                                   id="date_publication"
                                   value="{{ old('date_publication') }}"
                                   class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('date_publication') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Laisser vide pour publication immédiate si statut "Publié".</p>
                            @error('date_publication')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="auteur_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Auteur <span class="text-red-500">*</span>
                            </label>
                            <select name="auteur_id" 
                                    id="auteur_id" 
                                    class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('auteur_id') border-red-500 @enderror"
                                    required>
                                <option value="">Sélectionner un auteur</option>
                                @foreach($auteurs as $auteur)
                                    <option value="{{ $auteur->id }}" {{ old('auteur_id', auth()->id()) == $auteur->id ? 'selected' : '' }}>
                                        {{ $auteur->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('auteur_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Catégorie et options -->
                <div class="bg-white shadow border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h3 class="text-lg font-medium text-gray-900">Catégorie et options</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="categorie" class="block text-sm font-medium text-gray-700 mb-2">
                                Catégorie <span class="text-red-500">*</span>
                            </label>
                            <select name="categorie" 
                                    id="categorie" 
                                    class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('categorie') border-red-500 @enderror"
                                    required>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('categorie', 'actualite') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categorie')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ordre_affichage" class="block text-sm font-medium text-gray-700 mb-2">
                                Ordre d'affichage
                            </label>
                            <input type="number" 
                                   name="ordre_affichage" 
                                   id="ordre_affichage"
                                   min="0"
                                   value="{{ old('ordre_affichage', 0) }}"
                                   class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 @error('ordre_affichage') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">0 = pas de priorité spéciale</p>
                            @error('ordre_affichage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="featured" 
                                   id="featured"
                                   value="1"
                                   {{ old('featured') ? 'checked' : '' }}
                                   class="border-gray-300 text-primary-600 focus:border-primary-500 focus:ring-primary-500">
                            <label for="featured" class="ml-2 text-sm text-gray-700">
                                Mettre en avant cet article
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white shadow border border-gray-200">
                    <div class="p-6 space-y-4">
                        <button type="submit" 
                                class="w-full bg-primary-600 text-white px-4 py-2 font-medium hover:bg-primary-700 transition-colors">
                            <i data-lucide="save" class="w-4 h-4 mr-2 inline"></i>
                            Créer l'article
                        </button>
                        
                        <a href="{{ route('admin.articles.index') }}" 
                           class="w-full block text-center px-4 py-2 border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors rounded-md">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Auto-preview des images
document.getElementById('image_principale').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Créer un preview de l'image si nécessaire
            let preview = document.getElementById('image-preview');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'image-preview';
                preview.className = 'mt-2 max-w-xs h-auto border border-gray-300 rounded';
                e.target.parentNode.appendChild(preview);
            }
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Compteur de caractères pour meta description
document.getElementById('meta_description').addEventListener('input', function(e) {
    const length = e.target.value.length;
    let counter = document.getElementById('meta-counter');
    if (!counter) {
        counter = document.createElement('p');
        counter.id = 'meta-counter';
        counter.className = 'mt-1 text-xs text-gray-500';
        e.target.parentNode.appendChild(counter);
    }
    counter.textContent = `${length}/160 caractères`;
    counter.className = length > 160 ? 'mt-1 text-xs text-red-500' : 'mt-1 text-xs text-gray-500';
});
</script>
@endsection 