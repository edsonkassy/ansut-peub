@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Messagerie - Bachelier PEUB')

@php
    $moiId = auth()->id();

    // Donnees de chaque conversation, preparees cote serveur pour que le rail
    // n ait rien a deviner. other_participant, unread_count et latest_message sont
    // poses par InboxController@index.
    $lignes = $conversations->map(function ($conversation) {
        $autre = $conversation->other_participant;
        $nom = $autre->bachelier
            ? trim($autre->bachelier->prenoms . ' ' . $autre->bachelier->nom)
            : $autre->email;

        return [
            'id' => $conversation->id,
            'nom' => $nom,
            'initiale' => mb_substr($nom, 0, 1),
            'region' => $autre->bachelier?->region,
            'sujet' => $conversation->subject,
            'apercu' => $conversation->latest_message
                ? Str::limit($conversation->latest_message->content, 70)
                : null,
            'non_lus' => (int) $conversation->unread_count,
            'date' => $conversation->last_message_at?->locale('fr')->diffForHumans(),
        ];
    });

    $totalNonLus = $lignes->sum('non_lus');
@endphp

@section('content')
<div x-data="{
        large: false,
        vue: 'liste',
        active: null,
        messages: [],
        chargement: false,
        erreur: '',
        envoi: false,
        suppressionMessage: null,
        suppressionConversation: false,

        init() {
            const requete = window.matchMedia('(min-width: 1024px)');
            this.large = requete.matches;
            requete.addEventListener('change', (evenement) => { this.large = evenement.matches; });
            // Sur grand ecran le volet de droite serait vide : on ouvre la premiere
            // conversation. Sur mobile la liste reste le premier ecran.
            if (this.large && this.$refs.premiere) {
                this.ouvrir(
                    this.$refs.premiere.dataset.id,
                    this.$refs.premiere.dataset.nom,
                    this.$refs.premiere.dataset.region || ''
                );
            }
        },

        async ouvrir(identifiant, nom, region) {
            this.active = { id: String(identifiant), nom: nom, region: region };
            this.vue = 'fil';
            this.messages = [];
            this.erreur = '';
            this.chargement = true;
            try {
                const reponse = await fetch('/bachelier/inbox/' + identifiant + '/messages', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const donnees = await reponse.json();
                if (!reponse.ok || !donnees.success) {
                    this.erreur = donnees.message || 'Les messages n ont pas pu être chargés.';
                    return;
                }
                this.messages = donnees.messages;
                // La pastille de non-lus tombe a zero : le serveur vient de marquer
                // la conversation comme lue.
                const pastille = document.querySelector('[data-non-lus=\'' + identifiant + '\']');
                if (pastille) { pastille.remove(); }
                this.$nextTick(() => this.versLeBas());
            } catch (e) {
                this.erreur = 'Les messages n ont pas pu être chargés. Vérifiez votre connexion.';
            } finally {
                this.chargement = false;
            }
        },

        versLeBas() {
            const fil = this.$refs.fil;
            if (fil) { fil.scrollTop = fil.scrollHeight; }
        },

        async envoyer(evenement) {
            const formulaire = evenement.target;
            const champ = formulaire.elements.content;
            const texte = champ.value.trim();
            if (!texte || !this.active) { return; }

            this.envoi = true;
            this.erreur = '';
            const donneesFormulaire = new FormData(formulaire);
            try {
                const reponse = await fetch('/bachelier/inbox/' + this.active.id + '/reply', {
                    method: 'POST',
                    body: donneesFormulaire,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const donnees = await reponse.json();
                if (!reponse.ok || !donnees.success) {
                    this.erreur = donnees.message || 'Le message n a pas pu être envoyé.';
                    return;
                }
                this.messages.push(donnees.data);
                champ.value = '';
                champ.style.height = 'auto';
                this.$nextTick(() => this.versLeBas());
            } catch (e) {
                this.erreur = 'Le message n a pas pu être envoyé. Vérifiez votre connexion.';
            } finally {
                this.envoi = false;
            }
        },

        async confirmerSuppressionMessage() {
            const identifiant = this.suppressionMessage;
            this.suppressionMessage = null;
            try {
                const reponse = await fetch('/bachelier/inbox/messages/' + identifiant, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                    }
                });
                const donnees = await reponse.json();
                if (!donnees.success) { this.erreur = donnees.message || 'Le message n a pas pu être supprimé.'; return; }
                this.messages = this.messages.filter((m) => m.id !== Number(identifiant));
            } catch (e) {
                this.erreur = 'Le message n a pas pu être supprimé.';
            }
        },

        async confirmerSuppressionConversation() {
            if (!this.active) { return; }
            try {
                const reponse = await fetch('/bachelier/inbox/' + this.active.id + '/destroy', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                    }
                });
                const donnees = await reponse.json();
                if (!donnees.success) { this.erreur = donnees.message || 'La conversation n a pas pu être supprimée.'; return; }
                window.location.reload();
            } catch (e) {
                this.erreur = 'La conversation n a pas pu être supprimée.';
            }
        }
     }"
     @keydown.escape.window="suppressionMessage = null; suppressionConversation = false"
     class="ds-container ds-stack"
     style="padding-block: var(--space-4)">

    {{-- En-tete de page : la vue s ouvrait sur un fil d Ariane, sans aucun h1. --}}
    <header>
        <p class="ds-overline">MESSAGERIE</p>
        <h1 style="margin-top: var(--space-1)">Messagerie</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            {{ $conversations->total() }}
            {{ $conversations->total() > 1 ? 'conversations' : 'conversation' }}@if($totalNonLus > 0), dont {{ $totalNonLus }} {{ $totalNonLus > 1 ? 'messages non lus' : 'message non lu' }}@endif.
        </p>
        {{-- Renvoi vers la page de creation plutot qu une fenetre modale. Le
             formulaire de nouveau message existait en double : une modale ici, et
             la page bachelier.inbox.create, qui rendait exactement le meme service
             mais n etait liee depuis nulle part. Une seule des deux devait rester ;
             la page l emporte, elle fonctionne sans JavaScript et conserve les
             saisies quand la moderation refuse un message. --}}
        <a href="{{ route('bachelier.inbox.create') }}" class="ds-btn ds-btn-primary ds-btn-md" style="margin-top: var(--space-2)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <path d="M5 12h14"/><path d="M12 5v14"/>
            </svg>
            Nouveau message
        </a>
    </header>

    @if ($lignes->count() > 0)
    <div class="inbox-grille" style="display:grid; gap:var(--space-2); grid-template-columns:minmax(0, 1fr)">

        {{-- Rail des conversations.
             CHAMP MORT SUPPRIME : cette colonne portait un champ « Rechercher une
             conversation » sans aucun ecouteur, sans formulaire et sans route.
             InboxController@index ne lit aucun parametre : il n a jamais rien filtre. --}}
        <section x-show="large || vue === 'liste'" class="ds-stack-sm" style="min-width:0">
            <h2 class="ds-overline">Conversations</h2>

            @foreach ($lignes as $index => $ligne)
                <button type="button"
                        @if ($loop->first) x-ref="premiere" @endif
                        data-id="{{ $ligne['id'] }}"
                        data-nom="{{ $ligne['nom'] }}"
                        data-region="{{ $ligne['region'] }}"
                        @click="ouvrir(@js((string) $ligne['id']), @js($ligne['nom']), @js($ligne['region'] ?? ''))"
                        :aria-current="active && active.id === @js((string) $ligne['id']) ? 'true' : 'false'"
                        class="inbox-ligne ds-card">
                    <span class="inbox-avatar" aria-hidden="true">{{ $ligne['initiale'] }}</span>
                    <span style="min-width:0; flex:1; text-align:left">
                        <span style="display:flex; align-items:center; gap:var(--space-1)">
                            <span style="min-width:0; flex:1; font-weight:var(--font-semibold); overflow:hidden; text-overflow:ellipsis; white-space:nowrap">{{ $ligne['nom'] }}</span>
                            @if ($ligne['non_lus'] > 0)
                                <span class="ds-badge ds-badge-solid numbers" data-non-lus="{{ $ligne['id'] }}">
                                    {{ $ligne['non_lus'] }}
                                    <span class="sr-only">{{ $ligne['non_lus'] > 1 ? 'messages non lus' : 'message non lu' }}</span>
                                </span>
                            @endif
                        </span>
                        @if ($ligne['apercu'])
                            <span class="ds-text-secondary" style="display:block; margin-top:var(--space-0-5); font-size:var(--text-caption); overflow:hidden; text-overflow:ellipsis; white-space:nowrap">{{ $ligne['apercu'] }}</span>
                        @endif
                        <span class="ds-text-secondary" style="display:block; margin-top:var(--space-0-5); font-size:var(--text-label)">
                            {{ $ligne['date'] ?? 'Pas encore de message' }}@if($ligne['region']) &middot; {{ $ligne['region'] }}@endif
                        </span>
                    </span>
                </button>
            @endforeach

            {{-- La messagerie est paginee a 20 conversations par le controleur, et
                 la vue ne rendait aucun lien de page : au-dela de vingt, les
                 conversations suivantes etaient injoignables. --}}
            @if ($conversations->hasPages())
            <div>
                {{ $conversations->links() }}
            </div>
            @endif
        </section>

        {{-- Volet de conversation --}}
        <section x-show="large || vue === 'fil'" class="ds-card inbox-volet" style="min-width:0">

            <div class="inbox-volet-entete">
                <button type="button" @click="vue = 'liste'" class="inbox-retour" x-show="!large">
                    <span class="sr-only">Revenir à la liste des conversations</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                </button>

                {{-- L identite est le lien vers la page dediee de la conversation.
                     Un bouton distinct « ouvrir sur sa propre page » occupait 44px de
                     plus dans cet en-tete : a 360px il ne restait que 87px pour le nom,
                     tronque des « Koffi Ko... », et la region passait a la ligne, ce qui
                     portait l en-tete a 95px de haut. La region est retiree, elle figure
                     deja sur la ligne de la liste et sur la page de la conversation. --}}
                <a :href="active ? '/bachelier/inbox/' + active.id : '#'"
                   style="display:flex; gap:var(--space-1); align-items:center; min-width:0; flex:1; min-height:44px; color:inherit; text-decoration:none">
                    <span class="inbox-avatar" aria-hidden="true" x-text="active ? active.nom.charAt(0) : ''"></span>
                    <h2 style="min-width:0; font-size: var(--text-h3); overflow:hidden; text-overflow:ellipsis; white-space:nowrap"
                        x-text="active ? active.nom : 'Conversation'">Conversation</h2>
                </a>

                <button type="button" @click="suppressionConversation = true" class="inbox-action inbox-action-danger">
                    <span class="sr-only">Supprimer cette conversation</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                </button>
            </div>

            <div x-show="erreur" style="display:none; padding:var(--space-2) var(--space-2) 0" class="ds-alert ds-alert-error" role="alert">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0; margin-top:2px">
                    <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                </svg>
                <p x-text="erreur"></p>
            </div>

            {{-- Fil. Chaque message est pose par x-text, donc en texte : l ancienne
                 version l injectait dans innerHTML, ce qui executait toute balise
                 ecrite par l expediteur. --}}
            <div class="inbox-fil" x-ref="fil" role="log" aria-label="Messages de la conversation" tabindex="0">
                <p class="ds-text-secondary" x-show="chargement" style="padding:var(--space-3); text-align:center">Chargement des messages...</p>

                <template x-if="!chargement && messages.length === 0">
                    <div style="padding: var(--space-4); text-align:center">
                        <span class="ds-text-secondary" style="display:inline-flex">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>
                            </svg>
                        </span>
                        <h3 style="margin-top: var(--space-1-5)">Cette conversation commence</h3>
                        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                            Écrivez le premier message ci-dessous.
                        </p>
                    </div>
                </template>

                <template x-for="message in messages" :key="message.id">
                    <article class="inbox-message" :class="message.is_sender ? 'inbox-message-moi' : ''">
                        <div style="min-width:0; max-width:min(85%, 460px); display:flex; flex-direction:column"
                             :style="message.is_sender ? 'align-items:flex-end' : 'align-items:flex-start'">
                            <p class="ds-text-secondary" style="font-size:var(--text-label); font-weight:var(--font-semibold)"
                               x-text="message.is_sender ? 'Vous' : message.sender_name"></p>
                            <div class="inbox-bulle" :class="message.is_sender ? 'inbox-bulle-moi' : ''">
                                <p style="white-space:pre-wrap" x-text="message.content"></p>
                            </div>
                            <p class="ds-text-secondary" style="margin-top:var(--space-0-5); display:flex; align-items:center; gap:var(--space-0-5); font-size:var(--text-label)">
                                <span x-text="message.created_at"></span>
                                <template x-if="message.is_sender">
                                    {{-- Bouton de suppression. Il appelait deleteMessage(),
                                         fonction qui n existait nulle part : un clic levait
                                         une ReferenceError et ne supprimait rien. La fenetre
                                         de confirmation et sa route etaient donc inertes. --}}
                                    <button type="button" class="inbox-supprimer"
                                            @click="suppressionMessage = message.id">
                                        <span class="sr-only">Supprimer ce message</span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                            <path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </template>
                            </p>
                        </div>
                    </article>
                </template>
            </div>

            {{-- Champ de saisie, toujours en bas du volet : il reste atteignable sans
                 defiler jusqu au bout d un fil long, c est le fil qui defile. --}}
            <form class="inbox-saisie" @submit.prevent="envoyer($event)">
                @csrf
                <label class="sr-only" for="message-content">Votre message</label>
                <textarea name="content" id="message-content" rows="1" required maxlength="2000"
                          class="ds-field ds-textarea"
                          style="min-height:44px; max-height:140px"
                          placeholder="Votre message..."
                          x-on:input="$event.target.style.height='auto'; $event.target.style.height=Math.min($event.target.scrollHeight,140)+'px'"
                          x-on:keydown.enter="if (!$event.shiftKey) { $event.preventDefault(); $event.target.form.requestSubmit(); }"></textarea>
                <button type="submit" class="ds-btn ds-btn-primary ds-btn-md" :disabled="envoi">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>
                    </svg>
                    <span x-text="envoi ? 'Envoi...' : 'Envoyer'">Envoyer</span>
                </button>
            </form>
        </section>
    </div>
    @else
        {{-- Au lancement, la messagerie sera vide pour tout le monde. --}}
        <div class="ds-card-flat" style="padding: var(--space-6); text-align:center">
            <span class="ds-text-secondary" style="display:inline-flex">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="m22 6-10 7L2 6"/>
                </svg>
            </span>
            <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">Aucune conversation</h2>
            <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                Un bachelier a fait l'école qui vous intéresse, ou décroché la bourse que vous visez ?
                Écrivez-lui : ces échanges restent entre vous deux.
            </p>
            <div style="margin-top: var(--space-3); display:flex; gap:var(--space-1); flex-wrap:wrap; justify-content:center">
                <a href="{{ route('bachelier.inbox.create') }}" class="ds-btn ds-btn-primary ds-btn-lg">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M5 12h14"/><path d="M12 5v14"/>
                    </svg>
                    Écrire un premier message
                </a>
                <a href="{{ route('bachelier.forum.members') }}" class="ds-btn ds-btn-secondary ds-btn-lg">
                    Voir les membres
                </a>
            </div>
        </div>
    @endif

    {{-- ============ Confirmation de suppression d un message ============ --}}
    <div x-show="suppressionMessage !== null" style="display:none" class="inbox-modale"
         role="dialog" aria-modal="true" aria-labelledby="titre-suppression-message">
        <div class="inbox-modale-voile" @click="suppressionMessage = null" aria-hidden="true"></div>
        <div class="inbox-modale-boite ds-card">
            <h2 id="titre-suppression-message" style="font-size: var(--text-h3)">Supprimer ce message ?</h2>
            <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                Il disparaîtra pour vous comme pour votre interlocuteur. Cette action est irréversible.
            </p>
            <div style="margin-top: var(--space-3); display:flex; justify-content:flex-end; gap:var(--space-1); flex-wrap:wrap">
                <button type="button" @click="suppressionMessage = null" class="ds-btn ds-btn-secondary ds-btn-md">Annuler</button>
                <button type="button" @click="confirmerSuppressionMessage()" class="ds-btn ds-btn-danger ds-btn-md">Supprimer</button>
            </div>
        </div>
    </div>

    {{-- ========= Confirmation de suppression d une conversation ========= --}}
    <div x-show="suppressionConversation" style="display:none" class="inbox-modale"
         role="dialog" aria-modal="true" aria-labelledby="titre-suppression-conversation">
        <div class="inbox-modale-voile" @click="suppressionConversation = false" aria-hidden="true"></div>
        <div class="inbox-modale-boite ds-card">
            <h2 id="titre-suppression-conversation" style="font-size: var(--text-h3)">Supprimer cette conversation ?</h2>
            <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                Tous les messages échangés seront définitivement supprimés, pour vous comme
                pour votre interlocuteur. Pour la retirer de votre messagerie sans rien effacer,
                ouvrez la conversation et archivez-la.
            </p>
            <div style="margin-top: var(--space-3); display:flex; justify-content:flex-end; gap:var(--space-1); flex-wrap:wrap">
                <button type="button" @click="suppressionConversation = false" class="ds-btn ds-btn-secondary ds-btn-md">Annuler</button>
                <button type="button" @click="suppressionConversation = false; confirmerSuppressionConversation()" class="ds-btn ds-btn-danger ds-btn-md">Supprimer</button>
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
    @media (min-width: 1024px) {
        html[data-ds] .inbox-grille {
            grid-template-columns: 320px minmax(0, 1fr);
            align-items: start;
        }
    }

    html[data-ds] .inbox-avatar {
        display: grid;
        place-items: center;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        border-radius: var(--radius-pill);
        background: var(--accent-surface);
        /* --accent sur --accent-surface mesure 4,31:1, sous AA pour du texte. */
        color: var(--text-primary);
        font-weight: var(--font-semibold);
        text-transform: uppercase;
    }

    /* Ligne de conversation : un bouton pleine largeur, jamais une div cliquable. */
    html[data-ds] .inbox-ligne {
        display: flex;
        gap: var(--space-1-5);
        align-items: flex-start;
        width: 100%;
        min-height: 44px;
        padding: var(--space-2);
        text-align: left;
        font: inherit;
        color: inherit;
        cursor: pointer;
    }
    html[data-ds] .inbox-ligne:hover { background: var(--surface-hover); }
    html[data-ds] .inbox-ligne[aria-current="true"] {
        border-color: var(--accent-border);
        background: var(--accent-surface-faint);
    }

    html[data-ds] .inbox-volet {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        max-height: min(76dvh, 720px);
    }

    html[data-ds] .inbox-volet-entete {
        display: flex;
        align-items: center;
        gap: var(--space-1);
        flex-shrink: 0;
        padding: var(--space-2);
        border-bottom: 1px solid var(--border-default);
    }

    html[data-ds] .inbox-retour,
    html[data-ds] .inbox-action {
        display: grid;
        place-items: center;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        border: 0;
        background: none;
        color: var(--text-secondary);
        cursor: pointer;
        border-radius: var(--radius-chip);
    }
    html[data-ds] .inbox-retour:hover,
    html[data-ds] .inbox-action:hover { background: var(--surface-hover); color: var(--text-primary); }
    html[data-ds] .inbox-action-danger:hover { color: var(--error-text); }

    html[data-ds] .inbox-fil {
        flex: 1;
        min-height: 200px;
        overflow-y: auto;
        padding: var(--space-2);
        display: flex;
        flex-direction: column;
        gap: var(--space-2);
    }

    html[data-ds] .inbox-message { display: flex; justify-content: flex-start; }
    html[data-ds] .inbox-message-moi { justify-content: flex-end; }

    /* Bulle recue : surface secondaire, texte principal.
       Bulle envoyee : aplat d accent, texte pose dessus. Les deux appariements sont
       ceux du design system, ils basculent seuls en mode sombre. */
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

    html[data-ds] .inbox-supprimer {
        display: grid;
        place-items: center;
        width: 44px;
        height: 44px;
        margin: calc(var(--space-1-5) * -1) 0;
        border: 0;
        background: none;
        color: var(--text-secondary);
        cursor: pointer;
        border-radius: var(--radius-chip);
    }
    html[data-ds] .inbox-supprimer:hover { background: var(--surface-hover); color: var(--error-text); }

    /* Zone de saisie. A 360px, un champ et un bouton « Envoyer » cote a cote ne
       laissaient que 130px au champ : l invite s y coupait en deux lignes. Le champ
       prend donc toute la largeur et le bouton passe dessous, aligne a droite ;
       a partir de 640px les deux reviennent sur une seule ligne. */
    html[data-ds] .inbox-saisie {
        display: flex;
        flex-wrap: wrap;
        gap: var(--space-1);
        align-items: flex-end;
        flex-shrink: 0;
        padding: var(--space-2);
        border-top: 1px solid var(--border-default);
    }
    html[data-ds] .inbox-saisie textarea { flex: 1 1 100%; }
    html[data-ds] .inbox-saisie button { margin-inline-start: auto; }

    @media (min-width: 640px) {
        html[data-ds] .inbox-saisie { flex-wrap: nowrap; }
        html[data-ds] .inbox-saisie textarea { flex: 1 1 auto; }
    }

    html[data-ds] .inbox-modale {
        position: fixed;
        inset: 0;
        z-index: var(--z-modal);
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }
    html[data-ds] .inbox-modale-voile {
        position: absolute;
        inset: 0;
        background: var(--overlay-scrim);
    }
    html[data-ds] .inbox-modale-boite {
        position: relative;
        width: 100%;
        padding: var(--space-3);
        background: var(--surface-raised);
    }
    @media (min-width: 640px) {
        html[data-ds] .inbox-modale { align-items: center; padding: var(--space-3); }
        html[data-ds] .inbox-modale-boite { max-width: 460px; }
    }
</style>
@endpush
@endsection
