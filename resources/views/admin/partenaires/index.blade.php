@extends('layouts.admin')

@section('title', 'Gestion des Partenaires - PEUB Admin')

@section('page-title', 'Gestion des Partenaires')

@section('content')
<!-- Header Partenaires -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i data-lucide="building" class="w-6 h-6 mr-3 text-primary-600"></i>
                Gestion des Partenaires
            </h2>
            <p class="mt-1 text-gray-600">{{ $stats['total'] }} partenaires inscrits</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportPartenaires()" class="bg-secondary-600 hover:bg-secondary-700 text-white px-4 py-2 flex items-center">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                Exporter
            </button>
        </div>
    </div>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white border border-gray-300 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Partenaires</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
            </div>
            <i data-lucide="building" class="w-8 h-8 text-primary-600"></i>
        </div>
    </div>
    <div class="bg-white border border-gray-300 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Vérifiés</p>
                <p class="text-2xl font-bold text-primary-600">{{ number_format($stats['verifies']) }}</p>
            </div>
            <i data-lucide="check-circle" class="w-8 h-8 text-primary-600"></i>
        </div>
    </div>
    <div class="bg-white border border-gray-300 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">En Attente</p>
                <p class="text-2xl font-bold text-secondary-600">{{ number_format($stats['en_attente']) }}</p>
            </div>
            <i data-lucide="clock" class="w-8 h-8 text-secondary-600"></i>
        </div>
    </div>
    <div class="bg-white border border-gray-300 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Rejetés</p>
                <p class="text-2xl font-bold text-gray-600">{{ number_format($stats['rejetes']) }}</p>
            </div>
            <i data-lucide="x-circle" class="w-8 h-8 text-gray-600"></i>
        </div>
    </div>
</div>

<!-- Filtres et Recherche -->
<div class="bg-white border border-gray-300 p-6 mb-6">
    <form method="GET" action="{{ route('admin.partenaires.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Recherche -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Organisation, contact..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            
            <!-- Statut -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status_partenaire" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ request('status_partenaire') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="verifie" {{ request('status_partenaire') == 'verifie' ? 'selected' : '' }}>Vérifié</option>
                    <option value="rejete" {{ request('status_partenaire') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                </select>
            </div>
            
            <!-- Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type_organisation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Tous les types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type_organisation') == $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Région -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Région</label>
                <select name="region" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Toutes les régions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                            {{ $region }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mt-4 flex space-x-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                Filtrer
            </button>
            <a href="{{ route('admin.partenaires.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 flex items-center rounded-md border border-gray-700">
                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                Réinitialiser
            </a>
        </div>
    </form>
</div>

<!-- Liste des Partenaires -->
<div class="bg-white border border-gray-300">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organisation</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Région</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opportunités</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($partenaires as $partenaire)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 flex-shrink-0 border border-gray-200 overflow-hidden mr-4">
                                @if($partenaire->logo)
                                    <img src="{{ Storage::url($partenaire->logo) }}" 
                                         alt="Logo {{ $partenaire->nom_organisation }}" 
                                         class="w-full h-full object-contain">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                        <i data-lucide="building" class="w-6 h-6 text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('admin.partenaires.show', $partenaire) }}" 
                                   class="text-sm font-medium text-gray-900 hover:text-primary-600">
                                    {{ $partenaire->nom_organisation }}
                                </a>
                                @if($partenaire->site_web)
                                    <div class="text-sm text-gray-500">
                                        <a href="{{ $partenaire->site_web }}" target="_blank" 
                                           class="text-primary-600 hover:text-primary-700">
                                            {{ parse_url($partenaire->site_web, PHP_URL_HOST) }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $partenaire->personne_contact_nom }}</div>
                        <div class="text-sm text-gray-500">{{ $partenaire->personne_contact_email }}</div>
                        @if($partenaire->personne_contact_telephone)
                            <div class="text-sm text-gray-500">{{ $partenaire->personne_contact_telephone }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $partenaire->type_organisation ?? '')) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-900">{{ $partenaire->region }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @switch($partenaire->status_partenaire)
                            @case('verifie')
                                <span class="px-2 py-1 text-xs bg-primary-100 text-primary-700">Vérifié</span>
                                @break
                            @case('en_attente')
                                <span class="px-2 py-1 text-xs bg-secondary-100 text-secondary-700">En attente</span>
                                @break
                            @case('rejete')
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700">Rejeté</span>
                                @break
                            @default
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700">{{ $partenaire->status_partenaire }}</span>
                        @endswitch
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $partenaire->opportunites->count() }} opportunité(s)</div>
                        <div class="text-sm text-gray-500">
                            {{ $partenaire->opportunites->sum(function($opp) { return $opp->candidatures->count(); }) }} candidature(s)
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.partenaires.show', $partenaire) }}" 
                               class="text-primary-600 hover:text-primary-700" title="Voir détails">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            
                            @if($partenaire->status_partenaire === 'en_attente')
                                <form method="POST" action="{{ route('admin.partenaires.verify', $partenaire) }}" 
                                      class="inline" onsubmit="return confirm('Vérifier ce partenaire ?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-primary-600 hover:text-primary-700" title="Vérifier">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                
                                <button onclick="showRejectModal({{ $partenaire->id }})" 
                                        class="text-gray-600 hover:text-gray-700" title="Rejeter">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i data-lucide="building" class="w-12 h-12 mx-auto mb-4 text-gray-400"></i>
                        <p>Aucun partenaire trouvé avec ces critères.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($partenaires->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $partenaires->links() }}
    </div>
    @endif
</div>

<!-- Modal de rejet -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-6 max-w-md mx-auto">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rejeter le partenaire</h3>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motif du rejet</label>
                    <textarea name="motif_rejet" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Expliquez pourquoi ce partenaire est rejeté..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideRejectModal()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2">
                        Rejeter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});

function showRejectModal(partenaireId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = `/admin/partenaires/${partenaireId}/reject`;
    modal.classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function exportPartenaires() {
    alert('Fonctionnalité d\'export en cours de développement');
}
</script>
@endpush 