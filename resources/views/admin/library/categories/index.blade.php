@extends('layouts.admin')

@section('title', 'Catégories de la Bibliothèque - Admin')

@section('page-title', 'Catégories de la Bibliothèque')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-primary-700">Gestion des Catégories</h2>
        <a href="{{ route('admin.library.categories.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center gap-2 transition">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Nouvelle Catégorie
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-gray-300 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ressources</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($category->icon)
                                <i data-lucide="{{ $category->icon }}" class="w-5 h-5 mr-2" style="color: {{ $category->color ?? '#265BFF' }}"></i>
                            @endif
                            <span class="font-medium">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600 truncate max-w-xs">{{ $category->description }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800">
                            {{ $category->resources_count }} ressources
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($category->is_active)
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800">Actif</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800">Inactif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.library.categories.edit', $category) }}" class="text-primary-600 hover:text-primary-900">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.library.categories.destroy', $category) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Aucune catégorie trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $categories->links() }}
</div>
@endsection