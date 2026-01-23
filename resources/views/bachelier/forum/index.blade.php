@extends('layouts.bachelier')

@section('title', 'Communauté - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb & Actions -->
    <div class="flex items-center justify-between mb-6">
        <x-breadcrumb text="COMMUNAUTÉ / DISCUSSIONS" />
        <button onclick="openNewThreadModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 rounded-lg transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Nouvelle Discussion
        </button>
    </div>

    <!-- Navigation Pills -->
    <div class="mb-6">
        <nav class="flex space-x-3 overflow-x-auto pb-2 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
            <a href="{{ route('bachelier.forum.index') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap {{ request()->routeIs('bachelier.forum.index') && !request()->has('filter') ? 'bg-[#00BFA5] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
               style="touch-action: manipulation;">
                <div class="flex items-center space-x-2">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    <span>Discussions</span>
                </div>
            </a>
            
            <a href="{{ route('bachelier.forum.favorites') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap {{ request()->routeIs('bachelier.forum.favorites') ? 'bg-[#00BFA5] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
               style="touch-action: manipulation;">
                <div class="flex items-center space-x-2">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                    <span>Mes favoris</span>
                </div>
            </a>
            
            <a href="{{ route('bachelier.forum.members') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap {{ request()->routeIs('bachelier.forum.members') ? 'bg-[#00BFA5] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
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
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
            <form id="filter-form" method="GET" action="{{ route('bachelier.forum.index') }}">
                <!-- Barre de recherche pleine largeur -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" id="search" name="search" placeholder="Rechercher des discussions par titre ou contenu..." 
                               value="{{ request('search') }}"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row flex-wrap items-center gap-4">
                    <div>
                        <select id="category-forum" name="category" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <select id="sort-forum" name="sort" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Plus populaire</option>
                            <option value="replies" {{ request('sort') == 'replies' ? 'selected' : '' }}>Plus de réponses</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Plus de vues</option>
                        </select>
                    </div>
                    
                    <button type="button" id="reset-filters" class="p-2 text-[#00BFA5] hover:text-[#00BFA5]/80 transition-colors" title="Réinitialiser les filtres">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-3">
                <!-- Discussions épinglées -->
                @if($pinnedThreads->count() > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6">
                    <div class="px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            Discussions épinglées
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($pinnedThreads as $thread)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i data-lucide="pin" class="w-4 h-4 text-[#00BFA5]"></i>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-[#00BFA5]/10 text-[#00BFA5]">
                                            {{ $thread->category->name }}
                                        </span>
                                        @if($thread->is_locked)
                                            <i data-lucide="lock" class="w-4 h-4 text-gray-500"></i>
                                        @endif
                                    </div>
                                    <h3 class="font-medium text-gray-900">
                                        <a href="{{ route('bachelier.forum.thread', $thread) }}" class="text-black hover:text-[#00BFA5]">{{ $thread->title }}</a>
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Par <span class="font-medium">{{ $thread->user->name }}</span> · 
                                        {{ $thread->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="text-right text-sm text-gray-500">
                                        <div>{{ $thread->posts_count }} réponses</div>
                                        <div>{{ $thread->views_count }} vues</div>
                                    </div>
                                    @auth
                                    @php
                                        $isFavorited = auth()->user()->favoriteThreads()->where('forum_thread_id', $thread->id)->exists();
                                    @endphp
                                    <button onclick="toggleFavoriteInList({{ $thread->id }}, this)" 
                                            class="text-gray-400 hover:text-red-500 transition p-1"
                                            title="{{ $isFavorited ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                                        <i data-lucide="heart" class="w-4 h-4 {{ $isFavorited ? 'fill-current text-red-500' : '' }}"></i>
                                    </button>
                                    @endauth
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Catégories -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6">
                    <div class="px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Catégories de Discussion</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($categories as $category)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 flex items-center justify-center" style="background-color: {{ $category->color }}20;">
                                        @if($category->icon)
                                            <i data-lucide="{{ $category->icon }}" class="w-6 h-6" style="color: {{ $category->color }}"></i>
                                        @else
                                            <i data-lucide="message-circle" class="w-6 h-6" style="color: {{ $category->color }}"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">
                                            <a href="{{ route('bachelier.forum.category', $category) }}" class="text-black hover:text-[#00BFA5]">
                                                {{ $category->name }}
                                            </a>
                                        </h3>
                                        @if($category->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $category->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right text-sm text-gray-500">
                                    <div>{{ $category->threads_count }} discussions</div>
                                    <div>{{ $category->posts_count }} messages</div>
                                    @if($category->last_thread)
                                        <div class="text-xs mt-1">
                                            Dernier: <a href="{{ route('bachelier.forum.thread', $category->last_thread) }}" class="text-black hover:text-[#00BFA5]">
                                                {{ Str::limit($category->last_thread->title, 30) }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Discussions récentes -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Discussions Récentes</h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($recentThreads as $thread)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $thread->category->name }}
                                        </span>
                                        @if($thread->is_locked)
                                            <i data-lucide="lock" class="w-4 h-4 text-gray-500"></i>
                                        @endif
                                        @if($thread->is_featured)
                                            <i data-lucide="star" class="w-4 h-4 text-[#00BFA5]"></i>
                                        @endif
                                    </div>
                                    <h3 class="font-medium text-gray-900">
                                        <a href="{{ route('bachelier.forum.thread', $thread) }}" class="text-black hover:text-[#00BFA5]">{{ $thread->title }}</a>
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Par <span class="font-medium">{{ $thread->user->name }}</span> · 
                                        {{ $thread->created_at->diffForHumans() }}
                                        @if($thread->last_post)
                                            · Dernière réponse: {{ $thread->last_post->created_at->diffForHumans() }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="text-right text-sm text-gray-500">
                                        <div>{{ $thread->posts_count }} réponses</div>
                                        <div>{{ $thread->views_count }} vues</div>
                                    </div>
                                    @auth
                                    @php
                                        $isFavorited = auth()->user()->favoriteThreads()->where('forum_thread_id', $thread->id)->exists();
                                    @endphp
                                    <button onclick="toggleFavoriteInList({{ $thread->id }}, this)" 
                                            class="text-gray-400 hover:text-red-500 transition p-1"
                                            title="{{ $isFavorited ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                                        <i data-lucide="heart" class="w-4 h-4 {{ $isFavorited ? 'fill-current text-red-500' : '' }}"></i>
                                    </button>
                                    @endauth
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center">
                            <i data-lucide="message-circle" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                            <p class="text-gray-600">Aucune discussion pour le moment</p>
                            <p class="text-sm text-gray-500 mt-1">Soyez le premier à démarrer une discussion !</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Statistiques -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Statistiques</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Discussions</span>
                            <span class="font-medium">{{ number_format($stats['total_threads']) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Messages</span>
                            <span class="font-medium">{{ number_format($stats['total_posts']) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Membres</span>
                            <span class="font-medium">{{ number_format($stats['active_users']) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation personnelle -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Ma Communauté</h3>
                    <div class="space-y-2">
                        <a href="{{ route('bachelier.forum.favorites') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#00BFA5] transition">
                            <i data-lucide="heart" class="w-4 h-4"></i>
                            Opportunités
                        </a>
                        <button onclick="openNewThreadModal()" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#00BFA5] transition">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Nouvelle discussion
                        </button>
                    </div>
                </div>

                <!-- Discussions populaires -->
                @if($popularThreads->count() > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Discussions Populaires</h3>
                    <div class="space-y-3">
                        @foreach($popularThreads as $thread)
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2">
                                <a href="{{ route('bachelier.forum.thread', $thread) }}" class="text-black hover:text-[#00BFA5]">
                                    {{ $thread->title }}
                                </a>
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $thread->views_count }} vues · {{ $thread->posts_count }} réponses
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
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
    const searchInput = document.querySelector('#search');
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
            window.location.href = '{{ route("bachelier.forum.index") }}';
        });
    }
});

// Fonctions pour le modal de nouvelle discussion
function openNewThreadModal() {
    document.getElementById('newThreadModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeNewThreadModal() {
    document.getElementById('newThreadModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('newThreadForm').reset();
    const errorBox = document.getElementById('newThreadError');
    const errorText = document.getElementById('newThreadErrorText');
    if (errorBox) {
        errorBox.classList.add('hidden');
        errorText.textContent = '';
    }
}

function submitNewThread() {
    const form = document.getElementById('newThreadForm');
    const submitBtn = document.getElementById('newThreadSubmitBtn');
    const submitText = document.getElementById('newThreadSubmitText');
    const submitLoader = document.getElementById('newThreadSubmitLoader');
    const errorBox = document.getElementById('newThreadError');
    const errorText = document.getElementById('newThreadErrorText');
    
    // Validation basique
    const title = form.title.value.trim();
    const content = form.content.value.trim();
    const category = form.forum_category_id.value;
    
    if (!title || !content || !category) {
        alert('Veuillez remplir tous les champs obligatoires.');
        return;
    }
    
    if (title.length < 5) {
        alert('Le titre doit contenir au moins 5 caractères.');
        return;
    }
    
    if (content.length < 10) {
        alert('Le contenu doit contenir au moins 10 caractères.');
        return;
    }
    
    // Reset error box
    if (errorBox) {
        errorBox.classList.add('hidden');
        errorText.textContent = '';
    }

    // Désactiver le bouton et afficher le loader
    submitBtn.disabled = true;
    submitText.textContent = 'Création en cours...';
    submitLoader.classList.remove('hidden');
    
    // Soumettre le formulaire
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(async (response) => {
        let payload = null;
        try {
            payload = await response.json();
        } catch (e) {}
        if (!response.ok) {
            const message = payload && payload.message ? payload.message : 'Une erreur est survenue lors de la création de la discussion.';
            throw new Error(message);
        }
        return payload;
    })
    .then(data => {
        if (data.success) {
            closeNewThreadModal();
            
            // Notification de succès
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-[10001]';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                    <span>Discussion créée avec succès !</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
                // Rediriger vers la nouvelle discussion
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    location.reload();
                }
            }, 2000);
        } else {
            alert(data.message || 'Une erreur est survenue.');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (errorBox) {
            errorText.textContent = error.message || 'Une erreur est survenue lors de la création de la discussion.';
            errorBox.classList.remove('hidden');
        } else {
            alert(error.message || 'Une erreur est survenue lors de la création de la discussion.');
        }
    })
    .finally(() => {
        // Réactiver le bouton
        submitBtn.disabled = false;
        submitText.textContent = 'Créer la discussion';
        submitLoader.classList.add('hidden');
    });
}

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    const modal = document.getElementById('newThreadModal');
    if (e.target === modal) {
        closeNewThreadModal();
    }
});

// Fermer le modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeNewThreadModal();
    }
});

function toggleFavoriteInList(threadId, button) {
    @auth
    fetch(`/bachelier/forum/${threadId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const icon = button.querySelector('i');
            
            if (data.isFavorited) {
                icon.classList.add('fill-current', 'text-red-500');
                button.title = 'Retirer des favoris';
            } else {
                icon.classList.remove('fill-current', 'text-red-500');
                button.title = 'Ajouter aux favoris';
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
    @else
    window.location.href = '{{ route("login") }}';
    @endauth
}
</script>

<!-- Modal Nouvelle Discussion -->
<div id="newThreadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[10000] hidden">
    <div class="relative md:top-20 mx-auto p-4 md:p-5 border w-full max-w-[500px] bg-white shadow-lg md:rounded-lg h-full md:h-auto md:mt-20"
         style="max-width: min(500px, calc(100vw - 1.5rem));">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 ">
                <h3 class="text-lg font-semibold text-gray-900">
                    Nouvelle Discussion
                </h3>
                <button onclick="closeNewThreadModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Zone d'erreur -->
            <div id="newThreadError" class="mt-6 hidden">
                <div class="flex items-start gap-2 px-3 py-2 text-sm text-red-700 bg-red-50 border border-red-200">
                    <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5"></i>
                    <span id="newThreadErrorText"></span>
                </div>
            </div>

            <!-- Formulaire -->
            <form id="newThreadForm" action="{{ route('bachelier.forum.store-thread') }}" method="POST" class="mt-4">
                @csrf
                
                <!-- Titre -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Titre de la discussion <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#00BFA5] focus:border-[#00BFA5]"
                           placeholder="Un titre accrocheur pour votre discussion..."
                           maxlength="255">
                    <p class="mt-1 text-sm text-gray-500">Minimum 5 caractères</p>
                </div>

                <!-- Catégorie -->
                <div class="mb-4">
                    <label for="forum_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select name="forum_category_id" 
                            id="forum_category_id" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#00BFA5] focus:border-[#00BFA5]">
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Contenu -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Contenu <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" 
                              id="content" 
                              rows="8" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#00BFA5] focus:border-[#00BFA5]"
                              placeholder="Décrivez votre question ou partagez votre réflexion..."></textarea>
                    <p class="mt-1 text-sm text-gray-500">Minimum 10 caractères. Vous pouvez utiliser du texte simple.</p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeNewThreadModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button 
                        type="button" 
                        id="newThreadSubmitBtn"
                        onclick="submitNewThread()"
                        class="inline-flex items-center justify-center px-6 py-2 bg-[#00BFA5] border border-transparent rounded-lg text-sm font-medium text-white hover:bg-[#00BFA5]/90 focus:outline-none focus:ring-1 focus:ring-offset-2 focus:ring-[#00BFA5] disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="newThreadSubmitText">Créer la discussion</span>
                        <i data-lucide="loader" class="w-4 h-4 ml-2 animate-spin hidden" id="newThreadSubmitLoader"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection