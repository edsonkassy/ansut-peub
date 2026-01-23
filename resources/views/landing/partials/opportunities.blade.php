<!-- Opportunities Section -->
<section id="opportunities" class="py-12 sm:py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#0E7490] mb-3">
                TYPES D'OPPORTUNITÉS
            </h2>
            <div class="font-script text-4xl sm:text-5xl lg:text-6xl text-orange-500 mb-6">
                Disponibles
            </div>
            <p class="mt-4 text-base sm:text-lg text-gray-600 font-semibold tracking-wide">
                EXPLOREZ TOUTES LES POSSIBILITÉS QUI S'OFFRENT À VOUS
            </p>
        </div>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Bourses d'études -->
            <a href="{{ route('auth.login', ['redirect_to' => route('bachelier.opportunites', ['type' => 'bourse'])]) }}" class="block group relative">
                <div class="bg-white border border-gray-200 shadow-lg hover:shadow-xl transition-shadow duration-300 group overflow-hidden">
                    <!-- Image de couverture -->
                    <div class="h-40 sm:h-48 overflow-hidden">
                        <img src="{{ asset('images/opportunites/bourses.jpg') }}" 
                             alt="Bourses d'études" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <!-- Contenu -->
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-cyan-100 flex items-center justify-center group-hover:bg-cyan-200 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-[#0E7490]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <span class="bg-cyan-100 text-cyan-800 text-xs sm:text-sm font-medium px-2 sm:px-2.5 py-0.5 rounded-full">45+ disponibles</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Bourses d'études</h3>
                        <p class="text-sm sm:text-base text-gray-600">Accédez à un financement complet pour vos études supérieures en Côte d'Ivoire et à l'international</p>
                    </div>
                </div>
                <div class="absolute inset-0 bg-primary-600 bg-opacity-0 group-hover:bg-opacity-10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <span class="bg-white text-primary-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded">Se connecter pour voir les détails</span>
                </div>
            </a>

            <!-- Stages & Emplois -->
            <a href="{{ route('auth.login', ['redirect_to' => route('bachelier.opportunites', ['type' => 'stage'])]) }}" class="block group relative">
                <div class="bg-white border border-gray-200 shadow-lg hover:shadow-xl transition-shadow duration-300 group overflow-hidden">
                    <!-- Image de couverture -->
                    <div class="h-40 sm:h-48 overflow-hidden">
                        <img src="{{ asset('images/opportunites/stages.jpg') }}" 
                             alt="Stages & Emplois" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <!-- Contenu -->
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="bg-green-100 text-green-800 text-xs sm:text-sm font-medium px-2 sm:px-2.5 py-0.5 rounded-full">78+ disponibles</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Stages & Emplois</h3>
                        <p class="text-sm sm:text-base text-gray-600">Découvrez des opportunités professionnelles adaptées à votre profil et vos ambitions</p>
                    </div>
                </div>
                <div class="absolute inset-0 bg-primary-600 bg-opacity-0 group-hover:bg-opacity-10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <span class="bg-white text-primary-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded">Se connecter pour voir les détails</span>
                </div>
            </a>

            <!-- Formations -->
            <a href="{{ route('auth.login', ['redirect_to' => route('bachelier.opportunites', ['type' => 'formation'])]) }}" class="block group relative">
                <div class="bg-white border border-gray-200 shadow-lg hover:shadow-xl transition-shadow duration-300 group overflow-hidden">
                    <!-- Image de couverture -->
                    <div class="h-40 sm:h-48 overflow-hidden">
                        <img src="{{ asset('images/opportunites/formations.jpg') }}" 
                             alt="Formations" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <!-- Contenu -->
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <span class="bg-purple-100 text-purple-800 text-xs sm:text-sm font-medium px-2 sm:px-2.5 py-0.5 rounded-full">32+ disponibles</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Formations</h3>
                        <p class="text-sm sm:text-base text-gray-600">Développez vos compétences avec des formations certifiantes et des programmes d'excellence</p>
                    </div>
                </div>
                <div class="absolute inset-0 bg-primary-600 bg-opacity-0 group-hover:bg-opacity-10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <span class="bg-white text-primary-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded">Se connecter pour voir les détails</span>
                </div>
            </a>

            <!-- Événements -->
            <a href="{{ route('auth.login', ['redirect_to' => route('bachelier.opportunites', ['type' => 'event'])]) }}" class="block group relative">
                <div class="bg-white border border-gray-200 shadow-lg hover:shadow-xl transition-shadow duration-300 group overflow-hidden">
                    <!-- Image de couverture -->
                    <div class="h-40 sm:h-48 overflow-hidden">
                        <img src="{{ asset('images/opportunites/evenements.jpg') }}" 
                             alt="Événements" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <!-- Contenu -->
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="bg-orange-100 text-orange-800 text-xs sm:text-sm font-medium px-2 sm:px-2.5 py-0.5 rounded-full">12+ disponibles</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Événements</h3>
                        <p class="text-sm sm:text-base text-gray-600">Participez à des masterclass, conférences et événements exclusifs pour enrichir votre réseau</p>
                    </div>
                </div>
                <div class="absolute inset-0 bg-primary-600 bg-opacity-0 group-hover:bg-opacity-10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <span class="bg-white text-primary-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded">Se connecter pour voir les détails</span>
                </div>
            </a>

            <!-- Concours -->
            <a href="{{ route('auth.login', ['redirect_to' => route('bachelier.opportunites', ['type' => 'concours'])]) }}" class="block group relative">
                <div class="bg-white border border-gray-200 shadow-lg hover:shadow-xl transition-shadow duration-300 group overflow-hidden">
                    <!-- Image de couverture -->
                    <div class="h-40 sm:h-48 overflow-hidden">
                        <img src="{{ asset('images/opportunites/concours.jpg') }}" 
                             alt="Concours" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <!-- Contenu -->
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="bg-red-100 text-red-800 text-xs sm:text-sm font-medium px-2 sm:px-2.5 py-0.5 rounded-full">8+ disponibles</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Concours</h3>
                        <p class="text-sm sm:text-base text-gray-600">Relevez des défis d'excellence et mesurez-vous aux meilleurs talents de votre génération</p>
                    </div>
                </div>
                <div class="absolute inset-0 bg-primary-600 bg-opacity-0 group-hover:bg-opacity-10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <span class="bg-white text-primary-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded">Se connecter pour voir les détails</span>
                </div>
            </a>

            <!-- Promotions -->
            <a href="{{ route('auth.login', ['redirect_to' => route('bachelier.opportunites', ['type' => 'promotion'])]) }}" class="block group relative">
                <div class="bg-white border border-gray-200 shadow-lg hover:shadow-xl transition-shadow duration-300 group overflow-hidden">
                    <!-- Image de couverture -->
                    <div class="h-40 sm:h-48 overflow-hidden">
                        <img src="{{ asset('images/opportunites/promotion.jpg') }}" 
                             alt="Promotions" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <!-- Contenu -->
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                </svg>
                            </div>
                            <span class="bg-yellow-100 text-yellow-800 text-xs sm:text-sm font-medium px-2 sm:px-2.5 py-0.5 rounded-full">Nouvelles</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Promotions</h3>
                        <p class="text-sm sm:text-base text-gray-600">Bénéficiez d'offres exclusives et de réductions spéciales négociées avec nos partenaires</p>
                    </div>
                </div>
                <div class="absolute inset-0 bg-primary-600 bg-opacity-0 group-hover:bg-opacity-10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <span class="bg-white text-primary-600 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded">Se connecter pour voir les détails</span>
                </div>
            </a>
        </div>

        <!-- CTA Buttons -->
        <div class="mt-8 sm:mt-12 text-center">
            <a href="{{ route('partenaire.register') }}" class="bg-secondary-600 hover:bg-secondary-700 text-white px-6 sm:px-8 py-3 sm:py-4 font-semibold text-base sm:text-lg rounded-lg transition-all duration-300 hover:scale-105">
                Devenir partenaire
            </a>
        </div>
    </div>
</section> 