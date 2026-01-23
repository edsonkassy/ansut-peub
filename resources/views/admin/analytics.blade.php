@extends('layouts.admin')

@section('title', 'Analytics Avancées - PEUB Admin')

@section('content')
<div class="p-6">
    <!-- Header Analytics -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i data-lucide="trending-up" class="w-6 h-6 mr-3 text-primary-600"></i>
                    Analytics & Intelligence
                </h1>
                <p class="mt-1 text-gray-600">Tableau de bord des performances de la plateforme PEUB</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="window.location.reload()" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                    Actualiser
                </button>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        
        <!-- KPI Dashboard Simplifié -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Bacheliers -->
            <div class="bg-white shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Bacheliers</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($analytics['performance_globale']['total_bacheliers'] ?? 0) }}</p>
                        <p class="text-green-600 text-sm mt-1 flex items-center">
                            <i data-lucide="arrow-up" class="w-3 h-3 mr-1"></i>
                            {{ $analytics['performance_globale']['nouveaux_ce_mois'] ?? 0 }} ce mois
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 flex items-center justify-center rounded-lg">
                        <i data-lucide="graduation-cap" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Boursiers PEUB -->
            <div class="bg-white shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Boursiers PEUB</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($analytics['performance_globale']['total_boursiers'] ?? 0) }}</p>
                        <p class="text-green-600 text-sm mt-1">
                            {{ $analytics['performance_globale']['total_bacheliers'] > 0 ? 
                               round(($analytics['performance_globale']['total_boursiers'] / $analytics['performance_globale']['total_bacheliers']) * 100, 1) : 0 }}% du total
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 flex items-center justify-center rounded-lg">
                        <i data-lucide="award" class="w-6 h-6 text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Candidatures -->
            <div class="bg-white shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Candidatures</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($analytics['performance_globale']['total_candidatures'] ?? 0) }}</p>
                        <p class="text-green-600 text-sm mt-1 flex items-center">
                            <i data-lucide="arrow-up" class="w-3 h-3 mr-1"></i>
                            {{ $analytics['performance_globale']['nouvelles_candidatures_mois'] ?? 0 }} ce mois
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 flex items-center justify-center rounded-lg">
                        <i data-lucide="file-text" class="w-6 h-6 text-purple-600"></i>
                    </div>
                </div>
            </div>

            <!-- Taux de Conversion -->
            <div class="bg-white shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Taux d'Acceptation</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $analytics['performance_globale']['taux_conversion'] ?? 0 }}%</p>
                        <p class="text-sm mt-1 {{ ($analytics['performance_globale']['taux_conversion'] ?? 0) >= 70 ? 'text-green-600' : (($analytics['performance_globale']['taux_conversion'] ?? 0) >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                            @if(($analytics['performance_globale']['taux_conversion'] ?? 0) >= 70)
                                Excellent
                            @elseif(($analytics['performance_globale']['taux_conversion'] ?? 0) >= 50)
                                Bon
                            @else
                                À améliorer
                            @endif
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 flex items-center justify-center rounded-lg">
                        <i data-lucide="target" class="w-6 h-6 text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Partenaires Actifs</h3>
                        <p class="text-3xl font-bold text-primary-600 mt-2">{{ $analytics['stats_simples']['partenaires_actifs'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 flex items-center justify-center rounded-lg">
                        <i data-lucide="building" class="w-6 h-6 text-primary-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Opportunités Ouvertes</h3>
                        <p class="text-3xl font-bold text-secondary-600 mt-2">{{ $analytics['stats_simples']['opportunites_ouvertes'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-secondary-100 flex items-center justify-center rounded-lg">
                        <i data-lucide="briefcase" class="w-6 h-6 text-secondary-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">En Attente</h3>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $analytics['stats_simples']['candidatures_en_attente'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 flex items-center justify-center rounded-lg">
                        <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Académique -->
        <div class="bg-white shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <i data-lucide="bar-chart" class="w-5 h-5 mr-2 text-primary-600"></i>
                Performance Académique
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Moyenne BAC</p>
                    <p class="text-3xl font-bold text-primary-600">{{ $analytics['performance_globale']['moyenne_notes'] ?? 0 }}/20</p>
                </div>
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Nouvelles inscriptions</p>
                    <p class="text-3xl font-bold text-secondary-600">{{ $analytics['performance_globale']['nouveaux_ce_mois'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500 mt-1">ce mois</p>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="bg-white shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.bacheliers.index') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center mb-2">
                        <i data-lucide="users" class="w-5 h-5 text-primary-600 mr-2"></i>
                        <h4 class="font-medium text-gray-900">Gérer Bacheliers</h4>
                    </div>
                    <p class="text-sm text-gray-600">Consulter les profils</p>
                </a>
                <a href="{{ route('admin.opportunites.index') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center mb-2">
                        <i data-lucide="briefcase" class="w-5 h-5 text-secondary-600 mr-2"></i>
                        <h4 class="font-medium text-gray-900">Opportunités</h4>
                    </div>
                    <p class="text-sm text-gray-600">Modérer les offres</p>
                </a>
                <a href="{{ route('admin.reports') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                    <div class="flex items-center mb-2">
                        <i data-lucide="file-text" class="w-5 h-5 text-purple-600 mr-2"></i>
                        <h4 class="font-medium text-gray-900">Rapports</h4>
                    </div>
                    <p class="text-sm text-gray-600">Générer des rapports</p>
                </a>
            </div>
        </div>

    </div>
</div>

<script>
// Auto-refresh toutes les 5 minutes pour garder les données à jour
setTimeout(() => {
    window.location.reload();
}, 300000); // 5 minutes
</script>
@endsection 