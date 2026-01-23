@if(auth()->check())
<div class="relative" x-data="{ open: false }" @close-dropdowns.window="open = false">
    <button @click="open = !open" class="flex items-center space-x-3 text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->email) }}&color=7C3AED&background=EDE9FE" alt="Avatar">
        <div class="text-left">
            <p class="text-sm font-medium text-gray-900 dark:text-white">
                @if(auth()->user()->role === 'bachelier' && auth()->user()->bachelier)
                    {{ auth()->user()->bachelier->prenoms ?? 'Utilisateur' }}
                @elseif(auth()->user()->role === 'partenaire' && auth()->user()->partenaire)
                    {{ auth()->user()->partenaire->nom_organisation ?? 'Organisation' }}
                @else
                    Administrateur
                @endif
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ ucfirst(auth()->user()->role) }}
                @if(auth()->user()->role === 'bachelier' && auth()->user()->bachelier?->boursier_peub)
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 ml-1 rounded-full">
                        Boursier
                    </span>
                @endif
            </p>
        </div>
        <i data-lucide="chevron-down" class="h-4 w-4 text-gray-400 transition-transform duration-150" :class="{ 'rotate-180': open }"></i>
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         @click.away="open = false" 
         class="absolute right-0 mt-2 w-56 bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50 dark:bg-gray-800 rounded-md">
        <div class="py-1">
            @if(auth()->user()->role === 'bachelier')
                <a href="{{ route('bachelier.profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <i data-lucide="user" class="h-4 w-4 mr-3"></i>
                        Mon Profil
                    </div>
                </a>
                 <a href="{{ route('bachelier.parcours.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <i data-lucide="graduation-cap" class="h-4 w-4 mr-3"></i>
                        Parcours de Formation
                    </div>
                </a>
                @if(auth()->user()->bachelier?->boursier_peub)
                    <a href="{{ route('bachelier.dotations') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center">
                            <i data-lucide="gift" class="h-4 w-4 mr-3"></i>
                            Mes Dotations
                        </div>
                    </a>
                @endif
            @elseif(auth()->user()->role === 'partenaire')
                <a href="{{ route('partenaire.profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <i data-lucide="building-2" class="h-4 w-4 mr-3"></i>
                        Profil Organisation
                    </div>
                </a>
            @endif
            
            <div class="border-t border-gray-100 dark:border-gray-700"></div>
            
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex items-center">
                    <i data-lucide="settings" class="h-4 w-4 mr-3"></i>
                    Paramètres
                </div>
            </a>
            
            <div class="border-t border-gray-100 dark:border-gray-700"></div>
            
            <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <i data-lucide="log-out" class="h-4 w-4 mr-3"></i>
                        Déconnexion
                    </div>
                </button>
            </form>
        </div>
    </div>
</div>
@endif