@extends('layouts.admin')

@section('title', 'Analytics des Articles')

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
                        <h1 class="text-2xl font-bold text-gray-900">Analytics des Articles</h1>
                        <p class="text-sm text-gray-600">Statistiques et performances du blog PEUB</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
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
        <!-- Vue d'ensemble -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="file-text" class="h-8 w-8 text-blue-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Articles</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $stats['articles_by_status']->sum() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="eye" class="h-8 w-8 text-green-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Vues Totales</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ number_format($stats['most_viewed']->sum('vues')) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="check-circle" class="h-8 w-8 text-emerald-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Articles Publiés</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $stats['articles_by_status']['published'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i data-lucide="trending-up" class="h-8 w-8 text-orange-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Moyenne Vues/Article</dt>
                            <dd class="text-2xl font-bold text-gray-900">
                                {{ $stats['most_viewed']->count() > 0 ? number_format($stats['most_viewed']->avg('vues')) : 0 }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Articles par statut -->
            <div class="bg-white shadow border border-gray-200">
                <div class="px-6 py-4 ">
                    <h3 class="text-lg font-medium text-gray-900">Répartition par Statut</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($stats['articles_by_status'] as $status => $count)
                            @php
                                $total = $stats['articles_by_status']->sum();
                                $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                                $statusLabels = \App\Models\Article::getStatuses();
                                $colors = [
                                    'published' => 'bg-green-500',
                                    'draft' => 'bg-yellow-500',
                                    'archived' => 'bg-gray-500'
                                ];
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 {{ $colors[$status] ?? 'bg-gray-400' }} mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">{{ $statusLabels[$status] ?? ucfirst($status) }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500">{{ number_format($percentage, 1) }}%</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 h-2">
                                <div class="{{ $colors[$status] ?? 'bg-gray-400' }} h-2" style="width: {{ $percentage }}%"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Articles par catégorie -->
            <div class="bg-white shadow border border-gray-200">
                <div class="px-6 py-4 ">
                    <h3 class="text-lg font-medium text-gray-900">Répartition par Catégorie</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($stats['articles_by_category'] as $category => $count)
                            @php
                                $total = $stats['articles_by_category']->sum();
                                $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                                $categoryLabels = \App\Models\Article::getCategories();
                                $colors = [
                                    'annonce' => 'bg-red-500',
                                    'success' => 'bg-green-500',
                                    'evenement' => 'bg-blue-500',
                                    'partenariat' => 'bg-purple-500',
                                    'formation' => 'bg-indigo-500',
                                    'conseil' => 'bg-yellow-500',
                                    'interview' => 'bg-pink-500',
                                    'actualite' => 'bg-gray-500'
                                ];
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 {{ $colors[$category] ?? 'bg-gray-400' }} mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">{{ $categoryLabels[$category] ?? ucfirst($category) }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-500">{{ number_format($percentage, 1) }}%</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 h-2">
                                <div class="{{ $colors[$category] ?? 'bg-gray-400' }} h-2" style="width: {{ $percentage }}%"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <!-- Articles les plus vus -->
            <div class="bg-white shadow border border-gray-200">
                <div class="px-6 py-4 ">
                    <h3 class="text-lg font-medium text-gray-900">Articles les Plus Vus</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($stats['most_viewed'] as $index => $article)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-600 text-sm font-medium">
                                            {{ $index + 1 }}
                                        </span>
                                        <h4 class="text-sm font-medium text-gray-900 truncate">
                                            <a href="{{ route('admin.articles.show', $article) }}" class="hover:text-primary-600">
                                                {{ $article->titre }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span>{{ $article->auteur->name }}</span>
                                        <span>{{ $article->created_at->format('d/m/Y') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ \App\Models\Article::getCategories()[$article->categorie] }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    <div class="flex items-center space-x-1 text-sm text-gray-900">
                                        <i data-lucide="eye" class="w-4 h-4 text-gray-400"></i>
                                        <span class="font-medium">{{ number_format($article->vues) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            <i data-lucide="bar-chart-3" class="mx-auto h-12 w-12 text-gray-400"></i>
                            <p class="mt-2 text-sm">Aucune donnée de vue disponible</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Articles récents -->
            <div class="bg-white shadow border border-gray-200">
                <div class="px-6 py-4 ">
                    <h3 class="text-lg font-medium text-gray-900">Articles Récents</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($stats['recent_articles'] as $article)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">
                                        <a href="{{ route('admin.articles.show', $article) }}" class="hover:text-primary-600">
                                            {{ $article->titre }}
                                        </a>
                                    </h4>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span>{{ $article->auteur->name }}</span>
                                        <span>{{ $article->created_at->format('d/m/Y H:i') }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ \App\Models\Article::getCategories()[$article->categorie] }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-shrink-0">
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
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            <i data-lucide="clock" class="mx-auto h-12 w-12 text-gray-400"></i>
                            <p class="mt-2 text-sm">Aucun article récent</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Articles par auteur -->
        @if($stats['articles_by_author']->count() > 0)
            <div class="bg-white shadow border border-gray-200 mt-8">
                <div class="px-6 py-4 ">
                    <h3 class="text-lg font-medium text-gray-900">Productivité des Auteurs</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($stats['articles_by_author'] as $authorStat)
                            <div class="bg-gray-50 p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $authorStat->auteur->name }}</h4>
                                        <p class="text-xs text-gray-500">Auteur</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-bold text-primary-600">{{ $authorStat->count }}</span>
                                        <p class="text-xs text-gray-500">article{{ $authorStat->count > 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions rapides -->
        <div class="bg-white shadow border border-gray-200 mt-8">
            <div class="px-6 py-4 ">
                <h3 class="text-lg font-medium text-gray-900">Actions Rapides</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.articles.create') }}" 
                       class="flex items-center justify-center px-4 py-3 bg-primary-600 text-white font-medium hover:bg-primary-700 transition-colors">
                        <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                        Créer un Article
                    </a>
                    
                    <a href="{{ route('admin.articles.index', ['status' => 'draft']) }}" 
                       class="flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        <i data-lucide="edit" class="w-5 h-5 mr-2"></i>
                        Voir les Brouillons
                    </a>
                    
                    <a href="{{ route('admin.articles.index', ['featured' => '1']) }}" 
                       class="flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        <i data-lucide="star" class="w-5 h-5 mr-2"></i>
                        Articles en Avant
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 