@extends('layouts.bachelier')

{{-- Active le design system PEUB sur cette vue uniquement. --}}
@section('html-attrs', 'data-ds')

@section('title', 'Communauté - Bachelier PEUB')

@php
    // Onglets de la communaute. Repris a l identique dans favorites et members :
    // ces trois vues partagent le meme rail. Un composant commun serait le bon
    // reflexe, mais le creer suppose un fichier hors du perimetre de ce lot.
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

    // Une seule requete pour tous les favoris de l utilisateur, au lieu d un
    // exists() par discussion affichee : la vue en lancait jusqu a quinze.
    $favoris = auth()->check()
        ? auth()->user()->favoriteThreads()->pluck('forum_thread_id')->all()
        : [];

    // Les epinglees ne sont annoncees que s il y en a ; les recentes portent
    // seules l etat vide, qui invite a publier.
    $sections = [];
    if ($pinnedThreads->count() > 0) {
        $sections[] = ['titre' => 'Discussions épinglées', 'fils' => $pinnedThreads];
    }
    $sections[] = ['titre' => 'Discussions récentes', 'fils' => $recentThreads];
@endphp

@section('content')
{{-- Le x-data enveloppe toute la page : le bouton d en-tete et celui de la colonne
     laterale ouvrent la meme fenetre de creation. --}}
