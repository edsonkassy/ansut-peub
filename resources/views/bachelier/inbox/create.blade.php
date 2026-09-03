@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Nouveau message - Bachelier PEUB')

@php
    // startConversation valide recipient_id, subject (facultatif) et content.
    // En cas de refus de moderation il fait back()->withInput() : on revient ici
    // avec les saisies dans old(). Sans reprise explicite, le formulaire restait
    // masque et le message ecrit etait perdu.
    $destinataireChoisi = old('recipient_id');
    $erreurEnvoi = session('error');

    // Nom du destinataire deja choisi, retrouve dans les resultats affiches. S il
    // n y est pas (recherche differente au retour), le formulaire reste ouvert et
    // le nom est simplement omis : la saisie, elle, n est jamais perdue.
    $nomDestinataireChoisi = null;
    if ($destinataireChoisi) {
        $trouve = $bacheliers->firstWhere('id', (int) $destinataireChoisi);
        if ($trouve) {
            $nomDestinataireChoisi = $trouve->bachelier
                ? trim($trouve->bachelier->prenoms . ' ' . $trouve->bachelier->nom)
                : $trouve->email;
        }
    }
@endphp

@section('content')
<div x-data="{
        destinataireId: @js((string) ($destinataireChoisi ?? '')),
        destinataireNom: @js($nomDestinataireChoisi ?? ''),
        choisir(identifiant, nom) {
            this.destinataireId = identifiant;
            this.destinataireNom = nom;
            this.$nextTick(() => this.$refs.champMessage?.focus());
        },
        effacer() { this.destinataireId = ''; this.destinataireNom = ''; }
     }"
     class="ds-container-tight ds-stack"
     style="padding-block: var(--space-4)">

    {{-- En-tete de page : la vue s ouvrait sur un fil d Ariane, sans aucun h1. --}}
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
        <h1 style="margin-top: var(--space-1)">Nouveau message</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            Cherchez un bachelier par son nom, son prénom ou son adresse, puis écrivez-lui.
        </p>
    </header>

    @if ($erreurEnvoi)
        <div class="ds-alert ds-alert-error" role="alert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0; margin-top:2px">
                <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <div>
                <p style="font-weight:var(--font-semibold)">Votre message n'a pas été envoyé</p>
                <p style="margin-top: var(--space-0-5)">{{ $erreurEnvoi }}</p>
                <p style="margin-top: var(--space-0-5)">Votre texte est conservé plus bas. Reformulez le passage concerné, puis renvoyez.</p>
            </div>
        </div>
    @endif

    {{-- Recherche de destinataire. Formulaire GET explicite : l ancienne version
         rechargeait la page toute seule 500 ms apres la frappe, ce qui coupait la
         saisie en cours et ne laissait aucun moyen de lancer la recherche au
         clavier. Le bloc #search-results et son commentaire « via AJAX » sont
         retires : aucun appel n a jamais rempli cette zone. --}}
    <section class="ds-card" style="padding: var(--space-2)">
        <form method="GET" action="{{ route('bachelier.inbox.create') }}"
              style="display:grid; gap:var(--space-1-5); grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); align-items:end">
            <div style="grid-column:1 / -1">
                <label class="ds-label" for="search">Rechercher un bachelier</label>
                <input type="search" name="search" id="search"
                       class="ds-field"
                       placeholder="Nom, prénom ou adresse électronique"
                       value="{{ $search }}">
                <p class="ds-hint">2 caractères au minimum.</p>
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
    </section>

    @if (strlen($search) >= 2)
        <section class="ds-stack-sm">
            <h2 style="font-size: var(--text-h3)">
                {{ $bacheliers->count() }}
                {{ $bacheliers->count() > 1 ? 'bacheliers trouvés' : 'bachelier trouvé' }}
            </h2>

            @forelse ($bacheliers as $bachelier)
                @php
                    $nom = $bachelier->bachelier
                        ? trim($bachelier->bachelier->prenoms . ' ' . $bachelier->bachelier->nom)
                        : $bachelier->email;
                    $region = $bachelier->bachelier?->region;
                @endphp
                <article class="ds-card" style="display:flex; gap:var(--space-1-5); align-items:center; padding:var(--space-2); flex-wrap:wrap">
                    <span class="inbox-avatar" aria-hidden="true">{{ mb_substr($nom, 0, 1) }}</span>
                    <div style="min-width:0; flex:1">
                        <h3 style="font-size: var(--text-body)">{{ $nom }}</h3>
                        @if ($region)
                            <p class="ds-text-secondary" style="display:flex; align-items:center; gap:var(--space-0-5); font-size:var(--text-label)">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><path d="M12 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                                </svg>
                                {{ $region }}
                            </p>
                        @endif
                    </div>
                    <button type="button"
                            @click="choisir(@js((string) $bachelier->id), @js($nom))"
                            class="ds-btn ds-btn-md"
                            :class="destinataireId === @js((string) $bachelier->id) ? 'ds-btn ds-btn-secondary ds-btn-md' : 'ds-btn ds-btn-primary ds-btn-md'">
                        <span x-text="destinataireId === @js((string) $bachelier->id) ? 'Sélectionné' : 'Écrire à cette personne'">Écrire à cette personne</span>
                    </button>
                </article>
            @empty
                <div class="ds-card-flat" style="padding: var(--space-6); text-align:center">
                    <span class="ds-text-secondary" style="display:inline-flex">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M11 3a8 8 0 1 0 0 16 8 8 0 0 0 0-16"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                    </span>
                    <h3 style="margin-top: var(--space-2)">Personne ne correspond à « {{ $search }} »</h3>
                    <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                        Vérifiez l'orthographe, ou parcourez l'annuaire des membres de la communauté.
                    </p>
                    <a href="{{ route('bachelier.forum.members') }}" class="ds-btn ds-btn-secondary ds-btn-lg" style="margin-top: var(--space-3)">
                        Voir les membres
                    </a>
                </div>
            @endforelse
        </section>
    @else
        <div class="ds-card-flat" style="padding: var(--space-6); text-align:center">
            <span class="ds-text-secondary" style="display:inline-flex">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M9 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </span>
            <h2 style="margin-top: var(--space-2); font-size: var(--text-h3)">À qui voulez-vous écrire ?</h2>
            <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                Tapez au moins deux caractères dans le champ ci-dessus, ou parcourez
                l'annuaire des membres pour trouver quelqu'un.
            </p>
            <a href="{{ route('bachelier.forum.members') }}" class="ds-btn ds-btn-secondary ds-btn-lg" style="margin-top: var(--space-3)">
                Voir les membres
            </a>
        </div>
    @endif

    {{-- Formulaire d envoi, revele des qu un destinataire est choisi. --}}
    <section class="ds-card" x-show="destinataireId" style="padding: var(--space-3){{ $destinataireChoisi ? '' : '; display:none' }}">
        <h2 style="font-size: var(--text-h3)">Votre message</h2>

        <form action="{{ route('bachelier.inbox.start-conversation') }}" method="POST" class="ds-stack-sm" style="margin-top: var(--space-2)">
            @csrf
            <input type="hidden" name="recipient_id" :value="destinataireId" value="{{ $destinataireChoisi }}">

            <div class="ds-panel" style="display:flex; align-items:center; justify-content:space-between; gap:var(--space-1); padding:var(--space-1-5)">
                <span style="min-width:0; display:flex; align-items:center; gap:var(--space-1)">
                    <span class="inbox-avatar inbox-avatar-petit" aria-hidden="true" x-text="destinataireNom.charAt(0)"></span>
                    <span style="min-width:0">
                        <span class="ds-text-secondary" style="display:block; font-size:var(--text-label)">Destinataire</span>
                        <span style="display:block; font-weight:var(--font-semibold)"
                              x-text="destinataireNom || 'Bachelier sélectionné'"></span>
                    </span>
                </span>
                <button type="button" @click="effacer()" class="ds-btn ds-btn-ghost ds-btn-md">Changer</button>
            </div>
            @error('recipient_id')<p class="ds-error-text">{{ $message }}</p>@enderror

            <div>
                <label class="ds-label" for="subject">Sujet</label>
                <input type="text" name="subject" id="subject" maxlength="255"
                       class="ds-field @error('subject') ds-field-error @enderror"
                       placeholder="Ex : Question sur ton école d'ingénieurs"
                       value="{{ old('subject') }}">
                <p class="ds-hint">Facultatif, 255 caractères au maximum.</p>
                @error('subject')<p class="ds-error-text">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="ds-label" for="content">Message</label>
                <textarea name="content" id="content" rows="7" required maxlength="2000"
                          x-ref="champMessage"
                          class="ds-field ds-textarea @error('content') ds-field-error @enderror"
                          placeholder="Présentez-vous en une phrase, puis posez votre question.">{{ old('content') }}</textarea>
                {{-- L aide indiquait « Minimum 5 caractères ». startConversation valide
                     required|string|max:2000, sans aucun minimum : la consigne etait
                     fausse. La seule contrainte reelle est la borne haute. --}}
                <p class="ds-hint">2 000 caractères au maximum.</p>
                @error('content')<p class="ds-error-text">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex; justify-content:flex-end; gap:var(--space-1); flex-wrap:wrap; padding-top:var(--space-2); border-top:1px solid var(--border-default)">
                <a href="{{ route('bachelier.inbox.index') }}" class="ds-btn ds-btn-secondary ds-btn-md">Annuler</a>
                <button type="submit" class="ds-btn ds-btn-primary ds-btn-md">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>
                    </svg>
                    Envoyer le message
                </button>
            </div>
        </form>
    </section>

    <section class="ds-card-flat" style="padding: var(--space-2)">
        <h2 class="ds-overline">Pour bien commencer</h2>
        <ul class="ds-text-secondary" style="margin-top: var(--space-1); padding-left: var(--space-3); font-size: var(--text-caption)">
            <li>Dites qui vous êtes et pourquoi vous écrivez : on répond plus volontiers.</li>
            <li>Une question précise vaut mieux qu'une demande générale.</li>
            <li>Restez respectueux : les messages injurieux ne sont pas transmis.</li>
        </ul>
    </section>

</div>

@push('styles')
<style>
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
    html[data-ds] .inbox-avatar-petit {
        width: 36px;
        height: 36px;
        font-size: var(--text-caption);
    }
</style>
@endpush
@endsection
