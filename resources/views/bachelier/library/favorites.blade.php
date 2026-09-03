@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Mes favoris - Bibliothèque PEUB')

@php
    $onglets = [
        [
            'route' => 'bachelier.library.index',
            'libelle' => 'Toutes les ressources',
            'actif' => request()->routeIs('bachelier.library.index'),
            'icone' => ['M12 7v14', 'M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z'],
        ],
        [
            'route' => 'bachelier.library.favorites',
            'libelle' => 'Mes favoris',
            'actif' => request()->routeIs('bachelier.library.favorites'),
            'icone' => ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z'],
        ],
    ];

    $typesLibelles = [
        'pdf' => 'PDF',
        'video' => 'Vidéo',
        'audio' => 'Audio',
        'document' => 'Document',
        'presentation' => 'Présentation',
    ];

    $typesIcones = [
        'pdf' => ['M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z', 'M14 2v4a2 2 0 0 0 2 2h4', 'M16 13H8', 'M16 17H8', 'M10 9H8'],
        'video' => ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'm10 8 6 4-6 4z'],
        'audio' => ['M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z', 'M18 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2z', 'M3 16a9 9 0 1 1 18 0'],
        'document' => ['M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z', 'M14 2v4a2 2 0 0 0 2 2h4', 'M16 13H8', 'M16 17H8'],
        'presentation' => ['M2 3h20', 'M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3', 'm7 21 5-5 5 5'],
        'defaut' => ['M12 7v14', 'M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z'],
    ];

    $niveauxLibelles = [
        'debutant' => 'Débutant',
        'intermediaire' => 'Intermédiaire',
        'avance' => 'Avancé',
    ];

    // LibraryController@favorites charge la relation resource sous contrainte
    // is_active : une ressource depubliee revient donc a null. La page comptait
    // $favorites->count(), lignes nulles comprises, puis n en affichait aucune :
    // une page entiere de favoris depublies donnait une grille vide sans un mot.
    // On filtre d abord, on compte ensuite.
    $favorisVisibles = $favorites->getCollection()->filter(fn ($f) => $f->resource !== null);
    $masques = $favorites->count() - $favorisVisibles->count();
@endphp

