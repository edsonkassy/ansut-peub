@extends('layouts.guest')

@section('title', 'Lancement officiel de PEUB 2024 - Actualités PEUB')

@section('content')
<!-- Article Hero -->
<section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Meta info -->
        <div class="flex items-center mb-6">
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium bg-primary-100 text-primary-700">
                Annonce Importante
            </span>
            <span class="mx-4 text-gray-300">•</span>
            <time class="text-gray-500">20 Janvier 2024</time>
            <span class="mx-4 text-gray-300">•</span>
            <span class="text-gray-500">5 min de lecture</span>
        </div>
        
        <!-- Title -->
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Lancement officiel de PEUB 2024 : 500 nouvelles bourses disponibles
        </h1>
        
        <!-- Subtitle -->
        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
            Le Programme d'Excellence Universelle pour les Bacheliers annonce officiellement l'ouverture de 500 nouvelles bourses d'études pour l'année 2024, avec des partenariats renforcés avec les meilleures universités internationales.
        </p>
        
        <!-- Share buttons -->
        <div class="flex items-center space-x-4 pb-8 ">
            <span class="text-sm font-medium text-gray-700">Partager :</span>
            <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                </svg>
                Twitter
            </button>
            <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Facebook
            </button>
            <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                </svg>
                Partager
            </button>
        </div>
    </div>
</section>

<!-- Article Content -->
<section class="py-8 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Featured Image -->
        <div class="mb-12">
            <div class="h-96 relative overflow-hidden mb-4">
                <img src="{{ asset("images/articles/article_1.webp") }}" 
                     alt="Cérémonie de lancement officiel du programme PEUB 2024" 
                     class="w-full h-full object-cover">
            </div>
            <p class="text-sm text-gray-500 text-center italic">
                Cérémonie de lancement officiel du programme PEUB 2024 au Palais de la Culture d'Abidjan
            </p>
        </div>
        
        <!-- Article Body -->
        <div class="prose prose-lg prose-gray max-w-none">
            <p class="text-lg leading-relaxed mb-6">
                C'est dans une ambiance solennelle et festive que s'est déroulée ce 20 janvier 2024, la cérémonie officielle de lancement de la nouvelle édition du <strong>Programme d'Excellence Universelle pour les Bacheliers (PEUB)</strong>. Cette année marque un tournant historique avec l'annonce de <strong>500 nouvelles bourses d'études</strong> destinées aux jeunes talents ivoiriens.
            </p>
            
            <h2 class="text-2xl font-bold text-gray-900 mt-10 mb-6">Une expansion sans précédent</h2>
            
            <p class="leading-relaxed mb-6">
                Le nombre de bourses disponibles représente une augmentation de <strong>67% par rapport à l'année précédente</strong>, témoignant de la volonté du gouvernement ivoirien d'investir massivement dans l'éducation de sa jeunesse. Ces bourses couvriront intégralement les frais de scolarité, d'hébergement et de subsistance dans les meilleures universités partenaires.
            </p>
            
            <!-- Quote -->
            <blockquote class="bg-primary-50 border-l-4 border-primary-600 p-6 my-8">
                <p class="text-lg italic text-gray-700 mb-4">
                    "Cette initiative représente notre engagement indéfectible envers l'excellence académique et le développement du capital humain ivoirien. Nous investissons aujourd'hui dans les leaders de demain."
                </p>
                <footer class="text-primary-600 font-semibold">
                    — Dr. Mariam Kouadio, Ministre de l'Enseignement Supérieur
                </footer>
            </blockquote>
            
            <h2 class="text-2xl font-bold text-gray-900 mt-10 mb-6">Nouveaux partenariats stratégiques</h2>
            
            <p class="leading-relaxed mb-6">
                L'édition 2024 se distingue également par l'établissement de nouveaux partenariats avec des institutions académiques de renom mondial :
            </p>
            
            <ul class="list-disc pl-6 mb-6 space-y-2">
                <li><strong>HEC Paris</strong> - Programmes en management et entrepreneuriat</li>
                <li><strong>McGill University</strong> - Ingénierie et sciences appliquées</li>
                <li><strong>University of Cape Town</strong> - Médecine et sciences de la santé</li>
                <li><strong>École Polytechnique</strong> - Sciences et technologies avancées</li>
                <li><strong>London School of Economics</strong> - Économie et sciences politiques</li>
            </ul>
            
            <h2 class="text-2xl font-bold text-gray-900 mt-10 mb-6">Processus de candidature simplifié</h2>
            
            <p class="leading-relaxed mb-6">
                Cette année, le processus de candidature a été entièrement digitalisé et simplifié. Les candidats peuvent désormais :
            </p>
            
            <!-- Info Box -->
            <div class="bg-secondary-50 border border-secondary-200 p-6 my-8">
                <h3 class="text-lg font-semibold text-secondary-800 mb-4">
                    Étapes de candidature
                </h3>
                <ol class="list-decimal pl-6 space-y-2 text-secondary-700">
                    <li>Inscription en ligne sur la plateforme PEUB</li>
                    <li>Soumission des documents académiques</li>
                    <li>Évaluation par intelligence artificielle</li>
                    <li>Entretien virtuel avec le jury</li>
                    <li>Notification des résultats sous 30 jours</li>
                </ol>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 mt-10 mb-6">Calendrier et dates importantes</h2>
            
            <p class="leading-relaxed mb-6">
                Les candidatures sont officiellement ouvertes depuis aujourd'hui et se dérouleront selon le calendrier suivant :
            </p>
            
            <!-- Timeline -->
            <div class="space-y-4 mb-8">
                <div class="flex items-start">
                    <div class="bg-primary-600 w-3 h-3 mt-2 mr-4"></div>
                    <div>
                        <p class="font-semibold text-gray-900">20 janvier - 20 mars 2024</p>
                        <p class="text-gray-600">Période de candidature en ligne</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-primary-400 w-3 h-3 mt-2 mr-4"></div>
                    <div>
                        <p class="font-semibold text-gray-900">25 mars - 15 avril 2024</p>
                        <p class="text-gray-600">Évaluation des dossiers</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-primary-300 w-3 h-3 mt-2 mr-4"></div>
                    <div>
                        <p class="font-semibold text-gray-900">20 avril - 10 mai 2024</p>
                        <p class="text-gray-600">Entretiens avec les candidats présélectionnés</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-secondary-500 w-3 h-3 mt-2 mr-4"></div>
                    <div>
                        <p class="font-semibold text-gray-900">15 mai 2024</p>
                        <p class="text-gray-600">Annonce des résultats définitifs</p>
                    </div>
                </div>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 mt-10 mb-6">Un impact déjà mesurable</h2>
            
            <p class="leading-relaxed mb-6">
                Depuis son lancement en 2020, le programme PEUB a permis à plus de <strong>1,200 jeunes ivoiriens</strong> de poursuivre leurs études dans les meilleures universités du monde. Le taux de réussite académique des boursiers PEUB s'élève à <strong>94%</strong>, un chiffre qui témoigne de la qualité de la sélection et de l'accompagnement.
            </p>
            
        </div>
    </div>
