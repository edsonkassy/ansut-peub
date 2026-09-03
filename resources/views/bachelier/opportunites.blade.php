@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Opportunités - Bachelier PEUB')

@php
    $typeIcones = [
        'bourse' => ['M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z', 'M22 10v6', 'M6 12.5V16a6 3 0 0 0 12 0v-3.5'],
        'stage' => ['M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16', 'M4 7h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z'],
        'emploi' => ['M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2', 'M9 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8', 'M22 21v-2a4 4 0 0 0-3-3.87'],
        'formation' => ['M12 7v14', 'M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z'],
        'concours' => ['M6 9H4.5a2.5 2.5 0 0 1 0-5H6', 'M18 9h1.5a2.5 2.5 0 0 0 0-5H18', 'M4 22h16', 'M18 2H6v7a6 6 0 0 0 12 0z'],
        'event' => ['M8 2v4', 'M16 2v4', 'M3 10h18', 'M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z'],
        'promotion' => ['m3 11 18-5v12L3 14v-3z', 'M11.6 16.8a3 3 0 1 1-5.8-1.6'],
        'defaut' => ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'M12 6a6 6 0 1 0 0 12 6 6 0 0 0 0-12', 'M12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4'],
    ];

    // Champs reellement traites par OpportuniteController::index.
    // Le filtre « secteur » du formulaire d origine n etait lu nulle part : retire.
    $champsFiltre = ['search', 'type', 'location', 'duration', 'sort'];
    $filtresActifs = collect($champsFiltre)->filter(fn ($c) => request()->filled($c))->count();
@endphp

