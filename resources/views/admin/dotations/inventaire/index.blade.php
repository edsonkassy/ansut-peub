@extends('layouts.admin')

@section('title', 'Gestion de l\'Inventaire des Dotations - PEUB Admin')

@section('page-title', 'Inventaire des Dotations')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold">Liste des Articles en Inventaire</h2>
    <a href="{{ route('admin.dotations.inventaire.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center rounded-md border border-primary-700">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
        Ajouter un article
    </a>
</div>

<!-- Statistiques de l'inventaire -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white border border-gray-300 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 flex items-center justify-center">
                    <i data-lucide="package" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Articles Totaux</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_items'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white border border-gray-300 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 flex items-center justify-center">
                    <i data-lucide="archive" class="w-5 h-5 text-green-600"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Valeur Totale Stock</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_value'], 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
    </div>
    <div class="bg-white border border-gray-300 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 flex items-center justify-center">
                    <i data-lucide="battery-warning" class="w-5 h-5 text-yellow-600"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Stock Faible</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['low_stock'] }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white border border-gray-300 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Hors Stock</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['out_of_stock'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Liste des articles -->
<div class="bg-white border border-gray-300">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom de l'article</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock (Dispo/Attr/Total)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valeur Unitaire</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y-2 divide-gray-200">
                @forelse($inventaireItems as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            <a href="{{ route('admin.dotations.inventaire.show', $item) }}" class="hover:text-primary-600">
                                {{ $item->nom }}
                            </a>
                        </div>
                        <div class="text-xs text-gray-500">{{ $item->code_interne }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ 
                            match($item->type_dotation) {
                                'ordinateur_portable' => 'bg-blue-100 text-blue-700',
                                'connexion_internet' => 'bg-green-100 text-green-700',
                                'abonnement_ia' => 'bg-purple-100 text-purple-700',
                                default => 'bg-gray-100 text-gray-700'
                            }
                        }}">
                            {{ ucfirst(str_replace('_', ' ', $item->type_dotation)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            <span class="font-bold text-green-600">{{ $item->stock_disponible }}</span> /
                            <span class="text-yellow-600">{{ $item->stock_attribue }}</span> /
                            <span class="text-gray-500">{{ $item->stock_total }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ number_format($item->valeur_unitaire, 0, ',', ' ') }} FCFA</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->status == 'active')
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Actif</span>
                        @else
                             <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Inactif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.dotations.inventaire.show', $item) }}" class="text-gray-500 hover:text-primary-600 p-1">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.dotations.inventaire.edit', $item) }}" class="text-gray-500 hover:text-primary-600 p-1">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.dotations.inventaire.destroy', $item) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-600 p-1">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Aucun article dans l'inventaire pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t-2 border-gray-200">
        {{ $inventaireItems->links() }}
    </div>
</div>
@endsection 