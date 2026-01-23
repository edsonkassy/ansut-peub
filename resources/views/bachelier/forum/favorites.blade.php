@extends('layouts.bachelier')

@section('title', 'Mes Favoris - Communauté PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb & Actions -->
    <div class="flex items-center justify-between mb-6">
        <x-breadcrumb text="COMMUNAUTÉ / MES FAVORIS" />
        <a href="{{ route('bachelier.forum.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-[#00BFA5] hover:bg-[#00BFA5]/90 text-white rounded-lg transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Toutes les discussions
        </a>
    </div>

    <!-- Navigation Pills -->
    <div class="mb-6">
        <nav class="flex space-x-3 overflow-x-auto pb-2 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
            <a href="{{ route('bachelier.forum.index') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap bg-gray-100 text-gray-700 hover:bg-gray-200"
               style="touch-action: manipulation;">
                <div class="flex items-center space-x-2">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    <span>Discussions</span>
                </div>
            </a>
            
            <a href="{{ route('bachelier.forum.favorites') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap bg-[#00BFA5] text-white"
               style="touch-action: manipulation;">
                <div class="flex items-center space-x-2">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                    <span>Mes favoris</span>
                </div>
            </a>
            
            <a href="{{ route('bachelier.forum.members') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap bg-gray-100 text-gray-700 hover:bg-gray-200"
               style="touch-action: manipulation;">
                <div class="flex items-center space-x-2">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    <span>Membres</span>
                </div>
            </a>
        </nav>
    </div>

    <div>
        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('bachelier.forum.favorites') }}" id="filter-form">
                <!-- Barre de recherche pleine largeur -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" id="search" name="search" placeholder="Rechercher dans vos discussions favorites..." 
                               value="{{ request('search') }}"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row flex-wrap items-center gap-4">
                    <div>
                        <select name="category" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <select name="sort" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Plus populaire</option>
                            <option value="replies" {{ request('sort') == 'replies' ? 'selected' : '' }}>Plus de réponses</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Plus de vues</option>
                        </select>
                    </div>
                    
                </div>
            </form>
        </div>
        
        <!-- Discussions favorites -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            @if($favoriteThreads->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($favoriteThreads as $favorite)
                        @if($favorite->thread)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-[#00BFA5]/10 text-[#00BFA5]">
                                            {{ $favorite->thread->category->name }}
                                        </span>
                                        @if($favorite->thread->is_locked)
                                            <i data-lucide="lock" class="w-4 h-4 text-gray-500"></i>
                                        @endif
                                        @if($favorite->thread->is_featured)
                                            <i data-lucide="star" class="w-4 h-4 text-[#00BFA5]"></i>
                                        @endif
                                        @if($favorite->thread->is_pinned)
                                            <i data-lucide="pin" class="w-4 h-4 text-[#00BFA5]"></i>
                                        @endif
                                    </div>
                                    <h3 class="font-medium text-gray-900">
                                        <a href="{{ route('bachelier.forum.thread', $favorite->thread) }}" class="text-black hover:text-[#00BFA5]">{{ $favorite->thread->title }}</a>
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Par <span class="font-medium">{{ $favorite->thread->user->name }}</span> · 
                                        {{ $favorite->thread->created_at->diffForHumans() }}
                                        @if($favorite->thread->last_post)
                                            · Dernière réponse: {{ $favorite->thread->last_post->created_at->diffForHumans() }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="text-right text-sm text-gray-500">
                                        <div>{{ $favorite->thread->posts_count }} réponses</div>
                                        <div>{{ $favorite->thread->views_count }} vues</div>
                                    </div>
                                    <button onclick="removeFavorite({{ $favorite->thread->id }}, this)" 
                                            class="text-red-600 hover:text-red-800 transition p-1"
                                            title="Retirer des favoris">
                                        <i data-lucide="heart" class="w-5 h-5 fill-current"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($favoriteThreads->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $favoriteThreads->withQueryString()->links() }}
                </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <i data-lucide="heart" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune discussion favorite</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">
                        Explorez la communauté et ajoutez des discussions à vos favoris pour les retrouver facilement ici.
                    </p>
                    <a href="{{ route('bachelier.forum.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-primary-600 text-white hover:bg-primary-700 transition font-medium rounded-md">
                        <i data-lucide="message-circle" class="w-5 h-5 mr-2"></i>
                        Découvrir les discussions
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#filter-form');
    const searchInput = document.querySelector('#search');
    const selects = form.querySelectorAll('select');
    
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
});

function removeFavorite(threadId, button) {
    fetch(`/bachelier/forum/${threadId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (!data.isFavorited) {
            // Faire disparaître la discussion en douceur
            const discussionCard = button.closest('.px-6');
            discussionCard.style.transition = 'opacity 0.3s ease';
            discussionCard.style.opacity = '0';
            setTimeout(() => {
                discussionCard.remove();
                
                // Vérifier s'il reste des favoris
                const remainingCards = document.querySelectorAll('.px-6.py-4');
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
@endsection