<!-- Nos Boursiers Section -->
<section id="boursiers" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900">Nos boursiers d'excellence</h2>
            <p class="mt-4 text-xl text-gray-600">Découvrez les parcours inspirants de nos boursiers PEUB</p>
        </div>

        <!-- Carte interactive Mapbox -->
        <div class="mb-16">
            <!-- Pills de cohortes avec bouton candidater -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex gap-4">
                    <button onclick="filterCohort('2023')" class="cohort-pill" data-cohort="2023">
                        Cohorte 2023
                    </button>
                    <button onclick="filterCohort('2024')" class="cohort-pill" data-cohort="2024">
                        Cohorte 2024
                    </button>
                    <button onclick="filterCohort('2025')" class="cohort-pill active" data-cohort="2025">
                        Cohorte 2025
                    </button>
                </div>
                <a href="{{ route('auth.register') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 font-medium transition-colors">
                    S'inscrire avant le 30 Sept.
                </a>
            </div>

            <!-- Carte Mapbox -->
            <div id="map" class="w-full h-[600px] border border-gray-300 map-container"></div>

            <!-- Tooltip pour les informations au hover -->
            <div id="tooltip" class="absolute bg-white p-3 shadow-lg border border-gray-200 rounded-lg pointer-events-none opacity-0 transition-opacity duration-200 z-50">
                <div id="tooltip-content"></div>
            </div>
        </div>

    </div>
</section>

<!-- Mapbox CSS et JS -->
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>

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

.marker-female {
    background-color: #ec4899;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    box-shadow: 0 3px 6px rgba(0,0,0,0.3);
    cursor: pointer;
    transition: all 0.2s ease;
}

.marker-female:hover {
    transform: scale(1.4);
    box-shadow: 0 6px 12px rgba(0,0,0,0.4);
}

.marker-male {
                                background-color: #2256a3;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    box-shadow: 0 3px 6px rgba(0,0,0,0.3);
    cursor: pointer;
    transition: all 0.2s ease;
}

.marker-male:hover {
    transform: scale(1.4);
    box-shadow: 0 6px 12px rgba(30, 67, 133, 0.4);
}

#map {
    border-radius: 0 !important;
    min-height: 600px;
    background-color: #f3f4f6;
    position: relative;
}

#map .mapboxgl-canvas {
    border-radius: 0 !important;
}

/* Améliorer l'affichage des contrôles Mapbox */
.mapboxgl-ctrl-bottom-left,
.mapboxgl-ctrl-bottom-right {
    display: none !important;
}
</style>

{{-- Inclusion des données des boursiers --}}
@include('landing.partials.boursiers-data')

<script>
// Token Mapbox depuis l'environnement Laravel
mapboxgl.accessToken = 'pk.eyJ1IjoibGFtaW5lYmFycm8iLCJhIjoiY20zZHMzOW9zMDc5dzJsczgwdWVoZ2NqYyJ9.3baMsQ3_mpKlnBdHCeu0kg';

let map;
let markers = [];
let currentCohort = '2025';

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
                    <p class="font-semibold">Carte interactive des boursiers</p>
                    <p class="text-sm mt-2">Service temporairement indisponible</p>
                    <div class="mt-4 grid grid-cols-4 gap-2 max-w-xs mx-auto">
                        <div class="w-3 h-3 bg-pink-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-primary-600 rounded-full"></div>
                        <div class="w-3 h-3 bg-pink-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-primary-600 rounded-full"></div>
                        <div class="w-3 h-3 bg-primary-600 rounded-full"></div>
                        <div class="w-3 h-3 bg-pink-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-primary-600 rounded-full"></div>
                        <div class="w-3 h-3 bg-pink-500 rounded-full"></div>
                    </div>
                    <p class="text-xs mt-2">🌸 Filles | 🔵 Garçons</p>
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

    const data = boursiersData[cohort];
    
    if (!data || !Array.isArray(data)) {
        console.error('Données de boursiers invalides pour la cohorte:', cohort);
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
                    console.warn('Erreur lors du masquage du tooltip:', error);
                }
            });
            
            markers.push(marker);
        } catch (error) {
            console.error('Erreur lors de la création du marqueur pour:', boursier.name, error);
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

function showTooltip(e, boursier) {
    const tooltip = document.getElementById('tooltip');
    const tooltipContent = document.getElementById('tooltip-content');
    
    tooltipContent.innerHTML = `
        <div class="flex items-center mb-2">
            <div class="w-10 h-10 ${boursier.gender === 'female' ? 'bg-pink-500' : 'bg-primary-600'} rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                ${boursier.avatar}
            </div>
            <div>
                <div class="font-semibold text-gray-900">${boursier.name}</div>
                <div class="text-sm text-gray-600">${boursier.serie}</div>
            </div>
        </div>
        <div class="text-sm text-gray-600">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                ${boursier.commune}
            </div>
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

// Initialiser la carte quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    // S'assurer que la pill active est correctement stylée au chargement
    updateActivePill('2025');
    
    // Attendre que le contenu soit complètement chargé
    setTimeout(function() {
        if (document.getElementById('map')) {
            initMap();
        } else {
            console.error('Élément map introuvable');
        }
    }, 100);
});

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

// Vérification périodique si la carte ne se charge pas
setTimeout(function() {
    if (!map && document.getElementById('map')) {
        console.warn('Carte non initialisée après 5 secondes');
        showMapFallback();
    }
}, 5000);
</script> 