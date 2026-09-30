@extends('layouts.admin')

@section('title', 'Palmarès BAC ' . $annee . ' - PEUB Admin')

@section('page-title', 'Palmarès officiel BAC ' . $annee)

@section('content')
<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white border border-gray-300 p-6">
        <p class="text-sm font-medium text-gray-600">Bacheliers du palmarès</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total'], 0, ',', ' ') }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $stats['femmes'] }} filles · {{ $stats['hommes'] }} garçons</p>
    </div>
    <div class="bg-white border border-gray-300 p-6">
        <p class="text-sm font-medium text-gray-600">Inscrits sur PEUB (vérifiés)</p>
        <p class="text-2xl font-bold text-green-700">{{ $stats['inscrits'] }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $stats['total'] > 0 ? round($stats['inscrits'] / $stats['total'] * 100, 1) : 0 }} % du palmarès</p>
    </div>
    <div class="bg-white border border-gray-300 p-6">
        <p class="text-sm font-medium text-gray-600">Inscrits à revoir</p>
        <p class="text-2xl font-bold text-yellow-700">{{ $stats['a_revoir'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Matricule trouvé, nom ou date différents</p>
    </div>
    <div class="bg-white border border-gray-300 p-6">
        <p class="text-sm font-medium text-gray-600">Pas encore inscrits</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['non_inscrits'], 0, ',', ' ') }}</p>
    </div>
</div>

