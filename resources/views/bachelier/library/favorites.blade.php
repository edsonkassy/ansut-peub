@extends('layouts.bachelier')

@section('title', 'Mes Favoris - Bibliothèque PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="RESSOURCES / MES FAVORIS" />

    <!-- Navigation Pills -->
    <div class="mb-6">
        <nav class="flex space-x-3 overflow-x-auto pb-2 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
            <a href="{{ route('bachelier.library.index') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap bg-gray-100 text-gray-700 hover:bg-gray-200"
               style="touch-action: manipulation;">
                <div class="flex items-center space-x-2">
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    <span>Toutes les ressources</span>
                </div>
            </a>
            
            <a href="{{ route('bachelier.library.favorites') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap bg-[#00BFA5] text-white"
               style="touch-action: manipulation;">
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
            <form id="filter-form" method="GET" action="{{ route('bachelier.library.favorites') }}">
                <!-- Barre de recherche pleine largeur -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" id="search" name="search" placeholder="Rechercher dans vos ressources favorites..." 
                               value="{{ request('search') }}"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row flex-wrap items-center gap-4">
                </div>
            </form>
        </div>
        
        @if($favorites->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($favorites as $favorite)
                    @if($favorite->resource)
                    <div class="bg-white border border-gray-200 hover:shadow-lg transition-shadow">
                        @if($favorite->resource->thumbnail)
                            <div class="h-48 bg-cover bg-center" style="background-image: url('{{ Storage::url($favorite->resource->thumbnail) }}')"></div>
                        @else
                            <div class="h-48 bg-gradient-to-r from-primary-400 to-primary-600 flex items-center justify-center">
                                @switch($favorite->resource->type)
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
                                <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 uppercase">{{ $favorite->resource->type }}</span>
                                <button onclick="removeFavorite({{ $favorite->resource->id }}, this)" 
                                        class="text-red-600 hover:text-red-800 transition"
                                        title="Retirer des favoris">
                                    <i data-lucide="heart" class="w-5 h-5 fill-current"></i>
                                </button>
                            </div>
                            
                            <h3 class="font-semibold text-lg text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ route('bachelier.library.show', $favorite->resource) }}" class="text-black hover:text-primary-600 transition-colors">{{ $favorite->resource->title }}</a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $favorite->resource->description }}</p>
                            
                            <div class="text-xs text-gray-500 mb-3">
                                <span>{{ $favorite->resource->category->name }}</span>
                                @if($favorite->resource->author)
                                    · <span>{{ $favorite->resource->author }}</span>
                                @endif
                                @if($favorite->resource->level)
                                    · <span class="capitalize">{{ $favorite->resource->level }}</span>
                                @endif
                            </div>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex gap-3">
                                    <span title="Vues"><i data-lucide="eye" class="w-4 h-4 inline mr-1"></i>{{ $favorite->resource->views_count }}</span>
                                    <span title="Téléchargements"><i data-lucide="download" class="w-4 h-4 inline mr-1"></i>{{ $favorite->resource->downloads_count }}</span>
                                </div>
                                <span>Ajouté {{ $favorite->created_at->diffForHumans() }}</span>
                            </div>
                            
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- Pagination -->
            @if($favorites->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $favorites->links() }}
            </div>
            @endif

        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 mx-auto mb-4 flex items-center justify-center">
                    <i data-lucide="heart" class="w-12 h-12 text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune ressource favorite</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Explorez notre bibliothèque et ajoutez des ressources à vos favoris pour les retrouver facilement ici.
                </p>
                <a href="{{ route('bachelier.library.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-primary-600 text-white hover:bg-primary-700 transition font-medium rounded-md">
                    <i data-lucide="book-open" class="w-5 h-5 mr-2"></i>
                    Découvrir la bibliothèque
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#filter-form');
    const searchInput = document.querySelector('#search');
    const resetBtn = document.getElementById('reset-filters');
    
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
            window.location.href = '{{ route("bachelier.library.favorites") }}';
        });
    }
});

function removeFavorite(resourceId, button) {
    fetch(`/bachelier/library/${resourceId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.isFavorited) {
            // Faire disparaître la carte en douceur
            const card = button.closest('.bg-white');
            card.style.transition = 'opacity 0.3s ease';
            card.style.opacity = '0';
            setTimeout(() => {
                card.remove();
                
                // Vérifier s'il reste des favoris
                const remainingCards = document.querySelectorAll('.bg-white.border');
                if (remainingCards.length === 0) {
                    // Recharger la page pour afficher le message "aucun favori"
                    window.location.reload();
                }
            }, 300);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection