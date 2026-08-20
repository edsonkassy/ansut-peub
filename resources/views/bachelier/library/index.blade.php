@extends('layouts.bachelier')

@section('title', 'Bibliothèque - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="RESSOURCES / BIBLIOTHÈQUE" />

    <!-- Navigation Pills -->
    <div class="mb-6">
        <nav class="flex space-x-3 overflow-x-auto pb-2 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
            <a href="{{ route('bachelier.library.index') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap {{ request()->routeIs('bachelier.library.index') ? 'bg-[#00BFA5] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <div class="flex items-center space-x-2">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    <span>Toutes les ressources</span>
                </div>
            </a>
            
            <a href="{{ route('bachelier.library.favorites') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap {{ request()->routeIs('bachelier.library.favorites') ? 'bg-[#00BFA5] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <div class="flex items-center space-x-2">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                    <span>Mes favoris</span>
                </div>
            </a>
        </nav>
    </div>

    <div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
            <form id="filter-form" method="GET" action="{{ route('bachelier.library.index') }}" class="space-y-4">
                
                <!-- Barre de recherche pleine largeur -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" id="search-filter" name="search" placeholder="Rechercher des ressources par titre, description ou catégorie..." 
                               value="{{ request('search') }}"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row flex-wrap items-center gap-4">
                    <div>
                        <select name="type" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="">Tous les types</option>
                            <option value="pdf" {{ request('type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Vidéo</option>
                            <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                            <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Document</option>
                            <option value="presentation" {{ request('type') == 'presentation' ? 'selected' : '' }}>Présentation</option>
                        </select>
                    </div>
                    <div>
                        <select id="category-filter" name="category" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->resources_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="level" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="">Tous les niveaux</option>
                            <option value="debutant" {{ request('level') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                            <option value="intermediaire" {{ request('level') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                            <option value="avance" {{ request('level') == 'avance' ? 'selected' : '' }}>Avancé</option>
                        </select>
                    </div>
                    <div>
                        <select name="sort" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Populaire</option>
                            <option value="downloads" {{ request('sort') == 'downloads' ? 'selected' : '' }}>Plus téléchargé</option>
                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>En vedette</option>
                        </select>
                    </div>
                    
                    <button type="button" id="reset-filters" class="p-2 text-[#00BFA5] hover:text-[#00BFA5]/80 transition-colors" title="Réinitialiser les filtres">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des ressources -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($resources as $resource)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow">
                @if($resource->thumbnail)
                    <div class="h-48 bg-cover bg-center" style="background-image: url('{{ Storage::url($resource->thumbnail) }}')"></div>
                @else
                    <div class="h-48 bg-gradient-to-r from-[#00BFA5] to-[#00BFA5]/80 flex items-center justify-center rounded-t-xl">
                        @switch($resource->type)
                            @case('pdf')
                                <i data-lucide="file-text" class="w-12 h-12 text-white"></i>
                                @break
                            @case('video')
                                <i data-lucide="play-circle" class="w-12 h-12 text-white"></i>
                                @break
                            @case('audio')
                                <i data-lucide="headphones" class="w-12 h-12 text-white"></i>
                                @break
                            @default
                                <i data-lucide="book-open" class="w-12 h-12 text-white"></i>
                        @endswitch
                    </div>
                @endif
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 uppercase">{{ $resource->type }}</span>
                        @if($resource->is_featured)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Vedette</span>
                        @endif
                    </div>
                    
                    <h3 class="font-semibold text-lg text-gray-900 mb-2 line-clamp-2">
                        <a href="{{ route('bachelier.library.show', $resource) }}" class="text-black hover:text-[#00BFA5] transition-colors">{{ $resource->title }}</a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $resource->description }}</p>
                    
                    <div class="text-xs text-gray-500 mb-3">
                        <span>{{ $resource->category->name }}</span>
                        @if($resource->author)
                            · <span>{{ $resource->author }}</span>
                        @endif
                        @if($resource->level)
                            · <span class="capitalize">{{ $resource->level }}</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <div class="flex gap-3">
                            <span title="Vues"><i data-lucide="eye" class="w-4 h-4 inline mr-1"></i>{{ $resource->views_count }}</span>
                            <span title="Téléchargements"><i data-lucide="download" class="w-4 h-4 inline mr-1"></i>{{ $resource->downloads_count }}</span>
                        </div>
                        <span>{{ $resource->published_at->diffForHumans() }}</span>
                    </div>
                    
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <i data-lucide="search" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune ressource trouvée</h3>
                <p class="text-gray-500">Essayez de modifier vos critères de recherche</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($resources->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $resources->withQueryString()->links() }}
        </div>
        @endif

    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#filter-form');
    const searchInput = document.querySelector('#search-filter');
    const selects = form.querySelectorAll('select');
    const resetBtn = document.getElementById('reset-filters');
    
    // Auto-submit on select change
    selects.forEach(select => {
        select.addEventListener('change', function() {
            form.submit();
        });
    });
    
    // Debounced search
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 300);
        });
    }
    
    // Reset filters
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            window.location.href = '{{ route("bachelier.library.index") }}';
        });
    }
});
</script>
@endsection