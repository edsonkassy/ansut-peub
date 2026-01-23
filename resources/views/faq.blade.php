@extends('layouts.guest')

@section('title', 'FAQ - Questions Fréquentes PEUB')

@section('content')
<!-- FAQ Hero -->
<section class="relative py-20 sm:py-24 bg-white overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-orange-100 rounded-full opacity-20 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-cyan-100 rounded-full opacity-20 blur-3xl"></div>
    </div>
    
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-gray-900 mb-4 leading-tight">
            QUESTIONS <span class="font-script text-5xl sm:text-6xl md:text-7xl text-orange-500 font-normal">Fréquentes</span>
        </h1>
        <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
            Tout ce que vous devez savoir sur le Programme d'Excellence Universelle pour les Bacheliers (PEUB)
        </p>
        <div class="flex items-center justify-center space-x-3 text-sm text-gray-500">
            <span class="font-medium">Une initiative de</span>
            <img src="{{ asset('images/logo_ansut_original.png') }}" alt="ANSUT" class="h-10 w-auto">
        </div>
    </div>
</section>

<!-- FAQ Content -->
<section class="py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Accordion FAQ -->
        <div class="space-y-4" x-data="{ activeAccordion: null }">
            <!-- Qu'est-ce que PEUB ? -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <button 
                    @click="activeAccordion = activeAccordion === 'peub' ? null : 'peub'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors group"
                >
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center mr-4 group-hover:bg-cyan-100 transition-colors">
                            <i data-lucide="help-circle" class="w-5 h-5 text-cyan-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Qu'est-ce que PEUB ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': activeAccordion === 'peub' }"></i>
                </button>
                <div 
                    x-show="activeAccordion === 'peub'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="px-6 pb-6"
                    style="display: none;"
                >
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-lg p-6 border border-gray-100">
                        <p class="text-gray-700 mb-4 leading-relaxed">
                            <strong class="text-cyan-700">PEUB</strong> (Programme d'Excellence Universelle pour les Bacheliers) est une initiative de l'<strong class="text-cyan-700">ANSUT</strong> (Agence Nationale du Service Universel des Télécommunications) visant à sélectionner chaque année les <strong class="text-orange-600">2 000 meilleurs bacheliers</strong> selon des critères académiques et sociaux pour leur fournir un accompagnement numérique, éducatif et social complet.
                        </p>
                        <p class="text-gray-700 mb-4 leading-relaxed">
                            L'objectif est de construire une élite académique inclusive et connectée, au service du développement national, en utilisant des algorithmes de ciblage intelligent et une plateforme numérique dédiée.
                        </p>
                        <div class="bg-gradient-to-r from-cyan-50 to-orange-50 border-l-4 border-orange-500 rounded-r-lg p-4">
                            <p class="text-gray-800 font-medium flex items-start">
                                <span class="text-2xl mr-3">🎯</span>
                                <span><strong class="text-orange-600">Notre mission :</strong> Transformer l'excellence académique en opportunités concrètes pour un avenir prometteur au service du développement national.</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Qui peut candidater ? -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <button 
                    @click="activeAccordion = activeAccordion === 'eligibilite' ? null : 'eligibilite'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors group"
                >
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center mr-4 group-hover:bg-orange-100 transition-colors">
                            <i data-lucide="users" class="w-5 h-5 text-orange-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Qui peut candidater à PEUB ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': activeAccordion === 'eligibilite' }"></i>
                </button>
                <div 
                    x-show="activeAccordion === 'eligibilite'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="px-6 pb-6"
                    style="display: none;"
                >
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="w-1 h-6 bg-orange-500 rounded-full mr-3"></span>
                            Critères d'éligibilité
                        </h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                                </div>
                                <span>Avoir obtenu le baccalauréat en 2025 (session 2024-2025)</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                                </div>
                                <span>Être admis avec une mention (Très Bien, Bien, Assez Bien, ou Passable)</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                                </div>
                                <span>Être résident en Côte d'Ivoire</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                                </div>
                                <span>Posséder une pièce d'identité valide (CNI, carte scolaire, attestation)</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                                </div>
                                <span>Rédiger une lettre de motivation conforme aux critères du programme</span>
                            </li>
                        </ul>
                        <div class="mt-4 p-4 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-lg border border-cyan-200">
                            <p class="text-cyan-900 text-sm leading-relaxed">
                                <strong class="text-cyan-700">💡 Note :</strong> Le programme utilise un algorithme de scoring intelligent qui prend en compte les résultats académiques, la zone géographique, le statut socio-économique et les motivations pour garantir une sélection équitable.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quels sont les avantages ? -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <button 
                    @click="activeAccordion = activeAccordion === 'avantages' ? null : 'avantages'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors group"
                >
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center mr-4 group-hover:bg-cyan-100 transition-colors">
                            <i data-lucide="gift" class="w-5 h-5 text-cyan-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Quels sont les avantages pour les boursiers PEUB ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': activeAccordion === 'avantages' }"></i>
                </button>
                <div 
                    x-show="activeAccordion === 'avantages'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="px-6 pb-6"
                    style="display: none;"
                >
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-lg p-6 border border-gray-100">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <span class="text-2xl mr-2">🎓</span>
                                    <span>Dotation Numérique Complète</span>
                                </h3>
                                <ul class="space-y-3 text-gray-700">
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-cyan-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="laptop" class="w-3.5 h-3.5 text-cyan-600"></i>
                                        </div>
                                        <span>Ordinateur portable performant</span>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-cyan-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="wifi" class="w-3.5 h-3.5 text-cyan-600"></i>
                                        </div>
                                        <span>Connexion internet gratuite : Data 3G/4G mensuelle</span>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-cyan-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="zap" class="w-3.5 h-3.5 text-cyan-600"></i>
                                        </div>
                                        <span>Abonnement IA Premium : ChatGPT Plus ou Claude</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <span class="text-2xl mr-2">🌟</span>
                                    <span>Plateforme PEUB Exclusif</span>
                                </h3>
                                <ul class="space-y-3 text-gray-700">
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="book-open" class="w-3.5 h-3.5 text-orange-600"></i>
                                        </div>
                                        <span>Accès e-learning et bibliothèque virtuelle</span>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="award" class="w-3.5 h-3.5 text-orange-600"></i>
                                        </div>
                                        <span>Offres de stages, bourses d'études, projets universitaires</span>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="users" class="w-3.5 h-3.5 text-orange-600"></i>
                                        </div>
                                        <span>Événements communautaires : masterclass, salons virtuels</span>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="star" class="w-3.5 h-3.5 text-orange-600"></i>
                                        </div>
                                        <span>Offres spéciales partenaires : e-Services, bons de réductions</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comment fonctionne la sélection ? -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <button 
                    @click="activeAccordion = activeAccordion === 'selection' ? null : 'selection'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors group"
                >
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center mr-4 group-hover:bg-orange-100 transition-colors">
                            <i data-lucide="brain" class="w-5 h-5 text-orange-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Comment fonctionne le processus de sélection ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': activeAccordion === 'selection' }"></i>
                </button>
                <div 
                    x-show="activeAccordion === 'selection'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="px-6 pb-6"
                    style="display: none;"
                >
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-lg p-6 border border-gray-100">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center mr-4 flex-shrink-0 shadow-sm">
                                    <span class="text-sm font-bold text-white">1</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Candidature en ligne</h4>
                                    <p class="text-gray-600 text-sm">Formulaire de candidature avec scoring automatique via la plateforme PEUB</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center mr-4 flex-shrink-0 shadow-sm">
                                    <span class="text-sm font-bold text-white">2</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Traitement automatisé</h4>
                                    <p class="text-gray-600 text-sm">Scoring par série, région, profil socio-économique avec algorithme intelligent</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center mr-4 flex-shrink-0 shadow-sm">
                                    <span class="text-sm font-bold text-white">3</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Validation finale</h4>
                                    <p class="text-gray-600 text-sm">Validation par un comité indépendant (5 à 10 membres)</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center mr-4 flex-shrink-0 shadow-sm">
                                    <span class="text-sm font-bold text-white">4</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-1">Annonce des résultats</h4>
                                    <p class="text-gray-600 text-sm">Annonce publique des 2 000 boursiers via site web, presse et réseaux sociaux</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-orange-200">
                            <p class="text-gray-800 text-sm leading-relaxed">
                                <strong class="text-orange-700">📊 Barème de scoring (100 points) :</strong>
                            </p>
                            <ul class="text-gray-800 text-sm mt-2 space-y-1 ml-4">
                                <li>• Excellence Académique: <strong>50 points</strong></li>
                                <li>• Situation Handicap: <strong>20 points</strong></li>
                                <li>• Situation Matrimoniale (Orphelinat): <strong>20 points</strong></li>
                                <li>• Genre: <strong>10 points</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rôle de l'ANSUT -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <button 
                    @click="activeAccordion = activeAccordion === 'ansut' ? null : 'ansut'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors group"
                >
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center mr-4 group-hover:bg-cyan-100 transition-colors">
                            <i data-lucide="building" class="w-5 h-5 text-cyan-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Quel est le rôle de l'ANSUT dans PEUB ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': activeAccordion === 'ansut' }"></i>
                </button>
                <div 
                    x-show="activeAccordion === 'ansut'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="px-6 pb-6"
                    style="display: none;"
                >
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-lg p-6 border border-gray-100">
                        <p class="text-gray-700 mb-4 leading-relaxed">
                            L'<strong class="text-cyan-700">Agence Nationale du Service Universel des Télécommunications (ANSUT)</strong> est l'institution publique responsable de la mise en œuvre de PEUB. Son rôle comprend :
                        </p>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-cyan-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <i data-lucide="shield" class="w-4 h-4 text-cyan-600"></i>
                                </div>
                                <span>La garantie de l'excellence et de la transparence du processus de sélection</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <i data-lucide="network" class="w-4 h-4 text-orange-600"></i>
                                </div>
                                <span>La mise en place de partenariats stratégiques avec des institutions académiques et entreprises</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-cyan-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <i data-lucide="monitor" class="w-4 h-4 text-cyan-600"></i>
                                </div>
                                <span>Le développement et la maintenance de la plateforme technologique intelligente</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <i data-lucide="heart" class="w-4 h-4 text-orange-600"></i>
                                </div>
                                <span>L'accompagnement personnalisé des boursiers tout au long de leur parcours</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Calendrier -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <button 
                    @click="activeAccordion = activeAccordion === 'calendrier' ? null : 'calendrier'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors group"
                >
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center mr-4 group-hover:bg-orange-100 transition-colors">
                            <i data-lucide="calendar" class="w-5 h-5 text-orange-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Quel est le calendrier de candidature ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': activeAccordion === 'calendrier' }"></i>
                </button>
                <div 
                    x-show="activeAccordion === 'calendrier'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="px-6 pb-6"
                    style="display: none;"
                >
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-lg p-6 border border-gray-100">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <span class="text-2xl mr-2">📅</span>
                                    <span>Dates importantes 2025</span>
                                </h3>
                                <ul class="space-y-3 text-gray-700">
                                    <li class="flex items-start">
                                        <span class="font-semibold text-cyan-600 mr-2 flex-shrink-0">3-13 Juin :</span>
                                        <span>Épreuves orales du BAC</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="font-semibold text-cyan-600 mr-2 flex-shrink-0">16-20 Juin :</span>
                                        <span>Épreuves écrites du BAC</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="font-semibold text-orange-600 mr-2 flex-shrink-0">7 Juillet :</span>
                                        <span>Proclamation des résultats BAC</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="font-semibold text-orange-600 mr-2 flex-shrink-0">15 Juil - 15 Août :</span>
                                        <span>Candidatures PEUB ouvertes</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="font-semibold text-cyan-600 mr-2 flex-shrink-0">Septembre :</span>
                                        <span>Annonce des 2 000 boursiers</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <span class="text-2xl mr-2">⏰</span>
                                    <span>Délais de traitement</span>
                                </h3>
                                <ul class="space-y-3 text-gray-700">
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-cyan-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-cyan-600"></i>
                                        </div>
                                        <span>Traitement automatisé : 48-72h</span>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-orange-600"></i>
                                        </div>
                                        <span>Validation comité : 1-2 semaines</span>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-cyan-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-orange-600"></i>
                                        </div>
                                        <span>Distribution kits : Septembre-Octobre</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <button 
                    @click="activeAccordion = activeAccordion === 'contact' ? null : 'contact'"
                    class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition-colors group"
                >
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-cyan-50 flex items-center justify-center mr-4 group-hover:bg-cyan-100 transition-colors">
                            <i data-lucide="message-circle" class="w-5 h-5 text-cyan-600"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900">Comment contacter l'équipe PEUB ?</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': activeAccordion === 'contact' }"></i>
                </button>
                <div 
                    x-show="activeAccordion === 'contact'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                    class="px-6 pb-6"
                    style="display: none;"
                >
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-lg p-6 border border-gray-100">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <span class="text-2xl mr-2">📞</span>
                                    <span>Contact direct</span>
                                </h3>
                                <ul class="space-y-3 text-gray-700">
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-cyan-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="phone" class="w-3.5 h-3.5 text-cyan-600"></i>
                                        </div>
                                        <span>+225 07 16 00 12 91</span>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="mail" class="w-3.5 h-3.5 text-orange-600"></i>
                                        </div>
                                        <span>support@ansut.ci</span>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-cyan-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-cyan-600"></i>
                                        </div>
                                        <span>Abidjan, Côte d'Ivoire</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <span class="text-2xl mr-2">💬</span>
                                    <span>Support en ligne</span>
                                </h3>
                                <ul class="space-y-3 text-gray-700">
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="message-square" class="w-3.5 h-3.5 text-orange-600"></i>
                                        </div>
                                        <span>Chatbot WhatsApp d'assistance 24h/7j</span>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-cyan-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="help-circle" class="w-3.5 h-3.5 text-cyan-600"></i>
                                        </div>
                                        <span>Centre d'aide intégré sur la plateforme</span>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                            <i data-lucide="video" class="w-3.5 h-3.5 text-orange-600"></i>
                                        </div>
                                        <span>Tutoriels vidéo disponibles</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-gradient-to-r from-green-50 to-cyan-50 rounded-lg border border-green-200">
                            <p class="text-gray-800 text-sm leading-relaxed">
                                <strong class="text-green-700">🏢 Points de candidature :</strong> Candidature possible dans les agences de La Poste CI et cybercafés partenaires pour garantir l'accessibilité dans toutes les régions.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Full Width -->
<section class="relative py-16 overflow-hidden">
    <!-- Gradient background -->
    <div class="absolute inset-0 bg-gradient-to-r from-cyan-600 via-cyan-700 to-orange-600"></div>
    
    <!-- Decorative elements -->
    <div class="absolute inset-0 overflow-hidden opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>
    </div>
    
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h2 class="text-3xl sm:text-4xl font-bold mb-4">
            Prêt à rejoindre <span class="font-script text-4xl sm:text-5xl text-orange-300">l'excellence</span> ?
        </h2>
        <p class="text-lg sm:text-xl text-cyan-50 mb-8 max-w-2xl mx-auto">
            Ne manquez pas cette opportunité unique de transformer votre avenir académique et professionnel.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('auth.register') }}" class="bg-white text-cyan-700 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-all duration-300 hover:scale-105 shadow-lg">
                S'inscrire maintenant
            </a>
            <a href="{{ route('landing') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white/10 transition-all duration-300 hover:scale-105">
                En savoir plus
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endpush
