@extends('layouts.admin')

@section('title', 'Rapports - PEUB Admin')

@section('page-title', 'Génération de Rapports')

@section('content')
<!-- Header Rapports -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i data-lucide="file-text" class="w-6 h-6 mr-3 text-primary-600"></i>
                Rapports & Documentation
            </h2>
            <p class="mt-1 text-gray-600">Rapports exécutifs pour ministères et bailleurs de fonds</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="scheduleReport()" class="bg-secondary-600 hover:bg-secondary-700 text-white px-4 py-2 flex items-center">
                <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                Programmer
            </button>
            <button onclick="generateCustomReport()" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Rapport Custom
            </button>
        </div>
    </div>
</div>

<div class="space-y-8">
    
    <!-- Rapports Prêts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Rapport Mensuel -->
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary-100 flex items-center justify-center mr-4">
                        <i data-lucide="calendar" class="w-6 h-6 text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Rapport Mensuel</h3>
                        <p class="text-sm text-gray-600">{{ $reports['rapport_mensuel']['periode'] ?? now()->format('F Y') }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-secondary-100 text-secondary-700 text-sm">Automatique</span>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50">
                    <div class="text-xl font-bold text-primary-600">{{ $reports['rapport_mensuel']['nouveaux_bacheliers'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600">Nouveaux Bacheliers</div>
                </div>
                <div class="text-center p-3 bg-gray-50">
                    <div class="text-xl font-bold text-secondary-600">{{ $reports['rapport_mensuel']['nouveaux_partenaires'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600">Nouveaux Partenaires</div>
                </div>
                <div class="text-center p-3 bg-gray-50">
                    <div class="text-xl font-bold text-primary-600">{{ $reports['rapport_mensuel']['nouvelles_opportunites'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600">Opportunités</div>
                </div>
                <div class="text-center p-3 bg-gray-50">
                    <div class="text-xl font-bold text-secondary-600">{{ $reports['rapport_mensuel']['dotations_attribuees'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600">Dotations</div>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <button onclick="generateReport('monthly')" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2 text-sm">
                    <i data-lucide="download" class="w-4 h-4 inline mr-1"></i>
                    Générer PDF
                </button>
                <button onclick="shareReport('monthly')" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-sm">
                    <i data-lucide="share" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Rapport Cohortes -->
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-secondary-100 flex items-center justify-center mr-4">
                        <i data-lucide="users" class="w-6 h-6 text-secondary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Analyse des Cohortes</h3>
                        <p class="text-sm text-gray-600">Évolution par année d'inscription</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-primary-100 text-primary-700 text-sm">Trimestriel</span>
            </div>

            <div class="space-y-2 mb-4">
                @if(isset($reports['rapport_cohortes']) && count($reports['rapport_cohortes']) > 0)
                    @foreach($reports['rapport_cohortes']->take(3) as $cohorte)
                    <div class="flex justify-between items-center p-2 bg-gray-50">
                        <span class="text-sm font-medium">{{ $cohorte->annee }}</span>
                        <div class="text-right">
                            <div class="text-sm font-bold text-gray-900">{{ $cohorte->total_inscrits }}</div>
                            <div class="text-xs text-gray-500">{{ $cohorte->boursiers_selectes }} boursiers</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Données simulées -->
                    <div class="flex justify-between items-center p-2 bg-gray-50">
                        <span class="text-sm font-medium">2024</span>
                        <div class="text-right">
                            <div class="text-sm font-bold text-gray-900">234</div>
                            <div class="text-xs text-gray-500">58 boursiers</div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-gray-50">
                        <span class="text-sm font-medium">2023</span>
                        <div class="text-right">
                            <div class="text-sm font-bold text-gray-900">189</div>
                            <div class="text-xs text-gray-500">45 boursiers</div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex space-x-2">
                <button onclick="generateReport('cohort')" class="flex-1 bg-secondary-600 hover:bg-secondary-700 text-white py-2 text-sm">
                    <i data-lucide="download" class="w-4 h-4 inline mr-1"></i>
                    Générer PDF
                </button>
                <button onclick="shareReport('cohort')" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-sm">
                    <i data-lucide="share" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Rapport Régional -->
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary-100 flex items-center justify-center mr-4">
                        <i data-lucide="map" class="w-6 h-6 text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Impact Régional</h3>
                        <p class="text-sm text-gray-600">Répartition géographique</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-secondary-100 text-secondary-700 text-sm">Annuel</span>
            </div>

            <div class="space-y-2 mb-4">
                @if(isset($reports['rapport_regional']) && count($reports['rapport_regional']) > 0)
                    @foreach($reports['rapport_regional']->take(5) as $region)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700">{{ $region->region }}</span>
                        <div class="text-right">
                            <div class="text-sm font-medium text-primary-600">{{ $region->total_bacheliers }}</div>
                            <div class="text-xs text-gray-500">{{ round($region->moyenne_region, 1) }} moy.</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Données simulées -->
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700">Abidjan</span>
                        <div class="text-right">
                            <div class="text-sm font-medium text-primary-600">89</div>
                            <div class="text-xs text-gray-500">16.2 moy.</div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700">Bouaké</span>
                        <div class="text-right">
                            <div class="text-sm font-medium text-primary-600">45</div>
                            <div class="text-xs text-gray-500">15.8 moy.</div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700">Yamoussoukro</span>
                        <div class="text-right">
                            <div class="text-sm font-medium text-primary-600">34</div>
                            <div class="text-xs text-gray-500">16.5 moy.</div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex space-x-2">
                <button onclick="generateReport('regional')" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2 text-sm">
                    <i data-lucide="download" class="w-4 h-4 inline mr-1"></i>
                    Générer PDF
                </button>
                <button onclick="shareReport('regional')" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-sm">
                    <i data-lucide="share" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Rapport d'Impact -->
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-secondary-100 flex items-center justify-center mr-4">
                        <i data-lucide="trending-up" class="w-6 h-6 text-secondary-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Impact & Performance</h3>
                        <p class="text-sm text-gray-600">Indicateurs de succès</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-primary-100 text-primary-700 text-sm">Semestriel</span>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="text-center p-2 bg-gray-50">
                    <div class="text-lg font-bold text-primary-600">{{ $reports['rapport_impact']['taux_reussite_global'] ?? '72' }}%</div>
                    <div class="text-xs text-gray-600">Taux Réussite</div>
                </div>
                <div class="text-center p-2 bg-gray-50">
                    <div class="text-lg font-bold text-secondary-600">{{ $reports['rapport_impact']['retention_boursiers'] ?? '92' }}%</div>
                    <div class="text-xs text-gray-600">Rétention</div>
                </div>
                <div class="text-center p-2 bg-gray-50">
                    <div class="text-lg font-bold text-primary-600">{{ $reports['rapport_impact']['satisfaction_bacheliers'] ?? '4.3' }}/5</div>
                    <div class="text-xs text-gray-600">Satisfaction</div>
                </div>
                <div class="text-center p-2 bg-gray-50">
                    <div class="text-lg font-bold text-secondary-600">{{ $reports['rapport_impact']['insertion_professionnelle'] ?? '78' }}%</div>
                    <div class="text-xs text-gray-600">Insertion Pro</div>
                </div>
            </div>

            <div class="flex space-x-2">
                <button onclick="generateReport('impact')" class="flex-1 bg-secondary-600 hover:bg-secondary-700 text-white py-2 text-sm">
                    <i data-lucide="download" class="w-4 h-4 inline mr-1"></i>
                    Générer PDF
                </button>
                <button onclick="shareReport('impact')" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 text-sm">
                    <i data-lucide="share" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Historique des Rapports -->
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="history" class="w-5 h-5 mr-2 text-primary-600"></i>
            Historique des Rapports
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="">
                        <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Type de Rapport</th>
                        <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Période</th>
                        <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Généré le</th>
                        <th class="text-left py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="text-right py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-sm text-gray-900">Rapport Mensuel</td>
                        <td class="py-3 text-sm text-gray-700">Décembre 2024</td>
                        <td class="py-3 text-sm text-gray-700">{{ now()->format('d/m/Y H:i') }}</td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs bg-secondary-100 text-secondary-700">Généré</span>
                        </td>
                        <td class="py-3 text-right">
                            <button class="text-primary-600 hover:text-primary-700 text-sm mr-2">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </button>
                            <button class="text-gray-600 hover:text-gray-700 text-sm">
                                <i data-lucide="share" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-sm text-gray-900">Analyse Cohortes</td>
                        <td class="py-3 text-sm text-gray-700">Q4 2024</td>
                        <td class="py-3 text-sm text-gray-700">{{ now()->subDays(7)->format('d/m/Y H:i') }}</td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs bg-secondary-100 text-secondary-700">Généré</span>
                        </td>
                        <td class="py-3 text-right">
                            <button class="text-primary-600 hover:text-primary-700 text-sm mr-2">
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </button>
                            <button class="text-gray-600 hover:text-gray-700 text-sm">
                                <i data-lucide="share" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-sm text-gray-900">Impact Annuel</td>
                        <td class="py-3 text-sm text-gray-700">2024</td>
                        <td class="py-3 text-sm text-gray-700">{{ now()->subDays(15)->format('d/m/Y H:i') }}</td>
                        <td class="py-3">
                            <span class="px-2 py-1 text-xs bg-primary-100 text-primary-700">En cours</span>
                        </td>
                        <td class="py-3 text-right">
                            <button class="text-gray-400 text-sm mr-2" disabled>
                                <i data-lucide="download" class="w-4 h-4"></i>
                            </button>
                            <button class="text-gray-400 text-sm" disabled>
                                <i data-lucide="share" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Configuration des Rapports Automatiques -->
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i data-lucide="settings" class="w-5 h-5 mr-2 text-secondary-600"></i>
            Configuration Automatique
        </h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-900">Rapports Mensuels</h4>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-checked:bg-primary-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>
                <p class="text-sm text-gray-600 mb-2">Envoi automatique le 1er de chaque mois</p>
                <input type="email" placeholder="admin@ansut.ci" class="w-full px-3 py-2 border border-gray-300 text-sm" value="admin@ansut.ci">
            </div>

            <div class="border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-900">Alertes Seuils</h4>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-checked:bg-secondary-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>
                <p class="text-sm text-gray-600 mb-2">Notification si taux < 70%</p>
                <input type="number" placeholder="70" class="w-full px-3 py-2 border border-gray-300 text-sm" value="70">
            </div>

            <div class="border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-900">Backup Automatique</h4>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-checked:bg-primary-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>
                <p class="text-sm text-gray-600 mb-2">Sauvegarde hebdomadaire</p>
                <select class="w-full px-3 py-2 border border-gray-300 text-sm">
                    <option>Google Drive</option>
                    <option>OneDrive</option>
                    <option>Serveur Local</option>
                </select>
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

function generateReport(type) {
    const reportTypes = {
        'monthly': 'Rapport Mensuel',
        'cohort': 'Analyse des Cohortes',
        'regional': 'Impact Régional',
        'impact': 'Rapport d\'Impact'
    };
    
    alert(`Génération du ${reportTypes[type]} en cours...\n\nLe rapport sera disponible dans quelques minutes.`);
}

function shareReport(type) {
    alert('Fonctionnalité de partage en cours de développement');
}

function scheduleReport() {
    alert('Interface de programmation des rapports en cours de développement');
}

function generateCustomReport() {
    alert('Créateur de rapports personnalisés en cours de développement');
}
</script>
@endpush 