</section>


<!-- Article Content Continued -->
<section class="py-8 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg prose-gray max-w-none">
        </div>
    </div>
</section>

<!-- Related Articles -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Articles similaires</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Related Article 1 -->
            <article class="bg-white shadow-sm hover:shadow-md border border-gray-100 transition-all duration-300 overflow-hidden group">
                <div class="h-48 relative overflow-hidden">
                    <img src="{{ asset("images/articles/article_2.webp") }}" 
                         alt="Partenariat universitaire" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-secondary-100 text-secondary-700">
                            Partenariat
                        </span>
                        <span class="mx-2 text-gray-300">•</span>
                        <span class="text-sm text-gray-500">18 Janvier 2024</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-secondary-600 transition-colors">Accord avec l'Université McGill</h3>
                    <p class="text-gray-600 mb-4">
                        Nouveau partenariat stratégique avec McGill University pour des programmes d'ingénierie.
                    </p>
                    <a href="#" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium underline underline-offset-4">
                        Lire la suite 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </article>
            
            <!-- Related Article 2 -->
            <article class="bg-white shadow-sm hover:shadow-md border border-gray-100 transition-all duration-300 overflow-hidden group">
                <div class="h-48 relative overflow-hidden">
                    <img src="{{ asset("images/articles/article_3.webp") }}" 
                         alt="Webinaire en ligne" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-primary-100 text-primary-700">
                            Événement
                        </span>
                        <span class="mx-2 text-gray-300">•</span>
                        <span class="text-sm text-gray-500">15 Janvier 2024</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors">Webinaire d'orientation</h3>
                    <p class="text-gray-600 mb-4">
                        Session d'information virtuelle pour guider les candidats dans leurs choix.
                    </p>
                    <a href="#" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium underline underline-offset-4">
                        Lire la suite 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </article>
            
            <!-- Related Article 3 -->
            <article class="bg-white shadow-sm hover:shadow-md border border-gray-100 transition-all duration-300 overflow-hidden group">
                <div class="h-48 relative overflow-hidden">
                    <img src="{{ asset("images/articles/article_4.webp") }}" 
                         alt="Cérémonie de remise de prix" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-secondary-100 text-secondary-700">
                            Succès
                        </span>
                        <span class="mx-2 text-gray-300">•</span>
                        <span class="text-sm text-gray-500">12 Janvier 2024</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-secondary-600 transition-colors">Prix d'Excellence PEUB</h3>
                    <p class="text-gray-600 mb-4">
                        Remise des prix d'excellence aux meilleurs étudiants boursiers 2023.
                    </p>
                    <a href="#" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium underline underline-offset-4">
                        Lire la suite 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </article>
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('actualites') }}" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 font-semibold transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour aux actualités
            </a>
        </div>
    </div>
</section>
@endsection