<div x-data="{
        ouvert: false,
        envoi: false,
        refus: '',
        erreurs: {},
        longueur: 0,
        ouvrir() {
            this.ouvert = true;
            this.$nextTick(() => this.$refs.champTitre?.focus());
        },
        fermer() {
            this.ouvert = false;
            this.refus = '';
            this.erreurs = {};
        },
        async publier(evenement) {
            const formulaire = evenement.target;
            this.envoi = true;
            this.refus = '';
            this.erreurs = {};
            try {
                const reponse = await fetch(formulaire.action, {
                    method: 'POST',
                    body: new FormData(formulaire),
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                let donnees = null;
                try { donnees = await reponse.json(); } catch (e) { donnees = null; }
                if (!reponse.ok) {
                    this.erreurs = (donnees && donnees.errors) ? donnees.errors : {};
                    this.refus = Object.keys(this.erreurs).length
                        ? ''
                        : ((donnees && donnees.message) || 'La discussion n a pas pu être publiée.');
                    return;
                }
                if (donnees && donnees.redirect) { window.location.href = donnees.redirect; return; }
                window.location.reload();
            } catch (e) {
                this.refus = 'La discussion n a pas pu être publiée. Vérifiez votre connexion, puis réessayez.';
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
        <p class="ds-overline">COMMUNAUTÉ / DISCUSSIONS</p>
        <h1 style="margin-top: var(--space-1)">Communauté</h1>
        <p class="ds-text-secondary" style="margin-top: var(--space-1)">
            {{ number_format($stats['total_threads'], 0, ',', ' ') }}
            {{ $stats['total_threads'] > 1 ? 'discussions' : 'discussion' }}
            et {{ number_format($stats['total_posts'], 0, ',', ' ') }}
            {{ $stats['total_posts'] > 1 ? 'messages' : 'message' }} entre bacheliers.
        </p>
        <button type="button" @click="ouvrir()" class="ds-btn ds-btn-primary ds-btn-md" style="margin-top: var(--space-2)">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                <path d="M5 12h14"/><path d="M12 5v14"/>
            </svg>
            Lancer une discussion
        </button>
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

    {{-- Recherche. Le formulaire d origine postait sur bachelier.forum.index avec
         search, category et sort : ForumController@index ne recoit meme pas la
         requete et ne lisait donc aucun de ces trois champs. Filtre entierement
         mort, remplace ici par le formulaire de la route de recherche reelle, qui
         attend q et category. Le tri n existe pas cote recherche : il est retire
         plutot que d etre affiche sans effet. --}}
    <form method="GET" action="{{ route('bachelier.forum.search') }}" class="ds-card"
          style="padding: var(--space-2); display:grid; gap:var(--space-1-5); grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); align-items:end">
        <div style="grid-column:1 / -1">
            <label class="ds-label" for="q">Rechercher une discussion</label>
            <input type="search" name="q" id="q" required
                   class="ds-field"
                   placeholder="Titre ou contenu d'une discussion"
                   value="{{ request('q') }}">
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

    <div style="display:grid; gap:var(--space-3); grid-template-columns:minmax(0, 1fr)"
         class="forum-grille">

        {{-- Colonne principale. Les deux listes de discussions partagent le meme
             gabarit de ligne : une seule boucle imbriquee, plutot que deux blocs
             de balisage jumeaux a maintenir en parallele. --}}
        <div class="ds-stack" style="min-width:0">

            @foreach ($sections as $section)
            <section>
                <h2 style="font-size: var(--text-h3)">{{ $section['titre'] }}</h2>
                <div class="ds-stack-sm" style="margin-top: var(--space-1-5)">
                    @forelse ($section['fils'] as $thread)
                        @php $enFavori = in_array($thread->id, $favoris, true); @endphp
                        <article class="ds-card" style="padding: var(--space-2)">
                            <div style="display:flex; gap:var(--space-1); align-items:flex-start">
                                <div style="min-width:0; flex:1">
                                    <div style="display:flex; flex-wrap:wrap; align-items:center; gap:var(--space-0-5)">
                                        @if ($thread->category)
                                            {{-- Etiquette de categorie, et non lien : rendue cliquable elle
                                                 mesurait 29px de haut, sous la cible de 44px, et doublait un
                                                 chemin deja offert par le bloc « Categories » de cette page et
                                                 par le fil d Ariane de la discussion. --}}
                                            <span class="ds-badge ds-badge-neutral">{{ $thread->category->name }}</span>
                                        @endif
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

                                    <h3 style="margin-top: var(--space-1); font-size: var(--text-body)">
                                        <a href="{{ route('bachelier.forum.thread', $thread) }}" style="color:inherit; text-decoration:none">
                                            {{ $thread->title }}
                                        </a>
                                    </h3>

                                    <p class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size: var(--text-label)">
                                        Par {{ $thread->user?->name ?? 'Membre retiré' }}
                                        &middot; {{ $thread->created_at?->locale('fr')->diffForHumans() }}
                                    </p>

                                    {{-- L accessor last_post declenche une requete par ligne affichee.
                                         last_activity_at, deja charge sur la discussion et deja le
                                         critere de tri du controleur, porte la meme information. --}}
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
                        {{-- Etat vide : un forum vide est le cas le plus probable au
                             lancement, la page doit inviter a publier. --}}
                        <div class="ds-card-flat" style="padding: var(--space-6); text-align:center">
                            <span class="ds-text-secondary" style="display:inline-flex">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>
                                </svg>
                            </span>
                            <h3 style="margin-top: var(--space-2)">La communauté vous attend</h3>
                            <p class="ds-text-secondary" style="margin-top: var(--space-1)">
                                Aucune discussion n'a encore été lancée. Posez la première question,
                                elle servira à tous ceux qui arriveront après vous.
                            </p>
                            <button type="button" @click="ouvrir()" class="ds-btn ds-btn-primary ds-btn-lg" style="margin-top: var(--space-3)">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M5 12h14"/><path d="M12 5v14"/>
                                </svg>
                                Lancer la première discussion
                            </button>
                        </div>
                    @endforelse
                </div>
            </section>
            @endforeach

            <section>
                <h2 style="font-size: var(--text-h3)">Catégories</h2>
                <div class="ds-stack-sm" style="margin-top: var(--space-1-5)">
                    @forelse ($categories as $categorie)
                        <a href="{{ route('bachelier.forum.category', $categorie) }}"
                           class="ds-card-interactive"
                           style="display:flex; gap:var(--space-2); align-items:flex-start; padding:var(--space-2); color:inherit; text-decoration:none">
                            {{-- L icone et la couleur de la categorie sont stockees en base
                                 (colonnes icon et color). La couleur y est un hexadecimal brut,
                                 injecte tel quel dans un attribut style : il ne bascule pas en
                                 mode sombre et echappe au systeme de roles. L icone, elle,
                                 etait un nom Lucide rendu par l attribut du script Lucide.
                                 Une icone SVG en ligne, portee par les roles, remplace les deux. --}}
                            <span style="display:grid; place-items:center; width:40px; height:40px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--accent-surface); color:var(--accent)">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                            </span>
                            <span style="min-width:0; flex:1">
                                <span style="display:block; font-weight:var(--font-semibold)">{{ $categorie->name }}</span>
                                @if ($categorie->description)
                                    <span class="ds-text-secondary line-clamp-2" style="display:block; margin-top:var(--space-0-5); font-size:var(--text-caption)">{{ $categorie->description }}</span>
                                @endif
                                <span class="ds-text-secondary" style="display:block; margin-top:var(--space-0-5); font-size:var(--text-label)">
                                    {{ $categorie->threads_count }} {{ $categorie->threads_count > 1 ? 'discussions' : 'discussion' }}
                                    &middot; {{ $categorie->posts_count }} {{ $categorie->posts_count > 1 ? 'messages' : 'message' }}
                                </span>
                            </span>
                        </a>
                    @empty
                        <div class="ds-card-flat" style="padding: var(--space-3); text-align:center">
                            <p class="ds-text-secondary">Aucune catégorie n'est ouverte pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- Colonne laterale. Sous 1024px elle passe simplement a la suite. --}}
        <aside class="ds-stack" style="min-width:0">
            <section class="ds-card" style="padding: var(--space-2)">
                <h2 class="ds-overline">La communauté en chiffres</h2>
                {{-- Le compteur « Membres » a ete retire : le controleur le calcule par
                     Auth::user()->bachelier()->count(), c est-a-dire le nombre de fiches
                     bachelier du seul utilisateur connecte. Il affichait donc 1 a tout le
                     monde, sous un libelle qui promettait l effectif de la communaute.
                     Le renvoi vers l annuaire des membres le remplace. --}}
                <dl style="margin-top: var(--space-1-5); display:grid; gap:var(--space-1); grid-template-columns:repeat(auto-fit, minmax(90px, 1fr))">
                    <div style="min-width:0; text-align:center">
                        <dd class="ds-stat" style="font-size: var(--text-h2)">{{ number_format($stats['total_threads'], 0, ',', ' ') }}</dd>
                        <dt class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size: var(--text-label)">Discussions</dt>
                    </div>
                    <div style="min-width:0; text-align:center">
                        <dd class="ds-stat" style="font-size: var(--text-h2)">{{ number_format($stats['total_posts'], 0, ',', ' ') }}</dd>
                        <dt class="ds-text-secondary" style="margin-top: var(--space-0-5); font-size: var(--text-label)">Messages</dt>
                    </div>
                </dl>
                <a href="{{ route('bachelier.forum.members') }}" class="ds-btn ds-btn-ghost ds-btn-md ds-btn-block" style="margin-top: var(--space-1-5)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M9 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    Voir les membres
                </a>
            </section>

            @if ($popularThreads->count() > 0)
            <section class="ds-card" style="padding: var(--space-2)">
                <h2 class="ds-overline">Les plus consultées</h2>
                <ul style="margin-top: var(--space-1-5); list-style:none; padding:0; display:grid; gap:var(--space-1-5)">
                    @foreach ($popularThreads as $thread)
                        <li>
                            <a href="{{ route('bachelier.forum.thread', $thread) }}"
                               class="line-clamp-2"
                               style="display:block; min-height:44px; font-size:var(--text-caption); font-weight:var(--font-medium); color:inherit; text-decoration:none">
                                {{ $thread->title }}
                            </a>
                            <p class="ds-text-secondary" style="margin-top:var(--space-0-5); font-size:var(--text-label)">
                                {{ $thread->views_count }} {{ $thread->views_count > 1 ? 'vues' : 'vue' }}
                                &middot; {{ $thread->posts_count }} {{ $thread->posts_count > 1 ? 'réponses' : 'réponse' }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            </section>
            @endif
        </aside>
    </div>

    {{-- ================= Fenetre de creation ================= --}}
    <div x-show="ouvert"
         class="forum-modale"
         style="display:none"
         role="dialog"
         aria-modal="true"
         aria-labelledby="titre-nouvelle-discussion">

        <div class="forum-modale-voile" @click="fermer()" aria-hidden="true"></div>

        <div class="forum-modale-boite ds-card">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:var(--space-1-5); padding:var(--space-2); border-bottom:1px solid var(--border-default)">
                <h2 id="titre-nouvelle-discussion" style="font-size: var(--text-h3)">Lancer une discussion</h2>
                <button type="button" @click="fermer()"
                        style="display:grid; place-items:center; width:44px; height:44px; flex-shrink:0; margin:calc(var(--space-1) * -1) calc(var(--space-1) * -1) 0 0; color:var(--text-secondary); background:none; border:0; cursor:pointer">
                    <span class="sr-only">Fermer</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('bachelier.forum.store-thread') }}" method="POST"
                  @submit.prevent="publier($event)"
                  class="ds-stack-sm"
                  style="padding: var(--space-2); overflow-y:auto">
                @csrf

                {{-- Refus de moderation. storeThread soumet le titre et le contenu a un
                     service de moderation et repond 422 avec le message de politique
                     d utilisation. Il est affiche ici, dans la fenetre, saisies intactes :
                     l ancien code le passait a alert(), qui vide l ecran du texte ecrit. --}}
                <div x-show="refus" style="display:none" class="ds-alert ds-alert-error" role="alert">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0; margin-top:2px">
                        <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                    </svg>
                    <div>
                        <p style="font-weight:var(--font-semibold)">Cette discussion n'a pas été publiée</p>
                        <p style="margin-top: var(--space-0-5)" x-text="refus"></p>
                        <p style="margin-top: var(--space-0-5)">Votre texte est conservé. Reformulez le passage concerné, puis publiez à nouveau.</p>
                    </div>
                </div>

                <div>
                    <label class="ds-label" for="modale_forum_category_id">Catégorie</label>
                    <select name="forum_category_id" id="modale_forum_category_id" required
                            class="ds-field"
                            :class="erreurs.forum_category_id ? 'ds-field ds-field-error' : 'ds-field'">
                        <option value="">Choisissez une catégorie</option>
                        @foreach ($categories as $categorie)
                            <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                        @endforeach
                    </select>
                    <p class="ds-error-text" style="display:none" x-show="erreurs.forum_category_id"
                       x-text="erreurs.forum_category_id ? erreurs.forum_category_id[0] : ''"></p>
                </div>

                <div>
                    <label class="ds-label" for="modale_title">Titre</label>
                    <input type="text" name="title" id="modale_title" required maxlength="255"
                           x-ref="champTitre"
                           class="ds-field"
                           :class="erreurs.title ? 'ds-field ds-field-error' : 'ds-field'"
                           placeholder="Ex : Comment financer une année à l'étranger ?">
                    <p class="ds-hint">255 caractères au maximum. Un titre clair attire plus de réponses.</p>
                    <p class="ds-error-text" style="display:none" x-show="erreurs.title"
                       x-text="erreurs.title ? erreurs.title[0] : ''"></p>
                </div>

                <div>
                    <label class="ds-label" for="modale_content">Votre message</label>
                    <textarea name="content" id="modale_content" rows="7" required minlength="10"
                              x-on:input="longueur = $event.target.value.length"
                              class="ds-field ds-textarea"
                              :class="erreurs.content ? 'ds-field ds-textarea ds-field-error' : 'ds-field ds-textarea'"
                              placeholder="Décrivez votre question ou partagez votre réflexion..."></textarea>
                    <p class="ds-hint">
                        <span>10 caractères au minimum.</span>
                        <span x-text="longueur < 10 ? 'Encore ' + (10 - longueur) + ' caractère' + (10 - longueur > 1 ? 's' : '') + '.' : longueur + ' caractères saisis.'"></span>
                    </p>
                    <p class="ds-error-text" style="display:none" x-show="erreurs.content"
                       x-text="erreurs.content ? erreurs.content[0] : ''"></p>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:var(--space-1); flex-wrap:wrap; padding-top:var(--space-1-5); border-top:1px solid var(--border-default)">
                    <button type="button" @click="fermer()" class="ds-btn ds-btn-secondary ds-btn-md">Annuler</button>
                    <button type="submit" class="ds-btn ds-btn-primary ds-btn-md" :disabled="envoi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>
                        </svg>
                        <span x-text="envoi ? 'Publication...' : 'Publier la discussion'">Publier la discussion</span>
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

    /* Grille de la page : une colonne au mobile, deux a partir de 1024px.
       Roles uniquement, aucune valeur de couleur. */
    @media (min-width: 1024px) {
        html[data-ds] .forum-grille {
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
            align-items: start;
        }
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
            max-width: 560px;
            max-height: 86dvh;
        }
    }

    /* Coeur de mise en favori : l etat passe par aria-pressed, jamais par une
       classe de palette ajoutee au vol comme le faisait l ancien script. */
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
            const identifiant = bouton.dataset.threadId;

            fetch('/bachelier/forum/' + identifiant + '/favorite', {
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
