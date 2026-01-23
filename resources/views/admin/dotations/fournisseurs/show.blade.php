@extends('layouts.admin')

@section('title', 'Détails du Fournisseur - PEUB Admin')

@section('page-title', 'Détails du Fournisseur')

@section('content')
<!-- En-tête -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i data-lucide="truck" class="w-6 h-6 mr-3 text-secondary-500"></i>
                {{ $fournisseur->nom }}
            </h2>
            <p class="mt-1 text-gray-600">Détails du fournisseur</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.dotations.fournisseurs.edit', $fournisseur) }}" class="bg-secondary-600 hover:bg-secondary-700 text-white px-4 py-2 flex items-center">
                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('admin.dotations.fournisseurs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 flex items-center">
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
                <label class="block text-sm font-medium text-gray-700">Nom du fournisseur</label>
                <p class="mt-1 text-sm text-gray-900 font-semibold">{{ $fournisseur->nom }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Type de fournisseur</label>
                <span class="inline-flex px-2 py-1 text-xs font-semibold bg-primary-100 text-primary-800 rounded-full">
                    {{ ucfirst(str_replace('_', ' ', $fournisseur->type_fournisseur ?? 'Non spécifié')) }}
                </span>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Statut</label>
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
            </div>
            
            @if($fournisseur->adresse)
            <div>
                <label class="block text-sm font-medium text-gray-700">Adresse</label>
                <p class="mt-1 text-sm text-gray-900">{{ $fournisseur->adresse }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Informations de contact -->
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="phone" class="w-5 h-5 mr-2 text-green-600"></i>
            Informations de Contact
        </h3>
        
        <div class="space-y-4">
            @if($fournisseur->contact_nom)
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom du contact</label>
                <p class="mt-1 text-sm text-gray-900">{{ $fournisseur->contact_nom }}</p>
            </div>
            @endif
            
            @if($fournisseur->contact_email)
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <a href="mailto:{{ $fournisseur->contact_email }}" class="mt-1 text-sm text-primary-600 hover:text-primary-800">
                    {{ $fournisseur->contact_email }}
                </a>
            </div>
            @endif
            
            @if($fournisseur->contact_telephone)
            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                <a href="tel:{{ $fournisseur->contact_telephone }}" class="mt-1 text-sm text-primary-600 hover:text-primary-800">
                    {{ $fournisseur->contact_telephone }}
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Contrat -->
    @if($fournisseur->contrat_url)
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="file-text" class="w-5 h-5 mr-2 text-blue-600"></i>
            Contrat
        </h3>
        
        <div class="flex items-center space-x-3 p-4 bg-gray-50 border border-gray-200 rounded-md">
            <i data-lucide="file-text" class="w-8 h-8 text-gray-500"></i>
            <div>
                <p class="text-sm font-medium text-gray-900">Contrat de prestation</p>
                <a href="{{ $fournisseur->contrat_url }}" target="_blank" class="text-sm text-primary-600 hover:text-primary-800">
                    Télécharger le contrat
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Notes -->
    @if($fournisseur->notes)
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="file-text" class="w-5 h-5 mr-2 text-gray-600"></i>
            Notes
        </h3>
        
        <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
            <p class="text-sm text-gray-900">{{ $fournisseur->notes }}</p>
        </div>
    </div>
    @endif
</div>

<!-- Articles fournis -->
@if($fournisseur->inventaires && $fournisseur->inventaires->count() > 0)
<div class="bg-white border border-gray-300 mt-6 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <i data-lucide="package" class="w-5 h-5 mr-2 text-gray-600"></i>
        Articles Fournis
    </h3>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valeur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($fournisseur->inventaires as $article)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.dotations.inventaire.show', $article) }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">
                            {{ $article->nom }}
                        </a>
                        <div class="text-sm text-gray-500">{{ $article->code_interne }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ ucfirst(str_replace('_', ' ', $article->type_dotation)) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $article->stock_disponible }}/{{ $article->stock_total }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($article->valeur_unitaire, 0, ',', ' ') }} FCFA
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($article->status == 'active')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                Actif
                            </span>
                        @elseif($article->status == 'suspendu')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                Suspendu
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                Archivé
                            </span>
                        @endif
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