<nav class="bg-white shadow-sm border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-3 md:px-6 lg:px-8">
        <div class="flex justify-between items-center h-12 md:h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('landing') }}" class="flex items-center">
                    <img class="h-8 w-auto" src="{{ asset('images/logo_ansut.png') }}" alt="ANSUT" />
                </a>
            </div>

            <!-- Center Navigation Links -->
            <div class="hidden md:flex items-center space-x-3">
                <a href="{{ route('landing') }}#about"
                   class="bg-[#0E7490] hover:bg-cyan-800 text-white px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 hover:scale-105">
                    C'est quoi PEUB ?
                </a>
                <a href="{{ route('landing') }}#opportunities"
                   class="text-gray-700 hover:text-gray-900 px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:scale-105">
                    Nos opportunités
                </a>
                <a href="{{ route('actualites') }}"
                   class="text-gray-700 hover:text-gray-900 px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:scale-105 {{ request()->routeIs('actualites*') ? 'text-[#0E7490]' : '' }}">
                    Actualités
                </a>
                <a href="{{ route('partenaire.register') }}"
                   class="text-gray-700 hover:text-gray-900 px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:scale-105 {{ request()->routeIs('partenaire.register*') ? 'text-[#0E7490]' : '' }}">
                    Devenir Partenaire
                </a>
            </div>

            <!-- Right Navigation - Auth Links -->
            <div class="hidden md:flex items-center space-x-4">
                @guest
                    <a href="{{ route('auth.login') }}"
                       class="text-gray-700 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('auth.login') ? 'text-[#0E7490] font-semibold' : '' }}">
                        Se connecter
                    </a>
                    <a href="{{ route('auth.register') }}"
                       class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 text-sm font-semibold transition-all duration-300 hover:scale-105 rounded-full shadow-lg {{ request()->routeIs('auth.register') ? 'bg-orange-600' : '' }}">
                        S'inscrire
                    </a>
                @else
                    <a href="{{ 
                        auth()->user()->role === 'bachelier' ? route('bachelier.dashboard') : 
                        (auth()->user()->role === 'partenaire' ? route('partenaire.dashboard') : 
                        route('admin.dashboard')) 
                    }}" 
                       class="text-black hover:text-black px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('bachelier.dashboard') || request()->routeIs('partenaire.dashboard') || request()->routeIs('admin.dashboard') ? 'text-primary-600 font-semibold' : '' }}">
                        Mon Tableau de Bord
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-800 px-3 py-2 text-sm font-medium transition-colors">
                            Se déconnecter
                        </button>
                    </form>
                @endguest
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900 transition-transform duration-200" :class="{ 'rotate-90': mobileMenuOpen }">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!mobileMenuOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="mobileMenuOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-1"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-1"
             class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50 border-t border-gray-200">
                <a href="{{ route('landing') }}#about" 
                   class="block px-3 py-2 text-base font-medium text-black hover:text-black hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('landing') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        C'est quoi PEUB ?
                    </div>
                </a>
                <a href="{{ route('landing') }}#opportunities" 
                   class="block px-3 py-2 text-base font-medium text-black hover:text-black hover:bg-gray-100 rounded-md transition-colors">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Nos opportunités
                    </div>
                </a>
                <a href="{{ route('actualites') }}" 
                   class="block px-3 py-2 text-base font-medium text-black hover:text-black hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('actualites*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                        Actualités
                    </div>
                </a>
                <a href="{{ route('partenaire.register') }}" 
                   class="block px-3 py-2 text-base font-medium text-black hover:text-black hover:bg-gray-100 rounded-md transition-colors {{ request()->routeIs('partenaire.register*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Devenir Partenaire
                    </div>
                </a>
            </div>
            
            <!-- Mobile Auth Section -->
            <div class="border-t border-gray-200 pt-3 pb-3 bg-gray-50">
                @guest
                    <a href="{{ route('auth.login') }}" 
                       class="block px-3 py-2 mx-3 mb-2 text-base font-medium text-black hover:text-black hover:bg-primary-50 rounded-md transition-colors {{ request()->routeIs('auth.login') ? 'text-primary-600 bg-primary-50 font-semibold' : '' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Se connecter
                        </div>
                    </a>
                    <a href="{{ route('auth.register') }}"
                       class="block px-4 py-3 mx-3 text-base font-semibold bg-orange-500 text-white text-center hover:bg-orange-600 transition-all duration-300 rounded-full shadow-lg {{ request()->routeIs('auth.register') ? 'bg-orange-600' : '' }}">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            S'inscrire
                        </div>
                    </a>
                @else
                    <a href="{{ 
                        auth()->user()->role === 'bachelier' ? route('bachelier.dashboard') : 
                        (auth()->user()->role === 'partenaire' ? route('partenaire.dashboard') : 
                        route('admin.dashboard')) 
                    }}" 
                       class="block px-3 py-2 mx-3 mb-2 text-base font-medium text-primary-600 hover:text-primary-800 hover:bg-primary-50 rounded-md transition-colors">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            Mon Tableau de Bord
                        </div>
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}" class="mx-3">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-3 text-base font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors rounded-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Se déconnecter
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav> 