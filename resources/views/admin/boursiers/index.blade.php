@extends('layouts.admin')

@section('title', 'Visualisation des Boursiers')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Nos Boursiers d'Excellence</h1>
        <p class="mt-2 text-sm text-gray-700">Répartition géographique et visualisation des parcours de nos boursiers PEUB</p>
    </div>

    <!-- Statistiques Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Boursiers</p>
                        <p class="text-3xl font-bold text-gray-900" id="total-boursiers">{{ $stats['total_boursiers'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-pink-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Filles</p>
                        <p class="text-3xl font-bold text-gray-900" id="total-filles">{{ $stats['total_filles'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Garçons</p>
                        <p class="text-3xl font-bold text-gray-900" id="total-garcons">{{ $stats['total_garcons'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Actifs</p>
                        <p class="text-3xl font-bold text-gray-900" id="total-actifs">{{ $stats['total_actifs'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Filtres par sexe -->
    <div class="bg-white overflow-hidden shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrer par sexe</h3>
        <form method="GET" action="{{ route('admin.boursiers.map') }}" class="flex gap-4">
            <div class="flex items-center">
                <input type="checkbox" id="sexe-filles" name="sexe[]" value="F" 
                       {{ in_array('F', $selectedGenders) ? 'checked' : '' }} 
                       class="w-4 h-4 text-pink-600 border-gray-300 rounded">
                <label for="sexe-filles" class="ml-2 text-sm font-medium text-gray-700">Filles</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="sexe-garcons" name="sexe[]" value="M" 
                       {{ in_array('M', $selectedGenders) ? 'checked' : '' }} 
                       class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                <label for="sexe-garcons" class="ml-2 text-sm font-medium text-gray-700">Garçons</label>
            </div>
            <button type="submit" class="ml-4 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md text-sm font-medium">
                Appliquer
            </button>
        </form>
    </div>

    <!-- Carte interactive des Boursiers -->
    <div class="bg-white overflow-hidden shadow-sm border border-gray-200">
        <div class="p-6">
            <!-- Filtres par régions -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex gap-2 flex-wrap">
                    <button onclick="filterRegions('Toutes')" class="region-pill active" data-region="Toutes">
                        Toutes les Régions
                    </button>
                    <!-- Régions dynamiques -->
                    <div id="region-buttons-container"></div>
                </div>
                <div class="text-sm text-gray-600">
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-pink-100 text-pink-800 mr-2">
                        <span class="w-2 h-2 bg-pink-500 mr-1"></span>
                        Filles
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                        <span class="w-2 h-2 bg-blue-600 mr-1"></span>
                        Garçons
                    </span>
                </div>
            </div>

            <!-- Carte Mapbox -->
            <div id="map" class="w-full h-[600px] border border-gray-300 map-container"></div>

            <!-- Tooltip pour les informations au hover -->
            <div id="tooltip" class="absolute bg-white p-3 shadow-lg border border-gray-200 pointer-events-none opacity-0 transition-opacity duration-200 z-50">
                <div id="tooltip-content"></div>
            </div>
        </div>
    </div>
</div>

<!-- Mapbox CSS et JS -->
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>

<style>
.region-pill {
    padding: 0.5rem 0.75rem;
    background-color: #f8fafc;
    color: #64748b;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #e2e8f0;
    font-size: 0.75rem;
    white-space: nowrap;
}

.region-pill.active {
    background-color: #1e40af !important;
    color: white !important;
    border-color: #1e40af !important;
    box-shadow: 0 1px 3px 0 rgba(30, 64, 175, 0.3);
}

.region-pill:hover:not(.active) {
    background-color: #e2e8f0;
    border-color: #cbd5e1;
    transform: translateY(-1px);
}

.marker-female {
    background-color: #ec4899;
    width: 18px;
    height: 18px;
    border: 2px solid white;
    border-radius: 50%;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    cursor: pointer;
    transition: all 0.3s ease;
    display: block;
}

.marker-female:hover {
    transform: scale(1.3);
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.4);
    border-color: #ec4899;
}

.marker-male {
    background-color: #1e40af;
    width: 18px;
    height: 18px;
    border: 2px solid white;
    border-radius: 50%;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    cursor: pointer;
    transition: all 0.3s ease;
    display: block;
}

.marker-male:hover {
    transform: scale(1.3);
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.4);
    border-color: #1e40af;
}

#map {
    min-height: 600px;
    background-color: #f8fafc;
    position: relative;
}

#map .mapboxgl-canvas {
    
}

/* Améliorer l'affichage des contrôles Mapbox */
.mapboxgl-ctrl-bottom-left,
.mapboxgl-ctrl-bottom-right {
    display: none !important;
}

#tooltip {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Statistiques par région */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}
</style>

<script>
// Données des boursiers depuis le contrôleur
const boursiersData = @json($boursiers_data);

// Régions disponibles
const regionsData = {
    'Abidjan': 'Abidjan (District)',
    'Yamoussoukro': 'Yamoussoukro (District)',
    'Agnéby‑Tiassa': 'Agnéby‑Tiassa',
    'Bafing': 'Bafing',
    'Bagoué': 'Bagoué',
    'Bélier': 'Bélier',
    'Béré': 'Béré',
    'Bounkani': 'Bounkani',
    'Cavally': 'Cavally',
    'Folon': 'Folon',
    'Gbêkê': 'Gbêkê',
    'Gbôklé': 'Gbôklé',
    'Gôh': 'Gôh',
    'Gontougo': 'Gontougo',
    'Grands‑Ponts': 'Grands‑Ponts',
    'Guémon': 'Guémon',
    'Hambol': 'Hambol',
    'Haut‑Sassandra': 'Haut‑Sassandra',
    'Iffou': 'Iffou',
    'Indénié‑Djuablin': 'Indénié‑Djuablin',
    'Kabadougou': 'Kabadougou',
    'La Mé': 'La Mé',
    'LôhDjiboua': 'LôhDjiboua',
    'Marahoué': 'Marahoué',
    'Moronou': 'Moronou',
    'Nawa': 'Nawa',
    'Nzi': 'Nzi',
    'Poro': 'Poro',
    'San‑Pédro': 'San‑Pédro',
    'Sud‑Comoé': 'Sud‑Comoé',
    'Tchologo': 'Tchologo',
    'Tonkpi': 'Tonkpi',
    'Worodougou': 'Worodougou'
};

// Token Mapbox depuis l'environnement Laravel
mapboxgl.accessToken = 'pk.eyJ1IjoibGFtaW5lYmFycm8iLCJhIjoiY20zZHMzOW9zMDc5dzJsczgwdWVoZ2NqYyJ9.3baMsQ3_mpKlnBdHCeu0kg';

let map;
let markers = [];
let currentRegion = 'Toutes';
let selectedRegions = new Set(['Toutes']);

function initMap() {
    // Générer les boutons de régions
    generateRegionButtons();
    
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
            minZoom: 5,
            language: 'fr'
        });

        map.on('load', function() {
            console.log('Carte Mapbox chargée avec succès');
            addFrenchLabels();
            displayMultipleRegionMarkers();
            updateMultipleRegionStats();
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

function generateRegionButtons() {
    const container = document.getElementById('region-buttons-container');
    if (!container) return;
    
    Object.entries(regionsData).forEach(([key, label]) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'region-pill';
        button.dataset.region = key;
        button.textContent = label;
        button.onclick = () => filterRegions(key);
        container.appendChild(button);
    });
}

function addFrenchLabels() {
    if (!map) return;
    
    // Ajouter une couche de texte en français pour les régions
    map.addSource('regions-source', {
        type: 'geojson',
        data: {
            type: 'FeatureCollection',
            features: Object.entries(regionsData).map(([key, label]) => {
                const coords = getRegionCenterCoordinates(key);
                return {
                    type: 'Feature',
                    geometry: {
                        type: 'Point',
                        coordinates: coords
                    },
                    properties: {
                        name: label
                    }
                };
            })
        }
    });
    
    map.addLayer({
        id: 'region-labels',
        type: 'symbol',
        source: 'regions-source',
        layout: {
            'text-field': ['get', 'name'],
            'text-font': ['Open Sans Semibold', 'Arial Unicode MS Bold'],
            'text-size': 12,
            'text-offset': [0, 1.5],
            'text-anchor': 'top'
        },
        paint: {
            'text-color': '#1e40af',
            'text-halo-color': '#fff',
            'text-halo-width': 1
        }
    });
}

function getRegionCenterCoordinates(region) {
    const coordinates = {
        'Abidjan': [-4.0167, 5.3167],
        'Yamoussoukro': [-5.2767, 6.8205],
        'Agnéby‑Tiassa': [-4.2139, 5.9267],
        'Bafing': [-7.6833, 8.2833],
        'Bagoué': [-6.4833, 9.5167],
        'Bélier': [-5.0305, 7.6922],
        'Béré': [-6.1858, 8.0583],
        'Bounkani': [-2.9833, 9.2667],
        'Cavally': [-7.4978, 6.5439],
        'Folon': [-8.1500, 7.2667],
        'Gbêkê': [-5.0305, 7.6922],
        'Gbôklé': [-6.0919, 4.9500],
        'Gôh': [-5.9500, 6.1333],
        'Gontougo': [-2.8000, 8.0333],
        'Grands‑Ponts': [-3.7378, 5.2111],
        'Guémon': [-7.5539, 7.4122],
        'Hambol': [-5.1000, 8.1333],
        'Haut‑Sassandra': [-6.4442, 6.8770],
        'Iffou': [-4.7058, 6.6475],
        'Indénié‑Djuablin': [-3.4972, 6.7289],
        'Kabadougou': [-7.5667, 9.5086],
        'La Mé': [-3.8633, 6.1089],
        'LôhDjiboua': [-5.3572, 5.8397],
        'Marahoué': [-5.7450, 6.9900],
        'Moronou': [-3.1692, 7.8008],
        'Nawa': [-6.5944, 5.7856],
        'Nzi': [-4.7058, 6.6475],
        'Poro': [-5.6283, 9.4583],
        'San‑Pédro': [-6.6370, 4.7467],
        'Sud‑Comoé': [-3.2067, 5.4706],
        'Tchologo': [-5.1967, 9.6000],
        'Tonkpi': [-7.5539, 7.4122],
        'Worodougou': [-6.6733, 7.9611]
    };
    return coordinates[region] || [-5.5, 7.5];
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
                    <p class="font-semibold text-blue-600">Carte interactive des boursiers</p>
                    <p class="text-sm mt-2">Service temporairement indisponible</p>
                    <div class="mt-4 grid grid-cols-6 gap-2 max-w-xs mx-auto">
                        <div class="w-3 h-3 bg-pink-500"></div>
                        <div class="w-3 h-3 bg-blue-600"></div>
                        <div class="w-3 h-3 bg-pink-500"></div>
                        <div class="w-3 h-3 bg-blue-600"></div>
                        <div class="w-3 h-3 bg-blue-600"></div>
                        <div class="w-3 h-3 bg-pink-500"></div>
                    </div>
                    <p class="text-xs mt-2 text-gray-500">🌸 Filles | 🔵 Garçons</p>
                </div>
            </div>
        `;
    }
}

function displayMarkers(region) {
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

    const data = boursiersData[region];
    
    if (!data || !Array.isArray(data)) {
        console.error('Données de boursiers invalides pour la région:', region);
        return;
    }
    
    data.forEach((boursier, index) => {
        try {
            // Vérifier les coordonnées
            if (typeof boursier.lng !== 'number' || typeof boursier.lat !== 'number') {
                console.warn('Coordonnées invalides pour le boursier:', boursier.name);
                return;
            }
            
            // Créer l'élément marqueur
            const el = document.createElement('div');
            el.className = boursier.gender === 'female' ? 'marker-female' : 'marker-male';
            el.setAttribute('data-boursier-id', index);
            
            // Créer le marqueur
            const marker = new mapboxgl.Marker(el)
                .setLngLat([boursier.lng, boursier.lat])
                .addTo(map);
            
            // Événements hover avec gestion d'erreurs
            el.addEventListener('mouseenter', function(e) {
                try {
                    showTooltip(e, boursier);
                } catch (error) {
                    console.warn('Erreur lors de l\'affichage du tooltip:', error);
                }
            });
            
            el.addEventListener('mouseleave', function() {
                try {
                    hideTooltip();
                } catch (error) {
                    console.warn('Erreur lors de la fermeture du tooltip:', error);
                }
            });
            
            markers.push(marker);
            
        } catch (error) {
            console.warn('Erreur lors de la création du marqueur:', error);
        }
    });
    
    console.log(`${markers.length} marqueurs affichés pour la région ${region}`);
}

function updateStats(region) {
    const data = boursiersData[region];
    if (!data || !Array.isArray(data)) return;
    
    const total = data.length;
    const filles = data.filter(b => b.gender === 'female').length;
    const garcons = data.filter(b => b.gender === 'male').length;
    const actifs = data.filter(b => b.status === 'active').length;
    
    // Mettre à jour les compteurs avec animation
    if (region === 'Toutes') {
        // Afficher les stats globales
        animateValue('total-boursiers', {{ $stats['total_boursiers'] ?? 0 }});
        animateValue('total-filles', {{ $stats['total_filles'] ?? 0 }});
        animateValue('total-garcons', {{ $stats['total_garcons'] ?? 0 }});
        animateValue('total-actifs', {{ $stats['total_actifs'] ?? 0 }});
    } else {
        // Afficher les stats de la région sélectionnée
        animateValue('total-boursiers', total || 0);
        animateValue('total-filles', filles || 0);
        animateValue('total-garcons', garcons || 0);
        animateValue('total-actifs', actifs || 0);
    }
}

function animateValue(id, targetValue) {
    const element = document.getElementById(id);
    if (!element) return;
    
    // S'assurer que targetValue est un nombre valide
    targetValue = parseInt(targetValue) || 0;
    
    const startValue = parseInt(element.textContent) || 0;
    const duration = 800; // 0.8 seconde
    const startTime = performance.now();
    
    function updateValue(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const currentValue = Math.round(startValue + (targetValue - startValue) * progress);
        element.textContent = currentValue;
        
        if (progress < 1) {
            requestAnimationFrame(updateValue);
        }
    }
    
    requestAnimationFrame(updateValue);
}

function getSelectedGenders() {
    // Récupère les genres sélectionnés depuis les checkboxes
    const filles = document.getElementById('sexe-filles');
    const garcons = document.getElementById('sexe-garcons');
    
    const genders = [];
    if (filles && filles.checked) genders.push('F');
    if (garcons && garcons.checked) genders.push('M');
    
    return genders.length > 0 ? genders : ['F', 'M'];
}

function filterRegions(region) {
    if (region === 'Toutes') {
        selectedRegions.clear();
        selectedRegions.add('Toutes');
    } else {
        selectedRegions.delete('Toutes');
        if (selectedRegions.has(region)) {
            selectedRegions.delete(region);
        } else {
            selectedRegions.add(region);
        }
        
        if (selectedRegions.size === 0) {
            selectedRegions.add('Toutes');
        }
    }
    
    // Mettre à jour les boutons
    document.querySelectorAll('.region-pill').forEach(btn => {
        btn.classList.remove('active');
        if (selectedRegions.has(btn.dataset.region)) {
            btn.classList.add('active');
        }
    });
    
    // Afficher les nouveaux marqueurs avec le filtre de sexe
    displayMultipleRegionMarkersWithGenderFilter();
    updateMultipleRegionStats();
}

function displayMultipleRegionMarkersWithGenderFilter() {
    if (!map) {
        console.warn('Carte non initialisée, impossible d\'afficher les marqueurs');
        return;
    }

    // Récupérer les genres sélectionnés
    const selectedGenders = getSelectedGenders();
    console.log('Genres sélectionnés:', selectedGenders);

    // Supprimer les anciens marqueurs
    markers.forEach(marker => {
        try {
            marker.remove();
        } catch (e) {
            console.warn('Erreur lors de la suppression du marqueur:', e);
        }
    });
    markers = [];

    console.log('Régions sélectionnées:', Array.from(selectedRegions));
    console.log('Données disponibles:', Object.keys(boursiersData));

    // Afficher les marqueurs pour les régions sélectionnées
    selectedRegions.forEach(region => {
        let data = boursiersData[region];
        
        console.log(`Données pour région ${region}:`, data);
        
        if (!data || !Array.isArray(data)) {
            console.warn(`Pas de données pour la région: ${region}`);
            return;
        }
        
        // Filtrer les boursiers selon le sexe sélectionné
        data = data.filter(boursier => {
            const gender = boursier.gender === 'female' ? 'F' : 'M';
            return selectedGenders.includes(gender);
        });
        
        console.log(`Nombre de boursiers dans ${region} après filtrage: ${data.length}`);
        
        // Grouper les marqueurs par localisation
        const groups = groupMarkersByLocation(data);
        
        data.forEach((boursier, index) => {
            try {
                if (typeof boursier.lng !== 'number' || typeof boursier.lat !== 'number') {
                    console.warn(`Coordonnées invalides pour boursier ${index}:`, boursier);
                    return;
                }
                
                // Trouver le groupe auquel appartient ce boursier
                const tolerance = 0.001;
                const key = `${Math.round(boursier.lng / tolerance) * tolerance},${Math.round(boursier.lat / tolerance) * tolerance}`;
                const group = groups[key];
                const positionInGroup = group.findIndex(item => item.index === index);
                
                // Calculer les coordonnées décalées si plusieurs marqueurs au même endroit
                const [offsetLng, offsetLat] = getOffsetCoordinates(
                    boursier.lng, 
                    boursier.lat, 
                    positionInGroup, 
                    group.length
                );
                
                const el = document.createElement('div');
                el.className = boursier.gender === 'female' ? 'marker-female' : 'marker-male';
                el.setAttribute('data-boursier-id', index);
                el.setAttribute('title', boursier.name);
                el.style.borderRadius = '50%';
                el.style.display = 'flex';
                el.style.alignItems = 'center';
                el.style.justifyContent = 'center';
                el.style.position = 'relative';
                
                // Ajouter un numéro si plusieurs marqueurs au même endroit
                if (group.length > 1) {
                    const numberBadge = document.createElement('div');
                    numberBadge.style.position = 'absolute';
                    numberBadge.style.width = '100%';
                    numberBadge.style.height = '100%';
                    numberBadge.style.display = 'flex';
                    numberBadge.style.alignItems = 'center';
                    numberBadge.style.justifyContent = 'center';
                    numberBadge.style.fontSize = '10px';
                    numberBadge.style.fontWeight = 'bold';
                    numberBadge.style.color = 'white';
                    numberBadge.style.textShadow = '0 0 2px rgba(0,0,0,0.5)';
                    numberBadge.style.zIndex = '10';
                    numberBadge.textContent = positionInGroup + 1; // Numérotation à partir de 1
                    el.appendChild(numberBadge);
                }
                
                const marker = new mapboxgl.Marker(el)
                    .setLngLat([offsetLng, offsetLat])
                    .addTo(map);
                
                el.addEventListener('mouseenter', function(e) {
                    try {
                        showTooltip(e, boursier);
                    } catch (error) {
                        console.warn('Erreur lors de l\'affichage du tooltip:', error);
                    }
                });
                
                el.addEventListener('mouseleave', function() {
                    try {
                        hideTooltip();
                    } catch (error) {
                        console.warn('Erreur lors de la fermeture du tooltip:', error);
                    }
                });
                
                markers.push(marker);
            } catch (error) {
                console.warn('Erreur lors de la création du marqueur:', error);
            }
        });
    });
    
    console.log(`${markers.length} marqueurs affichés pour les régions sélectionnées`);
}

function filterRegion(region) {
    filterRegions(region);
}

function getOffsetCoordinates(lng, lat, index, total) {
    // Crée un décalage circulaire pour les marqueurs proches
    if (total === 1) return [lng, lat];
    
    const radius = 0.01; // Rayon de décalage en degrés (environ 1km)
    const angle = (index / total) * (2 * Math.PI);
    
    const offsetLng = lng + radius * Math.cos(angle);
    const offsetLat = lat + radius * Math.sin(angle);
    
    return [offsetLng, offsetLat];
}

function groupMarkersByLocation(data) {
    // Groupe les marqueurs par localisation (avec tolérance)
    const tolerance = 0.001; // Tolérance en degrés
    const groups = {};
    
    data.forEach((boursier, index) => {
        const key = `${Math.round(boursier.lng / tolerance) * tolerance},${Math.round(boursier.lat / tolerance) * tolerance}`;
        if (!groups[key]) {
            groups[key] = [];
        }
        groups[key].push({ boursier, index });
    });
    
    return groups;
}

function displayMultipleRegionMarkers() {
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

    console.log('Régions sélectionnées:', Array.from(selectedRegions));
    console.log('Données disponibles:', Object.keys(boursiersData));

    // Afficher les marqueurs pour les régions sélectionnées
    selectedRegions.forEach(region => {
        const data = boursiersData[region];
        
        console.log(`Données pour région ${region}:`, data);
        
        if (!data || !Array.isArray(data)) {
            console.warn(`Pas de données pour la région: ${region}`);
            return;
        }
        
        console.log(`Nombre de boursiers dans ${region}: ${data.length}`);
        
        // Grouper les marqueurs par localisation
        const groups = groupMarkersByLocation(data);
        
        data.forEach((boursier, index) => {
            try {
                if (typeof boursier.lng !== 'number' || typeof boursier.lat !== 'number') {
                    console.warn(`Coordonnées invalides pour boursier ${index}:`, boursier);
                    return;
                }
                
                // Trouver le groupe auquel appartient ce boursier
                const tolerance = 0.001;
                const key = `${Math.round(boursier.lng / tolerance) * tolerance},${Math.round(boursier.lat / tolerance) * tolerance}`;
                const group = groups[key];
                const positionInGroup = group.findIndex(item => item.index === index);
                
                // Calculer les coordonnées décalées si plusieurs marqueurs au même endroit
                const [offsetLng, offsetLat] = getOffsetCoordinates(
                    boursier.lng, 
                    boursier.lat, 
                    positionInGroup, 
                    group.length
                );
                
                const el = document.createElement('div');
                el.className = boursier.gender === 'female' ? 'marker-female' : 'marker-male';
                el.setAttribute('data-boursier-id', index);
                el.setAttribute('title', boursier.name);
                el.style.borderRadius = '50%';
                el.style.display = 'flex';
                el.style.alignItems = 'center';
                el.style.justifyContent = 'center';
                el.style.position = 'relative';
                
                // Ajouter un numéro si plusieurs marqueurs au même endroit
                if (group.length > 1) {
                    const numberBadge = document.createElement('div');
                    numberBadge.style.position = 'absolute';
                    numberBadge.style.width = '100%';
                    numberBadge.style.height = '100%';
                    numberBadge.style.display = 'flex';
                    numberBadge.style.alignItems = 'center';
                    numberBadge.style.justifyContent = 'center';
                    numberBadge.style.fontSize = '10px';
                    numberBadge.style.fontWeight = 'bold';
                    numberBadge.style.color = 'white';
                    numberBadge.style.textShadow = '0 0 2px rgba(0,0,0,0.5)';
                    numberBadge.style.zIndex = '10';
                    numberBadge.textContent = positionInGroup + 1; // Numérotation à partir de 1
                    el.appendChild(numberBadge);
                }
                
                const marker = new mapboxgl.Marker(el)
                    .setLngLat([offsetLng, offsetLat])
                    .addTo(map);
                
                el.addEventListener('mouseenter', function(e) {
                    try {
                        showTooltip(e, boursier);
                    } catch (error) {
                        console.warn('Erreur lors de l\'affichage du tooltip:', error);
                    }
                });
                
                el.addEventListener('mouseleave', function() {
                    try {
                        hideTooltip();
                    } catch (error) {
                        console.warn('Erreur lors de la fermeture du tooltip:', error);
                    }
                });
                
                markers.push(marker);
            } catch (error) {
                console.warn('Erreur lors de la création du marqueur:', error);
            }
        });
    });
    
    console.log(`${markers.length} marqueurs affichés pour les régions sélectionnées`);
}

function updateMultipleRegionStats() {
    let total = 0;
    let filles = 0;
    let garcons = 0;
    let actifs = 0;

    selectedRegions.forEach(region => {
        const data = boursiersData[region];
        if (!data || !Array.isArray(data)) return;
        
        total += data.length;
        filles += data.filter(b => b.gender === 'female').length;
        garcons += data.filter(b => b.gender === 'male').length;
        actifs += data.filter(b => b.status === 'active').length;
    });

    if (selectedRegions.has('Toutes')) {
        animateValue('total-boursiers', {{ $stats['total_boursiers'] ?? 0 }});
        animateValue('total-filles', {{ $stats['total_filles'] ?? 0 }});
        animateValue('total-garcons', {{ $stats['total_garcons'] ?? 0 }});
        animateValue('total-actifs', {{ $stats['total_actifs'] ?? 0 }});
    } else {
        animateValue('total-boursiers', total || 0);
        animateValue('total-filles', filles || 0);
        animateValue('total-garcons', garcons || 0);
        animateValue('total-actifs', actifs || 0);
    }
}

function showTooltip(event, boursier) {
    const tooltip = document.getElementById('tooltip');
    const content = document.getElementById('tooltip-content');
    
    if (!tooltip || !content) return;
    
    content.innerHTML = `
        <div class="text-sm">
            <p class="font-semibold text-gray-900">${boursier.name}</p>
            <p class="text-blue-600 font-medium">${boursier.serie}</p>
            <p class="text-gray-600 text-xs mt-1">📍 ${boursier.commune}, ${boursier.region}</p>
            <p class="text-gray-600 text-xs">🏫 ${boursier.etablissement}</p>
            <p class="text-gray-600 text-xs">📧 ${boursier.email}</p>
            <div class="mt-2 flex items-center">
                <span class="inline-flex items-center px-2 py-1 text-xs font-medium ${boursier.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                    ${boursier.status === 'active' ? '✅ Actif' : '⏸️ Inactif'}
                </span>
            </div>
        </div>
    `;
    
    tooltip.style.left = event.pageX + 10 + 'px';
    tooltip.style.top = event.pageY - 10 + 'px';
    tooltip.classList.remove('opacity-0');
    tooltip.classList.add('opacity-100');
}

function hideTooltip() {
    const tooltip = document.getElementById('tooltip');
    if (tooltip) {
        tooltip.classList.remove('opacity-100');
        tooltip.classList.add('opacity-0');
    }
}

// Initialiser la carte une fois que le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initMap, 100);
});
</script>
@endsection 