@section('content')
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    {{-- En-tete de page : la vue s ouvrait sur un fil d Ariane, sans aucun h1. --}}
    <header>
        <p class="ds-overline">RESSOURCES / MES FAVORIS</p>
        <h1 style="margin-top: var(--space-1)">Mes favoris</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            {{ $favorites->total() }}
            {{ $favorites->total() > 1 ? 'ressources mises de côté' : 'ressource mise de côté' }}.
        </p>
    </header>

    <nav aria-label="Navigation de la bibliothèque"
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

    {{-- FILTRE MORT SUPPRIME. Cette page portait un formulaire GET avec un champ
         « search » et une soumission automatique au bout de 300 ms.
         LibraryController@favorites ne prend meme pas de Request en parametre : la
         recherche n a jamais rien filtre, elle rechargeait la page a l identique.
         Le bloc contenait aussi une rangee de filtres entierement vide et un
         gestionnaire pour un bouton #reset-filters absent du balisage. --}}

    @if ($masques > 0)
        <div class="ds-alert ds-alert-info" role="status">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0; margin-top:2px">
                <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
            </svg>
            <p>
                {{ $masques }} {{ $masques > 1 ? 'ressources ne sont plus disponibles' : 'ressource n\'est plus disponible' }}
                et {{ $masques > 1 ? 'ont' : 'a' }} été retirée{{ $masques > 1 ? 's' : '' }} de cet affichage.
            </p>
        </div>
    @endif

    @if ($favorisVisibles->count() > 0)
        <div style="display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fill, minmax(260px, 1fr))" id="liste-favoris">
            @foreach ($favorisVisibles as $favorite)
                @php
                    $resource = $favorite->resource;
                    $chemins = $typesIcones[$resource->type] ?? $typesIcones['defaut'];
                    $typeLibelle = $typesLibelles[$resource->type] ?? $resource->type;
                    $vignette = $resource->thumbnail ? Storage::url($resource->thumbnail) : null;
                @endphp
                <article class="ds-card" data-favori="{{ $resource->id }}" style="display:flex; flex-direction:column; overflow:hidden">
                    @if ($vignette)
                        <img src="{{ $vignette }}" alt="Aperçu de {{ $resource->title }}"
                             width="400" height="160" loading="lazy" decoding="async"
                             style="width:100%; height:140px; object-fit:cover; display:block">
                    @else
                        <div style="display:grid; place-items:center; height:140px; background:var(--accent-surface); color:var(--accent)" aria-hidden="true">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" focusable="false">
                                @foreach ($chemins as $d)<path d="{{ $d }}"/>@endforeach
                            </svg>
                        </div>
                    @endif

                    <div style="display:flex; flex-direction:column; gap:var(--space-1); padding:var(--space-2); flex:1">
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:var(--space-1)">
                            <div style="display:flex; flex-wrap:wrap; align-items:center; gap:var(--space-0-5); min-width:0">
                                <span class="ds-badge ds-badge-neutral">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                        @foreach ($chemins as $d)<path d="{{ $d }}"/>@endforeach
                                    </svg>
                                    {{ $typeLibelle }}
                                </span>
                                @if ($resource->level)
                                    <span class="ds-badge ds-badge-neutral">{{ $niveauxLibelles[$resource->level] ?? $resource->level }}</span>
                                @endif
                            </div>

                            <button type="button" class="library-favori"
                                    data-resource-id="{{ $resource->id }}"
                                    aria-pressed="true">
                                <span class="sr-only">Retirer « {{ $resource->title }} » de mes favoris</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>
                                </svg>
                            </button>
                        </div>

                        <h2 style="font-size: var(--text-body)">
                            <a href="{{ route('bachelier.library.show', $resource) }}" style="color:inherit; text-decoration:none">
                                {{ $resource->title }}
                            </a>
                        </h2>

                        @if ($resource->description)
                            <p class="ds-text-secondary line-clamp-2" style="font-size: var(--text-caption)">{{ $resource->description }}</p>
                        @endif

                        <p class="ds-text-secondary" style="font-size: var(--text-label)">
                            {{ $resource->category?->name ?? 'Sans catégorie' }}@if($resource->author) &middot; {{ $resource->author }}@endif
                        </p>

                        <p class="ds-text-secondary numbers" style="margin-top:auto; font-size:var(--text-label)">
                            {{ $resource->views_count }} {{ $resource->views_count > 1 ? 'vues' : 'vue' }}
                            &middot; ajoutée {{ $favorite->created_at?->locale('fr')->diffForHumans() }}
                        </p>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($favorites->hasPages())
        <div>
            {{ $favorites->links() }}
        </div>
        @endif
    @else
        <div class="ds-card-flat" style="padding: var(--space-6); text-align:center">
            <span class="ds-text-secondary" style="display:inline-flex">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>
                </svg>
            </span>
            <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">Aucune ressource en favori</h2>
            <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                Le cœur sur la fiche d'une ressource la met de côté.
                Vous la retrouverez ici, même des mois plus tard.
            </p>
            <a href="{{ route('bachelier.library.index') }}" class="ds-btn ds-btn-primary ds-btn-lg" style="margin-top: var(--space-3)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>
                </svg>
                Parcourir la bibliothèque
            </a>
        </div>
    @endif

</div>

@push('styles')
<style>
    html[data-ds] .library-favori {
        display: grid;
        place-items: center;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        margin: calc(var(--space-1) * -1) calc(var(--space-1) * -1) 0 0;
        border: 0;
        background: none;
        color: var(--accent);
        cursor: pointer;
        border-radius: var(--radius-pill);
    }
    html[data-ds] .library-favori:hover { background: var(--surface-hover); }
    html[data-ds] .library-favori[aria-pressed="false"] { color: var(--text-secondary); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Retrait d un favori. L ancien script remontait au premier parent .bg-white
    // puis comptait les .bg-white.border restantes : deux classes utilitaires
    // devenues des reperes de structure, que la moindre retouche de style cassait.
    // Le repere est desormais l attribut data-favori.
    document.querySelectorAll('.library-favori').forEach(function (bouton) {
        bouton.addEventListener('click', function () {
            const identifiant = bouton.dataset.resourceId;

            fetch('/bachelier/library/' + identifiant + '/favorite', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(function (reponse) { return reponse.json(); })
            .then(function (donnees) {
                bouton.setAttribute('aria-pressed', donnees.isFavorited ? 'true' : 'false');
                bouton.querySelector('svg').setAttribute('fill', donnees.isFavorited ? 'currentColor' : 'none');

                if (donnees.isFavorited) { return; }

                const carte = document.querySelector('[data-favori="' + identifiant + '"]');
                if (!carte) { return; }
                carte.style.transition = 'opacity var(--duration-normal) var(--easing)';
                carte.style.opacity = '0';
                setTimeout(function () {
                    carte.remove();
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
