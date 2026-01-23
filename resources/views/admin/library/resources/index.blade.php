@extends('layouts.admin')

@section('title', 'Ressources de la Bibliothèque - Admin')

@section('page-title', 'Ressources de la Bibliothèque')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-primary-700">Gestion des Ressources</h2>
        <a href="{{ route('admin.library.resources.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center gap-2 transition rounded-md border border-primary-700">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Nouvelle Ressource
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white border border-gray-300 p-4">
        <form method="GET" action="{{ route('admin.library.resources.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Rechercher..." 
                       value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <select name="category" class="px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="type" class="px-3 py-2 border border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Tous les types</option>
                    <option value="pdf" {{ request('type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Vidéo</option>
                    <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                    <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Document</option>
                    <option value="presentation" {{ request('type') == 'presentation' ? 'selected' : '' }}>Présentation</option>
                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Image</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white hover:bg-primary-700 transition">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Liste des ressources -->
    <div class="bg-white border border-gray-300 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ressource</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statistiques</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($resources as $resource)
                <tr>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $resource->title }}</p>
                            <p class="text-sm text-gray-500">Par {{ $resource->author ?? $resource->user->name }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-600">{{ $resource->category->name }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 uppercase">
                            {{ $resource->type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex gap-4">
                            <span title="Vues"><i data-lucide="eye" class="w-4 h-4 inline"></i> {{ $resource->views_count }}</span>
                            <span title="Téléchargements"><i data-lucide="download" class="w-4 h-4 inline"></i> {{ $resource->downloads_count }}</span>
                            <span title="Favoris"><i data-lucide="heart" class="w-4 h-4 inline"></i> {{ $resource->favorites_count }}</span>
                            <span title="Commentaires"><i data-lucide="message-circle" class="w-4 h-4 inline"></i> {{ $resource->comments_count }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col gap-1">
                            @if($resource->is_featured)
                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800">Mise en avant</span>
                            @endif
                            @if($resource->is_active)
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800">Inactive</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.library.resources.show', $resource) }}" class="text-gray-600 hover:text-gray-900">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.library.resources.edit', $resource) }}" class="text-primary-600 hover:text-primary-900">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.library.resources.destroy', $resource) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?')">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Aucune ressource trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $resources->withQueryString()->links() }}
</div>
@endsection