@extends('layouts.admin')

@section('title', 'Visualisation des Boursiers')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Nos Boursiers d'Excellence</h1>
        <p class="mt-2 text-sm text-gray-700">Répartition géographique des boursiers PEUB par région</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600">Total Boursiers</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_boursiers'] }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600">Filles</p>
            <p class="text-3xl font-bold text-pink-600">{{ $stats['total_filles'] }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600">Garçons</p>
            <p class="text-3xl font-bold text-blue-800">{{ $stats['total_garcons'] }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600">Actifs</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_actifs'] }}</p>
        </div>
    </div>

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

    <div class="bg-white overflow-hidden shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-4 text-sm">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-pink-500 inline-block"></span> Filles</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-800 inline-block"></span> Garçons</span>
                    <span class="text-gray-500">— Taille de la bulle = nombre de boursiers</span>
                </div>
            </div>
            <div id="map" class="w-full h-[600px] border border-gray-300"></div>
            <div id="region-panel" class="hidden mt-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-center mb-3">
                    <h3 id="panel-title" class="text-lg font-bold text-gray-900"></h3>
                    <button onclick="closePanel()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>
                <div id="panel-stats" class="flex gap-4 mb-3 text-sm"></div>
                <div id="panel-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 max-h-64 overflow-y-auto"></div>
            </div>
        </div>
    </div>

</div>

<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet"/>
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>

<style>
#map { min-height: 600px; }
.mapboxgl-ctrl-bottom-left, .mapboxgl-ctrl-bottom-right { display: none !important; }
.region-bubble {
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%; cursor: pointer; border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3); color: white;
    font-weight: bold; font-family: sans-serif; transition: transform 0.2s;
}
.region-bubble:hover { transform: scale(1.15); }
</style>

<script>
const regionsData = @json($boursiers_data);
mapboxgl.accessToken = '{{ config('services.mapbox.public_token') }}';

let map;
let markers = [];

function initMap() {
    map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/light-v11',
        center: [-5.5, 7.5],
        zoom: 6,
        attributionControl: false,
    });

    map.on('load', function() {
        displayBubbles();
    });
}

function getBubbleSize(total) {
    const min = 30, max = 70;
    const maxTotal = Math.max(...regionsData.map(r => r.total));
    return min + ((total / maxTotal) * (max - min));
}

function getBubbleColor(region) {
    return region.filles >= region.garcons ? '#ec4899' : '#1e40af';
}

function displayBubbles() {
    markers.forEach(m => m.remove());
    markers = [];

    regionsData.forEach(region => {
        if (region.total === 0) return;

        const size = getBubbleSize(region.total);
        const color = getBubbleColor(region);

        const el = document.createElement('div');
        el.className = 'region-bubble';
        el.style.width = size + 'px';
        el.style.height = size + 'px';
        el.style.background = color;
        el.style.fontSize = Math.max(11, size / 4) + 'px';
        el.innerHTML = '<div style="text-align:center;line-height:1.2"><div>' + region.total + '</div><div style="font-size:9px;font-weight:400">' + escapeHtml(region.region_label || region.region) + '</div></div>';

        el.addEventListener('click', function() {
            showPanel(region);
        });

        const marker = new mapboxgl.Marker(el)
            .setLngLat([region.lng, region.lat])
            .addTo(map);

        markers.push(marker);
    });
}

function showPanel(region) {
    const panel = document.getElementById('region-panel');
    const title = document.getElementById('panel-title');
    const stats = document.getElementById('panel-stats');
    const list = document.getElementById('panel-list');

    title.textContent = region.region_label || region.region;
    stats.innerHTML = '<span class="text-gray-700"><strong>' + region.total + '</strong> boursiers</span>' +
        '<span class="text-pink-600"><strong>' + region.filles + '</strong> filles</span>' +
        '<span class="text-blue-800"><strong>' + region.garcons + '</strong> garçons</span>';

    list.innerHTML = region.boursiers.map(b => '<div class="bg-white border border-gray-200 rounded p-2 text-sm">' +
        '<p class="font-semibold text-gray-900">' + escapeHtml(b.name) + '</p>' +
        '<p class="text-xs ' + (b.gender === 'female' ? 'text-pink-600' : 'text-blue-800') + '">' + (b.gender === 'female' ? 'Fille' : 'Garçon') + ' · ' + escapeHtml(b.commune) + '</p>' +
        '<p class="text-xs text-gray-500">' + escapeHtml(b.etablissement) + '</p>' +
        '</div>'
    ).join('');

    panel.classList.remove('hidden');
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value == null ? '' : String(value);
    return div.innerHTML;
}

function closePanel() {
    document.getElementById('region-panel').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initMap, 100);
});
</script>
@endsection
