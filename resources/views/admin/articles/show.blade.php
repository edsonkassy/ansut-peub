@extends('layouts.admin')

@section('title', 'Détails de l\'Article')

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
                        <h1 class="text-2xl font-bold text-gray-900">Détails de l'Article</h1>
                        <p class="text-sm text-gray-600">{{ $article->titre }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @if($article->canBeViewed())
                        <a href="{{ route('actualite', $article->slug) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i data-lucide="external-link" class="w-4 h-4 mr-2"></i>
                            Voir sur le site
                        </a>
                    @endif
                    
                    <a href="{{ route('admin.articles.edit', $article) }}" 
                       class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 transition-colors">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Contenu de l'article -->
                <div class="bg-white shadow border border-gray-200">
                    <div class="px-6 py-4 ">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Contenu de l'article</h3>
                            <div class="flex items-center space-x-2">
                                <!-- Badges -->
                                @if($article->status === 'published')
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-green-100 text-green-800">
                                        <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                                        Publié
                                    </span>
                                @elseif($article->status === 'draft')
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                                        Brouillon
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800">
                                        <i data-lucide="archive" class="w-4 h-4 mr-1"></i>
                                        Archivé
                                    </span>
                                @endif

                                @if($article->featured)
                                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-orange-100 text-orange-800">
                                        <i data-lucide="star" class="w-4 h-4 mr-1"></i>
                                        En avant
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <!-- Titre -->
                        <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $article->titre }}</h1>
                        
                        <!-- Résumé -->
                        @if($article->resume)
                            <div class="bg-gray-50 border-l-4 border-primary-500 p-4 mb-6">
                                <p class="text-lg text-gray-700 italic">{{ $article->resume }}</p>
                            </div>
                        @endif

                        <!-- Image principale -->
                        @if($article->image_principale)
                            <div class="mb-6">
                                <img src="{{ asset('storage/' . $article->image_principale) }}" 
                                     alt="{{ $article->titre }}" 
                                     class="w-full h-64 object-cover rounded-lg border border-gray-200">
                            </div>
                        @endif

                        <!-- Contenu -->
                        <div class="prose prose-lg max-w-none">
                            {!! nl2br(e($article->contenu)) !!}
                        </div>

                        <!-- Tags -->
                        @if($article->tags && count($article->tags) > 0)
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Tags :</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($article->tags as $tag)
                                        <span class="inline-flex items-center px-3 py-1 text-sm bg-gray-100 text-gray-700">
                                            #{{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white shadow border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h3 class="text-lg font-medium text-gray-900">Actions rapides</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @if($article->status !== 'published')
                                <form method="POST" action="{{ route('admin.articles.publish', $article) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition-colors">
                                        <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                                        Publier
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.articles.unpublish', $article) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium hover:bg-yellow-700 transition-colors">
                                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                        Dépublier
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.articles.toggle-featured', $article) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-2 {{ $article->featured ? 'bg-orange-600 hover:bg-orange-700' : 'bg-gray-600 hover:bg-gray-700' }} text-white text-sm font-medium transition-colors">
                                    <i data-lucide="star" class="w-4 h-4 mr-2 {{ $article->featured ? 'fill-current' : '' }}"></i>
                                    {{ $article->featured ? 'Retirer' : 'Mettre en avant' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.articles.duplicate', $article) }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
                                    <i data-lucide="copy" class="w-4 h-4 mr-2"></i>
                                    Dupliquer
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article définitivement ?')"
                                        class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Statistiques -->
                <div class="bg-white shadow border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h3 class="text-lg font-medium text-gray-900">Statistiques</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Vues totales</span>
                            <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['vues_totales']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Vues aujourd'hui</span>
                            <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['vues_aujourdhui']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Vues cette semaine</span>
                            <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['vues_cette_semaine']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Vues ce mois</span>
                            <span class="text-lg font-semibold text-gray-900">{{ number_format($stats['vues_ce_mois']) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informations de l'article -->
                <div class="bg-white shadow border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h3 class="text-lg font-medium text-gray-900">Informations</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <span class="text-sm text-gray-600">Auteur</span>
                            <p class="font-medium text-gray-900">{{ $article->auteur->name }}</p>
                        </div>
                        
                        <div>
                            <span class="text-sm text-gray-600">Catégorie</span>
                            <p class="font-medium text-gray-900">{{ \App\Models\Article::getCategories()[$article->categorie] }}</p>
                        </div>
                        
                        <div>
                            <span class="text-sm text-gray-600">Créé le</span>
                            <p class="font-medium text-gray-900">{{ $article->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        
                        <div>
                            <span class="text-sm text-gray-600">Dernière modification</span>
                            <p class="font-medium text-gray-900">{{ $article->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        
                        @if($article->date_publication)
                            <div>
                                <span class="text-sm text-gray-600">Date de publication</span>
                                <p class="font-medium text-gray-900">{{ $article->date_publication->format('d/m/Y à H:i') }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <span class="text-sm text-gray-600">Temps de lecture</span>
                            <p class="font-medium text-gray-900">{{ $article->reading_time }} min</p>
                        </div>
                        
                        <div>
                            <span class="text-sm text-gray-600">Slug</span>
                            <p class="font-mono text-xs text-gray-700 break-all">{{ $article->slug }}</p>
                        </div>
                        
                        @if($article->ordre_affichage > 0)
                            <div>
                                <span class="text-sm text-gray-600">Ordre d'affichage</span>
                                <p class="font-medium text-gray-900">{{ $article->ordre_affichage }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- SEO -->
                @if($article->meta_description)
                    <div class="bg-white shadow border border-gray-200">
                        <div class="px-6 py-4 ">
                            <h3 class="text-lg font-medium text-gray-900">SEO</h3>
                        </div>
                        <div class="p-6">
                            <div>
                                <span class="text-sm text-gray-600">Meta description</span>
                                <p class="mt-1 text-sm text-gray-900">{{ $article->meta_description }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ strlen($article->meta_description) }}/160 caractères</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions avancées -->
                <div class="bg-white shadow border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h3 class="text-lg font-medium text-gray-900">Actions avancées</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('admin.articles.edit', $article) }}" 
                           class="w-full block text-center px-4 py-2 bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 transition-colors">
                            <i data-lucide="edit" class="w-4 h-4 mr-2 inline"></i>
                            Modifier l'article
                        </a>
                        
                        @if($article->canBeViewed())
                            <a href="{{ route('actualite', $article->slug) }}" 
                               target="_blank"
                               class="w-full block text-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors">
                                <i data-lucide="external-link" class="w-4 h-4 mr-2 inline"></i>
                                Voir sur le site
                            </a>
                        @endif
                        
                        <a href="{{ route('admin.articles.analytics') }}" 
                           class="w-full block text-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 mr-2 inline"></i>
                            Voir analytics globales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 