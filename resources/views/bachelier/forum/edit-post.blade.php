@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Modifier mon message - Communauté PEUB')

@section('content')
<div class="ds-container-tight ds-stack" style="padding-block: var(--space-4)">

    {{-- En-tete de page : la vue s ouvrait sur un fil d Ariane, sans aucun h1. --}}
    <header>
        <p class="ds-overline">
            <a href="{{ route('bachelier.forum.thread', $post->thread) }}"
               style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; color:inherit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                RETOUR À LA DISCUSSION
            </a>
        </p>
        <h1 style="margin-top: var(--space-1)">Modifier mon message</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            Publié {{ $post->created_at?->locale('fr')->diffForHumans() }}@if($post->is_edited), modifié {{ $post->edited_at->locale('fr')->diffForHumans() }}@endif.
            La modification est visible par toute la communauté.
        </p>
    </header>

    {{-- Contexte : dans quelle discussion ce message a-t-il ete ecrit. Le bloc
         « Apercu du thread », qui repetait plus bas le contenu de la discussion et
         ses compteurs, est fusionne ici : deux cartes de contexte encadraient le
         formulaire, il n en reste qu une, avant le champ. --}}
    <section class="ds-card-flat" style="padding: var(--space-2)">
        <p class="ds-overline">Discussion</p>
        <h2 style="margin-top: var(--space-0-5); font-size: var(--text-h3)">
            <a href="{{ route('bachelier.forum.thread', $post->thread) }}" style="color:inherit; text-decoration:none">
                {{ $post->thread->title }}
            </a>
        </h2>

        <div style="margin-top: var(--space-1); display:flex; flex-wrap:wrap; gap:var(--space-0-5)">
            @if ($post->thread->category)
                <span class="ds-badge ds-badge-neutral">{{ $post->thread->category->name }}</span>
            @endif
            @if ($post->thread->is_locked)
                <span class="ds-badge ds-badge-neutral">Fermée aux réponses</span>
            @endif
        </div>

        <p class="ds-text-secondary line-clamp-3" style="margin-top: var(--space-1); font-size: var(--text-caption)">
            {{ $post->thread->content }}
        </p>

        <p class="ds-text-secondary" style="margin-top: var(--space-1); font-size: var(--text-label)">
            Ouverte par {{ $post->thread->user?->name ?? 'Membre retiré' }}
            &middot; {{ $post->thread->posts_count }} {{ $post->thread->posts_count > 1 ? 'réponses' : 'réponse' }}
            &middot; {{ $post->thread->views_count }} {{ $post->thread->views_count > 1 ? 'vues' : 'vue' }}
        </p>
    </section>

    {{-- Message auquel celui-ci repond, quand il y en a un. --}}
    @if ($post->parent)
    <section class="ds-card" style="padding: var(--space-2)">
        <p class="ds-overline">Vous répondiez à</p>
        <div style="margin-top: var(--space-1); display:flex; gap:var(--space-1); align-items:center">
            <span class="forum-avatar" aria-hidden="true">{{ mb_substr($post->parent->user?->name ?? '?', 0, 1) }}</span>
            <div style="min-width:0">
                <p style="font-size:var(--text-caption); font-weight:var(--font-semibold)">{{ $post->parent->user?->name ?? 'Membre retiré' }}</p>
                <p class="ds-text-secondary" style="font-size:var(--text-label)">{{ $post->parent->created_at?->locale('fr')->diffForHumans() }}</p>
            </div>
        </div>
        <p class="ds-text-secondary line-clamp-3" style="margin-top: var(--space-1); font-size:var(--text-caption); white-space:pre-wrap">{{ $post->parent->content }}</p>
    </section>
    @endif

    <section class="ds-card" style="padding: var(--space-3)">
        <form action="{{ route('bachelier.forum.update-post', $post) }}" method="POST" class="ds-stack-sm">
            @csrf
            @method('PUT')

            <div>
                <label class="ds-label" for="content">Votre message</label>
                <textarea name="content" id="content" rows="10" required minlength="5"
                          class="ds-field ds-textarea @error('content') ds-field-error @enderror"
                          aria-describedby="aide-contenu{{ $errors->has('content') ? ' erreur-contenu' : '' }}">{{ old('content', $post->content) }}</textarea>
                <p class="ds-hint" id="aide-contenu">5 caractères au minimum.</p>
                @error('content')
                    <p class="ds-error-text" id="erreur-contenu">{{ $message }}</p>
                @enderror
            </div>

            <div style="display:flex; justify-content:flex-end; gap:var(--space-1); flex-wrap:wrap; padding-top:var(--space-2); border-top:1px solid var(--border-default)">
                <a href="{{ route('bachelier.forum.thread', $post->thread) }}" class="ds-btn ds-btn-secondary ds-btn-md">
                    Annuler
                </a>
                <button type="submit" class="ds-btn ds-btn-primary ds-btn-md">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/>
                    </svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </section>

    {{-- AVERTISSEMENT DE REVUE, hors perimetre de ce lot.
         ForumController@updatePost, en cas de refus de moderation, fait
         redirect()->route('bachelier.forum.thread', ...)->with('error', ...) :
         ni ->withInput(), ni retour sur cette page. Le texte reecrit est donc
         perdu, contrairement a storeThread et storePost qui font back()->withInput().
         Aucun affichage cote vue ne peut rattraper cela : la correction est un
         ->withInput() dans le controleur, interdit ici. Signale plutot que fait. --}}
    <section class="ds-card-flat" style="padding: var(--space-2)">
        <h2 class="ds-overline">Avant d'enregistrer</h2>
        <ul class="ds-text-secondary" style="margin-top: var(--space-1); padding-left: var(--space-3); font-size: var(--text-caption)">
            <li>Gardez le message respectueux et utile à celles et ceux qui liront après vous.</li>
            <li>Relisez avant d'enregistrer : la modification remplace définitivement l'ancien texte.</li>
            <li>Pensez à recopier votre texte ailleurs si vous le retouchez beaucoup.</li>
        </ul>
    </section>

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

    html[data-ds] .forum-avatar {
        display: grid;
        place-items: center;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        border-radius: var(--radius-pill);
        background: var(--surface-secondary);
        /* --text-secondary sur --surface-secondary mesure 4,48:1, sous AA. */
        color: var(--text-primary);
        font-size: var(--text-label);
        font-weight: var(--font-semibold);
        text-transform: uppercase;
    }
</style>
@endpush
@endsection
