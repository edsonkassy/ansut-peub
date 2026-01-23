@extends('layouts.admin')

@section('title', 'Gestion des Bacheliers - PEUB Admin')

@section('page-title', 'Gestion des Bacheliers')

@section('content')
<!-- Statistiques -->
@if($stats['has_filters'])
<div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-blue-700">
                <strong>Résultats filtrés :</strong> Affichage de <strong>{{ $stats['total'] }}</strong> résultat(s) sur <strong>{{ $stats['total_global'] }}</strong> bachelier(s) total(aux)
            </p>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white border {{ $stats['has_filters'] ? 'border-blue-300 bg-blue-50' : 'border-gray-300' }} p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 {{ $stats['has_filters'] ? 'bg-blue-200' : 'bg-blue-100' }} flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Bacheliers</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                @if($stats['has_filters'])
                    <p class="text-xs text-gray-500 mt-1">Global: {{ $stats['total_global'] }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white border {{ $stats['has_filters'] ? 'border-green-300 bg-green-50' : 'border-gray-300' }} p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 {{ $stats['has_filters'] ? 'bg-green-200' : 'bg-green-100' }} flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Profils Vérifiés</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['verifies'] }}</p>
                @if($stats['has_filters'])
                    <p class="text-xs text-gray-500 mt-1">Global: {{ $stats['verifies_global'] }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white border {{ $stats['has_filters'] ? 'border-yellow-300 bg-yellow-50' : 'border-gray-300' }} p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 {{ $stats['has_filters'] ? 'bg-yellow-200' : 'bg-yellow-100' }} flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">En Attente</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['en_attente'] }}</p>
                @if($stats['has_filters'])
                    <p class="text-xs text-gray-500 mt-1">Global: {{ $stats['en_attente_global'] }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white border {{ $stats['has_filters'] ? 'border-purple-300 bg-purple-50' : 'border-gray-300' }} p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 {{ $stats['has_filters'] ? 'bg-purple-200' : 'bg-purple-100' }} flex items-center justify-center">
                    <i data-lucide="award" class="w-5 h-5 text-purple-600"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Boursiers PEUB</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['boursiers'] }}</p>
                @if($stats['has_filters'])
                    <p class="text-xs text-gray-500 mt-1">Global: {{ $stats['boursiers_global'] }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white border border-gray-300 p-6 mb-6">
    <form method="GET" action="{{ route('admin.bacheliers.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            <!-- Recherche -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nom, prénom, email..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            
            <!-- Statut Profil -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut Profil</label>
                <select name="status_profil" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Tous les statuts</option>
                    @foreach($status_profils as $value => $label)
                        <option value="{{ $value }}" {{ request('status_profil') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Boursier PEUB -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bourse PEUB</label>
                <select name="boursier_peub" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Tous</option>
                    <option value="1" {{ request('boursier_peub') == '1' ? 'selected' : '' }}>Boursiers</option>
                    <option value="0" {{ request('boursier_peub') == '0' ? 'selected' : '' }}>Non boursiers</option>
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
            
            <!-- Série Bac -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Série Bac</label>
                <select name="serie_bac" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Toutes les séries</option>
                    @foreach($series as $value => $label)
                        <option value="{{ $value }}" {{ request('serie_bac') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Année d'obtention du Bac -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Année Bac</label>
                <select name="annee_bac" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Toutes les années</option>
                    @foreach($annees_bac as $value => $label)
                        <option value="{{ $value }}" {{ request('annee_bac') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mt-4 flex space-x-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center rounded-md">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                Filtrer
            </button>
            <a href="{{ route('admin.bacheliers.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 flex items-center rounded-md border border-gray-700">
                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                Réinitialiser
            </a>
        </div>
    </form>
</div>

<!-- Information sur le classement temporaire -->
<div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-blue-700">
                <strong>Classement temporaire :</strong> Les bacheliers sont automatiquement classés par score PEUB (du plus élevé au plus bas). 
                Le rang affiché est <strong>temporaire</strong> et peut évoluer au fur et à mesure des nouvelles inscriptions.
            </p>
        </div>
    </div>
</div>

<!-- Liste des Bacheliers -->
<div class="bg-white border border-gray-300">
    <!-- Actions de masse (masquées par défaut) -->
    <div id="bulkActions" class="hidden border-b-2 border-gray-200 p-4">
        <form id="bulkForm" method="POST" action="{{ route('admin.bacheliers.bulk-validate') }}">
            @csrf
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">
                        <span id="selectedCount">0</span> bachelier(s) sélectionné(s)
                    </span>
                    <select name="action" class="px-3 py-2 border border-gray-300 text-sm">
                        <option value="">Choisir une action</option>
                        <option value="valider">Valider les profils</option>
                        <option value="rejeter">Marquer comme incomplets</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 text-sm">
                        Appliquer
                    </button>
                    <button type="button" onclick="hideBulkActions()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 text-sm rounded-md border border-gray-700">
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="text-primary-600 focus:ring-primary-500 border-gray-300">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bachelier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Série</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Année</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bac (Note, Mention, Moyenne)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score PEUB</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PEUB</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y-2 divide-gray-200">
                @forelse($bacheliers as $bachelier)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <input type="checkbox" name="bachelier_ids[]" value="{{ $bachelier->id }}" 
                               class="bachelier-checkbox text-primary-600 focus:ring-primary-500 border-gray-300"
                               onchange="updateBulkActions()">
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary-100 flex items-center justify-center mr-4">
                                @if($bachelier->photo_profil)
                                    <img src="{{ asset('storage/' . $bachelier->photo_profil) }}" alt="Photo" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <i data-lucide="user" class="w-5 h-5 text-primary-600"></i>
                                @endif
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('admin.bacheliers.show', $bachelier) }}" class="hover:text-primary-600">
                                        {{ $bachelier->nom }} {{ $bachelier->prenoms }}
                                    </a>
                                </div>
                                <div class="text-xs text-gray-500">{{ $bachelier->region }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $bachelier->email_eleve }}</div>
                        <div class="text-sm text-gray-500">{{ $bachelier->telephone_eleve }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $bachelier->etablissement_nom ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $bachelier->serie_bac ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $bachelier->annee_bac ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 font-medium">
                            {{ $bachelier->note_bac ? number_format($bachelier->note_bac, 2) : 'N/A' }}
                        </div>
                        @if($bachelier->mention)
                            <span class="px-2 py-1 text-xs rounded-full font-medium mt-1 inline-block {{ 
                                match($bachelier->mention) {
                                    'passable' => 'bg-gray-100 text-gray-700',
                                    'assez_bien' => 'bg-blue-100 text-blue-700',
                                    'bien' => 'bg-green-100 text-green-700',
                                    'tres_bien' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-700'
                                }
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $bachelier->mention)) }}
                            </span>
                        @endif
                        <div class="text-xs text-gray-500 mt-1">
                            Moyenne: {{ $bachelier->note_bac ? number_format(($bachelier->note_bac / 400) * 20, 2) : 'N/A' }}/20
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($bachelier->score_final_peub !== null)
                            <div class="text-sm font-medium text-gray-900">
                                {{ number_format($bachelier->score_final_peub, 2) }}/100
                            </div>
                            @if($bachelier->rang_temporaire)
                                <div class="flex items-center mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        Rang #{{ $bachelier->rang_temporaire }}
                                    </span>
                                    <span class="ml-1 text-xs text-gray-400" title="Ce rang est temporaire et peut évoluer avec les nouvelles inscriptions">
                                        *
                                    </span>
                                </div>
                            @endif
                        @else
                            <span class="text-sm text-gray-400">Non évalué</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($bachelier->boursier_peub)
                            <span class="px-2 py-1 text-xs bg-secondary-100 text-secondary-700 rounded-full">
                                <i data-lucide="award" class="w-3 h-3 inline mr-1"></i>
                                Boursier
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">Non boursier</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.bacheliers.show', $bachelier) }}" 
                               class="text-primary-600 hover:text-primary-900">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.bacheliers.edit', $bachelier) }}" 
                               class="text-secondary-600 hover:text-secondary-900">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            
                            @if($bachelier->user && $bachelier->user->isPending())
                                <form method="POST" action="{{ route('admin.bacheliers.validate', $bachelier->user->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 rounded-md" 
                                            onclick="return confirm('Valider ce bachelier ?')">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif
                            
                            @if($bachelier->user && $bachelier->user->isActive())
                                <form method="POST" action="{{ route('admin.bacheliers.suspend', $bachelier->user->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900 rounded-md" 
                                            onclick="return confirm('Suspendre ce bachelier ?')">
                                        <i data-lucide="pause-circle" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i data-lucide="users" class="w-12 h-12 text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun bachelier trouvé</h3>
                            <p class="text-gray-500">Aucun bachelier ne correspond aux critères de recherche.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($bacheliers->hasPages())
    <div class="px-6 py-4 border-t-2 border-gray-200">
        {{ $bacheliers->links() }}
    </div>
    @endif
</div>

<!-- Boutons d'action en bas à gauche -->
<div class="mt-6 flex justify-start space-x-3">
    <button onclick="exportBacheliers()" class="bg-secondary-600 hover:bg-secondary-700 text-white px-4 py-2 flex items-center border-2 border-secondary-600 hover:border-secondary-700 rounded-md">
        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
        Exporter
    </button>
    <button onclick="showBulkActions()" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center border-2 border-primary-600 hover:border-primary-700 rounded-md">
        <i data-lucide="check-square" class="w-4 h-4 mr-2"></i>
        Actions Groupées
    </button>
</div>
@endsection

@push('scripts')
<script>
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const bachelierCheckboxes = document.querySelectorAll('.bachelier-checkbox');
    
    bachelierCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateBulkActions();
}

