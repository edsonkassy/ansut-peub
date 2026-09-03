@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Recherche - Communauté PEUB')

@php
    /*
     * FAILLE CORRIGEE. La vue posait le terme recherche et le contenu des discussions
     * dans du HTML brut :
     *   {!! str_replace(request('q'), '<mark ...>' . request('q') . '</mark>', $thread->title) !!}
     * Ni le terme, saisi par l utilisateur dans l URL, ni le titre et le contenu,
     * ecrits par les membres, n etaient echappes : n importe quelle balise passait.
     * Ici le texte est echappe morceau par morceau, et seules les balises <mark>
     * que cette fonction produit elle-meme entrent dans la sortie.
     *
     * Effet de bord au passage : str_replace etait sensible a la casse, une recherche
     * « bourse » ne surlignait pas « Bourse ». preg_split avec le drapeau i le fait.
     *
     * Le surlignage force --text-primary : herite de --text-secondary, le terme
     * surligne dans l extrait mesurait 3,99:1 sur --accent-surface, sous AA.
     */
    $souligner = function (?string $texte, string $terme): string {
        $texte = (string) $texte;
        if ($terme === '') {
            return e($texte);
        }

        $morceaux = preg_split('/(' . preg_quote($terme, '/') . ')/iu', $texte, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($morceaux === false) {
            return e($texte);
        }

        $sortie = '';
        foreach ($morceaux as $rang => $morceau) {
            $sortie .= $rang % 2 === 1
                ? '<mark style="background:var(--accent-surface); color:var(--text-primary); padding:0 2px; border-radius:var(--radius-chip)">' . e($morceau) . '</mark>'
                : e($morceau);
        }

        return $sortie;
    };

    $terme = (string) $query;
@endphp

@section('content')
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    {{-- En-tete de page : la vue s ouvrait sur un fil d Ariane, sans aucun h1. --}}
    <header>
        <p class="ds-overline">
            <a href="{{ route('bachelier.forum.index') }}"
               style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; color:inherit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                COMMUNAUTÉ
            </a>
        </p>
        <h1 style="margin-top: var(--space-1)">Recherche</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            {{ $threads->total() }}
            {{ $threads->total() > 1 ? 'discussions trouvées' : 'discussion trouvée' }}
            pour « {{ $terme }} ».
        </p>
    </header>

    <form method="GET" action="{{ route('bachelier.forum.search') }}" class="ds-card"
          style="padding: var(--space-2); display:grid; gap:var(--space-1-5); grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); align-items:end">
        <div style="grid-column:1 / -1">
            <label class="ds-label" for="q">Rechercher une discussion</label>
            <input type="search" name="q" id="q" required
                   class="ds-field"
                   placeholder="Titre ou contenu d'une discussion"
                   value="{{ $terme }}">
        </div>
        <div>
            <label class="ds-label" for="category">Catégorie</label>
            <select name="category" id="category" class="ds-field">
                <option value="">Toutes les catégories</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->id }}" @selected(request('category') == $categorie->id)>{{ $categorie->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="ds-btn ds-btn-secondary ds-btn-md ds-btn-block">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M11 3a8 8 0 1 0 0 16 8 8 0 0 0 0-16"/><path d="m21 21-4.3-4.3"/>
                </svg>
                Rechercher
            </button>
        </div>
    </form>

    {{-- Le bloc « Rechercher dans les discussions », affiche quand q etait vide, a
         ete retire : ForumController@search redirige vers l accueil de la communaute
         des que q est vide, cette branche ne pouvait donc jamais s afficher. --}}
    <div class="ds-stack-sm">
        @forelse ($threads as $thread)
            <article class="ds-card" style="padding: var(--space-2)">
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
                        {!! $souligner($thread->title, $terme) !!}
                    </a>
                </h2>

                <p class="ds-text-secondary line-clamp-3" style="margin-top: var(--space-0-5); font-size: var(--text-caption)">
                    {!! $souligner(Str::limit($thread->content, 200), $terme) !!}
                </p>

                @if ($thread->tags && count($thread->tags) > 0)
                    <div style="margin-top: var(--space-1); display:flex; flex-wrap:wrap; gap:var(--space-0-5)">
                        @foreach ($thread->tags as $tag)
                            <span class="ds-badge ds-badge-neutral">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <p class="ds-text-secondary" style="margin-top: var(--space-1); font-size: var(--text-label)">
                    Par {{ $thread->user?->name ?? 'Membre retiré' }}
                    &middot; {{ $thread->created_at?->locale('fr')->diffForHumans() }}
                </p>

                {{-- last_post est un accessor : une requete par ligne affichee.
                     last_activity_at est deja charge sur la discussion. --}}
                <p class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size: var(--text-label)">
                    {{ $thread->posts_count }} {{ $thread->posts_count > 1 ? 'réponses' : 'réponse' }}
                    &middot; {{ $thread->views_count }} {{ $thread->views_count > 1 ? 'vues' : 'vue' }}
                    @if ($thread->last_activity_at)
                        &middot; activité {{ $thread->last_activity_at->locale('fr')->diffForHumans() }}
                    @endif
                </p>
            </article>
        @empty
            <div class="ds-card-flat" style="padding: var(--space-6); text-align:center">
                <span class="ds-text-secondary" style="display:inline-flex">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M11 3a8 8 0 1 0 0 16 8 8 0 0 0 0-16"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                </span>
                <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">Aucun résultat pour « {{ $terme }} »</h2>
                <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                    Essayez un terme plus général, vérifiez l'orthographe,
                    ou élargissez la recherche à toutes les catégories.
                </p>
                <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                    Personne n'a encore écrit sur ce sujet ? C'est le bon moment pour lancer la discussion.
                </p>
                <a href="{{ route('bachelier.forum.index') }}" class="ds-btn ds-btn-primary ds-btn-lg" style="margin-top: var(--space-3)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M5 12h14"/><path d="M12 5v14"/>
                    </svg>
                    Lancer une discussion
                </a>
            </div>
        @endforelse
    </div>

    @if ($threads->hasPages())
    <div>
        {{ $threads->appends(request()->query())->links() }}
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
</style>
@endpush
@endsection
