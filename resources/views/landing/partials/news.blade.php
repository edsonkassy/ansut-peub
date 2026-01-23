<!-- Actualités Section -->
<section id="news" class="py-12 sm:py-16 lg:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#0E7490] mb-3">
                ACTUALITÉS PEUB
            </h2>
            <div class="font-script text-4xl sm:text-5xl lg:text-6xl text-orange-500 mb-6">
                Confiance
            </div>
            <p class="mt-4 text-base sm:text-lg text-gray-600 font-semibold tracking-wide">
                RESTEZ INFORMÉ DES DERNIÈRES NOUVELLES ET ÉVÉNEMENTS
            </p>
            <div class="mt-6 sm:mt-8">
                <a href="{{ route('actualites') }}" class="inline-flex items-center bg-[#0E7490] hover:bg-cyan-800 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold transition-all duration-300 hover:scale-105 text-sm sm:text-base shadow-lg">
                    <i data-lucide="newspaper" class="w-4 h-4 sm:w-5 sm:h-5 mr-2"></i>
                    Voir toutes les actualités
                </a>
            </div>
        </div>
        
        @if($featured_articles->isNotEmpty())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                @foreach($featured_articles as $article)
                    <article class="bg-white shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group hover:-translate-y-1">
                        <div class="h-40 sm:h-48 relative overflow-hidden">
                            @if($article->image_principale)
                                <img src="{{ asset('storage/' . $article->image_principale) }}" 
                                     alt="{{ $article->titre }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-12 h-12 sm:w-16 sm:h-16 text-white opacity-50"></i>
                                </div>
                            @endif
                            
                            @if($article->featured)
                                <div class="absolute top-2 sm:top-4 left-2 sm:left-4">
                                    <span class="bg-secondary-500 text-white px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium">
                                        À la une
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center mb-2 sm:mb-3">
                                @php
                                    $categoryConfig = [
                                        'annonce' => ['bg-primary-100', 'text-primary-700', 'bell'],
                                        'success' => ['bg-green-100', 'text-green-700', 'trophy'],
                                        'evenement' => ['bg-purple-100', 'text-purple-700', 'calendar'],
                                        'partenariat' => ['bg-secondary-100', 'text-secondary-700', 'handshake'],
                                        'formation' => ['bg-cyan-100', 'text-cyan-700', 'graduation-cap'],
                                        'conseil' => ['bg-amber-100', 'text-amber-700', 'lightbulb'],
                                        'interview' => ['bg-pink-100', 'text-pink-700', 'mic'],
                                        'actualite' => ['bg-gray-100', 'text-gray-700', 'newspaper']
                                    ];
                                    $config = $categoryConfig[$article->categorie] ?? $categoryConfig['actualite'];
                                @endphp
                                
                                <span class="inline-flex items-center px-2 sm:px-3 py-1 text-xs font-medium {{ $config[0] }} {{ $config[1] }} rounded-full">
                                    <i data-lucide="{{ $config[2] }}" class="w-3 h-3 mr-1"></i>
                                    {{ ucfirst($article->categorie) }}
                                </span>
                                <span class="mx-2 text-gray-300">•</span>
                                <span class="text-xs sm:text-sm text-gray-500">{{ $article->date_publication->format('d M Y') }}</span>
                            </div>
                            
                            <h3 class="text-base sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3 group-hover:text-primary-600 transition-colors line-clamp-2">
                                {{ $article->titre }}
                            </h3>
                            
                            <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4 line-clamp-3">
                                {{ $article->resume ?: Str::limit(strip_tags($article->contenu), 120) }}
                            </p>
                            
                            <a href="{{ route('actualite', $article->slug) }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium group-hover:underline text-sm sm:text-base">
                                Lire la suite 
                                <i data-lucide="arrow-right" class="w-3 h-3 sm:w-4 sm:h-4 ml-1 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <!-- Fallback si aucun article -->
            <div class="text-center py-8 sm:py-12">
                <i data-lucide="newspaper" class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-3 sm:mb-4"></i>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Aucun article disponible</h3>
                <p class="text-sm sm:text-base text-gray-600">Les dernières actualités seront bientôt disponibles.</p>
            </div>
        @endif
    </div>

    <!-- Newsletter -->
    <div class="mt-12 sm:mt-16 bg-primary-600 py-8 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h3 class="text-2xl sm:text-3xl font-bold text-white mb-3 sm:mb-4">Restez informé</h3>
                <p class="text-base sm:text-xl text-primary-100 mb-6 sm:mb-8 px-4 sm:px-0">Abonnez-vous à notre newsletter pour recevoir les dernières actualités PEUB</p>
                <div class="max-w-md mx-auto flex flex-col sm:flex-row gap-3 sm:gap-4 px-4 sm:px-0">
                    <input type="email" placeholder="Votre adresse email" class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 bg-white border border-transparent focus:ring-2 focus:ring-white focus:border-transparent text-sm sm:text-base">
                    <button class="bg-white hover:bg-gray-100 text-primary-600 px-4 sm:px-6 py-2.5 sm:py-3 font-medium transition-colors text-sm sm:text-base">
                        S'abonner
                    </button>
                </div>
            </div>
        </div>
    </div>
</section> 