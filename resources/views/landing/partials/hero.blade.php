<!-- Hero Section avec image en arrière-plan -->
<section class="relative min-h-screen sm:h-[calc(100vh-4rem)] w-full flex items-center justify-center overflow-hidden">
    <!-- Image en arrière-plan -->
    <div class="absolute inset-0 w-full h-full z-0">
        <img
            src="{{ asset('images/hero-bg.png') }}"
            alt="PEUB - Excellence Académique"
            class="absolute inset-0 w-full h-full object-cover">

        <!-- Overlay pour améliorer la lisibilité du texte -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/45 z-10"></div>
    </div>
    
    <!-- Contenu de la section hero -->
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-0">
        <div class="text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight tracking-wider">
                L'EXCELLENCE<br>
                <span class="font-script text-5xl sm:text-6xl md:text-7xl lg:text-8xl text-orange-500 font-normal">
                    Commence Ici
                </span>
            </h1>
            <p class="mt-6 sm:mt-8 text-xl sm:text-2xl md:text-3xl text-white font-semibold max-w-4xl mx-auto px-4 sm:px-0 tracking-wide">
                DEVIENS ACTEUR DU FUTUR NUMÉRIQUE IVOIRIEN
            </p>
            <div class="mt-10 sm:mt-12 flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center px-4 sm:px-0">
                <a href="{{ route('auth.register') }}" class="bg-[#0E7490] hover:bg-cyan-800 text-white px-8 sm:px-10 py-4 sm:py-5 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300 hover:scale-105 text-center shadow-lg">
                    S'inscrire maintenant
                </a>
                <a href="{{ route('faq') }}" class="border-2 border-white text-white hover:bg-white/10 px-8 sm:px-10 py-4 sm:py-5 rounded-lg font-semibold text-base sm:text-lg transition-all duration-300 hover:scale-105 text-center">
                    C'est quoi le PEUB ?
                </a>
            </div>
        </div>
    </div>
    
    <!-- Indicateur de défilement -->
    <div class="absolute bottom-6 sm:bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
        <a href="#about" class="text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </a>
    </div>
</section> 