function showBulkActions() {
    document.getElementById('bulkActions').classList.remove('hidden');
}

function hideBulkActions() {
    document.getElementById('bulkActions').classList.add('hidden');
    document.getElementById('selectAll').checked = false;
    document.querySelectorAll('.bachelier-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.bachelier-checkbox:checked');
    const count = checkboxes.length;
    
    document.getElementById('selectedCount').textContent = count;
    
    if (count > 0) {
        document.getElementById('bulkActions').classList.remove('hidden');
        
        // Ajouter les IDs au formulaire
        const form = document.getElementById('bulkForm');
        const existingInputs = form.querySelectorAll('input[name="bachelier_ids[]"]');
        existingInputs.forEach(input => input.remove());
        
        checkboxes.forEach(checkbox => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'bachelier_ids[]';
            hiddenInput.value = checkbox.value;
            form.appendChild(hiddenInput);
        });
    }
}

function exportBacheliers() {
    // Récupérer les paramètres de filtrage actuels
    const params = new URLSearchParams(window.location.search);
    
    // Créer une URL pour l'export avec les filtres
    const exportUrl = "{{ route('admin.bacheliers.export-excel') }}" + '?' + params.toString();
    
    // Télécharger le fichier
    window.location.href = exportUrl;
}
</script>
@endpush 