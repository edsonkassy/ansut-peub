@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Bibliothèque - Bachelier PEUB')

@php
    // Onglets de la bibliotheque. Repris a l identique dans favorites : les deux
    // vues partagent le meme rail. Un composant commun serait le bon reflexe, mais
    // le creer suppose un fichier hors du perimetre de ce lot.
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

    // La colonne type stocke des slugs sans accent. L affichage doit etre accentue :
    // la vue montrait « PDF », « VIDEO », « PRESENTATION » en majuscules brutes.
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

    // Champs reellement lus par LibraryController@index. Les cinq le sont.
    $champsFiltre = ['search', 'type', 'category', 'level', 'sort'];
    $filtresActifs = collect($champsFiltre)->filter(fn ($c) => request()->filled($c))->count();
@endphp

@section('content')
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    {{-- En-tete de page : la vue s ouvrait sur un fil d Ariane, sans aucun h1. --}}
    <header>
        <p class="ds-overline">RESSOURCES / BIBLIOTHÈQUE</p>
        <h1 style="margin-top: var(--space-1)">Bibliothèque</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            {{ $resources->total() }}
            {{ $resources->total() > 1 ? 'ressources disponibles' : 'ressource disponible' }}@if($filtresActifs) pour vos filtres @endif.
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

    {{-- Filtres replies. Le bloc occupait tout le premier ecran a 360px, avant la
         moindre ressource. Le formulaire GET et ses cinq champs, tous lus par le
         controleur, sont conserves : replie, il reste dans le DOM et soumet toujours
         ses valeurs. Ouvert d office si un filtre est actif. --}}
    <div x-data="{ ouvert: {{ $filtresActifs ? 'true' : 'false' }} }">
        <button type="button"
                @click="ouvert = !ouvert"
                :aria-expanded="ouvert ? 'true' : 'false'"
                aria-controls="filtres-bibliotheque"
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
        <div id="filtres-bibliotheque" x-show="ouvert" class="ds-card"
             style="margin-top: var(--space-1-5); padding: var(--space-3){{ $filtresActifs ? '' : '; display:none' }}">
            <form id="filter-form" method="GET" action="{{ route('bachelier.library.index') }}" class="ds-stack-sm">
                <div>
                    <label class="ds-label" for="search-filter">Rechercher</label>
                    <input type="search" id="search-filter" name="search"
                           placeholder="Titre, description, auteur ou mot-clé"
                           value="{{ request('search') }}"
                           class="ds-field">
                </div>

                <div style="display:grid; gap:var(--space-1-5); grid-template-columns:repeat(auto-fit, minmax(150px, 1fr))">
                    <div>
                        <label class="ds-label" for="type-filter">Type</label>
                        <select id="type-filter" name="type" class="ds-field">
                            <option value="">Tous les types</option>
                            @foreach ($typesLibelles as $valeur => $libelle)
                                <option value="{{ $valeur }}" @selected(request('type') === $valeur)>{{ $libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="ds-label" for="category-filter">Catégorie</label>
                        <select id="category-filter" name="category" class="ds-field">
                            <option value="">Toutes les catégories</option>
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->id }}" @selected(request('category') == $categorie->id)>
                                    {{ $categorie->name }} ({{ $categorie->resources_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="ds-label" for="level-filter">Niveau</label>
                        <select id="level-filter" name="level" class="ds-field">
                            <option value="">Tous les niveaux</option>
                            @foreach ($niveauxLibelles as $valeur => $libelle)
                                <option value="{{ $valeur }}" @selected(request('level') === $valeur)>{{ $libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="ds-label" for="sort-filter">Trier par</label>
                        <select id="sort-filter" name="sort" class="ds-field">
                            <option value="recent" @selected(request('sort', 'recent') === 'recent')>Plus récentes</option>
                            <option value="popular" @selected(request('sort') === 'popular')>Les plus consultées</option>
                            <option value="downloads" @selected(request('sort') === 'downloads')>Les plus téléchargées</option>
                            {{-- « En vedette » ne trie pas : le controleur ajoute aussi un
                                 where('is_featured', true), ce choix restreint donc la liste.
                                 Le libelle le dit, au lieu de laisser croire a un simple tri. --}}
                            <option value="featured" @selected(request('sort') === 'featured')>Uniquement les ressources à la une</option>
                        </select>
                    </div>
                </div>

                <div>
                    <a href="{{ route('bachelier.library.index') }}" class="ds-btn ds-btn-ghost ds-btn-md">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M3 2v6h6"/><path d="M3 13a9 9 0 1 0 3-7.7L3 8"/>
                        </svg>
                        Réinitialiser les filtres
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Ressources a la une. Le controleur calcule $featuredResources depuis
         toujours et la vue ne s en servait pas : la requete etait executee a chaque
         chargement pour rien. Le bloc n apparait que hors filtrage, pour ne pas
         contredire une liste que l utilisateur vient de restreindre. --}}
    @if (!$filtresActifs && $featuredResources->count() > 0)
    <section>
        <h2 style="font-size: var(--text-h3)">À la une</h2>
        <div style="margin-top: var(--space-1-5); display:grid; gap:var(--space-1); grid-template-columns:repeat(auto-fill, minmax(240px, 1fr))">
            @foreach ($featuredResources as $vedette)
                @php $cheminsVedette = $typesIcones[$vedette->type] ?? $typesIcones['defaut']; @endphp
                <a href="{{ route('bachelier.library.show', $vedette) }}"
                   class="ds-card-interactive"
                   style="display:flex; gap:var(--space-1-5); align-items:flex-start; padding:var(--space-2); color:inherit; text-decoration:none">
                    <span style="display:grid; place-items:center; width:40px; height:40px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--accent-surface); color:var(--accent)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            @foreach ($cheminsVedette as $d)<path d="{{ $d }}"/>@endforeach
                        </svg>
                    </span>
                    <span style="min-width:0; flex:1">
                        <span class="line-clamp-2" style="display:block; font-weight:var(--font-semibold); font-size:var(--text-caption)">{{ $vedette->title }}</span>
                        <span class="ds-text-secondary" style="display:block; margin-top:var(--space-0-5); font-size:var(--text-label)">
                            {{ $typesLibelles[$vedette->type] ?? $vedette->type }}
                            &middot; {{ $vedette->views_count }} {{ $vedette->views_count > 1 ? 'vues' : 'vue' }}
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Catalogue --}}
    <div style="display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fill, minmax(260px, 1fr))">
        @forelse ($resources as $resource)
            @php
                $chemins = $typesIcones[$resource->type] ?? $typesIcones['defaut'];
                $typeLibelle = $typesLibelles[$resource->type] ?? $resource->type;
                $vignette = $resource->thumbnail ? Storage::url($resource->thumbnail) : null;
            @endphp
            <article class="ds-card" style="display:flex; flex-direction:column; overflow:hidden">
                {{-- Bandeau de type. Sans vignette, un aplat de role et l icone du type :
                     l ancien degrade etait ecrit en hexadecimal en dur et ne basculait
                     pas en mode sombre. --}}
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
                    <div style="display:flex; flex-wrap:wrap; align-items:center; gap:var(--space-0-5)">
                        <span class="ds-badge ds-badge-neutral">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                @foreach ($chemins as $d)<path d="{{ $d }}"/>@endforeach
                            </svg>
                            {{ $typeLibelle }}
                        </span>
                        @if ($resource->level)
                            <span class="ds-badge ds-badge-neutral">{{ $niveauxLibelles[$resource->level] ?? $resource->level }}</span>
                        @endif
                        @if ($resource->is_featured)
                            <span class="ds-badge ds-badge-accent">À la une</span>
                        @endif
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

                    {{-- Disponibilite : un bachelier doit savoir avant d ouvrir la fiche
                         si la ressource se telecharge, se consulte en ligne, ou ni l un
                         ni l autre. Aucune des trois informations n etait affichee. --}}
                    <p style="display:flex; align-items:center; gap:var(--space-0-5); font-size:var(--text-label); font-weight:var(--font-medium); color:var(--accent)">
                        @if ($resource->file_path)
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/>
                            </svg>
                            Téléchargeable
                        @elseif ($resource->external_url)
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                            </svg>
                            Consultable en ligne
                        @else
                            <span class="ds-text-secondary" style="font-weight:var(--font-regular)">Fiche descriptive</span>
                        @endif
                    </p>

                    <p class="ds-text-secondary numbers" style="margin-top:auto; font-size:var(--text-label)">
                        {{ $resource->views_count }} {{ $resource->views_count > 1 ? 'vues' : 'vue' }}
                        &middot; {{ $resource->downloads_count }} {{ $resource->downloads_count > 1 ? 'téléchargements' : 'téléchargement' }}
                        {{-- published_at est nullable, et le controleur accepte
                             explicitement les fiches sans date. La vue appelait
                             diffForHumans() dessus sans garde : une seule ressource
                             sans date de publication faisait tomber la page entiere. --}}
                        @if ($resource->published_at)
                            &middot; {{ $resource->published_at->locale('fr')->diffForHumans() }}
                        @endif
                    </p>
                </div>
            </article>
        @empty
            <div class="ds-card-flat" style="grid-column:1 / -1; padding: var(--space-6); text-align:center">
                <span class="ds-text-secondary" style="display:inline-flex">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>
                    </svg>
                </span>
                <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">
                    {{ $filtresActifs ? 'Aucune ressource ne correspond' : 'La bibliothèque est encore vide' }}
                </h2>
                <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                    {{ $filtresActifs
                        ? 'Essayez un terme plus général, ou réinitialisez vos filtres.'
                        : "Les premiers documents arrivent bientôt. En attendant, la communauté partage déjà ses conseils." }}
                </p>
                <a href="{{ $filtresActifs ? route('bachelier.library.index') : route('bachelier.forum.index') }}"
                   class="ds-btn ds-btn-primary ds-btn-lg" style="margin-top: var(--space-3)">
                    {{ $filtresActifs ? 'Réinitialiser les filtres' : 'Aller à la communauté' }}
                </a>
            </div>
        @endforelse
    </div>

    @if ($resources->hasPages())
    <div>
        {{ $resources->withQueryString()->links() }}
    </div>
    @endif

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formulaire = document.querySelector('#filter-form');
    if (!formulaire) { return; }

    formulaire.querySelectorAll('select').forEach(function (liste) {
        liste.addEventListener('change', function () { formulaire.submit(); });
    });

    const champRecherche = document.querySelector('#search-filter');
    if (champRecherche) {
        let minuteur;
        champRecherche.addEventListener('input', function () {
            clearTimeout(minuteur);
            minuteur = setTimeout(function () { formulaire.submit(); }, 300);
        });
    }
});
</script>
@endpush
@push('styles')
<style>
    /* CONTRASTE AA, mesure a 360px et non suppose. --accent sur --accent-surface
       mesure 4,31:1 en mode clair, sous le seuil de 4,5:1. L appariement vient de
       theme.css, hors perimetre de ce lot ; la correction de fond est un --accent
       plus sombre ou une --accent-surface plus dense. En attendant, tout TEXTE pose
       sur cette teinte passe en --text-primary : mesure 11,0:1 en clair et 13,5:1
       en sombre. Les icones, elles, gardent --accent : a 4,31:1 elles depassent
       largement le seuil de 3:1 des elements non textuels.
       Selecteur en html[data-ds] .x, soit (0,2,1), pour battre la classe du design
       system, (0,1,0). Aucune regle propre au mode sombre. */
    html[data-ds] .ds-badge-accent { color: var(--text-primary); }
</style>
@endpush
@endsection