<!-- Carte -->
<div class="bg-white border border-gray-300 p-6 mb-6">
    <button type="button" id="toggle-carte" class="flex items-center justify-between w-full text-left">
        <span class="text-sm font-medium text-gray-700">🗺️ Localisation géographique des bacheliers</span>
        <span id="toggle-carte-label" class="text-sm text-primary-600 font-medium">Afficher la carte</span>
    </button>
    <div id="carte-wrapper" class="hidden mt-4">
        <div id="carte-alerte" class="hidden mb-3 text-sm bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 rounded"></div>
        <div id="palmares-carte" class="w-full h-[500px] border border-gray-300"></div>
        <div class="mt-2 flex items-center gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full inline-block" style="background:#ec4899"></span> Filles</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full inline-block" style="background:#2256a3"></span> Garçons</span>
            <span>Opacité réduite = pas encore inscrit sur PEUB</span>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white border border-gray-300 p-6 mb-6">
    <form method="GET" action="{{ route('admin.palmares.index') }}">
        <input type="hidden" name="annee" value="{{ $annee }}">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, prénom, matricule..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">DRENA</label>
                <select name="drena" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Toutes</option>
                    @foreach($drenas as $d)
                        <option value="{{ $d }}" {{ request('drena') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sexe</label>
                <select name="sexe" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Tous</option>
                    <option value="F" {{ request('sexe') === 'F' ? 'selected' : '' }}>Filles</option>
                    <option value="M" {{ request('sexe') === 'M' ? 'selected' : '' }}>Garçons</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Série</label>
                <select name="serie" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Toutes</option>
                    @foreach($series as $s)
                        <option value="{{ $s }}" {{ request('serie') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mention</label>
                <select name="mention" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Toutes</option>
                    <option value="tres_bien" {{ request('mention') === 'tres_bien' ? 'selected' : '' }}>Très bien</option>
                    <option value="bien" {{ request('mention') === 'bien' ? 'selected' : '' }}>Bien</option>
                    <option value="assez_bien" {{ request('mention') === 'assez_bien' ? 'selected' : '' }}>Assez bien</option>
                    <option value="passable" {{ request('mention') === 'passable' ? 'selected' : '' }}>Passable</option>
                    <option value="ims" {{ request('mention') === 'ims' ? 'selected' : '' }}>IMS</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Inscription PEUB</label>
                <select name="inscrit" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Tous</option>
                    <option value="oui" {{ request('inscrit') === 'oui' ? 'selected' : '' }}>Inscrits (vérifiés)</option>
                    <option value="revoir" {{ request('inscrit') === 'revoir' ? 'selected' : '' }}>À revoir</option>
                    <option value="non" {{ request('inscrit') === 'non' ? 'selected' : '' }}>Pas inscrits</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md transition-colors">Filtrer</button>
                <a href="{{ route('admin.palmares.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-center">Réinitialiser</a>
            </div>
        </div>
    </form>
    <div class="mt-4">
        <a href="{{ route('admin.palmares.export', request()->query()) }}" class="text-sm text-primary-600 hover:text-primary-900 font-medium">
            Exporter la sélection en CSV
        </a>
    </div>
</div>

<!-- Tableau -->
<div class="bg-white border border-gray-300 overflow-hidden rounded-lg">
    <table class="w-full">
        <thead class="bg-gray-50 border-b-2 border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DRENA / Rang</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bachelier</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matricule</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Série</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bac (points, mention)</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscription PEUB</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($palmares as $p)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $p->drena }}</div>
                        <div class="text-xs text-gray-500">Rang #{{ $p->rang_drena }} ({{ $p->sexe === 'F' ? 'filles' : 'garçons' }})</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $p->nom }} {{ $p->prenoms }}</div>
                        <div class="text-xs text-gray-500">{{ $p->date_naissance->format('d/m/Y') }} · {{ $p->etablissement }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-mono">{{ $p->matricule }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $p->serie }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 font-medium">{{ $p->points }}/400</div>
                        <div class="text-xs text-gray-500">{{ $p->mention === 'ims' ? 'IMS' : ($p->mention ? ucfirst(str_replace('_', ' ', $p->mention)) : 'Sans mention') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($p->bac_verification_statut === 'verifie')
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full font-medium">Inscrit · vérifié</span>
                            <a href="{{ route('admin.bacheliers.show', $p->bachelier_id) }}" class="block mt-1 text-xs text-primary-600 hover:text-primary-900">Voir le dossier</a>
                        @elseif($p->bac_verification_statut === 'divergent')
                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full font-medium">Inscrit · à revoir</span>
                            <a href="{{ route('admin.bacheliers.show', $p->bachelier_id) }}" class="block mt-1 text-xs text-primary-600 hover:text-primary-900">Voir le dossier</a>
                        @else
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full font-medium">Pas encore inscrit</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">Aucun bachelier trouvé.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $palmares->links() }}</div>
@endsection

@push('styles')
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
@endpush

@push('scripts')
<script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggle-carte');
    const wrapper = document.getElementById('carte-wrapper');
    const label = document.getElementById('toggle-carte-label');
    const alerteBox = document.getElementById('carte-alerte');
    let carteInitialisee = false;
    let map;

    toggleBtn.addEventListener('click', function () {
        const wasHidden = wrapper.classList.contains('hidden');
        wrapper.classList.toggle('hidden');
        label.textContent = wasHidden ? 'Masquer la carte' : 'Afficher la carte';

        if (wasHidden && !carteInitialisee) {
            carteInitialisee = true;
            initCarte();
        } else if (wasHidden && map) {
            setTimeout(() => map.resize(), 50);
        }
    });

    function initCarte() {
        if (typeof mapboxgl === 'undefined') {
            alerteBox.textContent = "Mapbox GL JS n'a pas pu être chargé.";
            alerteBox.classList.remove('hidden');
            return;
        }

        mapboxgl.accessToken = '{{ config('services.mapbox.public_token') }}';

        map = new mapboxgl.Map({
            container: 'palmares-carte',
            style: 'mapbox://styles/mapbox/light-v11',
            center: [-5.5, 7.5],
            zoom: 6.2,
            attributionControl: false,
            logoPosition: 'bottom-right',
            maxZoom: 12,
            minZoom: 5
        });

        map.on('load', function () {
            fetch("{{ route('admin.palmares.carte-data', array_merge(request()->query(), ['annee' => $annee])) }}")
                .then(r => r.json())
                .then(function (data) {
                    const geojson = {
                        type: 'FeatureCollection',
                        features: (data.markers || []).map(function (p) {
                            return { type: 'Feature', properties: p, geometry: { type: 'Point', coordinates: [p.lng, p.lat] } };
                        })
                    };

                    map.addSource('palmares-admin', { type: 'geojson', data: geojson });
                    map.addLayer({
                        id: 'palmares-admin-pastilles',
                        type: 'circle',
                        source: 'palmares-admin',
                        paint: {
                            'circle-radius': 5,
                            'circle-color': ['case', ['==', ['get', 'gender'], 'female'], '#ec4899', '#2256a3'],
                            'circle-opacity': ['case', ['get', 'inscrit'], 1, 0.35],
                            'circle-stroke-width': ['case', ['get', 'inscrit'], 2, 0],
                            'circle-stroke-color': '#ffffff'
                        }
                    });

                    if (data.drenas_non_localisees && data.drenas_non_localisees.length > 0) {
                        alerteBox.textContent = 'DRENA non localisées (absentes de config/drena.php) : ' + data.drenas_non_localisees.join(', ');
                        alerteBox.classList.remove('hidden');
                    }

                    const popup = new mapboxgl.Popup({ closeButton: false, closeOnClick: false });

                    map.on('mousemove', 'palmares-admin-pastilles', function (e) {
                        map.getCanvas().style.cursor = 'pointer';
                        const p = e.features[0].properties;
                        popup.setLngLat(e.lngLat)
                            .setHTML(
                                '<div class="text-sm"><strong>' + p.name + '</strong><br>' +
                                p.matricule + ' · ' + p.serie + '<br>' +
                                p.drena + ' · rang #' + p.rang + '<br>' +
                                p.points + '/400 · ' + p.mention +
                                (p.inscrit ? '<br><span style="color:#16a34a">Inscrit sur PEUB</span>' : '') +
                                '</div>'
                            )
                            .addTo(map);
                    });

                    map.on('mouseleave', 'palmares-admin-pastilles', function () {
                        map.getCanvas().style.cursor = '';
                        popup.remove();
                    });
                })
                .catch(function () {
                    alerteBox.textContent = 'Impossible de charger les données de la carte.';
                    alerteBox.classList.remove('hidden');
                });
        });
    }
});
</script>
@endpush
