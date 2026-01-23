@extends('layouts.admin')

@section('title', 'Gestion des Articles')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm ">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Gestion des Articles</h1>
                    <p class="text-sm text-gray-600">Gérez tous les articles du blog PEUB</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.articles.analytics') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 mr-2"></i>
                        Analytics
                    </a>
                    <a href="{{ route('admin.articles.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Nouvel Article
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="file-text" class="h-8 w-8 text-gray-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Articles</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="check-circle" class="h-8 w-8 text-green-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Publiés</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['published'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="edit" class="h-8 w-8 text-yellow-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Brouillons</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['draft'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="archive" class="h-8 w-8 text-gray-400"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Archivés</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['archived'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="star" class="h-8 w-8 text-orange-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Mis en avant</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['featured'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="eye" class="h-8 w-8 text-blue-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Vues</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_views']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white shadow border border-gray-200 mb-6">
            <div class="px-6 py-4 ">
                <h3 class="text-lg font-medium text-gray-900">Filtres</h3>
            </div>
            <form method="GET" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Titre, contenu..."
                               class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select name="status" id="status" class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                            <option value="">Tous les statuts</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="categorie" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <select name="categorie" id="categorie" class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ request('categorie') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="auteur" class="block text-sm font-medium text-gray-700 mb-1">Auteur</label>
                        <select name="auteur" id="auteur" class="w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                            <option value="">Tous les auteurs</option>
                            @foreach($auteurs as $auteur)
                                <option value="{{ $auteur->id }}" {{ request('auteur') == $auteur->id ? 'selected' : '' }}>
                                    {{ $auteur->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 bg-primary-600 text-white px-4 py-2 text-sm font-medium hover:bg-primary-700 transition-colors">
                            Filtrer
                        </button>
                        <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors rounded-md">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Liste des articles -->
        <div class="bg-white shadow border border-gray-200">
            <div class="px-6 py-4 ">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Articles ({{ $articles->total() }})</h3>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <span>Trier par:</span>
                        <select onchange="updateSort(this)" class="border-gray-300 text-sm">
                            <option value="created_at-desc" {{ request('sort_by') === 'created_at' && request('sort_direction') === 'desc' ? 'selected' : '' }}>Plus récent</option>
                            <option value="created_at-asc" {{ request('sort_by') === 'created_at' && request('sort_direction') === 'asc' ? 'selected' : '' }}>Plus ancien</option>
                            <option value="titre-asc" {{ request('sort_by') === 'titre' && request('sort_direction') === 'asc' ? 'selected' : '' }}>Titre A-Z</option>
                            <option value="titre-desc" {{ request('sort_by') === 'titre' && request('sort_direction') === 'desc' ? 'selected' : '' }}>Titre Z-A</option>
                            <option value="vues-desc" {{ request('sort_by') === 'vues' && request('sort_direction') === 'desc' ? 'selected' : '' }}>Plus vus</option>
                        </select>
                    </div>
                </div>
            </div>

            @if($articles->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($articles as $article)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-medium text-gray-900 truncate">
                                            <a href="{{ route('admin.articles.show', $article) }}" class="hover:text-primary-600">
                                                {{ $article->titre }}
                                            </a>
                                        </h3>
                                        
                                        <!-- Badges -->
                                        <div class="flex items-center space-x-2">
                                            @if($article->status === 'published')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800">
                                                    Publié
                                                </span>
                                            @elseif($article->status === 'draft')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Brouillon
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-800">
                                                    Archivé
                                                </span>
                                            @endif

                                            @if($article->featured)
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-orange-100 text-orange-800">
                                                    <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                                                    En avant
                                                </span>
                                            @endif

                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $categories[$article->categorie] }}
                                            </span>
                                        </div>
                                    </div>

                                    <p class="text-sm text-gray-600 mb-3">{{ $article->excerpt }}</p>

                                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                                            {{ $article->auteur->name }}
                                        </span>
                                        <span class="flex items-center">
                                            <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                            {{ $article->created_at->format('d/m/Y') }}
                                        </span>
                                        @if($article->date_publication)
                                            <span class="flex items-center">
                                                <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                                                Publié le {{ $article->date_publication->format('d/m/Y') }}
                                            </span>
                                        @endif
                                        <span class="flex items-center">
                                            <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                            {{ number_format($article->vues) }} vues
                                        </span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('admin.articles.edit', $article) }}" 
                                       class="text-gray-400 hover:text-gray-600">
                                        <i data-lucide="edit" class="w-5 h-5"></i>
                                    </a>
                                    
                                    @if($article->status !== 'published')
                                        <form method="POST" action="{{ route('admin.articles.publish', $article) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-400 hover:text-green-600" title="Publier">
                                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.articles.unpublish', $article) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-yellow-400 hover:text-yellow-600" title="Mettre en brouillon">
                                                <i data-lucide="edit" class="w-5 h-5"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.articles.toggle-featured', $article) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-orange-400 hover:text-orange-600" title="Basculer mise en avant">
                                            <i data-lucide="star" class="w-5 h-5 {{ $article->featured ? 'fill-current' : '' }}"></i>
                                        </button>
                                    </form>

                                    <div class="relative inline-block text-left">
                                        <button onclick="toggleDropdown('{{ $article->id }}')" class="text-gray-400 hover:text-gray-600">
                                            <i data-lucide="more-vertical" class="w-5 h-5"></i>
                                        </button>
                                        <div id="dropdown-{{ $article->id }}" class="hidden origin-top-right absolute right-0 mt-2 w-48 bg-white shadow-lg border border-gray-200 z-10">
                                            <div class="py-1">
                                                <a href="{{ route('admin.articles.show', $article) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Voir détails
                                                </a>
                                                <a href="{{ route('admin.articles.duplicate', $article) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Dupliquer
                                                </a>
                                                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $articles->links() }}
                </div>
            @else
                <div class="p-6 text-center">
                    <i data-lucide="file-text" class="mx-auto h-12 w-12 text-gray-400"></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun article</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premier article.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.articles.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 transition-colors">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Nouvel Article
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function updateSort(select) {
    const [sortBy, sortDirection] = select.value.split('-');
    const url = new URL(window.location);
    url.searchParams.set('sort_by', sortBy);
    url.searchParams.set('sort_direction', sortDirection);
    window.location = url;
}

function toggleDropdown(articleId) {
    const dropdown = document.getElementById(`dropdown-${articleId}`);
    dropdown.classList.toggle('hidden');
    
    // Fermer les autres dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        if (el.id !== `dropdown-${articleId}`) {
            el.classList.add('hidden');
        }
    });
}

// Fermer les dropdowns en cliquant ailleurs
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick^="toggleDropdown"]')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            el.classList.add('hidden');
        });
    }
});
</script>
@endsection 