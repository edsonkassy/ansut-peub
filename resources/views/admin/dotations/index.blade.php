@extends('layouts.admin')

@section('title', 'Gestion des Attributions - PEUB Admin')

@section('page-title', 'Gestion des Attributions')

@section('content')
<!-- En-tête -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i data-lucide="gift" class="w-6 h-6 mr-3 text-secondary-500"></i>
                Attributions de Dotations
            </h2>
            <p class="mt-1 text-gray-600">Suivi des dotations attribuées aux boursiers PEUB.</p>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-primary-100 p-4 border border-primary-200">
            <div class="text-2xl font-bold text-primary-600">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-600">Total Attributions</div>
        </div>
        <div class="bg-green-100 p-4 border border-green-200">
            <div class="text-2xl font-bold text-green-600">{{ $stats['actives'] }}</div>
            <div class="text-sm text-gray-600">Actives</div>
        </div>
        <div class="bg-secondary-100 p-4 border border-secondary-200">
            <div class="text-2xl font-bold text-secondary-600">{{ number_format($stats['montant_total']) }}</div>
            <div class="text-sm text-gray-600">F CFA Valeur Totale</div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select name="status" class="w-full border border-gray-300 px-3 py-2">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspendue" {{ request('status') == 'suspendue' ? 'selected' : '' }}>Suspendue</option>
                <option value="terminee" {{ request('status') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                <option value="retournee" {{ request('status') == 'retournee' ? 'selected' : '' }}>Retournée</option>
                <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Nom bachelier, nom article..." 
                   class="w-full border border-gray-300 px-3 py-2">
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 mr-2 flex items-center">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                Filtrer
            </button>
            <a href="{{ route('admin.dotations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 flex items-center rounded-md border border-gray-600">
                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Liste des dotations -->
<div class="bg-white border border-gray-300">
    <div class="p-6 ">
        <h3 class="text-lg font-semibold text-gray-900">Liste des Attributions</h3>
    </div>
    
    @if($dotations->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bachelier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article de Dotation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Attribution</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($dotations as $dotation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if($dotation->bachelier)
                            <a href="{{ route('admin.bacheliers.show', $dotation->bachelier) }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">
                                {{ $dotation->bachelier->nom_complet }}
                            </a>
                            <div class="text-sm text-gray-500">{{ $dotation->bachelier->email_eleve }}</div>
                            @else
                            <span class="text-sm text-red-500">Bachelier non trouvé</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($dotation->inventaire)
                            <a href="{{ route('admin.dotations.inventaire.show', $dotation->inventaire) }}" class="text-sm font-medium text-gray-900 hover:text-primary-600">
                                {{ $dotation->inventaire->nom }}
                            </a>
                            <div class="text-sm text-gray-500">{{ $dotation->inventaire->code_interne }}</div>
                            @else
                             <span class="text-sm text-red-500">Article non trouvé</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                             @if($dotation->inventaire)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ 
                                match($dotation->inventaire->type_dotation) {
                                    'ordinateur_portable' => 'bg-blue-100 text-blue-800',
                                    'connexion_internet' => 'bg-green-100 text-green-800',
                                    'abonnement_ia' => 'bg-purple-100 text-purple-800',
                                    default => 'bg-gray-100 text-gray-800'
                                }
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $dotation->inventaire->type_dotation)) }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ 
                                match($dotation->status) {
                                    'active' => 'bg-green-100 text-green-800',
                                    'suspendue' => 'bg-yellow-100 text-yellow-800',
                                    'terminee' => 'bg-red-100 text-red-800',
                                    'retournee' => 'bg-gray-100 text-gray-800',
                                    'en_attente' => 'bg-blue-100 text-blue-800',
                                    default => 'bg-gray-100 text-gray-800'
                                }
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $dotation->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $dotation->date_attribution ? $dotation->date_attribution->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.dotations.show', $dotation) }}" 
                                   class="text-gray-500 hover:text-primary-600 p-1">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.dotations.edit', $dotation) }}" 
                                   class="text-gray-500 hover:text-secondary-600 p-1">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $dotations->links() }}
        </div>
    @else
        <div class="p-6 text-center">
            <i data-lucide="gift" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune attribution trouvée</h3>
            <p class="text-gray-500">Aucune attribution ne correspond à vos critères de recherche.</p>
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