@extends('layouts.app')

@section('title', 'Analytics Bacheliers & Boursiers - PEUB')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i data-lucide="map-pin" class="w-8 h-8 mr-3 text-primary-500"></i>
            Analytics Bacheliers & Boursiers
        </h1>
        <p class="mt-2 text-gray-600">Statistiques démographiques des bacheliers et boursiers en Côte d'Ivoire</p>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total bacheliers -->
        <div class="bg-white border border-gray-200 p-6  shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="users" class="w-8 h-8 text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Bacheliers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_bacheliers']) }}</p>
                </div>
            </div>
        </div>

        <!-- Bacheliers vérifiés -->
        <div class="bg-white border border-gray-200 p-6  shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="check-circle" class="w-8 h-8 text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Vérifiés</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['bacheliers_verifies']) }}</p>
                </div>
            </div>
        </div>

        <!-- Total boursiers -->
        <div class="bg-white border border-gray-200 p-6  shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="award" class="w-8 h-8 text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Boursiers Actifs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_boursiers']) }}</p>
                </div>
            </div>
        </div>

        <!-- En attente -->
        <div class="bg-white border border-gray-200 p-6  shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i data-lucide="clock" class="w-8 h-8 text-yellow-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">En attente</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['bacheliers_en_attente']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte Mapbox et statistiques -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Carte Mapbox et répartitions -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Carte Mapbox -->
            <div class="bg-white border border-gray-200  shadow-sm">
                <div class="p-6 ">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i data-lucide="map" class="w-5 h-5 mr-2 text-primary-500"></i>
                        Répartition géographique des bacheliers
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Carte interactive des bacheliers et boursiers en Côte d'Ivoire</p>
                </div>
                <div class="p-6">
                    <!-- Filtres par cohorte -->
                    <div class="flex gap-4 mb-4">
                        <button onclick="filterCohort('all')" class="cohort-pill active" data-cohort="all">
                            Tous les bacheliers
                        </button>
                        <button onclick="filterCohort('boursiers')" class="cohort-pill" data-cohort="boursiers">
                            Boursiers uniquement
                        </button>
                        <button onclick="filterCohort('verifies')" class="cohort-pill" data-cohort="verifies">
                            Vérifiés uniquement
                        </button>
                    </div>
                    
                    <div id="map" class="w-full h-[768px]  map-container"></div>
                    
                    <!-- Tooltip pour les informations au hover -->
                    <div id="tooltip" class="absolute bg-white p-3 shadow-lg border border-gray-200  pointer-events-none opacity-0 transition-opacity duration-200 z-50">
                        <div id="tooltip-content"></div>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-center space-x-6 text-sm">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                            <span>Boursiers actifs</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
                            <span>Bacheliers vérifiés</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-gray-500 rounded-full mr-2"></div>
                            <span>En attente</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Répartition par genre et âge -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Répartition par genre -->
                <div class="bg-white border border-gray-200  shadow-sm">
                    <div class="p-6 ">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i data-lucide="users" class="w-5 h-5 mr-2 text-primary-500"></i>
                            Répartition par genre
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($stats_par_genre->count() > 0)
                            <div class="flex items-center justify-center mb-6">
                                <div class="relative w-40 h-40">
                                    <canvas id="genreChart" width="160" height="160"></canvas>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="text-xl font-bold text-gray-900">{{ number_format($stats_par_genre->sum('count')) }}</div>
                                            <div class="text-xs text-gray-600">Total</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                @foreach($stats_par_genre as $genre)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 rounded-full mr-3 {{ $genre->sexe == 'M' ? 'bg-blue-500' : 'bg-pink-500' }}"></div>
                                        <span class="text-sm font-medium text-gray-700">
                                            @if($genre->sexe == 'F') Femmes @elseif($genre->sexe == 'M') Hommes @else {{ $genre->sexe }} @endif
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-bold text-gray-900">{{ number_format($genre->count) }}</div>
                                        <div class="text-xs text-gray-500">
                                            @php
                                                $total = $stats_par_genre->sum('count');
                                                $percentage = $total > 0 ? round(($genre->count / $total) * 100, 1) : 0;
                                            @endphp
                                            {{ $percentage }}%
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
                        @endif
                    </div>
                </div>

                <!-- Répartition par tranche d'âge -->
                <div class="bg-white border border-gray-200  shadow-sm">
                    <div class="p-6 ">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i data-lucide="calendar" class="w-5 h-5 mr-2 text-primary-500"></i>
                            Répartition par tranche d'âge
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($stats_par_age->count() > 0)
                            <div class="flex items-center justify-center mb-6">
                                <div class="relative w-40 h-40">
                                    <canvas id="ageChart" width="160" height="160"></canvas>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="text-xl font-bold text-gray-900">{{ number_format($stats_par_age->sum('count')) }}</div>
                                            <div class="text-xs text-gray-600">Total</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                @foreach($stats_par_age as $index => $age)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6'][$index % 5] }}"></div>
                                        <span class="text-sm font-medium text-gray-700">{{ $age->tranche_age }}</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-bold text-gray-900">{{ number_format($age->count) }}</div>
                                        <div class="text-xs text-gray-500">
                                            @php
                                                $total = $stats_par_age->sum('count');
                                                $percentage = $total > 0 ? round(($age->count / $total) * 100, 1) : 0;
                                            @endphp
                                            {{ $percentage }}%
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribution par régions -->
        <div class="bg-white border border-gray-200  shadow-sm">
            <div class="p-6 ">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 mr-2 text-primary-500"></i>
                    Distribution par Régions
                </h3>
                <p class="text-sm text-gray-600 mt-1">Répartition des bacheliers par région</p>
            </div>
            <div class="p-6">
                @if($top_regions->count() > 0)
                    <div class="space-y-4">
                        @foreach($top_regions as $region)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">{{ $region->region ?: 'Non spécifiée' }}</span>
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                    @php
                                        $max = $top_regions->max('count');
                                        $percentage = $max > 0 ? ($region->count / $max) * 100 : 0;
                                    @endphp
                                    <div class="bg-primary-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($region->count) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Total des régions : {{ $top_regions->count() }}</span>
                            <span>Total bacheliers : {{ number_format($top_regions->sum('count')) }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Bacheliers récents -->
    <div class="bg-white border border-gray-200  shadow-sm">
        <div class="p-6 ">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i data-lucide="user-plus" class="w-5 h-5 mr-2 text-primary-500"></i>
                Bacheliers récemment inscrits
            </h3>
        </div>
        <div class="p-6">
            @if($bacheliers_recents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Région</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ville</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($bacheliers_recents as $bachelier)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $bachelier->nom }} {{ $bachelier->prenoms }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $bachelier->region }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $bachelier->commune }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($bachelier->boursier_peub) bg-green-100 text-green-800
                                        @elseif($bachelier->status_profil == 'verifie') bg-blue-100 text-blue-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        @if($bachelier->boursier_peub)
                                            Boursier
                                        @elseif($bachelier->status_profil == 'verifie')
                                            Vérifié
                                        @else
                                            En attente
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">
                                    {{ $bachelier->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucun bachelier récent</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.cohort-pill {
    padding: 0.5rem 0.75rem;
    background-color: #f3f4f6;
    color: #6b7280;
    font-weight: 500;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
    border-radius: 0;
}

.cohort-pill.active {
    background-color: #2256a3 !important;
    color: white !important;
}

.cohort-pill:hover:not(.active) {
    background-color: #e5e7eb;
}

.marker-boursier {
    background-color: #10B981;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    box-shadow: 0 3px 6px rgba(0,0,0,0.3);
    cursor: pointer;
    transition: all 0.2s ease;
}

.marker-boursier:hover {
    transform: scale(1.4);
    box-shadow: 0 6px 12px rgba(16, 185, 129, 0.4);
}

.marker-verifie {
    background-color: #3B82F6;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    box-shadow: 0 3px 6px rgba(0,0,0,0.3);
    cursor: pointer;
    transition: all 0.2s ease;
}

.marker-verifie:hover {
    transform: scale(1.4);
    box-shadow: 0 6px 12px rgba(59, 130, 246, 0.4);
}

#map {
    border-radius: 0.5rem !important;
    min-height: 768px;
    background-color: #f3f4f6;
    position: relative;
}

#map .mapboxgl-canvas {
    border-radius: 0.5rem !important;
}