@section('content')
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    {{-- En-tete de page : la vue s ouvrait sur un fil d Ariane, sans aucun h1. --}}
    <header>
        <p class="ds-overline">OPPORTUNITÉS / EXPLORER</p>
        <h1 style="margin-top: var(--space-1)">Opportunités</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            {{ $opportunites->total() }}
            {{ $opportunites->total() > 1 ? 'offres ouvertes' : 'offre ouverte' }}@if($filtresActifs) pour vos filtres @endif
        </p>
    </header>

    <x-opportunites-nav />

    {{-- Filtres replies : le bloc occupait tout le premier ecran a 360px, avant la
         moindre offre. Le formulaire GET et tous ses champs traites sont conserves ;
         replie, il reste dans le DOM et soumet toujours ses valeurs. --}}
    <div x-data="{ ouvert: {{ $filtresActifs ? 'true' : 'false' }} }">
        <button type="button"
                @click="ouvert = !ouvert"
                :aria-expanded="ouvert ? 'true' : 'false'"
                aria-controls="filtres-opportunites"
                class="ds-btn ds-btn-secondary ds-btn-md">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <path d="M3 6h18"/><path d="M7 12h10"/><path d="M10 18h4"/>
            </svg>
            Filtrer et trier
            @if ($filtresActifs)
                <span class="ds-badge ds-badge-accent">{{ $filtresActifs }} actif{{ $filtresActifs > 1 ? 's' : '' }}</span>
            @endif
        </button>

        {{-- x-cloak n est pas defini dans le projet : l etat initial est pose en style
             inline, qu Alpine reprend ensuite via x-show. --}}
        <div id="filtres-opportunites" x-show="ouvert" class="ds-card"
             style="margin-top: var(--space-1-5); padding: var(--space-3){{ $filtresActifs ? '' : '; display:none' }}">
            <form id="filter-form" method="GET" action="{{ route('bachelier.opportunites') }}" class="ds-stack-sm">
                <div>
                    <label class="ds-label" for="search">Rechercher</label>
                    <input type="search" id="search" name="search"
                           placeholder="Titre, description ou partenaire"
                           value="{{ request('search') }}"
                           class="ds-field">
                </div>

                <div style="display:grid; gap:var(--space-1-5); grid-template-columns:repeat(auto-fit, minmax(150px, 1fr))">
                    <div>
                        <label class="ds-label" for="type-filter">Type</label>
                        <select id="type-filter" name="type" class="ds-field">
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
                        <label class="ds-label" for="location-filter">Localisation</label>
                        <select id="location-filter" name="location" class="ds-field">
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

                    {{-- Filtre de duree : deja traite par le controleur (court, moyen, long)
                         mais aucune commande ne l exposait. --}}
                    <div>
                        <label class="ds-label" for="duration-filter">Durée</label>
                        <select id="duration-filter" name="duration" class="ds-field">
                            <option value="">Toutes</option>
                            <option value="court" @selected(request('duration') == 'court')>Courte, quelques mois</option>
                            <option value="moyen" @selected(request('duration') == 'moyen')>Moyenne, en mois</option>
                            <option value="long" @selected(request('duration') == 'long')>Longue, un an ou plus</option>
                        </select>
                    </div>

                    <div>
                        <label class="ds-label" for="sort-filter">Trier par</label>
                        <select id="sort-filter" name="sort" class="ds-field">
                            <option value="recent" @selected(request('sort') == 'recent')>Plus récentes</option>
                            <option value="popular" @selected(request('sort') == 'popular')>Plus populaires</option>
                            <option value="deadline" @selected(request('sort') == 'deadline')>Date limite</option>
                        </select>
                    </div>
                </div>

                <div>
                    <button type="button" id="reset-filters" class="ds-btn ds-btn-ghost ds-btn-md">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M3 2v6h6"/><path d="M3 13a9 9 0 1 0 3-7.7L3 8"/>
                        </svg>
                        Réinitialiser les filtres
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Liste des offres --}}
    <div class="ds-stack-sm">
        @forelse($opportunites as $opportunite)
            @php
                $paths = $typeIcones[$opportunite->type] ?? $typeIcones['defaut'];
                $limite = $opportunite->date_limite_candidature
                    ? \Carbon\Carbon::parse($opportunite->date_limite_candidature)
                    : null;
                $joursRestants = $limite ? (int) now()->startOfDay()->diffInDays($limite->copy()->startOfDay(), false) : null;
            @endphp
            <article class="ds-card" style="padding: var(--space-3)">
                <div style="display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fit, minmax(240px, 1fr))">

                    <div style="min-width:0">
                        {{-- Urgence : repond a « qu est-ce qui ferme bientot » sur chaque offre. --}}
                        <div style="display:flex; align-items:center; gap:var(--space-1); flex-wrap:wrap">
                            <span class="ds-badge ds-badge-neutral">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    @foreach ($paths as $d)<path d="{{ $d }}"/>@endforeach
                                </svg>
                                {{ ucfirst($opportunite->type) }}
                            </span>
                            @if(!is_null($joursRestants))
                                @if($joursRestants < 0)
                                    <span class="ds-badge ds-badge-neutral">Clôturée</span>
                                @elseif($joursRestants <= 7)
                                    <span class="ds-badge ds-badge-warning">
                                        {{ $joursRestants === 0 ? "Dernier jour" : 'Plus que ' . $joursRestants . ' jour' . ($joursRestants > 1 ? 's' : '') }}
                                    </span>
                                @else
                                    <span class="ds-text-secondary" style="font-size:var(--text-label)">
                                        Clôture le {{ $limite->format('d/m/Y') }}
                                    </span>
                                @endif
                            @endif
                        </div>

                        <h2 style="margin-top: var(--space-1); font-size: var(--text-h3)">
                            <a href="{{ route('bachelier.opportunites.show', $opportunite) }}" style="color:inherit; text-decoration:none">
                                {{ $opportunite->titre }}
                            </a>
                        </h2>

                        <div style="margin-top: var(--space-1); display:flex; align-items:center; gap:var(--space-1)">
                            <span style="display:grid; place-items:center; width:32px; height:32px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--surface-secondary); overflow:hidden">
                                @if($opportunite->partenaire->logo)
                                    <img src="{{ asset('storage/' . $opportunite->partenaire->logo) }}"
                                         alt="{{ $opportunite->partenaire->nom_organisation }}"
                                         width="24" height="24" loading="lazy" decoding="async"
                                         style="width:24px; height:24px; object-fit:contain">
                                @else
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="color:var(--text-secondary)">
                                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/>
                                    </svg>
                                @endif
                            </span>
                            <span style="min-width:0">
                                <span style="display:block; font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $opportunite->partenaire->nom_organisation }}</span>
                                <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)">{{ $opportunite->partenaire->secteur_activite }}</span>
                            </span>
                        </div>

                        <p class="ds-text-secondary line-clamp-3" style="margin-top: var(--space-1-5); font-size:var(--text-caption)">
                            {{ Str::limit($opportunite->description, 160) }}
                        </p>

                        <dl style="margin-top: var(--space-1-5); display:grid; gap:var(--space-1); grid-template-columns:repeat(auto-fit, minmax(120px, 1fr)); font-size:var(--text-label)">
                            @if($opportunite->regions_ciblees && is_array($opportunite->regions_ciblees))
                            <div>
                                <dt class="ds-text-secondary">Localisation</dt>
                                <dd style="font-weight:var(--font-medium)">{{ implode(', ', array_slice($opportunite->regions_ciblees, 0, 2)) }}@if(count($opportunite->regions_ciblees) > 2) +{{ count($opportunite->regions_ciblees) - 2 }}@endif</dd>
                            </div>
                            @elseif($opportunite->ville && $opportunite->pays)
                            <div>
                                <dt class="ds-text-secondary">Localisation</dt>
                                <dd style="font-weight:var(--font-medium)">{{ $opportunite->ville }}, {{ $opportunite->pays }}</dd>
                            </div>
                            @endif
                            @if($opportunite->duree)
                            <div>
                                <dt class="ds-text-secondary">Durée</dt>
                                <dd style="font-weight:var(--font-medium)">{{ $opportunite->duree }}</dd>
                            </div>
                            @endif
                            @if($opportunite->remuneration)
                            <div>
                                <dt class="ds-text-secondary">Rémunération</dt>
                                <dd style="font-weight:var(--font-medium)">{{ $opportunite->remuneration }}</dd>
                            </div>
                            @endif
                        </dl>

                        @if($opportunite->competences_requises)
                        @php
                            $competences = is_array($opportunite->competences_requises)
                                ? $opportunite->competences_requises
                                : explode(',', $opportunite->competences_requises);
                        @endphp
                        <div style="margin-top: var(--space-1-5); display:flex; flex-wrap:wrap; gap:var(--space-0-5)">
                            @foreach(array_slice($competences, 0, 3) as $competence)
                                <span class="ds-badge ds-badge-neutral">{{ trim($competence) }}</span>
                            @endforeach
                            @if(count($competences) > 3)
                                <span class="ds-badge ds-badge-neutral">+{{ count($competences) - 3 }}</span>
                            @endif
                        </div>
                        @endif

                        @if(($opportunite->nombre_places && $opportunite->nombre_places > 0) || ($opportunite->candidatures_count && $opportunite->candidatures_count > 0))
                        <p class="ds-text-secondary" style="margin-top: var(--space-1-5); font-size:var(--text-label)">
                            @if($opportunite->nombre_places && $opportunite->nombre_places > 0)
                                {{ $opportunite->nombre_places }} place(s) disponible(s)
                                @if($opportunite->candidatures_count && $opportunite->candidatures_count > 0) &middot; @endif
                            @endif
                            @if($opportunite->candidatures_count && $opportunite->candidatures_count > 0)
                                {{ $opportunite->candidatures_count }} candidature(s)
                            @endif
                        </p>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex; flex-direction:column; gap:var(--space-1); align-self:start">
                        @if(!$opportunite->hasApplied)
                            <button type="button"
                                    onclick="openCandidatureConfirmModal({{ $opportunite->id }}, '{{ addslashes($opportunite->titre) }}', '{{ addslashes($opportunite->partenaire->nom_organisation) }}', '{{ $opportunite->type }}', false)"
                                    class="ds-btn ds-btn-primary ds-btn-md ds-btn-block">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>
                                </svg>
                                Postuler
                            </button>
                        @else
                            <button type="button" disabled class="ds-btn ds-btn-secondary ds-btn-md ds-btn-block">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                Déjà postulé
                            </button>
                        @endif

                        <button type="button"
                                class="favorite-btn ds-btn ds-btn-ghost ds-btn-md ds-btn-block"
                                data-opportunite-id="{{ $opportunite->id }}"
                                aria-pressed="{{ $opportunite->isFavorited ? 'true' : 'false' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $opportunite->isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>
                            </svg>
                            {{ $opportunite->isFavorited ? 'En favori' : 'Ajouter aux favoris' }}
                        </button>

                        <a href="{{ route('bachelier.opportunites.show', $opportunite) }}" class="ds-btn ds-btn-ghost ds-btn-md ds-btn-block">
                            Voir le détail
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="ds-panel" style="padding: var(--space-6); text-align:center">
                <span class="ds-text-secondary" style="display:inline-flex">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                    </svg>
                </span>
                <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">Aucune opportunité trouvée</h2>
                <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                    {{ $filtresActifs ? 'Essayez de modifier ou de réinitialiser vos filtres.' : 'Revenez plus tard, de nouvelles offres sont publiées régulièrement.' }}
                </p>
                @if($filtresActifs)
                {{-- Lien plutot qu un second bouton id="reset-filters" : l identifiant etait
                     duplique, et getElementById ne cablait que le premier. --}}
                <a href="{{ route('bachelier.opportunites') }}" class="ds-btn ds-btn-primary ds-btn-lg" style="margin-top: var(--space-3)">
                    Réinitialiser les filtres
                </a>
                @endif
            </div>
        @endforelse
    </div>

    @if($opportunites->hasPages())
    <div>
        {{ $opportunites->links() }}
    </div>
    @endif

</div>

@include('bachelier.candidature-confirm-modal')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#filter-form');

    if (form) {
        const searchInput = document.querySelector('#search');
        const selects = form.querySelectorAll('select');
        const resetBtn = document.getElementById('reset-filters');

        selects.forEach(function (select) {
            select.addEventListener('change', function () { form.submit(); });
        });

        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function () { form.submit(); }, 300);
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                window.location.href = '{{ route("bachelier.opportunites") }}';
            });
        }
    }

    // Favoris. Le marqueur visuel est desormais l attribut fill du SVG et aria-pressed,
    // et non une classe de palette posee sur une balise <i>.
    document.querySelectorAll('.favorite-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const opportuniteId = this.dataset.opportuniteId;

            fetch('/bachelier/favoris/toggle', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ opportunite_id: opportuniteId })
            })
            .then(function (response) { return response.json(); })
            .then(function () { window.location.reload(); })
            .catch(function (error) { console.error('Erreur:', error); });
        });
    });
});
</script>
@endpush
@endsection
