@extends('layouts.admin')

@section('title', 'Gestion des Fournisseurs - PEUB Admin')

@section('page-title', 'Gestion des Fournisseurs')

@section('content')
<!-- En-tête -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i data-lucide="truck" class="w-6 h-6 mr-3 text-secondary-500"></i>
                Gestion des Fournisseurs
            </h2>
            <p class="mt-1 text-gray-600">Gestion des fournisseurs et prestataires pour les dotations PEUB.</p>
        </div>
        <a href="{{ route('admin.dotations.fournisseurs.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center rounded-md border border-primary-700">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Nouveau Fournisseur
        </a>
    </div>
    
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 flex items-center justify-center">
                        <i data-lucide="truck" class="w-5 h-5 text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Fournisseurs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $fournisseurs->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Actifs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $fournisseurs->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 flex items-center justify-center">
                        <i data-lucide="pause-circle" class="w-5 h-5 text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Suspendus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $fournisseurs->where('status', 'suspendu')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gray-100 flex items-center justify-center">
                        <i data-lucide="archive" class="w-5 h-5 text-gray-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Archivés</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $fournisseurs->where('status', 'archive')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.flash-messages')

<!-- Filtres -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Nom, email, téléphone..." 
                   class="w-full border border-gray-300 px-3 py-2">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select name="status" class="w-full border border-gray-300 px-3 py-2">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                <option value="suspendu" {{ request('status') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                <option value="archive" {{ request('status') == 'archive' ? 'selected' : '' }}>Archivé</option>
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 mr-2 flex items-center">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                Filtrer
            </button>
            <a href="{{ route('admin.dotations.fournisseurs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 flex items-center rounded-md border border-gray-600">
                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Liste des fournisseurs -->
<div class="bg-white border border-gray-300">
    @if($fournisseurs->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fournisseur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contrat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($fournisseurs as $fournisseur)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $fournisseur->nom }}</div>
                            <div class="text-sm text-gray-500">{{ $fournisseur->type_fournisseur }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $fournisseur->contact_nom }}</div>
                            <div class="text-sm text-gray-500">{{ $fournisseur->contact_email }}</div>
                            <div class="text-sm text-gray-500">{{ $fournisseur->contact_telephone }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($fournisseur->status == 'active')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                    Actif
                                </span>
                            @elseif($fournisseur->status == 'suspendu')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                    Suspendu
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                    Archivé
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($fournisseur->contrat_url)
                                <a href="{{ $fournisseur->contrat_url }}" target="_blank" class="text-primary-600 hover:text-primary-800">
                                    <i data-lucide="file-text" class="w-4 h-4 inline mr-1"></i>
                                    Voir contrat
                                </a>
                            @else
                                <span class="text-gray-400">Aucun contrat</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.dotations.fournisseurs.show', $fournisseur) }}" 
                                   class="text-gray-500 hover:text-primary-600 p-1" title="Voir">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.dotations.fournisseurs.edit', $fournisseur) }}" 
                                   class="text-gray-500 hover:text-secondary-600 p-1" title="Modifier">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.dotations.fournisseurs.destroy', $fournisseur) }}" 
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-600 p-1" title="Supprimer">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $fournisseurs->links() }}
        </div>
    @else
        <div class="p-6 text-center">
            <i data-lucide="truck" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun fournisseur trouvé</h3>
            <p class="text-gray-500">Aucun fournisseur ne correspond à vos critères de recherche.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush 