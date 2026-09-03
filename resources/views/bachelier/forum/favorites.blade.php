@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Mes favoris - Communauté PEUB')

@php
    $onglets = [
        [
            'route' => 'bachelier.forum.index',
            'libelle' => 'Discussions',
            'actif' => request()->routeIs('bachelier.forum.index'),
            'icone' => ['M7.9 20A9 9 0 1 0 4 16.1L2 22z'],
        ],
        [
            'route' => 'bachelier.forum.favorites',
            'libelle' => 'Mes favoris',
            'actif' => request()->routeIs('bachelier.forum.favorites'),
            'icone' => ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z'],
        ],
        [
            'route' => 'bachelier.forum.members',
            'libelle' => 'Membres',
            'actif' => request()->routeIs('bachelier.forum.members'),
            'icone' => ['M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2', 'M9 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8', 'M22 21v-2a4 4 0 0 0-3-3.87', 'M16 3.13a4 4 0 0 1 0 7.75'],
        ],
    ];

    // Champs reellement lus par ForumController@favorites. Les trois le sont.
    $champsFiltre = ['search', 'category', 'sort'];
    $filtresActifs = collect($champsFiltre)->filter(fn ($c) => request()->filled($c))->count();
@endphp

@section('content')
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    {{-- En-tete de page : la vue s ouvrait sur un fil d Ariane, sans aucun h1. --}}
    <header>
        <p class="ds-overline">COMMUNAUTÉ / MES FAVORIS</p>
        <h1 style="margin-top: var(--space-1)">Mes favoris</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            {{ $favoriteThreads->total() }}
            {{ $favoriteThreads->total() > 1 ? 'discussions mises de côté' : 'discussion mise de côté' }}@if($filtresActifs) pour vos filtres @endif.
        </p>
    </header>

    <nav aria-label="Navigation de la communauté"
         style="display:flex; gap:var(--space-1); overflow-x:auto; padding-bottom:var(--space-0-5); scrollbar-width:none">
        @foreach ($onglets as $onglet)
            <a href="{{ route($onglet['route']) }}"
               @if ($onglet['actif']) aria-current="page" @endif
               style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; padding:0 var(--space-2); border-radius:var(--radius-pill); white-space:nowrap; font-size:var(--text-caption); font-weight:var(--font-medium); text-decoration:none; {{ $onglet['actif'] ? 'background:var(--accent); color:var(--text-on-accent);' : 'background:var(--surface-secondary); color:var(--text-primary);' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0">
                    @foreach ($onglet['icone'] as $d)<path d="{{ $d }}"/>@endforeach
                </svg>
                {{ $onglet['libelle'] }}
            </a>
        @endforeach
    </nav>

    {{-- Filtres replies. Le bloc occupait tout le premier ecran a 360px, avant le
         moindre favori. Le formulaire GET et ses trois champs, tous lus par le
         controleur, sont conserves : replie, il reste dans le DOM et soumet
         toujours ses valeurs. Ouvert d office si un filtre est actif. --}}
    <div x-data="{ ouvert: {{ $filtresActifs ? 'true' : 'false' }} }">
        <button type="button"
                @click="ouvert = !ouvert"
                :aria-expanded="ouvert ? 'true' : 'false'"
                aria-controls="filtres-favoris"
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
        <div id="filtres-favoris" x-show="ouvert" class="ds-card"
             style="margin-top: var(--space-1-5); padding: var(--space-3){{ $filtresActifs ? '' : '; display:none' }}">
            <form id="filter-form" method="GET" action="{{ route('bachelier.forum.favorites') }}" class="ds-stack-sm">
                <div>
                    <label class="ds-label" for="search">Rechercher</label>
                    <input type="search" id="search" name="search"
                           placeholder="Titre d'une discussion favorite"
                           value="{{ request('search') }}"
                           class="ds-field">
                </div>

                <div style="display:grid; gap:var(--space-1-5); grid-template-columns:repeat(auto-fit, minmax(150px, 1fr))">
                    <div>
                        <label class="ds-label" for="category-filter">Catégorie</label>
                        <select id="category-filter" name="category" class="ds-field">
                            <option value="">Toutes</option>
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->id }}" @selected(request('category') == $categorie->id)>{{ $categorie->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Les options « populaire » et « vues » trient toutes deux sur
                         forum_threads.views_count cote controleur : elles donnaient le
                         meme resultat sous deux libelles differents. Une seule est
                         conservee, sans toucher au controleur. --}}
                    <div>
                        <label class="ds-label" for="sort-filter">Trier par</label>
                        <select id="sort-filter" name="sort" class="ds-field">
                            <option value="recent" @selected(request('sort', 'recent') === 'recent')>Ajout le plus récent</option>
                            <option value="views" @selected(request('sort') === 'views')>Les plus consultées</option>
                            <option value="replies" @selected(request('sort') === 'replies')>Les plus commentées</option>
                        </select>
                    </div>
                </div>

                <div>
                    <a href="{{ route('bachelier.forum.favorites') }}" class="ds-btn ds-btn-ghost ds-btn-md">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M3 2v6h6"/><path d="M3 13a9 9 0 1 0 3-7.7L3 8"/>
                        </svg>
                        Réinitialiser les filtres
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="ds-stack-sm" id="liste-favoris">
        @forelse ($favoriteThreads as $favorite)
            @continue(!$favorite->thread)
            @php $thread = $favorite->thread; @endphp
            <article class="ds-card" data-favori="{{ $thread->id }}" style="padding: var(--space-2)">
                <div style="display:flex; gap:var(--space-1); align-items:flex-start">
                    <div style="min-width:0; flex:1">
                        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:var(--space-0-5)">
                            @if ($thread->category)
                                {{-- Etiquette, et non lien : cliquable elle mesurait 29px de haut,
                                     sous la cible de 44px. --}}
                                <span class="ds-badge ds-badge-neutral">{{ $thread->category->name }}</span>
                            @endif
                            @if ($thread->is_pinned)
                                <span class="ds-badge ds-badge-accent">Épinglée</span>
                            @endif
                            @if ($thread->is_featured)
                                <span class="ds-badge ds-badge-accent">À la une</span>
                            @endif
                            @if ($thread->is_locked)
                                <span class="ds-badge ds-badge-neutral">Fermée aux réponses</span>
                            @endif
                        </div>

                        <h2 style="margin-top: var(--space-1); font-size: var(--text-body)">
                            <a href="{{ route('bachelier.forum.thread', $thread) }}" style="color:inherit; text-decoration:none">
                                {{ $thread->title }}
                            </a>
                        </h2>

                        <p class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size: var(--text-label)">
                            Par {{ $thread->user?->name ?? 'Membre retiré' }}
                            &middot; {{ $thread->created_at?->locale('fr')->diffForHumans() }}
                        </p>

                        <p class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size: var(--text-label)">
                            {{ $thread->posts_count }} {{ $thread->posts_count > 1 ? 'réponses' : 'réponse' }}
                            &middot; {{ $thread->views_count }} {{ $thread->views_count > 1 ? 'vues' : 'vue' }}
                            @if ($thread->last_activity_at)
                                &middot; activité {{ $thread->last_activity_at->locale('fr')->diffForHumans() }}
                            @endif
                        </p>
                    </div>

                    <button type="button" class="forum-favori"
                            data-thread-id="{{ $thread->id }}"
                            aria-pressed="true">
                        <span class="sr-only">Retirer cette discussion de mes favoris</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>
                        </svg>
                    </button>
                </div>
            </article>
        @empty
            <div class="ds-card-flat" style="padding: var(--space-6); text-align:center">
                <span class="ds-text-secondary" style="display:inline-flex">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>
                    </svg>
                </span>
                <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">
                    {{ $filtresActifs ? 'Aucun favori ne correspond' : 'Aucun favori pour le moment' }}
                </h2>
                <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                    {{ $filtresActifs
                        ? 'Essayez de modifier ou de réinitialiser vos filtres.'
                        : "Le cœur au bout de chaque discussion la met de côté. Vous la retrouverez ici, même des mois plus tard." }}
                </p>
                <a href="{{ $filtresActifs ? route('bachelier.forum.favorites') : route('bachelier.forum.index') }}"
                   class="ds-btn ds-btn-primary ds-btn-lg" style="margin-top: var(--space-3)">
                    {{ $filtresActifs ? 'Réinitialiser les filtres' : 'Parcourir les discussions' }}
                </a>
            </div>
        @endforelse
    </div>

    @if ($favoriteThreads->hasPages())
    <div>
        {{ $favoriteThreads->withQueryString()->links() }}
    </div>
    @endif

</div>

@push('styles')
<style>
    /* CONTRASTE AA, mesure a 360px et non suppose. Deux appariements du design
       system passent juste sous 4,5:1 en mode clair :
         --text-secondary sur --surface-secondary : 4,48:1  (.ds-badge-neutral)
         --accent         sur --accent-surface    : 4,31:1  (.ds-badge-accent)
       Les deux viennent de theme.css et design-system.css, hors perimetre de ce
       lot ; la correction de fond est un --text-secondary un cran plus sombre.
       En attendant, le texte de ces pastilles passe en --text-primary : mesure
       11,3:1 en clair et 13,8:1 en sombre. Une seule regle, valable dans les deux
       themes, aucune regle propre au sombre.
       Selecteur en html[data-ds] .x, soit (0,2,1), pour battre la classe du
       design system, (0,1,0). */
    html[data-ds] .ds-badge-neutral,
    html[data-ds] .ds-badge-accent { color: var(--text-primary); }

    html[data-ds] .forum-favori {
        display: grid;
        place-items: center;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        border: 0;
        background: none;
        color: var(--accent);
        cursor: pointer;
        border-radius: var(--radius-pill);
    }
    html[data-ds] .forum-favori:hover { background: var(--surface-hover); }
    html[data-ds] .forum-favori[aria-pressed="false"] { color: var(--text-secondary); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formulaire = document.querySelector('#filter-form');
    if (formulaire) {
        formulaire.querySelectorAll('select').forEach(function (liste) {
            liste.addEventListener('change', function () { formulaire.submit(); });
        });

        const champRecherche = document.querySelector('#search');
        if (champRecherche) {
            let minuteur;
            champRecherche.addEventListener('input', function () {
                clearTimeout(minuteur);
                minuteur = setTimeout(function () { formulaire.submit(); }, 300);
            });
        }
    }

    // Retrait d un favori. L ancien script remontait au premier parent .px-6 puis
    // comptait les .px-6.py-4 restantes : deux classes utilitaires devenues des
    // reperes de structure, que la moindre retouche de style cassait. Le repere est
    // desormais l attribut data-favori.
    document.querySelectorAll('.forum-favori').forEach(function (bouton) {
        bouton.addEventListener('click', function () {
            const identifiant = bouton.dataset.threadId;

            fetch('/bachelier/forum/' + identifiant + '/favorite', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(function (reponse) { return reponse.json(); })
            .then(function (donnees) {
                if (!donnees.success) { return; }
                bouton.setAttribute('aria-pressed', donnees.isFavorited ? 'true' : 'false');
                bouton.querySelector('svg').setAttribute('fill', donnees.isFavorited ? 'currentColor' : 'none');

                if (donnees.isFavorited) { return; }

                const ligne = document.querySelector('[data-favori="' + identifiant + '"]');
                if (!ligne) { return; }
                ligne.style.transition = 'opacity var(--duration-normal) var(--easing)';
                ligne.style.opacity = '0';
                setTimeout(function () {
                    ligne.remove();
                    if (!document.querySelector('[data-favori]')) { window.location.reload(); }
                }, 300);
            })
            .catch(function (erreur) { console.error('Erreur:', erreur); });
        });
    });
});
</script>
@endpush
@endsection
