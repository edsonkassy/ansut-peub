@extends('layouts.guest')

@section('title', 'Actualités PEUB')

@section('content')
<!-- Hero Section -->
<section class="bg-white text-gray-900 py-20 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900">Actualités PEUB</h1>
            <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Toutes les dernières nouvelles et événements du Programme d'Excellence Universelle pour les Bacheliers
            </p>
            <div class="flex justify-center">
                <div class="bg-primary-100 px-6 py-3 text-sm font-medium text-primary-700">
                    Mis à jour quotidiennement
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filtres -->
<section class="py-8 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('actualites') }}" 
               class="{{ !request('categorie') ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }} px-6 py-2 font-medium hover:bg-primary-700 hover:text-white transition-colors">
                Toutes
            </a>
            @foreach($categories as $key => $label)
                @if(($categoriesCounts[$key] ?? 0) > 0)
                    <a href="{{ route('actualites', ['categorie' => $key]) }}" 
                       class="{{ request('categorie') === $key ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700' }} px-6 py-2 font-medium hover:bg-primary-700 hover:text-white transition-colors">
                        {{ $label }} ({{ $categoriesCounts[$key] }})
                    </a>
                @endif
            @endforeach
        </div>
        
        <!-- Barre de recherche -->
        <div class="mt-6 max-w-md mx-auto">
            <form method="GET" action="{{ route('actualites') }}" class="flex">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Rechercher un article..."
                       class="flex-1 border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <button type="submit" 
                        class="ml-2 px-4 py-2 bg-primary-600 text-white hover:bg-primary-700 transition-colors">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Articles -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Articles à la une -->
        @if($featuredArticles->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Articles à la une</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredArticles as $index => $article)
                        <article class="{{ $index === 0 ? 'lg:col-span-2' : '' }} bg-white shadow-sm hover:shadow-md border border-gray-100 transition-all duration-300 overflow-hidden group">
                            @if($article->image_principale)
                                <div class="h-64 relative overflow-hidden">
                                    <img src="{{ asset('storage/' . $article->image_principale) }}" 
                                         alt="{{ $article->titre }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-secondary-500 text-white px-3 py-1 text-sm font-medium">
                                            À la une
                                        </span>
                                    </div>
                                </div>
                            @endif
                            <div class="p-{{ $index === 0 ? '8' : '6' }}">
                                <div class="flex items-center mb-4">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-primary-100 text-primary-700">
                                        {{ $categories[$article->categorie] }}
                                    </span>
                                    <span class="mx-2 text-gray-300">•</span>
                                    <span class="text-sm text-gray-500">{{ $article->date_publication->format('d M Y') }}</span>
                                </div>
                                <h2 class="{{ $index === 0 ? 'text-2xl' : 'text-xl' }} font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors">
                                    {{ $article->titre }}
                                </h2>
                                <p class="text-gray-600 mb-6 {{ $index === 0 ? 'text-lg' : 'text-base' }}">
                                    {{ $article->excerpt }}
                                </p>
                                <a href="{{ route('actualite', $article->slug) }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold {{ $index === 0 ? 'text-lg' : 'text-base' }} underline underline-offset-4">
                                    Lire l'article complet
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Tous les articles -->
        @if($articles->count() > 0)
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-8">{{ request('categorie') ? 'Articles de la catégorie ' . $categories[request('categorie')] : 'Tous les articles' }}</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($articles as $article)
                        <article class="bg-white shadow-sm hover:shadow-md border border-gray-100 transition-all duration-300 overflow-hidden group">
                            @if($article->image_principale)
                                <div class="h-48 relative overflow-hidden">
                                    <img src="{{ asset('storage/' . $article->image_principale) }}" 
                                         alt="{{ $article->titre }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @else
                                <div class="h-48 bg-gray-200 flex items-center justify-center">
                                    <i data-lucide="image" class="w-12 h-12 text-gray-400"></i>
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="flex items-center mb-3">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-primary-100 text-primary-700">
                                        {{ $categories[$article->categorie] }}
                                    </span>
                                    <span class="mx-2 text-gray-300">•</span>
                                    <span class="text-sm text-gray-500">{{ $article->date_publication->format('d M Y') }}</span>
                                    @if($article->reading_time)
                                        <span class="mx-2 text-gray-300">•</span>
                                        <span class="text-sm text-gray-500">{{ $article->reading_time }} min</span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors">
                                    {{ $article->titre }}
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    {{ $article->excerpt }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('actualite', $article->slug) }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium underline underline-offset-4">
                                        Lire la suite 
                                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                        {{ number_format($article->vues) }}
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-12">
                    {{ $articles->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <i data-lucide="file-text" class="mx-auto h-12 w-12 text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun article trouvé</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request('search'))
                        Aucun article ne correspond à votre recherche "{{ request('search') }}".
                    @elseif(request('categorie'))
                        Aucun article dans cette catégorie pour le moment.
                    @else
                        Aucun article publié pour le moment.
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('actualites') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                        Voir tous les articles
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Newsletter -->
<section class="py-16 bg-primary-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ne manquez aucune actualité</h2>
        <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
            Abonnez-vous à notre newsletter pour recevoir les dernières nouvelles PEUB directement dans votre boîte mail
        </p>
        <div class="max-w-md mx-auto flex gap-4">
            <input type="email" placeholder="Votre adresse email" 
                   class="flex-1 px-4 py-3 bg-white border border-primary-400 focus:ring-2 focus:ring-white focus:border-white outline-none text-gray-900">
            <button class="bg-secondary-500 hover:bg-secondary-600 text-white px-8 py-3 font-semibold transition-colors">
                S'abonner
            </button>
        </div>
        <p class="text-sm text-primary-200 mt-4">
            Vos données sont protégées et ne seront jamais partagées
        </p>
    </div>
</section>
@endsection