/* Améliorer l'affichage des contrôles Mapbox */
.mapboxgl-ctrl-bottom-left,
.mapboxgl-ctrl-bottom-right {
    display: none !important;
}
</style>

<script>
// Token Mapbox depuis l'environnement Laravel
mapboxgl.accessToken = 'pk.eyJ1IjoibGFtaW5lYmFycm8iLCJhIjoiY20zZHMzOW9zMDc5dzJsczgwdWVoZ2NqYyJ9.3baMsQ3_mpKlnBdHCeu0kg';

let map;
let markers = [];
let currentCohort = 'all';
let allBacheliersData = @json($donnees_carte);

// Configuration des graphiques en donut
function createDonutCharts() {
    // Données pour le graphique par genre
    const genreData = @json($stats_par_genre);
    if (genreData.length > 0) {
        const genreCtx = document.getElementById('genreChart');
        if (genreCtx) {
            new Chart(genreCtx, {
                type: 'doughnut',
                data: {
                    labels: genreData.map(item => item.sexe === 'F' ? 'Femmes' : 'Hommes'),
                    datasets: [{
                        data: genreData.map(item => item.count),
                        backgroundColor: ['#EC4899', '#3B82F6'],
                        borderWidth: 0,
                        cutout: '70%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return `${context.label}: ${context.parsed} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Données pour le graphique par âge
    const ageData = @json($stats_par_age);
    if (ageData.length > 0) {
        const ageCtx = document.getElementById('ageChart');
        if (ageCtx) {
            new Chart(ageCtx, {
                type: 'doughnut',
                data: {
                    labels: ageData.map(item => item.tranche_age),
                    datasets: [{
                        data: ageData.map(item => item.count),
                        backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6'],
                        borderWidth: 0,
                        cutout: '70%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return `${context.label}: ${context.parsed} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    }
}

function initMap() {
    // Vérifier que Mapbox GL JS est chargé
    if (typeof mapboxgl === 'undefined') {
        console.error('Mapbox GL JS n\'est pas chargé');
        showMapFallback();
        return;
    }

    // Vérifier que le token est défini
    if (!mapboxgl.accessToken) {
        console.error('Token Mapbox manquant');
        showMapFallback();
        return;
    }

    try {
        map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/light-v11',
            center: [-5.5, 7.5], // Centre de la Côte d'Ivoire
            zoom: 6.5,
            attributionControl: false,
            logoPosition: 'bottom-right',
            maxZoom: 12,
            minZoom: 5
        });

        map.on('load', function() {
            console.log('Carte Mapbox chargée avec succès');
            displayMarkers(currentCohort);
        });

        map.on('error', function(e) {
            console.error('Erreur Mapbox:', e);
            showMapFallback();
        });

        // Gérer les erreurs de style
        map.on('styleimagemissing', function(e) {
            console.warn('Image de style manquante:', e.id);
        });

    } catch (error) {
        console.error('Erreur lors de l\'initialisation de la carte:', error);
        showMapFallback();
    }
}

function showMapFallback() {
    const mapElement = document.getElementById('map');
    if (mapElement) {
        mapElement.innerHTML = `
            <div class="flex items-center justify-center h-full bg-gray-100 text-gray-600">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                    </svg>
                    <p class="font-semibold">Carte interactive des bacheliers</p>
                    <p class="text-sm mt-2">Service temporairement indisponible</p>
                    <div class="mt-4 grid grid-cols-2 gap-2 max-w-xs mx-auto">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    </div>
                    <p class="text-xs mt-2">🟢 Boursiers | 🔵 Vérifiés</p>
                </div>
            </div>
        `;
    }
}

function displayMarkers(cohort) {
    if (!map) {
        console.warn('Carte non initialisée, impossible d\'afficher les marqueurs');
        return;
    }

    // Supprimer les anciens marqueurs
    markers.forEach(marker => {
        try {
            marker.remove();
        } catch (e) {
            console.warn('Erreur lors de la suppression du marqueur:', e);
        }
    });
    markers = [];

    // Filtrer les données selon la cohorte
    let filteredData = allBacheliersData;
    if (cohort === 'boursiers') {
        filteredData = allBacheliersData.filter(item => item.properties.status_boursier === 'active');
    } else if (cohort === 'verifies') {
        filteredData = allBacheliersData.filter(item => item.properties.status_boursier === 'verifie');
    }
    
    if (!filteredData || !Array.isArray(filteredData)) {
        console.error('Données de bacheliers invalides pour la cohorte:', cohort);
        return;
    }
    
    filteredData.forEach((bachelier, index) => {
        try {
            // Vérifier les coordonnées
            if (!bachelier.geometry || !bachelier.geometry.coordinates || 
                typeof bachelier.geometry.coordinates[0] !== 'number' || 
                typeof bachelier.geometry.coordinates[1] !== 'number') {
                console.warn('Coordonnées invalides pour le bachelier:', bachelier.properties.nom);
                return;
            }
            
            // Créer l'élément marqueur
            const el = document.createElement('div');
            const status = bachelier.properties.status_boursier;
            if (status === 'active') {
                el.className = 'marker-boursier';
            } else if (status === 'verifie') {
                el.className = 'marker-verifie';
            } else if (cohort === 'all' && status === 'inactive') {
                el.className = 'marker-inactive';
                el.style.backgroundColor = '#6B7280';
                el.style.width = '14px';
                el.style.height = '14px';
                el.style.borderRadius = '50%';
                el.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
            } else {
                return;
            }
            el.setAttribute('data-bachelier-id', index);
            
            // Créer le marqueur
            const marker = new mapboxgl.Marker(el)
                .setLngLat(bachelier.geometry.coordinates)
                .addTo(map);
            
            // Événements hover avec gestion d'erreurs
            el.addEventListener('mouseenter', function(e) {
                try {
                    showTooltip(e, bachelier.properties);
                } catch (error) {
                    console.warn('Erreur lors de l\'affichage du tooltip:', error);
                }
            });
            
            el.addEventListener('mouseleave', function() {
                try {
                    hideTooltip();
                } catch (error) {
                    console.warn('Erreur lors du masquage du tooltip:', error);
                }
            });
            
            markers.push(marker);
        } catch (error) {
            console.error('Erreur lors de la création du marqueur pour:', bachelier.properties.nom, error);
        }
    });
    
    console.log(`${markers.length} marqueurs affichés pour la cohorte ${cohort}`);
}

function filterCohort(cohort) {
    currentCohort = cohort;
    
    // Mettre à jour les pills avec la fonction dédiée
    updateActivePill(cohort);
    
    // Afficher les nouveaux marqueurs
    displayMarkers(cohort);
}

function showTooltip(e, properties) {
    const tooltip = document.getElementById('tooltip');
    const tooltipContent = document.getElementById('tooltip-content');
    
    const statusClass = properties.status_boursier === 'active' ? 'bg-green-100 text-green-800' : 
                       properties.status_boursier === 'verifie' ? 'bg-blue-100 text-blue-800' : 
                       'bg-gray-100 text-gray-800';
    
    const statusText = properties.status_boursier === 'active' ? 'Boursier' : 
                      properties.status_boursier === 'verifie' ? 'Vérifié' : 'En attente';
    
    tooltipContent.innerHTML = `
        <div class="flex items-center mb-2">
            <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                ${properties.nom.charAt(0).toUpperCase()}
            </div>
            <div>
                <div class="font-semibold text-gray-900">${properties.nom}</div>
                <div class="text-sm text-gray-600">${properties.region}</div>
            </div>
        </div>
        <div class="text-sm text-gray-600">
            <div class="flex items-center mb-1">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                ${properties.commune}
            </div>
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                ${statusText}
            </span>
        </div>
    `;
    
    tooltip.style.left = e.pageX + 10 + 'px';
    tooltip.style.top = e.pageY - 10 + 'px';
    tooltip.classList.remove('opacity-0');
    tooltip.classList.add('opacity-100');
}

function hideTooltip() {
    const tooltip = document.getElementById('tooltip');
    tooltip.classList.remove('opacity-100');
    tooltip.classList.add('opacity-0');
}

function updateActivePill(cohort) {
    // Retirer la classe active de toutes les pills
    document.querySelectorAll('.cohort-pill').forEach(pill => {
        pill.classList.remove('active');
    });
    
    // Ajouter la classe active à la pill sélectionnée
    const targetPill = document.querySelector(`[data-cohort="${cohort}"]`);
    if (targetPill) {
        targetPill.classList.add('active');
    }
}

// Initialiser la carte quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // S'assurer que la pill active est correctement stylée au chargement
    updateActivePill('all');
    
    // Créer les graphiques en donut
    createDonutCharts();
    
    // Attendre que le contenu soit complètement chargé
    setTimeout(function() {
        if (document.getElementById('map')) {
            initMap();
        } else {
            console.error('Élément map introuvable');
        }
    }, 100);
});

// Vérification périodique si la carte ne se charge pas
setTimeout(function() {
    if (!map && document.getElementById('map')) {
        console.warn('Carte non initialisée après 5 secondes');
        showMapFallback();
    }
}, 5000);
</script>
@endpush
@endsection 