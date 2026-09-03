@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@php
    // $otherParticipant est fourni par InboxController@show depuis toujours, et la
    // vue ne s en servait nulle part : on ouvrait une conversation sans jamais lire
    // le nom de son interlocuteur, ni dans le titre de l onglet, ni a l ecran.
    $nomInterlocuteur = $otherParticipant->bachelier
        ? trim($otherParticipant->bachelier->prenoms . ' ' . $otherParticipant->bachelier->nom)
        : $otherParticipant->email;
    $initialeInterlocuteur = mb_substr($nomInterlocuteur, 0, 1);
    $regionInterlocuteur = $otherParticipant->bachelier?->region;

    $moiId = auth()->id();
    $erreurEnvoi = session('error');
@endphp

@section('title', 'Conversation avec ' . $nomInterlocuteur . ' - Bachelier PEUB')

@section('content')
<div class="ds-container-tight ds-stack-sm" style="padding-block: var(--space-4)">

    {{-- En-tete de page : la vue s ouvrait sur un fil d Ariane generique
         « MESSAGERIE / CONVERSATION », sans aucun h1 et sans dire a qui on parle. --}}
    <header>
        <p class="ds-overline">
            <a href="{{ route('bachelier.inbox.index') }}"
               style="display:inline-flex; align-items:center; gap:var(--space-0-5); min-height:44px; color:inherit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                MESSAGERIE
            </a>
        </p>

        <div style="margin-top: var(--space-1); display:flex; gap:var(--space-1-5); align-items:center">
            <span class="inbox-avatar" aria-hidden="true">{{ $initialeInterlocuteur }}</span>
            <div style="min-width:0">
                <h1 style="font-size: var(--text-h2)">{{ $nomInterlocuteur }}</h1>
                @if ($regionInterlocuteur)
                    <p class="ds-text-secondary" style="display:flex; align-items:center; gap:var(--space-0-5); font-size:var(--text-caption)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0">
                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><path d="M12 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                        </svg>
                        {{ $regionInterlocuteur }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Le sujet est saisi a la creation de la conversation et enregistre par
             startConversation, mais aucune vue ne l affichait. --}}
        @if ($conversation->subject)
            <p class="ds-text-secondary" style="margin-top: var(--space-1); font-size: var(--text-caption)">
                Sujet : {{ $conversation->subject }}
            </p>
        @endif

        <form action="{{ route('bachelier.inbox.archive', $conversation) }}" method="POST" style="margin-top: var(--space-2)">
            @csrf
            <button type="submit" class="ds-btn ds-btn-secondary ds-btn-md"
                    onclick="return confirm('Archiver cette conversation ? Elle disparaîtra de votre messagerie.')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M20 9v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9"/><path d="M2 5a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/><path d="M10 13h4"/>
                </svg>
                Archiver
            </button>
        </form>
    </header>

    {{-- Refus de moderation renvoye par store(). La notification passagere du layout
         disparait en sept secondes ; l alerte reste a l ecran, a cote du champ, avec
         la saisie conservee par old(). --}}
    @if ($erreurEnvoi)
        <div class="ds-alert ds-alert-error" role="alert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0; margin-top:2px">
                <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <div>
                <p style="font-weight:var(--font-semibold)">Votre message n'a pas été envoyé</p>
                <p style="margin-top: var(--space-0-5)">{{ $erreurEnvoi }}</p>
                <p style="margin-top: var(--space-0-5)">Votre texte est conservé ci-dessous. Reformulez le passage concerné, puis renvoyez.</p>
            </div>
        </div>
    @endif

    {{-- Fil de la conversation. Hauteur bornee en dvh plutot que les 500px fixes
         d origine : a 360px, le champ de saisie reste visible sans defiler jusqu au
         bas du fil, quelle que soit la longueur de l echange. --}}
    <section class="ds-card" id="messages-container"
             style="padding: var(--space-2); max-height:min(58dvh, 560px); overflow-y:auto"
             tabindex="0" role="log" aria-label="Messages de la conversation">
        <h2 class="sr-only">Messages</h2>
        <div class="ds-stack-sm">
            @forelse ($messages as $message)
                @php
                    $estMoi = $message->sender_id === $moiId;
                    $nomExpediteur = $estMoi
                        ? 'Vous'
                        : ($message->sender->bachelier
                            ? trim($message->sender->bachelier->prenoms . ' ' . $message->sender->bachelier->nom)
                            : $message->sender->email);
                @endphp
                <article style="display:flex; justify-content:{{ $estMoi ? 'flex-end' : 'flex-start' }}">
                    <div style="min-width:0; max-width:min(85%, 460px); display:flex; flex-direction:column; align-items:{{ $estMoi ? 'flex-end' : 'flex-start' }}">
                        <p class="ds-text-secondary" style="font-size:var(--text-label); font-weight:var(--font-semibold)">{{ $nomExpediteur }}</p>
                        <div class="{{ $estMoi ? 'inbox-bulle inbox-bulle-moi' : 'inbox-bulle' }}">
                            <p style="white-space:pre-wrap">{{ $message->content }}</p>
                        </div>
                        <p class="ds-text-secondary" style="margin-top:var(--space-0-5); display:flex; align-items:center; gap:var(--space-0-5); font-size:var(--text-label)">
                            <time datetime="{{ $message->created_at?->toDateString() }}">{{ $message->created_at?->format('d/m/Y à H:i') }}</time>
                            @if ($estMoi && $message->read_by_recipient)
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="color:var(--success-text)">
                                    <path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/>
                                </svg>
                                <span>Lu</span>
                            @elseif ($estMoi)
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                <span>Envoyé</span>
                            @endif
                        </p>
                    </div>
                </article>
            @empty
                <div style="padding: var(--space-4); text-align:center">
                    <span class="ds-text-secondary" style="display:inline-flex">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>
                        </svg>
                    </span>
                    <h3 style="margin-top: var(--space-1-5)">Cette conversation commence</h3>
                    <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                        Écrivez le premier message à {{ $nomInterlocuteur }} ci-dessous.
                    </p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Champ de saisie --}}
    <section class="ds-card" style="padding: var(--space-2)">
        <h2 class="sr-only">Écrire un message</h2>
        <form action="{{ route('bachelier.inbox.store', $conversation) }}" method="POST" id="reply-form">
            @csrf
            <label class="sr-only" for="contenu-message">Votre message à {{ $nomInterlocuteur }}</label>
            <textarea name="content" id="contenu-message" rows="3" required maxlength="2000"
                      class="ds-field ds-textarea @error('content') ds-field-error @enderror"
                      style="min-height:88px"
                      placeholder="Écrivez votre message...">{{ old('content') }}</textarea>
            @error('content')<p class="ds-error-text">{{ $message }}</p>@enderror

            <div style="margin-top: var(--space-1-5); display:flex; align-items:center; justify-content:space-between; gap:var(--space-1); flex-wrap:wrap">
                <p class="ds-hint" style="margin:0">Entrée pour envoyer, Maj et Entrée pour un retour à la ligne.</p>
                <button type="submit" class="ds-btn ds-btn-primary ds-btn-md">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>
                    </svg>
                    Envoyer
                </button>
            </div>
        </form>
    </section>

