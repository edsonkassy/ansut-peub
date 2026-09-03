@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', $thread->title . ' - Communauté PEUB')

@php
    // Reactions proposees. La table de reference du modele (ForumReaction::getReactionTypes)
    // n est pas utilisee ici : sa cle « color » transporte des classes de palette
    // Tailwind, qui n ont pas leur place dans une vue migree. Les citer meme en
    // commentaire suffirait d ailleurs a les faire generer dans le bundle, le
    // scanner de Tailwind lisant les fichiers Blade en entier.
    // Les valeurs envoyees au controleur restent identiques : like, love, wow, angry.
    $reactions = [
        'like'  => ['J\'aime',   ['M7 10v12', 'M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88z']],
        'love'  => ['J\'adore',  ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z']],
        'wow'   => ['Bravo',     ['m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z']],
        'angry' => ['Pas cool',  ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'M16 16s-1.5-2-4-2-4 2-4 2', 'M9 9h.01', 'M15 9h.01']],
    ];

    $estEnFavori = auth()->check()
        && auth()->user()->favoriteThreads()->where('forum_thread_id', $thread->id)->exists();

    // Les formulaires de reponse partagent tous le champ « content ». Sans ce
    // reperage, un retour en erreur reinjecterait la meme saisie dans tous les
    // champs de la page. old('parent_id') identifie celui qui a ete soumis :
    // null pour le formulaire principal, l identifiant du message pour une reponse.
    $formulaireEnErreur = old('parent_id');
    $erreurPublication = session('error');
@endphp

