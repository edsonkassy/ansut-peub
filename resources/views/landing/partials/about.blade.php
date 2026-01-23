<!-- About Section -->
<section id="about" class="py-12 sm:py-16 lg:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header avec image -->
        <div class="grid lg:grid-cols-2 gap-8 sm:gap-10 lg:gap-12 items-center mb-12 sm:mb-14 lg:mb-16">
            <!-- Contenu à gauche -->
            <div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#0E7490] mb-2 flex items-center gap-3">
                    UNE PLATEFORME INTELLIGENTE
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-[#0E7490]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </h2>
                <div class="font-script text-3xl sm:text-4xl lg:text-5xl text-orange-500 mb-6">
                    Pour l'Excellence
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4 sm:mb-6">
                    <span>Une initiative de</span>
                    <img src="{{ asset('images/logo_ansut_original.png') }}" alt="ANSUT" class="h-6 sm:h-8 w-auto">
                </div>
                <p class="text-sm sm:text-base text-gray-700 mb-4 sm:mb-6">
                    L'<strong>Agence Nationale du Service Universel des Télécommunications (ANSUT)</strong> s'engage à transformer l'éducation en Côte d'Ivoire en connectant l'excellence académique aux meilleures opportunités mondiales.
                </p>
                <p class="text-sm sm:text-base text-gray-700 mb-6 sm:mb-8">
                    PEUB représente notre vision d'un avenir où chaque talent ivoirien peut accéder aux ressources et opportunités nécessaires pour exceller sur la scène internationale.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="{{ route('auth.register') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold transition-all duration-300 hover:scale-105 text-center text-sm sm:text-base shadow-lg">
                        S'inscrire maintenant
                    </a>
                    <a href="{{ route('faq') }}" class="border-2 border-[#0E7490] text-[#0E7490] px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold hover:bg-cyan-50 transition-all duration-300 hover:scale-105 text-center text-sm sm:text-base">
                        Questions fréquentes
                    </a>
                </div>
            </div>
            
            <!-- Image à droite -->
            <div class="relative mt-8 lg:mt-0">
                <div class="bg-white overflow-hidden">
                    <img src="{{ asset('images/about.png') }}" alt="Plateforme PEUB - Excellence académique" class="w-full h-auto object-cover">
                </div>
            </div>
        </div>
        
        <!-- Fonctionnalités -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-8 sm:mb-12">
            <div class="text-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-primary-100 flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Sélection d'Excellence</h3>
                <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">Processus rigoureux de sélection pour identifier et accompagner les meilleurs talents.</p>
                <div class="text-xs sm:text-sm text-gray-500 space-y-1">
                    <p>• Critères académiques stricts</p>
                    <p>• Évaluation par intelligence artificielle</p>
                    <p>• Jury d'experts multidisciplinaires</p>
                </div>
            </div>
            
            <div class="text-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-primary-100 flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Opportunités Premium</h3>
                <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">Accès exclusif aux meilleures bourses, stages et formations de partenaires vérifiés.</p>
                <div class="text-xs sm:text-sm text-gray-500 space-y-1">
                    <p>• Bourses d'études internationales</p>
                    <p>• Stages dans des entreprises leaders</p>
                    <p>• Formations spécialisées gratuites</p>
                </div>
            </div>
            
            <div class="text-center sm:col-span-2 lg:col-span-1">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-primary-100 flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">IA Personnalisée</h3>
                <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">Assistant intelligent pour l'orientation, les conseils et les recommandations personnalisées.</p>
                <div class="text-xs sm:text-sm text-gray-500 space-y-1">
                    <p>• Analyse de profil avancée</p>
                    <p>• Recommandations personnalisées</p>
                    <p>• Accompagnement 24/7</p>
                </div>
            </div>
        </div>

    </div>
</section> 