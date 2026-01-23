@extends('layouts.admin')

@section('title', 'Détails de la Ressource - Admin')

@section('page-title', 'Détails de la Ressource')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-primary-700">{{ $resource->title }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.library.resources.edit', $resource) }}" 
               class="px-4 py-2 bg-primary-600 text-white hover:bg-primary-700 transition flex items-center gap-2">
                <i data-lucide="edit" class="w-4 h-4"></i>
                Modifier
            </a>
            <a href="{{ route('admin.library.resources.index') }}" 
               class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                Retour à la liste
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-gray-300 p-6">
                <h3 class="text-lg font-semibold mb-4">Informations générales</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $resource->category->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 uppercase">
                                {{ $resource->type }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Auteur</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $resource->author ?? $resource->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Niveau</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($resource->level ?? 'Tous niveaux') }}</dd>
                    </div>
                    @if($resource->duration)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Durée</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $resource->duration }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date d'ajout</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $resource->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>

                <div class="mt-4">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $resource->description }}</dd>
                </div>

                @if($resource->tags && count($resource->tags) > 0)
                <div class="mt-4">
                    <dt class="text-sm font-medium text-gray-500 mb-2">Tags</dt>
                    <dd class="flex flex-wrap gap-2">
                        @foreach($resource->tags as $tag)
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs">{{ $tag }}</span>
                        @endforeach
                    </dd>
                </div>
                @endif

                <div class="mt-6 pt-4 border-t flex gap-4">
                    @if($resource->file_path)
                        <a href="{{ Storage::url($resource->file_path) }}" target="_blank" 
                           class="px-4 py-2 bg-primary-600 text-white hover:bg-primary-700 transition flex items-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Télécharger ({{ $resource->file_size_formatted }})
                        </a>
                    @elseif($resource->external_url)
                        <a href="{{ $resource->external_url }}" target="_blank" 
                           class="px-4 py-2 bg-primary-600 text-white hover:bg-primary-700 transition flex items-center gap-2">
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                            Ouvrir le lien
                        </a>
                    @endif
                </div>
            </div>

            <!-- Commentaires -->
            <div class="bg-white border border-gray-300 p-6">
                <h3 class="text-lg font-semibold mb-4">Commentaires</h3>
                
                @if($comments->count() > 0)
                    <div class="space-y-4">
                        @foreach($comments as $comment)
                            <div class="border-b pb-4 last:border-0">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="font-medium text-sm">{{ $comment->user->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            @if(!$comment->is_approved)
                                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800">En attente</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-700">{{ $comment->content }}</p>
                                        
                                        @if($comment->replies->count() > 0)
                                            <div class="ml-6 mt-3 space-y-2">
                                                @foreach($comment->replies as $reply)
                                                    <div class="p-2 bg-gray-50">
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <span class="font-medium text-xs">{{ $reply->user->name }}</span>
                                                            <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs text-gray-700">{{ $reply->content }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex gap-2 ml-4">
                                        <form action="{{ route('admin.library.comments.toggle', $comment) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm text-primary-600 hover:text-primary-900">
                                                {{ $comment->is_approved ? 'Masquer' : 'Approuver' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.library.comments.destroy', $comment) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900" 
                                                    onclick="return confirm('Supprimer ce commentaire ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{ $comments->links() }}
                @else
                    <p class="text-gray-500 text-sm">Aucun commentaire pour cette ressource.</p>
                @endif
            </div>
        </div>

        <!-- Statistiques -->
        <div class="space-y-6">
            <div class="bg-white border border-gray-300 p-6">
                <h3 class="text-lg font-semibold mb-4">Statistiques</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Vues</span>
                        <span class="font-semibold">{{ number_format($resource->views_count) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Téléchargements</span>
                        <span class="font-semibold">{{ number_format($resource->downloads_count) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Favoris</span>
                        <span class="font-semibold">{{ number_format($resource->favorites_count) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">J'aime</span>
                        <span class="font-semibold">{{ number_format($resource->likes_count) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Commentaires</span>
                        <span class="font-semibold">{{ number_format($resource->comments_count) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-300 p-6">
                <h3 class="text-lg font-semibold mb-4">Statut</h3>
                <div class="space-y-2">
                    @if($resource->is_featured)
                        <span class="inline-block px-3 py-1 text-sm font-semibold bg-yellow-100 text-yellow-800">
                            Mise en avant
                        </span>
                    @endif
                    @if($resource->is_active)
                        <span class="inline-block px-3 py-1 text-sm font-semibold bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 text-sm font-semibold bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>

            @if($resource->thumbnail)
            <div class="bg-white border border-gray-300 p-6">
                <h3 class="text-lg font-semibold mb-4">Image de couverture</h3>
                <img src="{{ Storage::url($resource->thumbnail) }}" alt="{{ $resource->title }}" class="w-full">
            </div>
            @endif
        </div>
    </div>
</div>
@endsection