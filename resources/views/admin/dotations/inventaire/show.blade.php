@extends('layouts.admin')

@section('title', 'Détails de l\'Article - PEUB Admin')

@section('page-title', 'Détails de l\'Article')

@section('content')
<!-- En-tête -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i data-lucide="package" class="w-6 h-6 mr-3 text-secondary-500"></i>
                {{ $inventaire->nom }}
            </h2>
            <p class="mt-1 text-gray-600">Détails de l'article d'inventaire</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.dotations.inventaire.edit', $inventaire) }}" class="bg-secondary-600 hover:bg-secondary-700 text-white px-4 py-2 flex items-center rounded-md">
                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('admin.dotations.inventaire.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 flex items-center rounded-md">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Retour à la liste
            </a>
        </div>
    </div>
</div>

@include('components.flash-messages')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Informations principales -->
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="info" class="w-5 h-5 mr-2 text-primary-600"></i>
            Informations Principales
        </h3>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom de l'article</label>
                <p class="mt-1 text-sm text-gray-900">{{ $inventaire->nom }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Code interne</label>
                <p class="mt-1 text-sm text-gray-900">{{ $inventaire->code_interne }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Type de dotation</label>
                <span class="inline-flex px-2 py-1 text-xs font-semibold bg-primary-100 text-primary-800 rounded-full">
                    {{ ucfirst(str_replace('_', ' ', $inventaire->type_dotation)) }}
                </span>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Statut</label>
                @if($inventaire->status == 'active')
                    <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                        Actif
                    </span>
                @elseif($inventaire->status == 'suspendu')
                    <span class="inline-flex px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                        Suspendu
                    </span>
                @else
                    <span class="inline-flex px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                        Archivé
                    </span>
                @endif
            </div>
            
            @if($inventaire->description)
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <p class="mt-1 text-sm text-gray-900">{{ $inventaire->description }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Informations financières -->
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="dollar-sign" class="w-5 h-5 mr-2 text-green-600"></i>
            Informations Financières
        </h3>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Valeur unitaire</label>
                <p class="mt-1 text-sm text-gray-900 font-semibold">{{ number_format($inventaire->valeur_unitaire, 0, ',', ' ') }} FCFA</p>
            </div>
            
            @if($inventaire->prix_mensuel)
            <div>
                <label class="block text-sm font-medium text-gray-700">Prix mensuel</label>
                <p class="mt-1 text-sm text-gray-900 font-semibold">{{ number_format($inventaire->prix_mensuel, 0, ',', ' ') }} FCFA/mois</p>
            </div>
            @endif
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Valeur totale du stock</label>
                <p class="mt-1 text-sm text-gray-900 font-semibold text-primary-600">
                    {{ number_format($inventaire->valeur_unitaire * $inventaire->stock_total, 0, ',', ' ') }} FCFA
                </p>
            </div>
            
            @if($inventaire->date_achat)
            <div>
                <label class="block text-sm font-medium text-gray-700">Date d'achat</label>
                <p class="mt-1 text-sm text-gray-900">{{ $inventaire->date_achat->format('d/m/Y') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Informations de stock -->
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="archive" class="w-5 h-5 mr-2 text-blue-600"></i>
            Gestion du Stock
        </h3>
        
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stock total</label>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $inventaire->stock_total }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stock disponible</label>
                    <p class="mt-1 text-lg font-semibold {{ $inventaire->stock_disponible <= $inventaire->stock_minimum ? 'text-red-600' : 'text-green-600' }}">
                        {{ $inventaire->stock_disponible }}
                    </p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stock attribué</label>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $inventaire->stock_attribue }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stock minimum</label>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $inventaire->stock_minimum }}</p>
                </div>
            </div>
            
            <!-- Alerte stock faible -->
            @if($inventaire->stock_disponible <= $inventaire->stock_minimum)
            <div class="bg-red-50 border border-red-200 p-3 rounded-md">
                <div class="flex items-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 mr-2"></i>
                    <p class="text-sm text-red-800 font-medium">Stock faible !</p>
                </div>
                <p class="text-sm text-red-700 mt-1">Le stock disponible est inférieur ou égal au stock minimum.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Détails techniques -->
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="settings" class="w-5 h-5 mr-2 text-gray-600"></i>
            Détails Techniques
        </h3>
        
        <div class="space-y-4">
            @if($inventaire->marque)
            <div>
                <label class="block text-sm font-medium text-gray-700">Marque</label>
                <p class="mt-1 text-sm text-gray-900">{{ $inventaire->marque }}</p>
            </div>
            @endif
            
            @if($inventaire->modele)
            <div>
                <label class="block text-sm font-medium text-gray-700">Modèle</label>
                <p class="mt-1 text-sm text-gray-900">{{ $inventaire->modele }}</p>
            </div>
            @endif
            
            @if($inventaire->caracteristiques)
            <div>
                <label class="block text-sm font-medium text-gray-700">Caractéristiques</label>
                <p class="mt-1 text-sm text-gray-900">{{ $inventaire->caracteristiques }}</p>
            </div>
            @endif
            
            @if($inventaire->duree_validite)
            <div>
                <label class="block text-sm font-medium text-gray-700">Durée de validité</label>
                <p class="mt-1 text-sm text-gray-900">{{ $inventaire->duree_validite }}</p>
            </div>
            @endif
            
            @if($inventaire->fournisseur)
            <div>
                <label class="block text-sm font-medium text-gray-700">Fournisseur</label>
                <a href="{{ route('admin.dotations.fournisseurs.show', $inventaire->fournisseur) }}" class="mt-1 text-sm text-primary-600 hover:text-primary-800">
                    {{ $inventaire->fournisseur->nom }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Historique des mouvements -->
@if($inventaire->mouvementsStock && $inventaire->mouvementsStock->count() > 0)
<div class="bg-white border border-gray-300 mt-6 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <i data-lucide="history" class="w-5 h-5 mr-2 text-gray-600"></i>
        Historique des Mouvements
    </h3>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Effectué par</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarques</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($inventaire->mouvementsStock->take(10) as $mouvement)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $mouvement->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($mouvement->type_mouvement == 'entree')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                Entrée
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                Sortie
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $mouvement->type_mouvement == 'entree' ? '+' : '-' }}{{ $mouvement->quantite }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $mouvement->effectuePar->nom ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $mouvement->remarques ?? '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush 