@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Mes Candidatures - Bachelier PEUB')

@php
    // Statuts reels de la colonne enum : pending, reviewed, accepted, rejected, participated.
    // Chacun est rendu par un point de couleur et un libelle poses sur la carte, jamais par
    // un aplat plein : mesure 5,58:1 en sombre et 9,75:1 en clair pour « refusee », contre
    // 4,42:1 pour la pastille teintee du design system, qui echoue en sombre.
    $statutRoles = [
        'pending' => ['pending', 'En attente'],
        'reviewed' => ['review', 'En cours'],
        'accepted' => ['accepted', 'Acceptée'],
        'rejected' => ['rejected', 'Refusée'],
        'participated' => ['accepted', 'Participé'],
    ];

    $typeIcones = [
        'bourse' => ['M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z', 'M22 10v6', 'M6 12.5V16a6 3 0 0 0 12 0v-3.5'],
        'stage' => ['M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16', 'M4 7h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z'],
        'formation' => ['M12 7v14', 'M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z'],
        'concours' => ['M12 2a6 6 0 1 0 0 12 6 6 0 0 0 0-12', 'M15.477 12.89 17 22l-5-3-5 3 1.523-9.11'],
        'event' => ['M8 2v4', 'M16 2v4', 'M3 10h18', 'M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z'],
        'promotion' => ['m3 11 18-5v12L3 14v-3z', 'M11.6 16.8a3 3 0 1 1-5.8-1.6'],
        'defaut' => ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'M12 6a6 6 0 1 0 0 12 6 6 0 0 0 0-12', 'M12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4'],
    ];

    // Le formulaire GET reste identique, seul son affichage est replie.
    $champsFiltre = ['search', 'status', 'type', 'sort'];
    $filtresActifs = collect($champsFiltre)->filter(fn ($c) => request()->filled($c))->count();

    $reperes = [
        ['pending', 'En attente', $stats['pending']],
        ['review', 'En cours', $stats['reviewed']],
        ['accepted', 'Acceptées', $stats['accepted']],
        ['rejected', 'Refusées', $stats['rejected']],
    ];

    $ongletsNav = [
        ['route' => 'bachelier.opportunites', 'libelle' => 'Toutes les opportunités', 'actif' => request()->routeIs('bachelier.opportunites') && !request()->has('filter'),
         'icone' => ['M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16', 'M4 7h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z']],
        ['route' => 'bachelier.favoris', 'libelle' => 'Mes favoris', 'actif' => request()->routeIs('bachelier.favoris'),
         'icone' => ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z']],
        ['route' => 'bachelier.candidatures', 'libelle' => 'Mes candidatures', 'actif' => request()->routeIs('bachelier.candidatures'),
         'icone' => ['M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z', 'M14 2v4a2 2 0 0 0 2 2h4', 'M16 13H8', 'M16 17H8']],
    ];
@endphp

@section('content')
@php
    // La base stocke des slugs sans accent. L affichage doit etre accentue
    // et lisible : toute valeur absente de cette table retombe sur le slug.
    $secteurs = [
        'telecoms_services_numeriques' => 'Télécoms et services numériques',
        'agro_agroalimentaire'         => 'Agriculture et agroalimentaire',
        'energie_environnement'        => 'Énergie et environnement',
        'banque_finance'               => 'Banque et finance',
    ];
@endphp
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    {{-- En-tete de page : la page n avait aucun h1, elle s ouvrait sur un fil d Ariane. --}}
    <header>
        <p class="ds-overline">OPPORTUNITÉS / MES CANDIDATURES</p>
        <h1 style="margin-top: var(--space-1)">Mes candidatures</h1>
    </header>

    {{-- Navigation d onglets. Reprise en ligne plutot que via x-opportunites-nav :
         ce composant est hors perimetre et code ses couleurs en dur, il ne basculerait
         pas en mode sombre. Memes trois routes, memes libelles. --}}
    <nav aria-label="Navigation des opportunités" style="display:flex; gap:var(--space-1); overflow-x:auto; padding-bottom:var(--space-0-5)">
        @foreach ($ongletsNav as $onglet)
            <a href="{{ route($onglet['route']) }}"
               @if ($onglet['actif']) aria-current="page" @endif
               style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; padding:0 var(--space-2); border-radius:var(--radius-pill); white-space:nowrap; font-size:var(--text-caption); font-weight:var(--font-medium); text-decoration:none; {{ $onglet['actif'] ? 'background:var(--accent); color:var(--text-on-accent);' : 'background:var(--surface-secondary); color:var(--text-primary);' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    @foreach ($onglet['icone'] as $d)<path d="{{ $d }}"/>@endforeach
                </svg>
                {{ $onglet['libelle'] }}
            </a>
        @endforeach
    </nav>

    {{-- Reperes de suivi. Les quatre cartes separees d origine sont fusionnees en une
         bande compacte : a 360px elles occupaient deux rangees avant la premiere candidature. --}}
    <div class="ds-card" style="padding: var(--space-2)">
        <div style="display:grid; gap:var(--space-1); grid-template-columns:repeat(auto-fit, minmax(64px, 1fr))">
            @foreach ($reperes as [$role, $libelle, $valeur])
                <div style="min-width:0; text-align:center">
                    <p class="ds-stat" style="font-size: var(--text-h2); color: var(--status-{{ $role }}-text)">{{ $valeur }}</p>
                    <p class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size: var(--text-label)">{{ $libelle }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Filtres replies. Le formulaire GET et tous ses champs sont conserves : replie,
         il reste dans le DOM et soumet toujours ses valeurs. Ouvert d office si un filtre
         est actif, pour que l utilisateur voie ce qui restreint sa liste. --}}
    <div x-data="{ ouvert: {{ $filtresActifs ? 'true' : 'false' }} }">
        <button type="button"
                @click="ouvert = !ouvert"
                :aria-expanded="ouvert ? 'true' : 'false'"
                aria-controls="filtres-candidatures"
                class="ds-btn ds-btn-secondary ds-btn-md">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <path d="M3 6h18"/><path d="M7 12h10"/><path d="M10 18h4"/>
            </svg>
            Filtrer et trier
            @if ($filtresActifs)
                <span class="ds-badge ds-badge-accent">{{ $filtresActifs }} actif{{ $filtresActifs > 1 ? 's' : '' }}</span>
            @endif
        </button>

        {{-- x-cloak n est pas defini dans le projet : l etat initial est pose en style inline,
                 qu Alpine reprend ensuite via x-show. Evite le clignotement avant hydratation. --}}
        <div id="filtres-candidatures" x-show="ouvert" class="ds-card"
             style="margin-top: var(--space-1-5); padding: var(--space-3){{ $filtresActifs ? '' : '; display:none' }}">
            <form id="filter-form" method="GET" action="{{ route('bachelier.candidatures') }}" class="ds-stack-sm">
                <div>
                    <label class="ds-label" for="search">Rechercher</label>
                    <input type="search" id="search" name="search"
                           placeholder="Titre de l'offre ou partenaire"
                           value="{{ request('search') }}"
                           class="ds-field">
                </div>

                <div style="display:grid; gap:var(--space-1-5); grid-template-columns:repeat(auto-fit, minmax(150px, 1fr))">
                    <div>
                        <label class="ds-label" for="status-filter">Statut</label>
                        <select id="status-filter" name="status" class="ds-field">
                            <option value="">Tous</option>
                            <option value="pending" @selected(request('status') == 'pending')>En attente</option>
                            <option value="reviewed" @selected(request('status') == 'reviewed')>En cours</option>
                            <option value="accepted" @selected(request('status') == 'accepted')>Acceptée</option>
                            <option value="rejected" @selected(request('status') == 'rejected')>Refusée</option>
                        </select>
                    </div>

                    <div>
                        <label class="ds-label" for="type-filter">Type</label>
                        <select id="type-filter" name="type" class="ds-field">
                            <option value="">Tous</option>
                            <option value="bourse" @selected(request('type') == 'bourse')>Bourse</option>
                            <option value="stage" @selected(request('type') == 'stage')>Stage</option>
                            <option value="formation" @selected(request('type') == 'formation')>Formation</option>
                            <option value="emploi" @selected(request('type') == 'emploi')>Emploi</option>
                        </select>
                    </div>

                    <div>
                        <label class="ds-label" for="sort-filter">Trier par</label>
                        <select id="sort-filter" name="sort" class="ds-field">
                            <option value="recent" @selected(request('sort') == 'recent')>Plus récentes</option>
                            <option value="oldest" @selected(request('sort') == 'oldest')>Plus anciennes</option>
                            <option value="deadline" @selected(request('sort') == 'deadline')>Date limite</option>
                            <option value="score" @selected(request('sort') == 'score')>Score IA</option>
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

    {{-- Liste des candidatures, desormais devant les filtres. --}}
    <div class="ds-stack-sm">
        @forelse($candidatures as $candidature)
            @php
                [$roleStatut, $libelleStatut] = $statutRoles[$candidature->status] ?? ['draft', ucfirst($candidature->status)];
                $paths = $typeIcones[$candidature->opportunite->type] ?? $typeIcones['defaut'];
            @endphp
            <article class="ds-card" style="padding: var(--space-3)">
                <div style="display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fit, minmax(240px, 1fr))">

                    <div style="min-width:0">
                        {{-- Statut : point de couleur et libelle, la carte reste neutre. --}}
                        <p style="display:flex; align-items:center; gap:var(--space-0-5); font-size:var(--text-caption); font-weight:var(--font-semibold); color:var(--status-{{ $roleStatut }}-text)">
                            <span style="width:8px; height:8px; flex-shrink:0; border-radius:var(--radius-pill); background:currentColor"></span>
                            {{ $libelleStatut }}
                        </p>

                        <h2 style="margin-top: var(--space-1); font-size: var(--text-h3)">
                            <a href="{{ route('bachelier.opportunites.show', $candidature->opportunite) }}" style="color:inherit; text-decoration:none">
                                {{ $candidature->opportunite->titre }}
                            </a>
                        </h2>

                        <div style="margin-top: var(--space-1); display:flex; align-items:center; gap:var(--space-1)">
                            <span style="display:grid; place-items:center; width:32px; height:32px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--surface-secondary); overflow:hidden">
                                @if($candidature->opportunite->partenaire->logo)
                                    <img src="{{ asset('storage/' . $candidature->opportunite->partenaire->logo) }}"
                                         alt="{{ $candidature->opportunite->partenaire->nom_organisation }}"
                                         width="24" height="24" loading="lazy" decoding="async"
                                         style="width:24px; height:24px; object-fit:contain">
                                @else
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="color:var(--text-secondary)">
                                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/>
                                    </svg>
                                @endif
                            </span>
                            <span style="min-width:0">
                                <span style="display:block; font-size:var(--text-caption); font-weight:var(--font-medium)">{{ $candidature->opportunite->partenaire->nom_organisation }}</span>
                                <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)">{{ $secteurs[$candidature->opportunite->partenaire->secteur_activite] ?? $candidature->opportunite->partenaire->secteur_activite }}</span>
                            </span>
                        </div>

                        <div style="margin-top: var(--space-2); display:flex; flex-wrap:wrap; gap:var(--space-0-5) var(--space-2); font-size:var(--text-label)">
                            <span class="ds-badge ds-badge-neutral">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    @foreach ($paths as $d)<path d="{{ $d }}"/>@endforeach
                                </svg>
                                {{ ucfirst($candidature->opportunite->type) }}
                            </span>
                            <span class="ds-text-secondary">{{ $candidature->opportunite->ville }}, {{ $candidature->opportunite->pays }}</span>
                        </div>

                        <dl style="margin-top: var(--space-1-5); display:grid; gap:var(--space-1); grid-template-columns:repeat(auto-fit, minmax(120px, 1fr)); font-size:var(--text-label)">
                            <div>
                                <dt class="ds-text-secondary">Date limite</dt>
                                <dd style="font-weight:var(--font-medium)">{{ \Carbon\Carbon::parse($candidature->opportunite->date_limite_candidature)->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="ds-text-secondary">Postulé le</dt>
                                <dd style="font-weight:var(--font-medium)">{{ $candidature->created_at->format('d/m/Y') }}</dd>
                            </div>
                            @if($candidature->opportunite->remuneration)
                            <div>
                                <dt class="ds-text-secondary">Rémunération</dt>
                                <dd style="font-weight:var(--font-medium)">{{ $candidature->opportunite->remuneration }}</dd>
                            </div>
                            @endif
                        </dl>

                        {{-- Le champ est score_matching. La vue lisait score_ia, inexistant sur
                             le modele : ce bloc n avait jamais pu s afficher. Corrige le 20/08/2026. --}}
                        @if($candidature->score_matching)
                        <div style="margin-top: var(--space-1-5)">
                            <div style="display:flex; justify-content:space-between; font-size:var(--text-label)">
                                <span class="ds-text-secondary">Compatibilité</span>
                                <span style="font-weight:var(--font-medium)">{{ $candidature->score_matching }}%</span>
                            </div>
                            <div class="ds-progress" style="margin-top: var(--space-0-5)">
                                <div class="ds-progress-bar" style="width: {{ $candidature->score_matching }}%"></div>
                            </div>
                        </div>
                        @endif

                        <p class="ds-text-secondary" style="margin-top: var(--space-1-5); font-size:var(--text-label)">
                            {{ $candidature->opportunite->candidatures_count }} candidature(s) au total
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex; flex-direction:column; gap:var(--space-1); align-self:start">
                        <a href="{{ route('bachelier.candidatures.show', $candidature) }}" class="ds-btn ds-btn-primary ds-btn-md ds-btn-block">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><path d="M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6"/>
                            </svg>
                            Voir détails
                        </a>

                        @if($candidature->status === 'pending')
                        <form action="{{ route('bachelier.candidatures.destroy', $candidature) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Retirer définitivement cette candidature ? Cette action est irréversible.')"
                                    class="ds-btn ds-btn-secondary ds-btn-md ds-btn-block">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                                </svg>
                                Retirer ma candidature
                            </button>
                        </form>
                        @endif

                        @if($candidature->status === 'accepted')
                        <a href="{{ route('bachelier.opportunites.show', $candidature->opportunite) }}" class="ds-btn ds-btn-secondary ds-btn-md ds-btn-block">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            Voir l'opportunité
                        </a>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="ds-panel" style="padding: var(--space-6); text-align:center">
                <span class="ds-text-secondary" style="display:inline-flex">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M16 13H8"/><path d="M16 17H8"/>
                    </svg>
                </span>
                <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">
                    {{ $filtresActifs ? 'Aucune candidature ne correspond' : 'Aucune candidature' }}
                </h2>
                <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                    {{ $filtresActifs ? 'Essayez de modifier ou de réinitialiser vos filtres.' : "Vous n'avez pas encore postulé à des opportunités" }}
                </p>
                <a href="{{ route('bachelier.opportunites') }}" class="ds-btn ds-btn-primary ds-btn-lg" style="margin-top: var(--space-3)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M11 3a8 8 0 1 0 0 16 8 8 0 0 0 0-16"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    Découvrir des opportunités
                </a>
            </div>
        @endforelse
    </div>

    @if($candidatures->hasPages())
    <div>
        {{ $candidatures->links() }}
    </div>
    @endif

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#filter-form');
    if (!form) { return; }

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
            window.location.href = '{{ route("bachelier.candidatures") }}';
        });
    }
});
</script>
@endpush
@endsection
