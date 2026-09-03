@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', $category->name . ' - Communauté PEUB')

@php
    // Une seule requete pour tous les favoris de l utilisateur, plutot qu un
    // exists() par ligne affichee.
    $favoris = auth()->check()
        ? auth()->user()->favoriteThreads()->pluck('forum_thread_id')->all()
        : [];
@endphp

@section('content')
<div class="ds-container ds-stack" style="padding-block: var(--space-4)">

    {{-- En-tete de page : la vue s ouvrait sur un fil d Ariane, sans aucun h1, et
         n affichait ni la description de la categorie ni son intitule en clair. --}}
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
        <h1 style="margin-top: var(--space-1)">{{ $category->name }}</h1>
        @if ($category->description)
            <p class="ds-text-secondary" style="margin-top: var(--space-1)">{{ $category->description }}</p>
        @endif
        <p class="ds-text-secondary" style="margin-top: var(--space-1); font-size: var(--text-caption)">
            {{ $threads->total() }} {{ $threads->total() > 1 ? 'discussions' : 'discussion' }}
        </p>
    </header>

    <div class="ds-stack-sm">
        @forelse ($threads as $thread)
            @php $enFavori = in_array($thread->id, $favoris, true); @endphp
            <article class="ds-card" style="padding: var(--space-2)">
                <div style="display:flex; gap:var(--space-1); align-items:flex-start">
                    <div style="min-width:0; flex:1">
                        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:var(--space-0-5)">
                            @if ($thread->is_pinned)
                                <span class="ds-badge ds-badge-accent">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                        <path d="M12 17v5"/><path d="M9 10.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24V16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V7a1 1 0 0 1 1-1 2 2 0 0 0 0-4H8a2 2 0 0 0 0 4 1 1 0 0 1 1 1z"/>
                                    </svg>
                                    Épinglée
                                </span>
                            @endif
                            @if ($thread->is_featured)
                                <span class="ds-badge ds-badge-accent">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                        <path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/>
                                    </svg>
                                    À la une
                                </span>
                            @endif
                            @if ($thread->is_locked)
                                <span class="ds-badge ds-badge-neutral">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                        <path d="M5 11h14a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2z"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    Fermée aux réponses
                                </span>
                            @endif
                        </div>

                        <h2 style="margin-top: var(--space-1); font-size: var(--text-body)">
                            <a href="{{ route('bachelier.forum.thread', $thread) }}" style="color:inherit; text-decoration:none">
                                {{ $thread->title }}
                            </a>
                        </h2>

                        <p class="ds-text-secondary line-clamp-2" style="margin-top: var(--space-0-5); font-size: var(--text-caption)">
                            {{ Str::limit($thread->content, 150) }}
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

                        {{-- last_post est un accessor : une requete par ligne. last_activity_at
                             est deja charge sur la discussion et porte la meme information. --}}
                        <p class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size: var(--text-label)">
                            {{ $thread->posts_count }} {{ $thread->posts_count > 1 ? 'réponses' : 'réponse' }}
                            &middot; {{ $thread->views_count }} {{ $thread->views_count > 1 ? 'vues' : 'vue' }}
                            @if ($thread->last_activity_at)
                                &middot; activité {{ $thread->last_activity_at->locale('fr')->diffForHumans() }}
                            @endif
                        </p>
                    </div>

                    @auth
                    <button type="button" class="forum-favori"
                            data-thread-id="{{ $thread->id }}"
                            aria-pressed="{{ $enFavori ? 'true' : 'false' }}">
                        <span class="sr-only">Mettre cette discussion en favori</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="{{ $enFavori ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>
                        </svg>
                    </button>
                    @endauth
                </div>
            </article>
        @empty
            {{-- Etat vide qui invite a publier plutot que de constater l absence.
                 Le bouton renvoie a l accueil de la communaute : c est de la que se
                 lance une discussion, la page de creation n ayant pas de route. --}}
            <div class="ds-card-flat" style="padding: var(--space-6); text-align:center">
                <span class="ds-text-secondary" style="display:inline-flex">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>
                    </svg>
                </span>
                <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">Cette catégorie est encore vide</h2>
                <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                    Personne n'a encore écrit dans « {{ $category->name }} ». Ouvrez le sujet,
                    d'autres bacheliers se poseront la même question.
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
        {{ $threads->links() }}
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
        color: var(--text-secondary);
        cursor: pointer;
        border-radius: var(--radius-pill);
    }
    html[data-ds] .forum-favori:hover { background: var(--surface-hover); }
    html[data-ds] .forum-favori[aria-pressed="true"] { color: var(--accent); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.forum-favori').forEach(function (bouton) {
        bouton.addEventListener('click', function () {
            fetch('/bachelier/forum/' + bouton.dataset.threadId + '/favorite', {
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
                const icone = bouton.querySelector('svg');
                if (icone) { icone.setAttribute('fill', donnees.isFavorited ? 'currentColor' : 'none'); }
            })
            .catch(function (erreur) { console.error('Erreur:', erreur); });
        });
    });
});
</script>
@endpush
@endsection