@section('content')
<div class="ds-container-tight ds-stack" style="padding-block: var(--space-4)">

    {{-- En-tete de page. La vue n affichait NULLE PART le titre de la discussion :
         on ouvrait un fil sans jamais lire son sujet, seulement un fil d Ariane
         generique « COMMUNAUTE / CATEGORIE / DISCUSSION ». --}}
    <header>
        <p class="ds-overline">
            @if ($thread->category)
                <a href="{{ route('bachelier.forum.category', $thread->category) }}"
                   style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; color:inherit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    {{ mb_strtoupper($thread->category->name) }}
                </a>
            @else
                <a href="{{ route('bachelier.forum.index') }}" style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; color:inherit">COMMUNAUTÉ</a>
            @endif
        </p>

        <h1 style="margin-top: var(--space-1)">{{ $thread->title }}</h1>

        @if ($thread->is_pinned || $thread->is_featured || $thread->is_locked)
            <div style="margin-top: var(--space-1-5); display:flex; flex-wrap:wrap; gap:var(--space-0-5)">
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
        @endif

        <p class="ds-text-secondary" style="margin-top: var(--space-1-5); font-size: var(--text-caption)">
            {{ $thread->posts_count }} {{ $thread->posts_count > 1 ? 'réponses' : 'réponse' }}
            &middot; {{ $thread->views_count }} {{ $thread->views_count > 1 ? 'vues' : 'vue' }}
        </p>

        @auth
        {{-- Bouton de mise en favori. La fonction toggleFavorite() existait dans cette
             vue depuis l origine, elle cherchait un element #favorite-btn qui n a jamais
             ete pose : aucun bouton ne l appelait. La page « Mes favoris » ne pouvait donc
             se remplir que depuis l accueil. La route existait deja, elle est cablee ici. --}}
        <button type="button" id="favori-discussion"
                class="ds-btn ds-btn-secondary ds-btn-md"
                style="margin-top: var(--space-2)"
                data-thread-id="{{ $thread->id }}"
                aria-pressed="{{ $estEnFavori ? 'true' : 'false' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $estEnFavori ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/>
            </svg>
            <span data-libelle-favori>{{ $estEnFavori ? 'En favori' : 'Ajouter aux favoris' }}</span>
        </button>
        @endauth
    </header>

    {{-- Message d ouverture --}}
    <article class="ds-card" style="padding: var(--space-3)">
        <div style="display:flex; gap:var(--space-1-5); align-items:center">
            <span class="forum-avatar forum-avatar-auteur" aria-hidden="true">{{ mb_substr($thread->user?->name ?? '?', 0, 1) }}</span>
            <div style="min-width:0">
                <p style="font-weight:var(--font-semibold)">{{ $thread->user?->name ?? 'Membre retiré' }}</p>
                <p class="ds-text-secondary" style="font-size:var(--text-label)">
                    Auteur de la discussion
                    &middot; <time datetime="{{ $thread->created_at?->toDateString() }}">{{ $thread->created_at?->format('d/m/Y à H:i') }}</time>
                </p>
            </div>
        </div>
        <p style="margin-top: var(--space-2); white-space:pre-wrap">{{ $thread->content }}</p>
    </article>

    @if ($thread->is_locked)
        {{-- L etat verrouille n etait signale nulle part : les formulaires
             disparaissaient sans un mot d explication. --}}
        <div class="ds-alert ds-alert-info" role="status">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0; margin-top:2px">
                <path d="M5 11h14a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2z"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <p>Cette discussion est fermée aux nouvelles réponses. Vous pouvez toujours la lire et la mettre en favori.</p>
        </div>
    @endif

    {{-- Refus de moderation ou erreur de publication renvoyes par storePost. La
         notification passagere du layout disparait en sept secondes ; l alerte reste
         a l ecran, a cote du formulaire, avec la saisie conservee par old(). --}}
    @if ($erreurPublication)
        <div class="ds-alert ds-alert-error" role="alert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0; margin-top:2px">
                <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <div>
                <p style="font-weight:var(--font-semibold)">Votre message n'a pas été publié</p>
                <p style="margin-top: var(--space-0-5)">{{ $erreurPublication }}</p>
                <p style="margin-top: var(--space-0-5)">Votre texte est conservé plus bas. Reformulez le passage concerné, puis publiez à nouveau.</p>
            </div>
        </div>
    @endif

    <section class="ds-stack-sm">
        <h2 style="font-size: var(--text-h3)">
            {{ $thread->posts_count > 1 ? 'Réponses' : 'Réponse' }}
        </h2>

        @forelse ($posts as $post)
            @php $reactionActive = $post->user_reaction?->type; @endphp
            <article class="ds-card" id="post-{{ $post->id }}" style="padding: var(--space-3)">

                <div style="display:flex; gap:var(--space-1-5); align-items:flex-start; flex-wrap:wrap">
                    <span class="forum-avatar" aria-hidden="true">{{ mb_substr($post->user?->name ?? '?', 0, 1) }}</span>
                    <div style="min-width:0; flex:1">
                        <p style="font-weight:var(--font-semibold)">{{ $post->user?->name ?? 'Membre retiré' }}</p>
                        <p class="ds-text-secondary" style="font-size:var(--text-label)">
                            <time datetime="{{ $post->created_at?->toDateString() }}">{{ $post->created_at?->format('d/m/Y à H:i') }}</time>
                            @if ($post->is_edited)
                                &middot; modifié {{ $post->edited_at->locale('fr')->diffForHumans() }}
                            @endif
                        </p>
                    </div>

                    @if ($post->user_id === auth()->id())
                    <div style="display:flex; gap:var(--space-0-5); flex-shrink:0">
                        <a href="{{ route('bachelier.forum.edit-post', $post) }}" class="forum-action">
                            <span class="sr-only">Modifier mon message</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4z"/>
                            </svg>
                        </a>
                        <form action="{{ route('bachelier.forum.delete-post', $post) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="forum-action forum-action-danger"
                                    onclick="return confirm('Supprimer définitivement ce message ? Cette action est irréversible.')">
                                <span class="sr-only">Supprimer mon message</span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <p style="margin-top: var(--space-2); white-space:pre-wrap">{{ $post->content }}</p>

                {{-- Reactions --}}
                <div style="margin-top: var(--space-2); display:flex; flex-wrap:wrap; align-items:center; gap:var(--space-0-5)"
                     data-reactions="{{ $post->id }}">
                    @foreach ($reactions as $type => [$libelle, $chemins])
                        @php $compte = $post->reaction_counts[$type] ?? 0; @endphp
                        <button type="button" class="forum-reaction"
                                data-reaction="{{ $type }}"
                                data-post-id="{{ $post->id }}"
                                aria-pressed="{{ $reactionActive === $type ? 'true' : 'false' }}">
                            <span class="sr-only">{{ $libelle }}</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                @foreach ($chemins as $d)<path d="{{ $d }}"/>@endforeach
                            </svg>
                            <span class="forum-reaction-compte numbers" aria-hidden="true">{{ $compte > 0 ? $compte : '' }}</span>
                        </button>
                    @endforeach
                </div>

                {{-- Reponses imbriquees --}}
                @if ($post->replies->count() > 0)
                <div style="margin-top: var(--space-2); padding-left: var(--space-2); border-left:2px solid var(--border-default); display:grid; gap:var(--space-1-5)">
                    @foreach ($post->replies as $reply)
                    <div class="ds-panel" style="padding: var(--space-2)">
                        <div style="display:flex; gap:var(--space-1); align-items:center">
                            <span class="forum-avatar forum-avatar-petit" aria-hidden="true">{{ mb_substr($reply->user?->name ?? '?', 0, 1) }}</span>
                            <div style="min-width:0">
                                <p style="font-size:var(--text-caption); font-weight:var(--font-semibold)">{{ $reply->user?->name ?? 'Membre retiré' }}</p>
                                <p class="ds-text-secondary" style="font-size:var(--text-label)">
                                    <time datetime="{{ $reply->created_at?->toDateString() }}">{{ $reply->created_at?->format('d/m/Y à H:i') }}</time>
                                    @if ($reply->is_edited)
                                        &middot; modifié {{ $reply->edited_at->locale('fr')->diffForHumans() }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <p style="margin-top: var(--space-1); font-size:var(--text-caption); white-space:pre-wrap">{{ $reply->content }}</p>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Formulaire de reponse a ce message. Soumission classique, sans
                     JavaScript : les erreurs de validation et le refus de moderation
                     reviennent alors par le canal Laravel, avec la saisie conservee,
                     la ou l ancien appel fetch les envoyait dans un alert(). --}}
                @if (!$thread->is_locked)
                <div x-data="{ ouvert: {{ (string) $formulaireEnErreur === (string) $post->id ? 'true' : 'false' }} }" style="margin-top: var(--space-2)">
                    <button type="button" @click="ouvert = !ouvert"
                            :aria-expanded="ouvert ? 'true' : 'false'"
                            aria-controls="reponse-{{ $post->id }}"
                            class="ds-btn ds-btn-ghost ds-btn-md">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="m15 10 5 5-5 5"/><path d="M4 4v7a4 4 0 0 0 4 4h12"/>
                        </svg>
                        Répondre à {{ $post->user?->name ?? 'ce message' }}
                    </button>

                    <form id="reponse-{{ $post->id }}" x-show="ouvert"
                          action="{{ route('bachelier.forum.store-post', $thread) }}" method="POST"
                          style="margin-top: var(--space-1-5){{ (string) $formulaireEnErreur === (string) $post->id ? '' : '; display:none' }}">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $post->id }}">
                        <label class="sr-only" for="contenu-reponse-{{ $post->id }}">Votre réponse</label>
                        <textarea name="content" id="contenu-reponse-{{ $post->id }}" rows="4" required minlength="5"
                                  class="ds-field ds-textarea @if((string) $formulaireEnErreur === (string) $post->id) @error('content') ds-field-error @enderror @endif"
                                  style="min-height:100px"
                                  placeholder="Votre réponse...">{{ (string) $formulaireEnErreur === (string) $post->id ? old('content') : '' }}</textarea>
                        @if ((string) $formulaireEnErreur === (string) $post->id)
                            @error('content')<p class="ds-error-text">{{ $message }}</p>@enderror
                        @endif
                        <p class="ds-hint">5 caractères au minimum.</p>
                        <div style="margin-top: var(--space-1); display:flex; gap:var(--space-1); flex-wrap:wrap">
                            <button type="submit" class="ds-btn ds-btn-primary ds-btn-md">Publier ma réponse</button>
                            <button type="button" @click="ouvert = false" class="ds-btn ds-btn-secondary ds-btn-md">Annuler</button>
                        </div>
                    </form>
                </div>
                @endif
            </article>
        @empty
            <div class="ds-card-flat" style="padding: var(--space-6); text-align:center">
                <span class="ds-text-secondary" style="display:inline-flex">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>
                    </svg>
                </span>
                <h3 style="margin-top: var(--space-2)">Aucune réponse pour l'instant</h3>
                <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                    @if ($thread->is_locked)
                        Cette discussion s'est terminée sans réponse.
                    @else
                        Vous avez un avis, une expérience, une piste ? Écrivez la première réponse.
                    @endif
                </p>
            </div>
        @endforelse
    </section>

    @if ($posts->hasPages())
    <div>
        {{ $posts->links() }}
    </div>
    @endif

    {{-- Formulaire de reponse principal --}}
    @if (!$thread->is_locked)
    <section class="ds-card" style="padding: var(--space-3)">
        <h2 style="font-size: var(--text-h3)">Répondre à cette discussion</h2>
        <form action="{{ route('bachelier.forum.store-post', $thread) }}" method="POST" style="margin-top: var(--space-2)">
            @csrf
            <label class="ds-label" for="contenu-reponse-principale">Votre réponse</label>
            <textarea name="content" id="contenu-reponse-principale" rows="6" required minlength="5"
                      class="ds-field ds-textarea @if(!$formulaireEnErreur) @error('content') ds-field-error @enderror @endif"
                      placeholder="Partagez votre réponse, votre expérience ou une piste utile...">{{ $formulaireEnErreur ? '' : old('content') }}</textarea>
            @if (!$formulaireEnErreur)
                @error('content')<p class="ds-error-text">{{ $message }}</p>@enderror
            @endif
            <p class="ds-hint">5 caractères au minimum. Votre réponse est visible par toute la communauté.</p>
            <div style="margin-top: var(--space-2); display:flex; justify-content:flex-end">
                <button type="submit" class="ds-btn ds-btn-primary ds-btn-md">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>
                    </svg>
                    Publier ma réponse
                </button>
            </div>
        </form>
    </section>
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

    /* Meme cause : la bulle de reponse imbriquee garde --surface-secondary, qui
       porte la hierarchie visuelle de l imbrication, donc son texte secondaire
       (la date) remonte lui aussi en --text-primary. */
    html[data-ds] .ds-panel .ds-text-secondary { color: var(--text-primary); }

    /* Pastille d initiale. Roles uniquement : le contraste bascule seul en sombre. */
    html[data-ds] .forum-avatar {
        display: grid;
        place-items: center;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        border-radius: var(--radius-pill);
        background: var(--surface-secondary);
        /* --text-secondary sur --surface-secondary mesure 4,48:1, sous AA. */
        color: var(--text-primary);
        font-weight: var(--font-semibold);
        text-transform: uppercase;
    }
    html[data-ds] .forum-avatar-auteur {
        background: var(--accent-surface);
        color: var(--accent);
    }
    html[data-ds] .forum-avatar-petit {
        width: 32px;
        height: 32px;
        font-size: var(--text-label);
    }

    /* Actions sur son propre message : cible de 44px, jamais un lien de 16px. */
    html[data-ds] .forum-action {
        display: grid;
        place-items: center;
        width: 44px;
        height: 44px;
        border: 0;
        background: none;
        color: var(--text-secondary);
        cursor: pointer;
        border-radius: var(--radius-chip);
    }
    html[data-ds] .forum-action:hover { background: var(--surface-hover); color: var(--text-primary); }
    html[data-ds] .forum-action-danger:hover { color: var(--error-text); }

    /* Reaction : l etat passe par aria-pressed, et non par une reecriture de
       className comme le faisait l ancien script. */
    html[data-ds] .forum-reaction {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-0-5);
        min-width: 44px;
        height: 44px;
        padding: 0 var(--space-1);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-pill);
        background: var(--surface-raised);
        color: var(--text-secondary);
        font-size: var(--text-label);
        font-weight: var(--font-semibold);
        cursor: pointer;
    }
    html[data-ds] .forum-reaction:hover { background: var(--surface-hover); }
    html[data-ds] .forum-reaction[aria-pressed="true"] {
        background: var(--accent-surface);
        border-color: var(--accent-border);
        color: var(--accent);
    }
    html[data-ds] .forum-reaction-compte:empty { display: none; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const jeton = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ---- Reactions ----
    document.querySelectorAll('.forum-reaction').forEach(function (bouton) {
        bouton.addEventListener('click', function () {
            const identifiant = bouton.dataset.postId;
            const type = bouton.dataset.reaction;

            fetch('{{ route('bachelier.forum.toggle-reaction') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jeton,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    reactable_id: identifiant,
                    reactable_type: 'App\\Models\\ForumPost',
                    type: type
                })
            })
            .then(function (reponse) { return reponse.json(); })
            .then(function (donnees) {
                if (!donnees.success) { return; }
                const groupe = document.querySelector('[data-reactions="' + identifiant + '"]');
                if (!groupe) { return; }

                groupe.querySelectorAll('.forum-reaction').forEach(function (autre) {
                    const sonType = autre.dataset.reaction;
                    const compte = donnees.reactionCounts[sonType] || 0;
                    autre.querySelector('.forum-reaction-compte').textContent = compte > 0 ? compte : '';
                    autre.setAttribute('aria-pressed', donnees.reactionType === sonType ? 'true' : 'false');
                });
            })
            .catch(function (erreur) { console.error('Erreur:', erreur); });
        });
    });

    // ---- Mise en favori de la discussion ----
    const boutonFavori = document.getElementById('favori-discussion');
    if (boutonFavori) {
        boutonFavori.addEventListener('click', function () {
            fetch('/bachelier/forum/' + boutonFavori.dataset.threadId + '/favorite', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jeton,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(function (reponse) { return reponse.json(); })
            .then(function (donnees) {
                if (!donnees.success) { return; }
                boutonFavori.setAttribute('aria-pressed', donnees.isFavorited ? 'true' : 'false');
                boutonFavori.querySelector('svg').setAttribute('fill', donnees.isFavorited ? 'currentColor' : 'none');
                boutonFavori.querySelector('[data-libelle-favori]').textContent =
                    donnees.isFavorited ? 'En favori' : 'Ajouter aux favoris';
            })
            .catch(function (erreur) { console.error('Erreur:', erreur); });
        });
    }
});
</script>
@endpush
@endsection
