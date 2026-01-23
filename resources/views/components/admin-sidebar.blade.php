<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300 ease-in-out"
     :class="sidebarOpen ? 'w-64' : 'w-16'" 
     x-data="{ 
         gestionMenuOpen: {{ request()->routeIs('admin.bacheliers*', 'admin.partenaires*', 'admin.administrators*') ? 'true' : 'false' }},
         bachelierYearsOpen: false,
         opportunitesMenuOpen: {{ request()->routeIs('admin.opportunites*', 'admin.dotations*') ? 'true' : 'false' }},
         articlesMenuOpen: {{ request()->routeIs('admin.articles*') ? 'true' : 'false' }},
         analyticsMenuOpen: {{ request()->routeIs('admin.analytics*', 'admin.reports*') ? 'true' : 'false' }}
     }">
    
    <!-- Sidebar Background -->
    <div class="flex flex-col flex-1 bg-primary-800 overflow-hidden">
        
        <!-- Logo Section -->
        <div class="flex items-center h-16 px-4 bg-primary-900">
            <div class="flex items-center w-full">
                <!-- Logo complet quand sidebar ouvert -->
                <div x-show="sidebarOpen" x-transition class="flex items-center">
                    <img class="h-16 w-auto" src="{{ asset('images/logo_ansut_white.png') }}" alt="ANSUT" />
                    <span class="ml-3 text-white font-semibold text-lg">
                        Admin
                    </span>
                </div>
                <!-- Favicon quand sidebar réduit -->
                <div x-show="!sidebarOpen" x-transition class="flex items-center justify-center w-full">
                    <img class="h-8 w-8" src="{{ asset('favicon.png') }}" alt="ANSUT" />
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
            
            <!-- Dashboard -->
            @if(auth()->user()->hasAdminPermission('dashboard.view'))
                <a href="{{ route('admin.dashboard') }}" 
                   class="group flex items-center px-2 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary-700' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 text-primary-200 group-hover:text-white"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Tableau de Bord</span>
                </a>
            @endif

            <!-- Gestion des Utilisateurs -->
            @if(auth()->user()->hasAdminPermission('users.bacheliers.view') || auth()->user()->hasAdminPermission('users.partenaires.view') || auth()->user()->hasAdminPermission('users.administrators.view'))
                <div class="space-y-1">
                    <button @click="gestionMenuOpen = !gestionMenuOpen"
                            class="group w-full flex items-center justify-between px-2 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.bacheliers*', 'admin.partenaires*', 'admin.administrators*') ? 'bg-primary-700' : '' }}">
                        <div class="flex items-center">
                            <i data-lucide="users" class="w-5 h-5 text-primary-200 group-hover:text-white"></i>
                            <span x-show="sidebarOpen" x-transition class="ml-3">Gestion Utilisateurs</span>
                        </div>
                        <i x-show="sidebarOpen" data-lucide="chevron-down" class="w-4 h-4 text-primary-200 transform transition-transform" :class="gestionMenuOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <!-- Sous-menu Gestion -->
                    <div x-show="gestionMenuOpen && sidebarOpen" x-transition class="ml-6 space-y-1">
                        @if(auth()->user()->hasAdminPermission('users.bacheliers.view'))
                            <div class="space-y-1">
                                <button @click="bachelierYearsOpen = !bachelierYearsOpen"
                                        class="group w-full flex items-center justify-between px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.bacheliers*') ? 'text-white bg-primary-600' : '' }}">
                                    <div class="flex items-center">
                                        <i data-lucide="graduation-cap" class="w-4 h-4 mr-3"></i>
                                        Bacheliers
                                    </div>
                                    <i data-lucide="chevron-down" class="w-3 h-3 text-primary-200 transform transition-transform" :class="bachelierYearsOpen ? 'rotate-180' : ''"></i>
                                </button>
                                
                                <!-- Sous-menu années -->
                                <div x-show="bachelierYearsOpen" x-transition class="ml-6 space-y-1">
                                    <a href="{{ route('admin.bacheliers.index') }}" 
                                       class="group flex items-center px-2 py-2 text-xs text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.bacheliers.index') ? 'text-white bg-primary-600' : '' }}">
                                        <i data-lucide="list" class="w-3 h-3 mr-3"></i>
                                        Tous les bacheliers
                                    </a>
                                    
                                    <a href="{{ route('admin.bacheliers.bareme') }}" 
                                       class="group flex items-center px-2 py-2 text-xs text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.bacheliers.bareme') ? 'text-white bg-primary-600' : '' }}">
                                        <i data-lucide="award" class="w-3 h-3 mr-3"></i>
                                        Barème
                                    </a>
                                    
                                    @php
                                        $years = \App\Models\Bachelier::distinct()->orderBy('annee_bac', 'desc')->pluck('annee_bac')->filter();
                                    @endphp
                                    
                                    @forelse($years as $year)
                                        <a href="{{ route('admin.bacheliers.by-year', $year) }}" 
                                           class="group flex items-center px-2 py-2 text-xs text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.bacheliers.by-year') && request()->route('year') == $year ? 'text-white bg-primary-600' : '' }}">
                                            <i data-lucide="calendar" class="w-3 h-3 mr-3"></i>
                                            Année {{ $year }}
                                        </a>
                                    @empty
                                        <div class="px-2 py-2 text-xs text-primary-200">Aucune année disponible</div>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                        @if(auth()->user()->hasAdminPermission('users.partenaires.view'))
                            <a href="{{ route('admin.partenaires.index') }}" 
                               class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.partenaires*') ? 'text-white bg-primary-600' : '' }}">
                                <i data-lucide="building" class="w-4 h-4 mr-3"></i>
                                Partenaires
                            </a>
                        @endif
                        @if(auth()->user()->hasAdminPermission('users.administrators.view'))
                            <a href="{{ route('admin.administrators.index') }}" 
                               class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.administrators*') ? 'text-white bg-primary-600' : '' }}">
                                <i data-lucide="shield" class="w-4 h-4 mr-3"></i>
                                Administrateurs
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Visualisation Boursiers -->
            @if(auth()->user()->hasAdminPermission('boursiers.view'))
                <a href="{{ route('admin.boursiers.map') }}" 
                   class="group flex items-center px-2 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.boursiers.map') ? 'bg-primary-700' : '' }}">
                    <i data-lucide="map-pin" class="w-5 h-5 text-primary-200 group-hover:text-white"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Carte des Boursiers</span>
                </a>
            @endif

            <!-- Opportunités & Dotations -->
            @if(auth()->user()->hasAdminPermission('opportunities.view') || auth()->user()->hasAdminPermission('candidatures.view') || auth()->user()->hasAdminPermission('dotations.view') || auth()->user()->hasAdminPermission('inventaire.view') || auth()->user()->hasAdminPermission('fournisseurs.view'))
                <div class="space-y-1">
                    <button @click="opportunitesMenuOpen = !opportunitesMenuOpen"
                            class="group w-full flex items-center justify-between px-2 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.opportunites*', 'admin.dotations*') ? 'bg-primary-700' : '' }}">
                        <div class="flex items-center">
                            <i data-lucide="target" class="w-5 h-5 text-primary-200 group-hover:text-white"></i>
                            <span x-show="sidebarOpen" x-transition class="ml-3">Opportunités</span>
                        </div>
                        <i x-show="sidebarOpen" data-lucide="chevron-down" class="w-4 h-4 text-primary-200 transform transition-transform" :class="opportunitesMenuOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <!-- Sous-menu Opportunités -->
                    <div x-show="opportunitesMenuOpen && sidebarOpen" x-transition class="ml-6 space-y-1">
                        @if(auth()->user()->hasAdminPermission('opportunities.view'))
                            <a href="{{ route('admin.opportunites.index') }}" 
                               class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.opportunites*') ? 'text-white bg-primary-600' : '' }}">
                                <i data-lucide="briefcase" class="w-4 h-4 mr-3"></i>
                                Gérer Opportunités
                            </a>
                        @endif
                        @if(auth()->user()->hasAdminPermission('candidatures.view'))
                            <a href="{{ route('admin.candidatures.index') }}" 
                               class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.candidatures*') ? 'text-white bg-primary-600' : '' }}">
                                <i data-lucide="file-text" class="w-4 h-4 mr-3"></i>
                                Candidatures
                            </a>
                        @endif
                        @if(auth()->user()->hasAdminPermission('dotations.view'))
                            <a href="{{ route('admin.dotations.index') }}" 
                               class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.dotations.index', 'admin.dotations.show') ? 'text-white bg-primary-600' : '' }}">
                                <i data-lucide="gift" class="w-4 h-4 mr-3"></i>
                                Attributions
                            </a>
                        @endif
                        @if(auth()->user()->hasAdminPermission('inventaire.view'))
                            <a href="{{ route('admin.dotations.inventaire.index') }}" 
                               class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.dotations.inventaire*') ? 'text-white bg-primary-600' : '' }}">
                                <i data-lucide="package" class="w-4 h-4 mr-3"></i>
                                Inventaire
                            </a>
                        @endif
                        @if(auth()->user()->hasAdminPermission('fournisseurs.view'))
                            <a href="{{ route('admin.dotations.fournisseurs.index') }}" 
                               class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.dotations.fournisseurs*') ? 'text-white bg-primary-600' : '' }}">
                                <i data-lucide="truck" class="w-4 h-4 mr-3"></i>
                                Fournisseurs
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Gestion des Articles -->
            @if(auth()->user()->hasAdminPermission('articles.view'))
                <div class="space-y-1">
                    <button @click="articlesMenuOpen = !articlesMenuOpen"
                            class="group w-full flex items-center justify-between px-2 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.articles*') ? 'bg-primary-700' : '' }}">
                        <div class="flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 text-primary-200 group-hover:text-white"></i>
                            <span x-show="sidebarOpen" x-transition class="ml-3">Articles</span>
                        </div>
                        <i x-show="sidebarOpen" data-lucide="chevron-down" class="w-4 h-4 text-primary-200 transform transition-transform" :class="articlesMenuOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <!-- Sous-menu Articles -->
                    <div x-show="articlesMenuOpen && sidebarOpen" x-transition class="ml-6 space-y-1">
                        @if(auth()->user()->hasAdminPermission('articles.view'))
                            <a href="{{ route('admin.articles.index') }}" 
                               class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.articles.index', 'admin.articles.show', 'admin.articles.edit') ? 'text-white bg-primary-600' : '' }}">
                                <i data-lucide="list" class="w-4 h-4 mr-3"></i>
                                Tous les Articles
                            </a>
                        @endif

                        @if(auth()->user()->hasAdminPermission('articles.analytics'))
                            <a href="{{ route('admin.articles.analytics') }}" 
                               class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.articles.analytics') ? 'text-white bg-primary-600' : '' }}">
                                <i data-lucide="trending-up" class="w-4 h-4 mr-3"></i>
                                Analytics Articles
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Bibliothèque -->
            @if(auth()->user()->hasAdminPermission('library.view'))
                <div class="space-y-1">
                    <button @click="articlesMenuOpen = false; analyticsMenuOpen = false; gestionMenuOpen = false; opportunitesMenuOpen = false"
                            class="group w-full flex items-center justify-between px-2 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.library.*') ? 'bg-primary-700' : '' }}">
                        <div class="flex items-center">
                            <i data-lucide="library" class="w-5 h-5 text-primary-200 group-hover:text-white"></i>
                            <span x-show="sidebarOpen" x-transition class="ml-3">Bibliothèque</span>
                        </div>
                    </button>
                    <div x-show="sidebarOpen" class="ml-6 space-y-1">
                        <a href="{{ route('admin.library.resources.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.library.resources*') ? 'text-white bg-primary-600' : '' }}">
                            <i data-lucide="book" class="w-4 h-4 mr-3"></i>
                            Ressources
                        </a>
                        <a href="{{ route('admin.library.categories.index') }}" 
                           class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.library.categories*') ? 'text-white bg-primary-600' : '' }}">
                            <i data-lucide="folders" class="w-4 h-4 mr-3"></i>
                            Catégories
                        </a>
                    </div>
                </div>
            @endif

            <!-- Analytics & Rapports -->
            @if(auth()->user()->hasAdminPermission('analytics.view') || auth()->user()->hasAdminPermission('reports.view'))
                <div class="space-y-1">
                    <button @click="analyticsMenuOpen = !analyticsMenuOpen"
                            class="group w-full flex items-center justify-between px-2 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.analytics*', 'admin.reports*') ? 'bg-primary-700' : '' }}">
                        <div class="flex items-center">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 text-primary-200 group-hover:text-white"></i>
                            <span x-show="sidebarOpen" x-transition class="ml-3">Analytics</span>
                        </div>
                        <i x-show="sidebarOpen" data-lucide="chevron-down" class="w-4 h-4 text-primary-200 transform transition-transform" :class="analyticsMenuOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <!-- Sous-menu Analytics -->
                    <div x-show="analyticsMenuOpen && sidebarOpen" x-transition class="ml-6 space-y-1">
                        @if(auth()->user()->hasAdminPermission('analytics.view'))
                            <a href="{{ route('admin.analytics') }}" 
                               class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.analytics') ? 'text-white bg-primary-600' : '' }}">
                                <i data-lucide="trending-up" class="w-4 h-4 mr-3"></i>
                                Analytics Avancées
                            </a>
                        @endif
                        @if(auth()->user()->hasAdminPermission('reports.view'))
                            <a href="{{ route('admin.reports') }}" 
                               class="group flex items-center px-2 py-2 text-sm text-primary-100 hover:text-white hover:bg-primary-700 transition-colors {{ request()->routeIs('admin.reports') ? 'text-white bg-primary-600' : '' }}">
                                <i data-lucide="file-text" class="w-4 h-4 mr-3"></i>
                                Rapports
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Séparateur -->
            <div class="border-t border-primary-700 my-4"></div>

            {{-- Messages temporairement désactivé --}}
            {{-- @if(auth()->user()->hasAdminPermission('messages.view'))
                <a href="#" 
                   class="group flex items-center px-2 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                    <i data-lucide="message-circle" class="w-5 h-5 text-primary-200 group-hover:text-white"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Messages</span>
                    <span x-show="sidebarOpen" class="ml-auto bg-secondary-500 text-white text-xs px-2 py-1">3</span>
                </a>
            @endif --}}

            <!-- Paramètres -->
            @if(auth()->user()->hasAdminPermission('settings.view'))
                <a href="#" 
                   class="group flex items-center px-2 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                    <i data-lucide="settings" class="w-5 h-5 text-primary-200 group-hover:text-white"></i>
                    <span x-show="sidebarOpen" x-transition class="ml-3">Paramètres</span>
                </a>
            @endif

            <!-- Landing Page -->
            <a href="{{ route('landing') }}" 
               class="group flex items-center px-2 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                <i data-lucide="home" class="w-5 h-5 text-primary-200 group-hover:text-white"></i>
                <span x-show="sidebarOpen" x-transition class="ml-3">Accueil Public</span>
            </a>
        </nav>

        <!-- Bottom Section -->
        <div class="flex-shrink-0 p-4 border-t border-primary-700">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-secondary-500 flex items-center justify-center text-white font-medium text-sm">
                        {{ strtoupper(substr(auth()->user()->email, 0, 1)) }}
                    </div>
                </div>
                <div x-show="sidebarOpen" x-transition class="ml-3">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->email }}</p>
                    <p class="text-xs text-primary-200">
                        @if(auth()->user()->hasAdminRole('super_admin'))
                            Super Administrateur
                        @else
                            Administrateur
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Sidebar Overlay -->
<div x-show="sidebarOpen" 
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
     @click="sidebarOpen = false">
</div> 