</div>

@push('styles')
<style>
    html[data-ds] .inbox-avatar {
        display: grid;
        place-items: center;
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        border-radius: var(--radius-pill);
        background: var(--accent-surface);
        /* --accent sur --accent-surface mesure 4,31:1, sous AA pour du texte. */
        color: var(--text-primary);
        font-size: var(--text-h3);
        font-weight: var(--font-semibold);
        text-transform: uppercase;
    }

    /* Bulle recue : surface secondaire, texte principal.
       Bulle envoyee : aplat d accent, texte pose dessus. Les deux appariements
       sont ceux du design system, ils basculent seuls en mode sombre. */
    html[data-ds] .inbox-bulle {
        margin-top: var(--space-0-5);
        padding: var(--space-1) var(--space-1-5);
        border-radius: var(--radius-card);
        border-end-start-radius: var(--radius-chip);
        background: var(--surface-secondary);
        color: var(--text-primary);
        font-size: var(--text-caption);
    }
    html[data-ds] .inbox-bulle-moi {
        border-end-start-radius: var(--radius-card);
        border-end-end-radius: var(--radius-chip);
        background: var(--accent);
        color: var(--text-on-accent);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fil = document.getElementById('messages-container');
    const formulaire = document.getElementById('reply-form');
    if (!fil || !formulaire) { return; }

    // Ouvrir sur le dernier message.
    fil.scrollTop = fil.scrollHeight;

    const champ = formulaire.querySelector('[name="content"]');
    if (!champ) { return; }

    // Entree envoie, Maj et Entree passe a la ligne. keydown sur un champ texte :
    // aucun ecouteur tactile, aucun preventDefault de defilement.
    champ.addEventListener('keydown', function (evenement) {
        if (evenement.key === 'Enter' && !evenement.shiftKey) {
            evenement.preventDefault();
            if (champ.value.trim()) { formulaire.requestSubmit(); }
        }
    });

    // Le champ reprend la main apres un retour en erreur, sans voler le focus
    // au chargement normal de la page.
    if (champ.value.trim()) {
        champ.focus();
        champ.setSelectionRange(champ.value.length, champ.value.length);
    }
});
</script>
@endpush
@endsection
