<footer class="bg-white border-t-2 border-primary-200">
    <div class="max-w-7xl mx-auto py-8 md:py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 text-center md:text-left">
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center justify-center md:justify-start">
                    <img class="h-10 w-auto" src="{{ asset('images/logo_ansut.png') }}" alt="ANSUT" />
                </div>
                <p class="mt-4 text-gray-600 max-w-md mx-auto md:mx-0">
                    Projet d'Excellence Universelle pour les Bacheliers - Connecter l'excellence aux opportunités pour un avenir prometteur.
                </p>
                <div class="mt-6 flex space-x-4 justify-center md:justify-start">
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i data-lucide="linkedin" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">
                    Navigation
                </h3>
                <ul class="space-y-2">
                    @if(auth()->check())
                        @if(auth()->user()->role === 'bachelier')
                            <li><a href="{{ route('bachelier.opportunites') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('bachelier.opportunites*') ? 'text-primary-600' : '' }}">Opportunités</a></li>
                            <li><a href="{{ route('bachelier.candidatures') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('bachelier.candidatures*') ? 'text-primary-600' : '' }}">Mes Candidatures</a></li>
                            <li><a href="{{ route('bachelier.favoris') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('bachelier.favoris*') ? 'text-primary-600' : '' }}">Favoris</a></li>
                        @elseif(auth()->user()->role === 'partenaire')
                            <li><a href="{{ route('partenaire.opportunites.index') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('partenaire.opportunites*') ? 'text-primary-600' : '' }}">Mes Opportunités</a></li>
                            <li><a href="{{ route('partenaire.candidatures.index') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('partenaire.candidatures*') ? 'text-primary-600' : '' }}">Candidatures</a></li>
                            <li><a href="{{ route('partenaire.analytics') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('partenaire.analytics*') ? 'text-primary-600' : '' }}">Analytics</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('partenaire.register') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('partenaire.register*') ? 'text-primary-600' : '' }}">Devenir partenaire</a></li>
                    @endif
                    <li><a href="{{ route('actualites') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('actualites*') ? 'text-primary-600' : '' }}">Actualités</a></li>
                    <li><a href="{{ route('faq') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('faq') ? 'text-primary-600' : '' }}">FAQ</a></li>
                    <li><a href="{{ route('contact') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('contact') ? 'text-primary-600' : '' }}">Contact</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">
                    Informations légales
                </h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('mentions-legales') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('mentions-legales') ? 'text-primary-600' : '' }}">Mentions légales</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('privacy') ? 'text-primary-600' : '' }}">Confidentialité</a></li>
                    <li><a href="{{ route('terms') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('terms') ? 'text-primary-600' : '' }}">Conditions d'utilisation</a></li>
                    <li><a href="{{ route('cookies') }}" class="text-black hover:text-black transition-colors truncate block md:inline {{ request()->routeIs('cookies') ? 'text-primary-600' : '' }}">Politique de cookies</a></li>
                </ul>
            </div>
        </div>
        
        <div class="mt-8 pt-8">
            <div class="text-center">
                <p class="text-gray-600">
                    &copy; {{ date('Y') }} <span class="font-semibold text-primary-600">ANSUT</span> - PEUB. Tous droits réservés.
                </p>
            </div>
        </div>
    </div>
</footer> 