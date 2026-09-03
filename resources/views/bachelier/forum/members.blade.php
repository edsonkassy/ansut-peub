@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Membres de la communauté - PEUB')

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

    // Gabarit d URL de conversation : l identifiant reel est substitue cote client
    // apres la reponse du serveur. L ancien script fabriquait une URL a la main
    // (/bachelier/inbox?conversation=ID) qui ne correspond a aucune route nommee.
    $gabaritConversation = route('bachelier.inbox.show', ['conversation' => '__ID__']);
@endphp

@section('content')
<div x-data="{
        ouvert: false,
        envoi: false,
        erreur: '',
        destinataire: { id: '', nom: '', region: '', initiales: '' },
        ouvrir(identifiant, nom, region) {
            this.destinataire = {
                id: identifiant,
                nom: nom,
                region: region,
                initiales: nom.split(' ').filter(Boolean).map(m => m.charAt(0)).join('').substring(0, 2).toUpperCase()
            };
            this.erreur = '';
            this.ouvert = true;
            this.$nextTick(() => this.$refs.champMessage?.focus());
        },
        fermer() { this.ouvert = false; this.erreur = ''; },
        async envoyer(evenement) {
            const formulaire = evenement.target;
            this.envoi = true;
            this.erreur = '';
            try {
                const reponse = await fetch(formulaire.action, {
                    method: 'POST',
                    body: new FormData(formulaire),
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                let donnees = null;
                try { donnees = await reponse.json(); } catch (e) { donnees = null; }
                if (!reponse.ok || !donnees || !donnees.success) {
                    this.erreur = (donnees && donnees.message) || 'Le message n a pas pu être envoyé.';
                    return;
                }
                window.location.href = '{{ $gabaritConversation }}'.replace('__ID__', donnees.conversation_id);
            } catch (e) {
                this.erreur = 'Le message n a pas pu être envoyé. Vérifiez votre connexion, puis réessayez.';
            } finally {
                this.envoi = false;
            }
        }
     }"
     @keydown.escape.window="if (ouvert) { fermer() }"
     class="ds-container ds-stack"
     style="padding-block: var(--space-4)">

    {{-- En-tete de page : la vue s ouvrait sur un fil d Ariane, sans aucun h1. --}}
    <header>
        <p class="ds-overline">COMMUNAUTÉ / MEMBRES</p>
        <h1 style="margin-top: var(--space-1)">Membres de la communauté</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            {{ $bacheliers->total() }}
            {{ $bacheliers->total() > 1 ? 'bacheliers inscrits' : 'bachelier inscrit' }}.
            Écrivez à celles et ceux qui ont pris le chemin qui vous intéresse.
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

    <div style="display:grid; gap:var(--space-2); grid-template-columns:repeat(auto-fill, minmax(260px, 1fr))">
        @forelse ($bacheliers as $bachelier)
            @php
                $nomComplet = trim(($bachelier->prenoms ?? '') . ' ' . ($bachelier->nom ?? ''));
                $initiales = mb_substr($bachelier->prenoms ?? '?', 0, 1) . mb_substr($bachelier->nom ?? '', 0, 1);
                $estMoi = $bachelier->user_id === auth()->id();

                // La colonne photo peut nommer un fichier qui n existe pas sur le disque :
                // 26 fiches de la base en portent un, aucun n est present. Sans ce controle
                // la grille se remplit de vignettes cassees. On ne rend l image que si le
                // fichier existe vraiment ; sinon les initiales, qui ne cassent jamais.
                $photo = $bachelier->photo && Storage::disk('public')->exists($bachelier->photo)
                    ? asset('storage/' . $bachelier->photo)
                    : null;
            @endphp
            <article class="ds-card" style="padding: var(--space-2); display:flex; flex-direction:column; gap:var(--space-2)">
                <div style="display:flex; gap:var(--space-1-5); align-items:flex-start">
                    {{-- Avatar. La vue lisait $bachelier->user->photo_profil : la colonne
                         photo_profil est sur la table bacheliers, pas sur users, et la
                         photo reellement enregistree par le profil est la colonne photo.
                         L image ne s est donc jamais affichee, tout le monde tombait sur
                         les initiales. Corrige, avec asset('storage/...'), la convention
                         deja retenue par bachelier/profile.blade.php. --}}
                    @if ($photo)
                        <img src="{{ $photo }}"
                             alt="Photo de {{ $nomComplet }}"
                             width="56" height="56" loading="lazy" decoding="async"
                             style="width:56px; height:56px; flex-shrink:0; border-radius:var(--radius-pill); object-fit:cover">
                    @else
                        <span class="forum-avatar" aria-hidden="true">{{ $initiales }}</span>
                    @endif

                    <div style="min-width:0; flex:1">
                        <h2 style="font-size: var(--text-body)">{{ $nomComplet !== '' ? $nomComplet : 'Membre PEUB' }}</h2>

                        @if ($bachelier->boursier_peub)
                            <span class="ds-badge ds-badge-accent" style="margin-top: var(--space-0-5)">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M12 2a6 6 0 1 0 0 12 6 6 0 0 0 0-12"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
                                </svg>
                                Boursier PEUB
                            </span>
                        @endif

                        <p class="ds-text-secondary" style="margin-top: var(--space-1); font-size: var(--text-label)">
                            Membre depuis {{ $bachelier->created_at?->locale('fr')->diffForHumans() }}
                        </p>
                    </div>
                </div>

                {{-- Les deux reperes affiches sont ceux que la vue voulait deja montrer.
                     L etablissement lisait $bachelier->etablissement : la colonne s appelle
                     etablissement_nom, la ligne affichait donc « Établissement non spécifié »
                     pour tout le monde. Les champs absents sont maintenant tus, plutot que
                     remplis d une mention creuse repetee sur chaque carte. --}}
                @if ($bachelier->region || $bachelier->etablissement_nom)
                <dl class="ds-text-secondary" style="display:grid; gap:var(--space-0-5); font-size:var(--text-label)">
                    @if ($bachelier->region)
                    <div style="display:flex; gap:var(--space-0-5); align-items:flex-start">
                        <dt style="flex-shrink:0; line-height:0">
                            <span class="sr-only">Région</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="vertical-align:middle">
                                <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><path d="M12 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                            </svg>
                        </dt>
                        <dd style="min-width:0">{{ $bachelier->region }}</dd>
                    </div>
                    @endif
                    @if ($bachelier->etablissement_nom)
                    <div style="display:flex; gap:var(--space-0-5); align-items:flex-start">
                        <dt style="flex-shrink:0; line-height:0">
                            <span class="sr-only">Établissement d'origine</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="vertical-align:middle">
                                <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>
                            </svg>
                        </dt>
                        <dd style="min-width:0">{{ $bachelier->etablissement_nom }}</dd>
                    </div>
                    @endif
                </dl>
                @endif

                <div style="margin-top:auto">
                    @if ($estMoi)
                        <a href="{{ route('bachelier.profile') }}" class="ds-btn ds-btn-secondary ds-btn-md ds-btn-block">
                            Voir mon profil
                        </a>
                    @else
                        <button type="button"
                                @click="ouvrir('{{ $bachelier->user_id }}', @js($nomComplet), @js($bachelier->region ?? ''))"
                                class="ds-btn ds-btn-primary ds-btn-md ds-btn-block">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>
                            </svg>
                            Écrire un message
                        </button>
                    @endif
                </div>
            </article>
        @empty
            {{-- La grille etait un @foreach sans etat vide : au lancement, la page
                 s affichait entierement blanche sous les onglets. --}}
            <div class="ds-card-flat" style="grid-column:1 / -1; padding: var(--space-6); text-align:center">
                <span class="ds-text-secondary" style="display:inline-flex">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M9 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </span>
                <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">Aucun membre à afficher</h2>
                <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                    L'annuaire se remplira au fur et à mesure des inscriptions.
                    En attendant, la communauté se retrouve dans les discussions.
                </p>
                <a href="{{ route('bachelier.forum.index') }}" class="ds-btn ds-btn-primary ds-btn-lg" style="margin-top: var(--space-3)">
                    Voir les discussions
                </a>
            </div>
        @endforelse
    </div>

    @if ($bacheliers->hasPages())
    <div>
        {{ $bacheliers->links() }}
    </div>
    @endif

    {{-- ================= Fenetre d envoi de message ================= --}}
    <div x-show="ouvert"
         class="forum-modale"
         style="display:none"
         role="dialog"
         aria-modal="true"
         aria-labelledby="titre-nouveau-message">

        <div class="forum-modale-voile" @click="fermer()" aria-hidden="true"></div>

        <div class="forum-modale-boite ds-card">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:var(--space-1-5); padding:var(--space-2); border-bottom:1px solid var(--border-default)">
                <h2 id="titre-nouveau-message" style="font-size: var(--text-h3)">Écrire un message</h2>
                <button type="button" @click="fermer()"
                        style="display:grid; place-items:center; width:44px; height:44px; flex-shrink:0; margin:calc(var(--space-1) * -1) calc(var(--space-1) * -1) 0 0; color:var(--text-secondary); background:none; border:0; cursor:pointer">
                    <span class="sr-only">Fermer</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('bachelier.inbox.start-conversation') }}" method="POST"
                  @submit.prevent="envoyer($event)"
                  class="ds-stack-sm"
                  style="padding: var(--space-2); overflow-y:auto">
                @csrf
                <input type="hidden" name="recipient_id" :value="destinataire.id">

                <div class="ds-card-flat" style="display:flex; align-items:center; gap:var(--space-1-5); padding:var(--space-1-5)">
                    <span class="forum-avatar" aria-hidden="true" x-text="destinataire.initiales"></span>
                    <span style="min-width:0">
                        <span style="display:block; font-weight:var(--font-semibold)" x-text="destinataire.nom"></span>
                        <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)"
                              x-show="destinataire.region" x-text="destinataire.region"></span>
                    </span>
                </div>

                <div x-show="erreur" style="display:none" class="ds-alert ds-alert-error" role="alert">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0; margin-top:2px">
                        <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                    </svg>
                    <div>
                        <p style="font-weight:var(--font-semibold)">Message non envoyé</p>
                        <p style="margin-top: var(--space-0-5)" x-text="erreur"></p>
                        <p style="margin-top: var(--space-0-5)">Votre texte est conservé ci-dessous.</p>
                    </div>
                </div>

                <div>
                    <label class="ds-label" for="contenu-message">Votre message</label>
                    <textarea name="content" id="contenu-message" rows="5" required maxlength="2000"
                              x-ref="champMessage"
                              class="ds-field ds-textarea"
                              placeholder="Présentez-vous en une phrase, puis posez votre question."></textarea>
                    <p class="ds-hint">2 000 caractères au maximum.</p>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:var(--space-1); flex-wrap:wrap; padding-top:var(--space-1-5); border-top:1px solid var(--border-default)">
                    <button type="button" @click="fermer()" class="ds-btn ds-btn-secondary ds-btn-md">Annuler</button>
                    <button type="submit" class="ds-btn ds-btn-primary ds-btn-md" :disabled="envoi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>
                        </svg>
                        <span x-text="envoi ? 'Envoi...' : 'Envoyer'">Envoyer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

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
        width: 56px;
        height: 56px;
        flex-shrink: 0;
        border-radius: var(--radius-pill);
        background: var(--accent-surface);
        color: var(--accent);
        font-weight: var(--font-semibold);
        text-transform: uppercase;
    }

    html[data-ds] .forum-modale {
        position: fixed;
        inset: 0;
        z-index: var(--z-modal);
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding: 0;
    }

    html[data-ds] .forum-modale-voile {
        position: absolute;
        inset: 0;
        background: var(--overlay-scrim);
    }

    html[data-ds] .forum-modale-boite {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
        max-height: 92dvh;
        overflow: hidden;
        background: var(--surface-raised);
    }

    @media (min-width: 640px) {
        html[data-ds] .forum-modale {
            align-items: center;
            padding: var(--space-3);
        }
        html[data-ds] .forum-modale-boite {
            max-width: 520px;
            max-height: 86dvh;
        }
    }
</style>
@endpush
@endsection
