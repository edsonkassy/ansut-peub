@extends('layouts.admin')

@section('title', 'Détails de l\'Attribution - PEUB Admin')

@section('page-title')
    Détails de l'Attribution
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Colonne principale -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informations sur l'attribution -->
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $dotation->inventaire->nom }}</h3>
                    <p class="text-gray-500">Attribué à 
                        <a href="{{ route('admin.bacheliers.show', $dotation->bachelier) }}" class="text-primary-600 font-semibold hover:underline">
                            {{ $dotation->bachelier->nom_complet }}
                        </a>
                    </p>
                </div>
                <div class="text-right">
                     <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ 
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
                    <p class="text-sm text-gray-500 mt-1">
                        Valeur: {{ number_format($dotation->inventaire->valeur_unitaire, 0, ',', ' ') }} FCFA
                    </p>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Type de dotation</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $dotation->inventaire->type_dotation)) }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Date d'attribution</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dotation->date_attribution->format('d/m/Y') }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Date de début</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dotation->date_debut ? $dotation->date_debut->format('d/m/Y') : 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Date de fin</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dotation->date_fin ? $dotation->date_fin->format('d/m/Y') : 'N/A' }}</dd>
                    </div>
                     <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Identifiant Unique</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dotation->identifiant_unique ?? 'N/A' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Attribué par</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dotation->attribuePar->email ?? 'Système' }}</dd>
                    </div>
                    @if($dotation->status === 'suspendue')
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Raison de la suspension</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dotation->raison_suspension }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <!-- Colonne latérale -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Actions -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.dotations.edit', $dotation) }}" class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center justify-center">
                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                    Modifier le Statut
                </a>
                <form action="{{ route('admin.dotations.destroy', $dotation) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette attribution ? Cette action est irréversible.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 flex items-center justify-center">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                        Supprimer l'Attribution
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Informations sur l'article -->
        <div class="bg-white border border-gray-300 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">À propos de l'article</h3>
            <dl>
                 <div class="sm:col-span-1 mb-2">
                    <dt class="text-sm font-medium text-gray-500">Marque</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $dotation->inventaire->marque ?? 'N/A' }}</dd>
                </div>
                 <div class="sm:col-span-1 mb-2">
                    <dt class="text-sm font-medium text-gray-500">Modèle</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $dotation->inventaire->modele ?? 'N/A' }}</dd>
                </div>
                 <div class="sm:col-span-1 mb-2">
                    <dt class="text-sm font-medium text-gray-500">Fournisseur</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $dotation->inventaire->fournisseur->nom ?? 'N/A' }}</dd>
                </div>
            </dl>
            <a href="{{ route('admin.dotations.inventaire.show', $dotation->inventaire) }}" class="mt-4 text-primary-600 hover:underline flex items-center">
                Voir l'article dans l'inventaire <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
            </a>
        </div>
    </div>
</div>
@endsection 