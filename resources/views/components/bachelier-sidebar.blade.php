{{-- Squelette de page de l espace bachelier : rail de navigation, en-tete et @yield('content').
     Ce composant n est pas une simple navigation, c est la structure de la page. --}}
@php
    // Fond du rail : --brand-gradient-from et --brand-text sont apparies et ne s inversent
    // pas entre les themes, contrairement a --surface-inverse qui deviendrait clair en sombre.
    // Etat actif : barre laterale en --accent-highlight, 7,52:1 en clair et 9,78:1 en sombre
    // contre le fond du rail. Aucun role nouveau, theme.css reste fige.
    $navItems = [
        [
            'route' => 'bachelier.dashboard',
            'match' => ['bachelier.dashboard'],
            'label' => 'DASHBOARD',
            'icon' => ['M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z', 'M9 22V12h6v10'],
        ],
        [
            'route' => 'bachelier.opportunites',
            'match' => ['bachelier.opportunites*', 'bachelier.candidatures*', 'bachelier.favoris*'],
            'label' => 'OPPORTUNITÉ',
            'icon' => ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'm16.24 7.76-2.12 6.36-6.36 2.12 2.12-6.36z'],
        ],
        [
            'route' => 'bachelier.library.index',
            'match' => ['bachelier.library*'],
            'label' => 'RESSOURCES',
            'icon' => ['M12 7v14', 'M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z'],
        ],
        [
            'route' => 'bachelier.inbox.index',
            'match' => ['bachelier.inbox*'],
            'label' => 'MESSAGERIE',
            'icon' => ['M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z', 'm22 6-10 7L2 6'],
        ],
        [
            'route' => 'bachelier.forum.index',
            'match' => ['bachelier.forum*'],
            'label' => 'COMMUNAUTÉ',
            'icon' => ['M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2', 'M9 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8', 'M22 21v-2a4 4 0 0 0-3-3.87', 'M16 3.13a4 4 0 0 1 0 7.75'],
        ],
        [
            'route' => 'bachelier.parcours.index',
            'match' => ['bachelier.parcours*'],
            'label' => 'PARCOURS',
            'icon' => ['M6 9H4.5a2.5 2.5 0 0 1 0-5H6', 'M18 9h1.5a2.5 2.5 0 0 0 0-5H18', 'M4 22h16', 'M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22', 'M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22', 'M18 2H6v7a6 6 0 0 0 12 0z'],
        ],
        [
            'route' => 'bachelier.dotations',
            'match' => ['bachelier.dotations'],
            'label' => 'MES DOTATIONS',
            'icon' => ['M20 12v10H4V12', 'M2 7h20v5H2z', 'M12 22V7', 'M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7', 'M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7'],
        ],
        [
            'route' => 'bachelier.profile',
            'match' => ['bachelier.profile'],
            'label' => 'PARAMÈTRES',
            'icon' => ['M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z', 'M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6'],
        ],
    ];

    $railBase = 'display:flex; align-items:center; gap:var(--space-1-5); min-height:44px; padding:var(--space-1) var(--space-2); border-left:3px solid transparent; color:var(--brand-text); text-decoration:none; transition:opacity var(--duration-fast) var(--easing), background-color var(--duration-fast) var(--easing);';
    $railActive = 'border-left-color:var(--accent-highlight); background:color-mix(in srgb, var(--brand-text) 10%, transparent); font-weight:var(--font-semibold); opacity:1;';
    $railIdle = 'opacity:.8;';
@endphp

