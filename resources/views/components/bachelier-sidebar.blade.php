{{-- Sidebar pour l'espace Bachelier --}}
<div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
    {{-- Overlay pour mobile --}}
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 bg-opacity-75 z-[9998] lg:hidden"></div>

    {{-- Sidebar --}}
    <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
         class="bachelier-sidebar fixed inset-y-0 left-0 z-[9999] w-64 bg-[#0A2540] text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col h-screen" style="border-radius: 0 !important;">

        {{-- Logo --}}
        <div class="flex items-center justify-between h-16 px-6 border-b border-white/10 flex-shrink-0">
            <a href="{{ route('bachelier.dashboard') }}" class="flex items-center">
                <img class="h-10 w-auto" src="{{ asset('images/logo_ansut_white.png') }}" alt="ANSUT" />
            </a>
            {{-- Bouton fermer pour mobile --}}
            <button @click="sidebarOpen = false" class="lg:hidden text-white">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        {{-- Menu Items --}}
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            {{-- Dashboard --}}
            <a href="{{ route('bachelier.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 transition-all duration-200 {{ request()->routeIs('bachelier.dashboard') ? 'bg-orange-500 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="font-medium text-sm">DASHBOARD</span>
            </a>

            {{-- Opportunités --}}
            <a href="{{ route('bachelier.opportunites') }}"
               class="flex items-center gap-3 px-4 py-2.5 transition-all duration-200 {{ request()->routeIs('bachelier.opportunites*', 'bachelier.candidatures*', 'bachelier.favoris*') ? 'bg-orange-500 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                <i data-lucide="compass" class="w-5 h-5"></i>
                <span class="font-medium text-sm">OPPORTUNITÉ</span>
            </a>

            {{-- Ressources (Bibliothèque) --}}
            <a href="{{ route('bachelier.library.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 transition-all duration-200 {{ request()->routeIs('bachelier.library*') ? 'bg-orange-500 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                <span class="font-medium text-sm">RESSOURCES</span>
            </a>

            {{-- Messagerie (Inbox) --}}
            <a href="{{ route('bachelier.inbox.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 transition-all duration-200 {{ request()->routeIs('bachelier.inbox*') ? 'bg-orange-500 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                <i data-lucide="mail" class="w-5 h-5"></i>
                <span class="font-medium text-sm">MESSAGERIE</span>
            </a>

            {{-- Communauté --}}
            <a href="{{ route('bachelier.forum.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 transition-all duration-200 {{ request()->routeIs('bachelier.forum*') ? 'bg-orange-500 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="font-medium text-sm">COMMUNAUTÉ</span>
            </a>

            {{-- Parcours --}}
            <a href="{{ route('bachelier.parcours.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 transition-all duration-200 {{ request()->routeIs('bachelier.parcours*') ? 'bg-orange-500 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                <i data-lucide="trophy" class="w-5 h-5"></i>
                <span class="font-medium text-sm">PARCOURS</span>
            </a>

            {{-- Mes Dotations --}}
            <a href="{{ route('bachelier.dotations') }}"
               class="flex items-center gap-3 px-4 py-2.5 transition-all duration-200 {{ request()->routeIs('bachelier.dotations') ? 'bg-orange-500 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                <i data-lucide="gift" class="w-5 h-5"></i>
                <span class="font-medium text-sm">MES DOTATIONS</span>
            </a>

            {{-- Paramètres --}}
            <a href="{{ route('bachelier.profile') }}"
               class="flex items-center gap-3 px-4 py-2.5 transition-all duration-200 {{ request()->routeIs('bachelier.profile') ? 'bg-orange-500 text-white' : 'text-white/80 hover:bg-white/5 hover:text-white' }}">
                <i data-lucide="settings" class="w-5 h-5"></i>
                <span class="font-medium text-sm">PARAMÈTRES</span>
            </a>
        </nav>

        {{-- Déconnexion --}}
        <div class="px-4 py-3 border-t border-white/10 flex-shrink-0">
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-white/80 hover:bg-red-500/20 hover:text-white transition-all duration-200 w-full">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span class="font-medium text-sm">DÉCONNEXION</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Contenu principal --}}
    <div class="flex-1 flex flex-col min-w-0 lg:ml-64">
        {{-- Header --}}
        <header class="bg-[#0A2540] text-white shadow-lg">
            <div class="flex items-center justify-between px-4 lg:px-8 h-16">
                {{-- Bouton menu mobile --}}
                <button @click="sidebarOpen = true" class="lg:hidden text-white">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                {{-- Message de bienvenue --}}
                <div class="flex-1 text-center lg:text-left lg:ml-8 hidden lg:block">
                    <h1 class="text-lg lg:text-xl font-medium">
                        Bienvenue {{ auth()->user()->bachelier->nom ?? 'Utilisateur' }}, lauréat PEUB {{ auth()->user()->bachelier->annee_bac ?? 'N/A' }}
                    </h1>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-4">
                    {{-- Barre de recherche (desktop) --}}
                    <div class="hidden lg:block">
                        <div class="relative">
                            <input type="text"
                                   placeholder="Recherche"
                                   class="bg-white/10 border border-white/20 text-white placeholder-white/60 pl-4 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 w-64">
                            <button class="absolute right-2 top-1/2 -translate-y-1/2 text-white/60 hover:text-white">
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Notifications & Messages --}}
                    <div class="flex items-center gap-3">
                        <a href="{{ route('bachelier.inbox.index') }}" class="relative p-2 hover:bg-white/10 rounded-lg transition-colors" title="Messages">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </a>
                        <a href="{{ route('notifications.index') }}" class="relative p-2 hover:bg-white/10 rounded-lg transition-colors" title="Notifications">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                        </a>
                        <a href="{{ route('bachelier.profile') }}" class="p-2 hover:bg-white/10 rounded-lg transition-colors" title="Paramètres">
                            <i data-lucide="settings" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto bg-gray-50">
            @include('components.flash-messages')
            @yield('content')
        </main>
    </div>
</div>
