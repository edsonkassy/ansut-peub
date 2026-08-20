@extends('layouts.bachelier')

@section('title', 'Opportunités - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="OPPORTUNITÉS / EXPLORER" />

    <!-- Navigation Pills -->
    <div class="mb-6">
        <x-opportunites-nav />
    </div>

    <div>
        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
            <form id="filter-form" method="GET" action="{{ route('bachelier.opportunites') }}">
                <!-- Barre de recherche pleine largeur -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" id="search" name="search" 
                               placeholder="Rechercher des opportunités..." 
                               value="{{ request('search') }}"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5] transition-all">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row flex-wrap items-center gap-4">
                    <div>
                        <select id="type-filter" name="type" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="">Tous les types</option>
                            <option value="bourse" @selected(request('type') == 'bourse')>Bourse</option>
                            <option value="stage" @selected(request('type') == 'stage')>Stage</option>
                            <option value="emploi" @selected(request('type') == 'emploi')>Emploi</option>
                            <option value="formation" @selected(request('type') == 'formation')>Formation</option>
                            <option value="concours" @selected(request('type') == 'concours')>Concours</option>
                            <option value="event" @selected(request('type') == 'event')>Événement</option>
                            <option value="promotion" @selected(request('type') == 'promotion')>Promotion</option>
                        </select>
                    </div>

                    <div>
                        <select id="secteur-filter" name="secteur" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="">Tous les secteurs</option>
                            <option value="technologie" @selected(request('secteur') == 'technologie')>Technologie</option>
                            <option value="sante" @selected(request('secteur') == 'sante')>Santé</option>
                            <option value="education" @selected(request('secteur') == 'education')>Éducation</option>
                            <option value="finance" @selected(request('secteur') == 'finance')>Finance</option>
                            <option value="environnement" @selected(request('secteur') == 'environnement')>Environnement</option>
                        </select>
                    </div>

                    <div>
                        <select id="location-filter" name="location" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="">Toutes</option>
                            <option value="Abidjan" @selected(request('location') == 'Abidjan')>Abidjan</option>
                            <option value="Yamoussoukro" @selected(request('location') == 'Yamoussoukro')>Yamoussoukro</option>
                            <option value="Bouaké" @selected(request('location') == 'Bouaké')>Bouaké</option>
                            <option value="San-Pédro" @selected(request('location') == 'San-Pédro')>San-Pédro</option>
                            <option value="Daloa" @selected(request('location') == 'Daloa')>Daloa</option>
                            <option value="Korhogo" @selected(request('location') == 'Korhogo')>Korhogo</option>
                            <option value="Man" @selected(request('location') == 'Man')>Man</option>
                            <option value="Divo" @selected(request('location') == 'Divo')>Divo</option>
                            <option value="Gagnoa" @selected(request('location') == 'Gagnoa')>Gagnoa</option>
                            <option value="Toutes les régions" @selected(request('location') == 'Toutes les régions')>Toutes les régions</option>
                            <option value="À distance" @selected(request('location') == 'À distance')>À distance</option>
                        </select>
                    </div>

                    <div>
                        <select id="sort-filter" name="sort" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="recent" @selected(request('sort') == 'recent')>Plus récentes</option>
                            <option value="popular" @selected(request('sort') == 'popular')>Plus populaires</option>
                            <option value="deadline" @selected(request('sort') == 'deadline')>Date limite</option>
                            <option value="score" @selected(request('sort') == 'score')>Score IA</option>
                        </select>
                    </div>

                    <button type="button" id="reset-filters" class="p-2 text-[#00BFA5] hover:text-[#00BFA5]/80 transition-colors" title="Réinitialiser les filtres">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Grille des opportunités -->
        <div class="space-y-4">
            @forelse($opportunites as $opportunite)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 opportunity-card hover:shadow-xl transition-shadow">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
                        <!-- Image -->
                        <a href="{{ route('bachelier.opportunites.show', $opportunite) }}" class="block md:w-48 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden h-40 md:h-32">
                            @if($opportunite->illustration)
                                <img src="{{ asset('storage/' . $opportunite->illustration) }}" 
                                     alt="{{ $opportunite->titre }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <!-- Image par défaut selon le type -->
                                <div class="w-full h-full flex items-center justify-center">
                                    @php
                                        $typeIcons = [
                                            'bourse' => 'graduation-cap',
                                            'stage' => 'briefcase',
                                            'formation' => 'book-open',
                                            'concours' => 'award',
                                            'event' => 'calendar',
                                            'promotion' => 'megaphone'
                                        ];
                                        $typeColors = [
                                            'bourse' => 'text-yellow-600',
                                            'stage' => 'text-primary-600',
                                            'formation' => 'text-purple-600',
                                            'concours' => 'text-red-600',
                                            'event' => 'text-orange-600',
                                            'promotion' => 'text-pink-600'
                                        ];
                                    @endphp
                                    <i data-lucide="{{ $typeIcons[$opportunite->type] ?? 'target' }}" 
                                       class="w-12 h-12 {{ $typeColors[$opportunite->type] ?? 'text-primary-600' }}"></i>
                                </div>
                            @endif
                        </a>

                        <!-- Contenu -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4 mb-2">
                                <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">
                                        <a href="{{ route('bachelier.opportunites.show', $opportunite) }}" class="text-black hover:text-[#00BFA5] transition-colors">{{ $opportunite->titre }}</a>
                                    </h4>
                                    <div class="flex items-center flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @switch($opportunite->type)
                                            @case('bourse')
                                                bg-green-100 text-green-800
                                                @break
                                            @case('stage')
                                                bg-[#00BFA5]/10 text-[#00BFA5]
                                                @break
                                            @case('formation')
                                                bg-purple-100 text-purple-800
                                                @break
                                            @case('concours')
                                                bg-orange-100 text-orange-800
                                                @break
                                            @case('event')
                                                bg-pink-100 text-pink-800
                                                @break
                                            @case('promotion')
                                                bg-yellow-100 text-yellow-800
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800
                                        @endswitch">
                                        @switch($opportunite->type)
                                            @case('bourse')
                                                <i data-lucide="award" class="w-3 h-3 mr-1"></i>
                                                @break
                                            @case('stage')
                                                <i data-lucide="briefcase" class="w-3 h-3 mr-1"></i>
                                                @break
                                            @case('formation')
                                                <i data-lucide="graduation-cap" class="w-3 h-3 mr-1"></i>
                                                @break
                                            @case('concours')
                                                <i data-lucide="trophy" class="w-3 h-3 mr-1"></i>
                                                @break
                                            @case('event')
                                                <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                                @break
                                            @case('promotion')
                                                <i data-lucide="megaphone" class="w-3 h-3 mr-1"></i>
                                                @break
                                            @default
                                                <i data-lucide="target" class="w-3 h-3 mr-1"></i>
                                        @endswitch
                                        {{ ucfirst($opportunite->type) }}
                                    </span>
                                    @if($opportunite->score_ia)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#00BFA5]/10 text-[#00BFA5]">
                                        <i data-lucide="brain" class="w-3 h-3 mr-1"></i>
                                        {{ $opportunite->score_ia }}% match
                                    </span>
                                    @endif
                                    </div>
                                </div>
                                
                                <!-- Actions alignées -->
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <button class="favorite-btn p-2 hover:bg-gray-50 text-gray-400 hover:text-red-500" data-opportunite-id="{{ $opportunite->id }}">
                                        <i data-lucide="heart" class="w-5 h-5
                                            {{ $opportunite->isFavorited ? 'text-red-500 fill-current' : '' }}"></i>
                                    </button>
                    @if(!$opportunite->hasApplied)
                        <button type="button" 
                                onclick="openCandidatureConfirmModal({{ $opportunite->id }}, '{{ addslashes($opportunite->titre) }}', '{{ addslashes($opportunite->partenaire->nom_organisation) }}', '{{ $opportunite->type }}', false)"
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#00BFA5] hover:bg-[#00BFA5]/90 transition-colors">
                            <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                            Postuler
                        </button>
                    @else
                        <button disabled 
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-400 bg-gray-100 cursor-not-allowed">
                            <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                            Déjà postulé
                        </button>
                    @endif
                                </div>
                            </div>
                            
                            <!-- Partenaire -->
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-gray-100 flex items-center justify-center">
                                    @if($opportunite->partenaire->logo)
                                        <img src="{{ asset('storage/' . $opportunite->partenaire->logo) }}" 
                                             alt="{{ $opportunite->partenaire->nom_organisation }}" 
                                             class="w-6 h-6 object-contain">
                                    @else
                                        <i data-lucide="building" class="w-4 h-4 text-gray-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $opportunite->partenaire->nom_organisation }}</p>
                                    <p class="text-xs text-gray-600">{{ $opportunite->partenaire->secteur_activite }}</p>
                                </div>
                            </div>
                            
                            <p class="text-gray-600 mb-3 text-sm">{{ Str::limit($opportunite->description, 120) }}</p>
                            
                            <!-- Informations clés en grille -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-3">
                                @if($opportunite->regions_ciblees || ($opportunite->ville && $opportunite->pays))
                                <div>
                                    <span class="text-gray-500">Localisation:</span>
                                    @if($opportunite->regions_ciblees && is_array($opportunite->regions_ciblees))
                                        <p class="font-medium">{{ implode(', ', array_slice($opportunite->regions_ciblees, 0, 2)) }}@if(count($opportunite->regions_ciblees) > 2) +{{ count($opportunite->regions_ciblees) - 2 }}@endif</p>
                                    @elseif($opportunite->ville && $opportunite->pays)
                                        <p class="font-medium">{{ $opportunite->ville }}, {{ $opportunite->pays }}</p>
                                    @endif
                                </div>
                                @endif
                                @if($opportunite->duree)
                                <div>
                                    <span class="text-gray-500">Durée:</span>
                                    <p class="font-medium">{{ $opportunite->duree }}</p>
                                </div>
                                @endif
                                @if($opportunite->remuneration)
                                <div>
                                    <span class="text-gray-500">Rémunération:</span>
                                    <p class="font-medium">{{ $opportunite->remuneration }}</p>
                                </div>
                                @endif
                                <div>
                                    <span class="text-gray-500">Date limite:</span>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($opportunite->date_limite_candidature)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            
                            <!-- Tags -->
                            @if($opportunite->competences_requises)
                            <div class="flex flex-wrap gap-1 mb-3">
                                @if(is_array($opportunite->competences_requises))
                                    @foreach(array_slice($opportunite->competences_requises, 0, 3) as $competence)
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                            {{ trim($competence) }}
                                        </span>
                                    @endforeach
                                    @if(count($opportunite->competences_requises) > 3)
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                            +{{ count($opportunite->competences_requises) - 3 }}
                                        </span>
                                    @endif
                                @else
                                    @foreach(array_slice(explode(',', $opportunite->competences_requises), 0, 3) as $competence)
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                            {{ trim($competence) }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                            @endif
                            
                            <!-- Métadonnées -->
                            @if(($opportunite->nombre_places && $opportunite->nombre_places > 0) || ($opportunite->candidatures_count && $opportunite->candidatures_count > 0))
                            <div class="text-xs text-gray-500">
                                @if($opportunite->nombre_places && $opportunite->nombre_places > 0)
                                    {{ $opportunite->nombre_places }} place(s) disponible(s)
                                    @if($opportunite->candidatures_count && $opportunite->candidatures_count > 0) • @endif
                                @endif
                                @if($opportunite->candidatures_count && $opportunite->candidatures_count > 0)
                                    {{ $opportunite->candidatures_count }} candidature(s)
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-lg p-12 text-center border border-gray-200">
                    <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune opportunité trouvée</h3>
                    <p class="text-gray-600 mb-4">Essayez de modifier vos filtres ou revenez plus tard</p>
                    <button id="reset-filters" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#00BFA5] hover:bg-[#00BFA5]/90 transition-colors">
                        Réinitialiser les filtres
                    </button>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($opportunites->hasPages())
        <div class="mt-8">
            {{ $opportunites->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#filter-form');
    const searchInput = document.querySelector('#search');
    const selects = form.querySelectorAll('select');
    const resetBtn = document.getElementById('reset-filters');

    // Auto-submit on select change
    selects.forEach(select => {
        select.addEventListener('change', function() {
            form.submit();
        });
    });
    
    // Debounced search
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 300);
        });
    }

    // Réinitialisation des filtres
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            window.location.href = window.location.pathname;
        });
    }

    // Favoris
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const opportuniteId = this.dataset.opportuniteId;
            const icon = this.querySelector('i');
            
            fetch(`/bachelier/favoris/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    opportunite_id: opportuniteId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Recharger la page pour voir le statut mis à jour
                window.location.reload();
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        });
    });
});
</script>
@endpush

{{-- La modale doit rester dans la section : placee apres @endsection, elle
     etait rendue avant le <!DOCTYPE> et basculait la page en mode quirks
     (document.compatMode === "BackCompat"). Corrige le 20/08/2026. --}}
@include('bachelier.candidature-confirm-modal')
@endsection 