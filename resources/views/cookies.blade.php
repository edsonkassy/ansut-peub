@extends('layouts.app')

@section('title', 'Politique de Cookies - PEUB')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Politique de Cookies</h1>
            
            <div class="prose prose-gray max-w-none">
                <p class="text-gray-600 mb-6">
                    <strong>Dernière mise à jour :</strong> {{ date('d F Y') }}
                </p>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Qu'est-ce qu'un cookie ?</h2>
                    <p class="text-gray-700 mb-4">
                        Un cookie est un petit fichier texte stocké sur votre appareil (ordinateur, tablette, smartphone) 
                        lorsque vous visitez notre plateforme PEUB. Les cookies nous permettent de reconnaître votre navigateur 
                        et de mémoriser certaines informations pour améliorer votre expérience utilisateur.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Comment utilisons-nous les cookies ?</h2>
                    <p class="text-gray-700 mb-4">
                        La plateforme PEUB utilise des cookies pour :
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Maintenir votre session de connexion active</li>
                        <li>Mémoriser vos préférences d'affichage</li>
                        <li>Analyser l'utilisation de la plateforme pour l'améliorer</li>
                        <li>Assurer la sécurité et prévenir la fraude</li>
                        <li>Personnaliser votre expérience</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Types de cookies utilisés</h2>
                    
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Cookies essentiels</h3>
                        <p class="text-gray-700 mb-2">
                            Ces cookies sont nécessaires au fonctionnement de la plateforme. Sans eux, certaines fonctionnalités 
                            ne seraient pas disponibles.
                        </p>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left">
                                        <th class="pb-2">Nom du cookie</th>
                                        <th class="pb-2">Durée</th>
                                        <th class="pb-2">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-1">XSRF-TOKEN</td>
                                        <td class="py-1">2 heures</td>
                                        <td class="py-1">Protection contre les attaques CSRF</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">laravel_session</td>
                                        <td class="py-1">2 heures</td>
                                        <td class="py-1">Maintient votre session active</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Cookies de préférences</h3>
                        <p class="text-gray-700 mb-2">
                            Ces cookies permettent à la plateforme de mémoriser vos choix et préférences.
                        </p>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left">
                                        <th class="pb-2">Nom du cookie</th>
                                        <th class="pb-2">Durée</th>
                                        <th class="pb-2">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-1">theme_preference</td>
                                        <td class="py-1">1 an</td>
                                        <td class="py-1">Mémorise votre choix de thème (clair/sombre)</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">language</td>
                                        <td class="py-1">1 an</td>
                                        <td class="py-1">Mémorise votre langue préférée</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Cookies d'analyse</h3>
                        <p class="text-gray-700 mb-2">
                            Ces cookies nous aident à comprendre comment les visiteurs utilisent notre plateforme.
                        </p>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left">
                                        <th class="pb-2">Nom du cookie</th>
                                        <th class="pb-2">Durée</th>
                                        <th class="pb-2">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-1">_ga</td>
                                        <td class="py-1">2 ans</td>
                                        <td class="py-1">Google Analytics - distingue les utilisateurs</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">_gid</td>
                                        <td class="py-1">24 heures</td>
                                        <td class="py-1">Google Analytics - distingue les utilisateurs</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Gestion des cookies</h2>
                    <p class="text-gray-700 mb-4">
                        Vous avez le contrôle sur les cookies stockés sur votre appareil. Voici comment les gérer :
                    </p>
                    
                    <div class="bg-blue-50 p-6 rounded-lg mb-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">Paramètres de cookies sur PEUB</h3>
                        <p class="text-blue-800 mb-4">
                            Vous pouvez gérer vos préférences de cookies directement depuis votre compte :
                        </p>
                        <ol class="list-decimal pl-6 text-blue-800">
                            <li>Connectez-vous à votre compte</li>
                            <li>Accédez aux Paramètres</li>
                            <li>Cliquez sur "Préférences de cookies"</li>
                            <li>Choisissez les cookies que vous acceptez</li>
                        </ol>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Paramètres du navigateur</h3>
                    <p class="text-gray-700 mb-4">
                        Vous pouvez également configurer votre navigateur pour :
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Accepter tous les cookies</li>
                        <li>Refuser tous les cookies</li>
                        <li>Vous avertir avant d'accepter un cookie</li>
                        <li>Supprimer les cookies après chaque session</li>
                    </ul>

                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <p class="text-yellow-800">
                            <i data-lucide="info" class="inline w-5 h-5 mr-2"></i>
                            <strong>Attention :</strong> Le blocage de certains cookies peut affecter le fonctionnement 
                            de la plateforme et limiter votre accès à certaines fonctionnalités.
                        </p>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Cookies tiers</h2>
                    <p class="text-gray-700 mb-4">
                        Certains de nos partenaires peuvent également placer des cookies sur votre appareil :
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li><strong>Google Analytics :</strong> pour l'analyse du trafic</li>
                        <li><strong>Services de paiement :</strong> pour sécuriser les transactions</li>
                        <li><strong>Réseaux sociaux :</strong> pour le partage de contenu</li>
                    </ul>
                    <p class="text-gray-700 mb-4">
                        Ces tiers ont leurs propres politiques de confidentialité et nous vous encourageons à les consulter.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Durée de conservation</h2>
                    <p class="text-gray-700 mb-4">
                        Les cookies ont des durées de vie différentes :
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li><strong>Cookies de session :</strong> supprimés à la fermeture du navigateur</li>
                        <li><strong>Cookies persistants :</strong> restent sur votre appareil jusqu'à expiration ou suppression manuelle</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Mises à jour de cette politique</h2>
                    <p class="text-gray-700 mb-4">
                        Nous pouvons mettre à jour cette politique de cookies pour refléter les changements dans nos pratiques 
                        ou pour d'autres raisons opérationnelles, légales ou réglementaires.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Contact</h2>
                    <p class="text-gray-700 mb-4">
                        Pour toute question concernant notre utilisation des cookies :
                    </p>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p class="text-gray-700">
                            <strong>Email :</strong> info@ansut.ci<br>
                            <strong>Téléphone :</strong> +225 27 22 52 95 05<br>
                            <strong>Adresse :</strong> Abidjan Cocody, 2 Plateaux, 7e Tranche, Rue du 30e arrondissement
                        </p>
                    </div>
                </section>

                <div class="mt-8 p-6 bg-primary-50 rounded-lg text-center">
                    <p class="text-primary-800 mb-4">
                        En continuant à utiliser PEUB, vous acceptez notre utilisation des cookies conformément à cette politique.
                    </p>
                    <button class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                        J'accepte les cookies
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script pour gérer l'acceptation des cookies
    document.addEventListener('DOMContentLoaded', function() {
        const acceptButton = document.querySelector('button:contains("J\'accepte les cookies")');
        if (acceptButton) {
            acceptButton.addEventListener('click', function() {
                // Définir un cookie pour mémoriser l'acceptation
                document.cookie = "cookies_accepted=true; max-age=" + (365 * 24 * 60 * 60) + "; path=/";
                
                // Afficher un message de confirmation
                const message = document.createElement('div');
                message.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg';
                message.textContent = 'Préférences de cookies enregistrées';
                document.body.appendChild(message);
                
                // Supprimer le message après 3 secondes
                setTimeout(() => {
                    message.remove();
                }, 3000);
            });
        }
    });
</script>
@endpush