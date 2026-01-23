<nav class="bg-white shadow-sm border-b border-gray-200" x-data="{ 
    mobileMenuOpen: false,
    toggleMobileMenu() {
        this.mobileMenuOpen = !this.mobileMenuOpen;
    }
}">
    <div class="max-w-7xl mx-auto px-3 md:px-6 lg:px-8">
        <div class="flex justify-between items-center h-12 md:h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <img class="h-8 w-auto" src="{{ asset('images/logo_ansut.png') }}" alt="ANSUT" />
                </a>
            </div>
                
            <!-- Desktop Navigation Links - Centered -->
            <div class="hidden md:flex md:flex-1 md:justify-center">
                <div class="flex md:space-x-8">
                    @if(auth()->check() && auth()->user()->role === 'bachelier')
                        <!-- Opportunités -->
                        <a href="{{ route('bachelier.opportunites') }}" 
                           class="px-3 py-2 text-sm font-medium transition-colors @if(request()->routeIs('bachelier.opportunites*') || request()->routeIs('bachelier.favoris*') || request()->routeIs('bachelier.candidatures*')) text-primary-600 @else text-black hover:text-black @endif">
                            Opportunités
                        </a>
                        
                        <!-- Bibliothèque -->
                        <a href="{{ route('bachelier.library.index') }}" 
                           class="px-3 py-2 text-sm font-medium transition-colors @if(request()->routeIs('bachelier.library*')) text-primary-600 @else text-black hover:text-black @endif">
                            Bibliothèque
                        </a>

                        <!-- Communauté -->
                        <a href="{{ route('bachelier.forum.index') }}" 
                           class="px-3 py-2 text-sm font-medium transition-colors @if(request()->routeIs('bachelier.forum*')) text-primary-600 @else text-black hover:text-black @endif">
                            Communauté
                        </a>
                    @elseif(auth()->check() && auth()->user()->role === 'partenaire')
                        <a href="{{ route('partenaire.dashboard') }}" 
                           class="text-black hover:text-black px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('partenaire.dashboard') ? 'text-primary-600' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('partenaire.opportunites.index') }}" 
                           class="text-black hover:text-black px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('partenaire.opportunites*') ? 'text-primary-600' : '' }}">
                            Mes opportunités
                        </a>
                        <a href="{{ route('partenaire.candidatures.index') }}"
                           class="text-black hover:text-black px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('partenaire.candidatures*') ? 'text-primary-600' : '' }}">
                            Candidatures
                        </a>
                        <a href="{{ route('partenaire.analytics') }}" 
                           class="text-black hover:text-black px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('partenaire.analytics*') ? 'text-primary-600' : '' }}">
                            Analytics
                        </a>
                    @elseif(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" 
                           class="text-black hover:text-black px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-primary-600' : '' }}">
                            Administration
                        </a>
                        <a href="{{ route('admin.bacheliers.index') }}" 
                           class="text-black hover:text-black px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.bacheliers*') ? 'text-primary-600' : '' }}">
                            Bacheliers
                        </a>
                        <a href="{{ route('admin.partenaires.index') }}" 
                           class="text-black hover:text-black px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.partenaires*') ? 'text-primary-600' : '' }}">
                            Partenaires
                        </a>
                        <a href="{{ route('admin.opportunites.index') }}" 
                           class="text-black hover:text-black px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.opportunites*') ? 'text-primary-600' : '' }}">
                            Opportunités
                        </a>
                        <a href="{{ route('admin.dotations.index') }}" 
                           class="text-black hover:text-black px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.dotations*') ? 'text-primary-600' : '' }}">
                            Dotations
                        </a>
                        <a href="{{ route('admin.library.resources.index') }}" 
                           class="text-black hover:text-black px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.library*') ? 'text-primary-600' : '' }}">
                            Bibliothèque
                        </a>
                    @endif
                    </div>
                </div>

            <!-- Right side - Hidden on Mobile -->
            <div class="hidden md:flex md:items-center md:space-x-4">
                <!-- Quick Actions based on role -->
                @if(auth()->check() && auth()->user()->role === 'partenaire')
                    <a href="{{ route('partenaire.opportunites.create') }}" 
                       class="bg-secondary-500 hover:bg-secondary-600 text-white px-4 py-2 text-sm font-medium transition-colors rounded-md">
                        Créer Opportunité
                    </a>
                @endif
                
                @if(auth()->check() && auth()->user()->role === 'bachelier')
                    <!-- Inbox Icon -->
                    <a href="{{ route('bachelier.inbox.index') }}" 
                       class="text-gray-900 hover:text-primary-600 p-2 rounded-md transition-colors {{ request()->routeIs('bachelier.inbox*') ? 'text-primary-600 bg-primary-50' : '' }}"
                       title="Messages">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </a>
                @endif
                
                <!-- Notifications Icon -->
                @include('components.notifications-dropdown')
                
                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" @close-dropdowns.window="open = false">
                    @include('components.profile-dropdown')
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="toggleMobileMenu()" class="text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition-transform duration-200" :class="{ 'rotate-90': mobileMenuOpen }">
                    <i data-lucide="menu" class="h-6 w-6" x-show="!mobileMenuOpen"></i>
                    <i data-lucide="x" class="h-6 w-6" x-show="mobileMenuOpen"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-1"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-1"
         class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50 border-t border-gray-200">
            <!-- User Info Section -->
            <div class="flex items-center px-3 py-3 bg-white mb-3 shadow-sm">
                @if(auth()->check())
                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->email) }}&color=7C3AED&background=EDE9FE" alt="Avatar">
                @endif
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">
                        @if(auth()->check() && auth()->user()->role === 'bachelier' && auth()->user()->bachelier)
                            {{ auth()->user()->bachelier->prenoms ?? 'Utilisateur' }}
                        @elseif(auth()->check() && auth()->user()->role === 'partenaire' && auth()->user()->partenaire)
                            {{ auth()->user()->partenaire->nom_organisation ?? 'Organisation' }}
                        @else
                            Administrateur
                        @endif
                    </p>
                    <p class="text-xs text-gray-500">
                        @if(auth()->check())
                        {{ ucfirst(auth()->user()->role) }}
                        @if(auth()->user()->role === 'bachelier' && auth()->user()->bachelier?->boursier_peub)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 ml-1 rounded-full">
                                Boursier
                            </span>
                        @endif
                        @endif
                    </p>
                </div>
            </div>

            <!-- Navigation Links -->
            @if(auth()->check() && auth()->user()->role === 'bachelier')
                <a href="{{ route('bachelier.opportunites') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('bachelier.opportunites*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="briefcase" class="w-5 h-5 mr-3"></i>
                        Opportunités
                    </div>
                </a>
                <a href="{{ route('bachelier.favoris') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('bachelier.favoris*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="heart" class="w-5 h-5 mr-3"></i>
                        Mes Favoris
                    </div>
                </a>
                <a href="{{ route('bachelier.candidatures') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('bachelier.candidatures*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="file-text" class="w-5 h-5 mr-3"></i>
                        Candidatures
                    </div>
                </a>
                <a href="{{ route('bachelier.library.index') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('bachelier.library*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="book-open" class="w-5 h-5 mr-3"></i>
                        Bibliothèque
                    </div>
                </a>
                <a href="{{ route('bachelier.library.favorites') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('bachelier.library.favorites') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="heart" class="w-5 h-5 mr-3"></i>
                        Favoris Bibliothèque
                    </div>
                </a>
                <a href="{{ route('bachelier.forum.index') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('bachelier.forum*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="message-circle" class="w-5 h-5 mr-3"></i>
                        Communauté
                    </div>
                </a>
                <!-- No dedicated forum favorites route; keep main link only for now -->
                <a href="{{ route('bachelier.inbox.index') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('bachelier.inbox*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="mail" class="w-5 h-5 mr-3"></i>
                        Inbox
                    </div>
                </a>
            @elseif(auth()->check() && auth()->user()->role === 'partenaire')
                <a href="{{ route('partenaire.dashboard') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('partenaire.dashboard') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                        Dashboard
                    </div>
                </a>
                <a href="{{ route('partenaire.opportunites.index') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('partenaire.opportunites*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="briefcase" class="w-5 h-5 mr-3"></i>
                        Mes Opportunités
                    </div>
                </a>
                <a href="{{ route('partenaire.candidatures.index') }}"
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('partenaire.candidatures*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                        Candidatures
                    </div>
                </a>
                <a href="{{ route('partenaire.analytics') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('partenaire.analytics*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                        Analytics
                    </div>
                </a>
            @elseif(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                        Administration
                    </div>
                </a>
                <a href="{{ route('admin.bacheliers.index') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('admin.bacheliers*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="graduation-cap" class="w-5 h-5 mr-3"></i>
                        Bacheliers
                    </div>
                </a>
                <a href="{{ route('admin.partenaires.index') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('admin.partenaires*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="handshake" class="w-5 h-5 mr-3"></i>
                        Partenaires
                    </div>
                </a>
                <a href="{{ route('admin.opportunites.index') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('admin.opportunites*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="megaphone" class="w-5 h-5 mr-3"></i>
                        Opportunités
                    </div>
                </a>
                <a href="{{ route('admin.dotations.index') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('admin.dotations*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="gift" class="w-5 h-5 mr-3"></i>
                        Dotations
                    </div>
                </a>
                <a href="{{ route('admin.library.resources.index') }}" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('admin.library*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <i data-lucide="book-open" class="w-5 h-5 mr-3"></i>
                        Bibliothèque
                    </div>
                </a>
            @endif
            
            <!-- Profile Section -->
            <div class="border-t border-gray-200 pt-3 mt-3">
                @if(auth()->check() && auth()->user()->role === 'bachelier')
                    <a href="{{ route('bachelier.profile') }}" 
                       class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors">
                        <div class="flex items-center">
                            <i data-lucide="user" class="w-5 h-5 mr-3"></i>
                            Mon Profil
                        </div>
                    </a>
                    @if(auth()->user()->bachelier?->boursier_peub)
                        <a href="{{ route('bachelier.dotations') }}" 
                           class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors">
                            <div class="flex items-center">
                                <i data-lucide="gift" class="w-5 h-5 mr-3"></i>
                                Mes Dotations
                            </div>
                        </a>
                    @endif
                @elseif(auth()->check() && auth()->user()->role === 'partenaire')
                    <a href="{{ route('partenaire.profile') }}" 
                       class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors">
                        <div class="flex items-center">
                            <i data-lucide="building-2" class="w-5 h-5 mr-3"></i>
                            Profil Organisation
                        </div>
                    </a>
                @endif
                
                <!-- Settings -->
                <a href="#" 
                   class="block px-3 py-2 text-base font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-100 rounded-md transition-colors">
                    <div class="flex items-center">
                        <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                        Paramètres
                    </div>
                </a>
            </div>
            
            <!-- Mobile Quick Actions -->
            <div class="border-t border-gray-200 pt-3 mt-3">
                @if(auth()->check() && auth()->user()->role === 'partenaire')
                    <a href="{{ route('partenaire.opportunites.create') }}" 
                       class="block mx-3 my-2 px-4 py-3 text-center text-sm font-medium bg-secondary-500 text-white hover:bg-secondary-600 transition-colors rounded-lg">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Créer Opportunité
                        </div>
                    </a>
                @endif
                
                <!-- Logout -->
                <form method="POST" action="{{ route('auth.logout') }}" class="mx-3 mt-2">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors rounded-lg">
                        <i data-lucide="log-out" class="w-5 h-5 mr-2"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Tab Bar for Bachelier -->
@if(auth()->check() && auth()->user()->role === 'bachelier')
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50" style="padding-bottom: env(safe-area-inset-bottom);">
    <div class="flex items-center justify-around py-2">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="flex flex-col items-center py-2 px-3 min-w-0 flex-1 {{ request()->routeIs('dashboard') ? 'text-primary-600' : 'text-gray-500' }}">
            <div class="relative">
                <i data-lucide="home" class="w-5 h-5 mb-1"></i>
                @if(request()->routeIs('dashboard'))
                <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary-600 rounded-full"></div>
                @endif
            </div>
            <span class="text-xs font-medium truncate">Accueil</span>
        </a>

        <!-- Opportunités -->
        <a href="{{ route('bachelier.opportunites') }}" 
           class="flex flex-col items-center py-2 px-3 min-w-0 flex-1 {{ request()->routeIs('bachelier.opportunites*') || request()->routeIs('bachelier.favoris*') || request()->routeIs('bachelier.candidatures*') ? 'text-primary-600' : 'text-gray-500' }}">
            <div class="relative">
                <i data-lucide="briefcase" class="w-5 h-5 mb-1"></i>
                @if(request()->routeIs('bachelier.opportunites*') || request()->routeIs('bachelier.favoris*') || request()->routeIs('bachelier.candidatures*'))
                <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary-600 rounded-full"></div>
                @endif
            </div>
            <span class="text-xs font-medium truncate">Opportunités</span>
        </a>

        <!-- Bibliothèque -->
        <a href="{{ route('bachelier.library.index') }}" 
           class="flex flex-col items-center py-2 px-3 min-w-0 flex-1 {{ request()->routeIs('bachelier.library*') ? 'text-primary-600' : 'text-gray-500' }}">
            <div class="relative">
                <i data-lucide="book-open" class="w-5 h-5 mb-1"></i>
                @if(request()->routeIs('bachelier.library*'))
                <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary-600 rounded-full"></div>
                @endif
            </div>
            <span class="text-xs font-medium truncate">Bibliothèque</span>
        </a>

        <!-- Communauté -->
        <a href="{{ route('bachelier.forum.index') }}" 
           class="flex flex-col items-center py-2 px-3 min-w-0 flex-1 {{ request()->routeIs('bachelier.forum*') ? 'text-primary-600' : 'text-gray-500' }}">
            <div class="relative">
                <i data-lucide="users" class="w-5 h-5 mb-1"></i>
                @if(request()->routeIs('bachelier.forum*'))
                <div class="absolute -top-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary-600 rounded-full"></div>
                @endif
            </div>
            <span class="text-xs font-medium truncate">Communauté</span>
        </a>

    </div>
</div>

<!-- Mobile Bottom Padding for Content -->
<div class="md:hidden h-16"></div>
@endif 