<div x-data="{
        sidebarOpen: false,
        isDesktop: window.matchMedia('(min-width: 1024px)').matches,
        init() {
            window.matchMedia('(min-width: 1024px)').addEventListener('change', (e) => {
                this.isDesktop = e.matches;
                if (e.matches) { this.sidebarOpen = false; }
            });
        },
        sync() {
            if (!this.$refs.drawer || !this.$refs.main) { return; }
            /* Tiroir ferme sur mobile : retire de l ordre de tabulation.
               Tiroir ouvert sur mobile : le contenu principal devient inert, ce qui
               piege le focus dans le tiroir sans plugin ni boucle JS. */
            this.$refs.drawer.toggleAttribute('inert', !this.isDesktop && !this.sidebarOpen);
            this.$refs.main.toggleAttribute('inert', !this.isDesktop && this.sidebarOpen);
        },
        /* sync() est appele explicitement avant de deplacer le focus : sans cela la cible
           est encore inert au moment du $nextTick et focus() echoue en silence. */
        open() { this.sidebarOpen = true; this.sync(); this.$nextTick(() => this.$refs.closeButton?.focus()); },
        close() { this.sidebarOpen = false; this.sync(); this.$nextTick(() => this.$refs.menuButton?.focus()); }
     }"
     x-effect="sync()"
     @keydown.escape.window="if (sidebarOpen) { close() }"
     class="min-h-screen flex">

    {{-- Voile mobile --}}
    <div x-show="sidebarOpen"
         @click="close()"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9998] lg:hidden"
         style="background: var(--overlay-scrim)"
         aria-hidden="true"></div>

    {{-- Rail de navigation --}}
    <div x-ref="drawer"
         id="bachelier-navigation"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
         class="bachelier-sidebar fixed inset-y-0 left-0 z-[9999] w-64 transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col h-screen"
         style="background: var(--brand-gradient-from); color: var(--brand-text)">

        {{-- Logo --}}
        <div class="flex items-center justify-between h-16 px-6 flex-shrink-0"
             style="border-bottom: 1px solid color-mix(in srgb, var(--brand-text) 15%, transparent)">
            <a href="{{ route('bachelier.dashboard') }}" class="flex items-center" style="color: inherit">
                <img class="h-10 w-auto" src="{{ asset('images/logo_ansut_white.png') }}" alt="ANSUT" width="120" height="40">
            </a>
            <button type="button"
                    x-ref="closeButton"
                    @click="close()"
                    class="lg:hidden"
                    style="display:grid; place-items:center; width:44px; height:44px; color:inherit; background:none; border:0">
                <span class="sr-only">Fermer la navigation</span>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>

        {{-- Entrees de navigation --}}
        <nav class="flex-1 px-2 py-4 overflow-y-auto" aria-label="Navigation principale">
            @foreach ($navItems as $item)
                @php $isActive = request()->routeIs(...$item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   @if ($isActive) aria-current="page" @endif
                   style="{{ $railBase }} {{ $isActive ? $railActive : $railIdle }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0">
                        @foreach ($item['icon'] as $d)<path d="{{ $d }}"/>@endforeach
                    </svg>
                    <span style="font-size: var(--text-caption); font-weight: inherit">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        {{-- Deconnexion --}}
        <div class="px-2 py-3 flex-shrink-0"
             style="border-top: 1px solid color-mix(in srgb, var(--brand-text) 15%, transparent)">
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit"
                        style="{{ $railBase }} {{ $railIdle }} width:100%; background:none; border-left:3px solid transparent; cursor:pointer; text-align:left">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" style="flex-shrink:0">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>
                    </svg>
                    <span style="font-size: var(--text-caption)">DÉCONNEXION</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Contenu principal --}}
    <div x-ref="main" class="flex-1 flex flex-col min-w-0 lg:ml-64">

        <header style="background: var(--brand-gradient-from); color: var(--brand-text)">
            <div class="flex items-center justify-between px-4 lg:px-8 h-16 gap-2">

                <button type="button"
                        x-ref="menuButton"
                        @click="open()"
                        :aria-expanded="sidebarOpen ? 'true' : 'false'"
                        aria-controls="bachelier-navigation"
                        class="lg:hidden"
                        style="display:grid; place-items:center; width:44px; height:44px; color:inherit; background:none; border:0; flex-shrink:0">
                    <span class="sr-only">Ouvrir la navigation</span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/>
                    </svg>
                </button>

                {{-- Identite de session. Ce n est plus un h1 : ce bandeau est identique sur
                     les 26 pages de l espace, le h1 appartient desormais a chaque vue. --}}
                <p class="flex-1 hidden lg:block lg:ml-8" style="font-size: var(--text-body)">
                    Bienvenue {{ auth()->user()->bachelier->nom ?? 'Utilisateur' }}, lauréat PEUB {{ auth()->user()->bachelier->annee_bac ?? 'N/A' }}
                </p>

                <div class="flex items-center gap-1 lg:gap-2" style="flex-shrink:0">

                    {{-- Champ de recherche retire le 20/08/2026 : il n avait ni formulaire,
                         ni attribut name, ni route de traitement. Il promettait une fonction
                         inexistante. A retablir le jour ou la recherche sera implementee
                         cote serveur. --}}

                    <a href="{{ route('bachelier.inbox.index') }}" style="display:grid; place-items:center; width:44px; height:44px; color:inherit">
                        <span class="sr-only">Messagerie</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="m22 6-10 7L2 6"/>
                        </svg>
                    </a>
                    <a href="{{ route('notifications.index') }}" style="display:grid; place-items:center; width:44px; height:44px; color:inherit">
                        <span class="sr-only">Notifications</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M10.268 21a2 2 0 0 0 3.464 0"/><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/>
                        </svg>
                    </a>
                    <a href="{{ route('bachelier.profile') }}" style="display:grid; place-items:center; width:44px; height:44px; color:inherit">
                        <span class="sr-only">Paramètres</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><path d="M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto">
            @include('components.flash-messages')
            @yield('content')
        </main>
    </div>
</div>
