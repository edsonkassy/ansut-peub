@extends('layouts.admin')

@section('title', 'Exports de Données - PEUB Admin')

@section('page-title', 'Export de Données')

@section('content')
<!-- Header Exports -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i data-lucide="download" class="w-6 h-6 mr-3 text-primary-600"></i>
                Export de Données
            </h2>
            <p class="mt-1 text-gray-600">Exportation des données pour analyse externe et reporting</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="bulkExport()" class="bg-secondary-600 hover:bg-secondary-700 text-white px-4 py-2 flex items-center">
                <i data-lucide="package" class="w-4 h-4 mr-2"></i>
                Export Groupé
            </button>
            <button onclick="scheduleExport()" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center">
                <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                Programmer
            </button>
        </div>
    </div>
</div>

<div class="space-y-8">
    
    <!-- Export Options -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        
        @if(isset($export_options))
            @foreach($export_options as $key => $option)
            <div class="bg-white border border-gray-300 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-primary-100 flex items-center justify-center mr-4">
                        @switch($key)
                            @case('bacheliers')
                                <i data-lucide="graduation-cap" class="w-6 h-6 text-primary-600"></i>
                                @break
                            @case('partenaires')
                                <i data-lucide="building" class="w-6 h-6 text-secondary-600"></i>
                                @break
                            @case('opportunites')
                                <i data-lucide="target" class="w-6 h-6 text-primary-600"></i>
                                @break
                            @case('candidatures')
                                <i data-lucide="file-text" class="w-6 h-6 text-secondary-600"></i>
                                @break
                            @case('dotations')
                                <i data-lucide="gift" class="w-6 h-6 text-primary-600"></i>
                                @break
                            @case('analytics')
                                <i data-lucide="bar-chart-3" class="w-6 h-6 text-secondary-600"></i>
                                @break
                            @default
                                <i data-lucide="database" class="w-6 h-6 text-primary-600"></i>
                        @endswitch
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $option['title'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $option['description'] }}</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @foreach($option['formats'] as $format)
                    <button onclick="exportData('{{ $key }}', '{{ $format }}')" 
                            class="w-full px-4 py-2 border border-gray-300 hover:bg-gray-50 text-left flex items-center justify-between">
                        <span class="flex items-center">
                            @switch($format)
                                @case('excel')
                                    <i data-lucide="table" class="w-4 h-4 mr-2 text-primary-600"></i>
                                    Fichier Excel (.xlsx)
                                    @break
                                @case('csv')
                                    <i data-lucide="file-spreadsheet" class="w-4 h-4 mr-2 text-secondary-600"></i>
                                    Fichier CSV
                                    @break
                                @case('pdf')
                                    <i data-lucide="file-text" class="w-4 h-4 mr-2 text-primary-600"></i>
                                    Document PDF
                                    @break
                                @case('powerpoint')
                                    <i data-lucide="presentation" class="w-4 h-4 mr-2 text-secondary-600"></i>
                                    Présentation PPT
                                    @break
                                @default
                                    <i data-lucide="download" class="w-4 h-4 mr-2 text-gray-600"></i>
                                    {{ strtoupper($format) }}
                            @endswitch
                        </span>
                        <i data-lucide="download" class="w-4 h-4 text-gray-400"></i>
                    </button>
                    @endforeach
                </div>
            </div>
            @endforeach
        @else
            <!-- Options par défaut si pas de données -->
            <div class="bg-white border border-gray-300 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-primary-100 flex items-center justify-center mr-4">
                        <i data-lucide="graduation-cap" class="w-6 h-6 text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Données Bacheliers</h3>
                        <p class="text-sm text-gray-600">Export complet des profils bacheliers</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <button onclick="exportData('bacheliers', 'excel')" class="w-full px-4 py-2 border border-gray-300 hover:bg-gray-50 text-left flex items-center justify-between">
                        <span class="flex items-center">
                            <i data-lucide="table" class="w-4 h-4 mr-2 text-primary-600"></i>
                            Fichier Excel (.xlsx)
                        </span>
                        <i data-lucide="download" class="w-4 h-4 text-gray-400"></i>
                    </button>
                    <button onclick="exportData('bacheliers', 'csv')" class="w-full px-4 py-2 border border-gray-300 hover:bg-gray-50 text-left flex items-center justify-between">
                        <span class="flex items-center">
                            <i data-lucide="file-spreadsheet" class="w-4 h-4 mr-2 text-secondary-600"></i>
                            Fichier CSV
                        </span>
                        <i data-lucide="download" class="w-4 h-4 text-gray-400"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Historique des Exports -->
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="history" class="w-5 h-5 mr-2 text-primary-600"></i>
            Historique des Exports
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="">
                        <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Type de Données</th>
                        <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Format</th>
                        <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Généré le</th>
                        <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Taille</th>
                        <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="text-right py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-sm text-gray-900">Données Bacheliers</td>
                        <td class="py-3 text-sm text-gray-700">Excel</td>
                        <td class="py-3 text-sm text-gray-700">{{ now()->format('d/m/Y H:i') }}</td>
                        <td class="py-3 text-sm text-gray-700">2.4 MB</td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs bg-secondary-100 text-secondary-700">Disponible</span>
                        </td>
                        <td class="py-3 text-right">
                            <button class="text-primary-600 hover:text-primary-700 text-sm mr-2" title="Télécharger">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </button>
                            <button class="text-gray-600 hover:text-gray-700 text-sm" title="Partager">
                                <i data-lucide="share" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-sm text-gray-900">Analytics Complet</td>
                        <td class="py-3 text-sm text-gray-700">PDF</td>
                        <td class="py-3 text-sm text-gray-700">{{ now()->subHours(2)->format('d/m/Y H:i') }}</td>
                        <td class="py-3 text-sm text-gray-700">8.7 MB</td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs bg-secondary-100 text-secondary-700">Disponible</span>
                        </td>
                        <td class="py-3 text-right">
                            <button class="text-primary-600 hover:text-primary-700 text-sm mr-2" title="Télécharger">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </button>
                            <button class="text-gray-600 hover:text-gray-700 text-sm" title="Partager">
                                <i data-lucide="share" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-sm text-gray-900">Données Partenaires</td>
                        <td class="py-3 text-sm text-gray-700">CSV</td>
                        <td class="py-3 text-sm text-gray-700">{{ now()->subDays(1)->format('d/m/Y H:i') }}</td>
                        <td class="py-3 text-sm text-gray-700">456 KB</td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs bg-primary-100 text-primary-700">En cours</span>
                        </td>
                        <td class="py-3 text-right">
                            <button class="text-gray-400 text-sm mr-2" disabled title="En traitement">
                                <i data-lucide="loader" class="w-4 h-4 animate-spin"></i>
                            </button>
                            <button class="text-gray-400 text-sm" disabled>
                                <i data-lucide="share" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-sm text-gray-900">Export Groupé</td>
                        <td class="py-3 text-sm text-gray-700">ZIP</td>
                        <td class="py-3 text-sm text-gray-700">{{ now()->subDays(3)->format('d/m/Y H:i') }}</td>
                        <td class="py-3 text-sm text-gray-700">15.2 MB</td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs bg-secondary-100 text-secondary-700">Disponible</span>
                        </td>
                        <td class="py-3 text-right">
                            <button class="text-primary-600 hover:text-primary-700 text-sm mr-2" title="Télécharger">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </button>
                            <button class="text-gray-600 hover:text-gray-700 text-sm" title="Partager">
                                <i data-lucide="share" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Configuration des Exports -->
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="settings" class="w-5 h-5 mr-2 text-secondary-600"></i>
            Configuration des Exports
        </h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Filtres d'Export -->
            <div>
                <h4 class="font-medium text-gray-900 mb-3">Filtres d'Export</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" class="px-3 py-2 border border-gray-300 text-sm" placeholder="Du">
                            <input type="date" class="px-3 py-2 border border-gray-300 text-sm" placeholder="Au">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Régions</label>
                        <select multiple class="w-full px-3 py-2 border border-gray-300 text-sm h-24">
                            <option value="abidjan">Abidjan</option>
                            <option value="bouake">Bouaké</option>
                            <option value="yamoussoukro">Yamoussoukro</option>
                            <option value="korhogo">Korhogo</option>
                            <option value="san-pedro">San-Pédro</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select class="w-full px-3 py-2 border border-gray-300 text-sm">
                            <option value="">Tous les statuts</option>
                            <option value="active">Actif</option>
                            <option value="pending">En attente</option>
                            <option value="verified">Vérifié</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Options d'Export -->
            <div>
                <h4 class="font-medium text-gray-900 mb-3">Options d'Export</h4>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="include-photos" class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <label for="include-photos" class="ml-2 text-sm text-gray-700">Inclure les photos de profil</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="include-docs" class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <label for="include-docs" class="ml-2 text-sm text-gray-700">Inclure les documents joints</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="anonymize" class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <label for="anonymize" class="ml-2 text-sm text-gray-700">Anonymiser les données personnelles</label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="compress" checked class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <label for="compress" class="ml-2 text-sm text-gray-700">Compresser les fichiers</label>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format de date</label>
                        <select class="w-full px-3 py-2 border border-gray-300 text-sm">
                            <option value="dd/mm/yyyy">DD/MM/YYYY</option>
                            <option value="mm/dd/yyyy">MM/DD/YYYY</option>
                            <option value="yyyy-mm-dd">YYYY-MM-DD</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Encodage</label>
                        <select class="w-full px-3 py-2 border border-gray-300 text-sm">
                            <option value="utf-8">UTF-8</option>
                            <option value="iso-8859-1">ISO-8859-1</option>
                            <option value="windows-1252">Windows-1252</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Les exports volumineux peuvent prendre plusieurs minutes à générer.
                </div>
                <button onclick="applyExportConfig()" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 text-sm">
                    Appliquer la Configuration
                </button>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});

function exportData(type, format) {
    const notifications = {
        'bacheliers': 'Export des données bacheliers',
        'partenaires': 'Export des données partenaires',
        'opportunites': 'Export des opportunités',
        'candidatures': 'Export des candidatures',
        'dotations': 'Export des dotations',
        'analytics': 'Export du rapport analytics'
    };
    
    const formatNames = {
        'excel': 'Excel',
        'csv': 'CSV',
        'pdf': 'PDF',
        'powerpoint': 'PowerPoint'
    };
    
    alert(`${notifications[type]} en format ${formatNames[format]} en cours...\n\nVous recevrez une notification lorsque l'export sera prêt.`);
    
    // Simulation d'ajout à l'historique
    console.log(`Export initiated: ${type} -> ${format}`);
}

function bulkExport() {
    alert('Export groupé de toutes les données en cours...\n\nCela peut prendre plusieurs minutes. Vous recevrez un email une fois terminé.');
}

function scheduleExport() {
    alert('Interface de programmation d\'exports automatiques en cours de développement');
}

function applyExportConfig() {
    alert('Configuration d\'export sauvegardée avec succès !');
}
</script>
@endpush 