@extends('layouts.app')

@section('title', 'Politique de Confidentialité - PEUB')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Politique de Confidentialité</h1>
            
            <div class="prose prose-gray max-w-none">
                <p class="text-gray-600 mb-6">
                    <strong>Dernière mise à jour :</strong> {{ date('d F Y') }}
                </p>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Introduction</h2>
                    <p class="text-gray-700 mb-4">
                        La plateforme PEUB (Projet d'Excellence Universelle pour les Bacheliers), gérée par l'ANSUT (Agence Nationale du Service Universel de Télécommunications), 
                        s'engage à protéger la confidentialité de vos données personnelles. Cette politique de confidentialité explique comment nous collectons, 
                        utilisons et protégeons vos informations.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Données collectées</h2>
                    <p class="text-gray-700 mb-4">Nous collectons les données suivantes :</p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Informations d'identification : nom, prénom, date de naissance, numéro de téléphone, email</li>
                        <li>Informations académiques : série du bac, moyenne, établissement, relevé de notes</li>
                        <li>Documents : pièce d'identité, photo de profil, attestation du baccalauréat</li>
                        <li>Informations professionnelles : projet professionnel, domaines d'intérêt</li>
                        <li>Données d'utilisation : connexions, interactions avec la plateforme</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Utilisation des données</h2>
                    <p class="text-gray-700 mb-4">Vos données sont utilisées pour :</p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Gérer votre compte et votre profil sur la plateforme</li>
                        <li>Traiter vos candidatures aux opportunités</li>
                        <li>Vous mettre en relation avec les partenaires</li>
                        <li>Personnaliser votre expérience et les recommandations</li>
                        <li>Améliorer nos services et développer de nouvelles fonctionnalités</li>
                        <li>Communiquer avec vous concernant votre compte et les opportunités</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Partage des données</h2>
                    <p class="text-gray-700 mb-4">
                        Vos données peuvent être partagées avec :
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Les partenaires pour lesquels vous postulez</li>
                        <li>Les prestataires techniques qui nous aident à faire fonctionner la plateforme</li>
                        <li>Les autorités compétentes si la loi l'exige</li>
                    </ul>
                    <p class="text-gray-700 mb-4">
                        Nous ne vendons jamais vos données personnelles à des tiers.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Sécurité des données</h2>
                    <p class="text-gray-700 mb-4">
                        Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour protéger vos données contre :
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>L'accès non autorisé</li>
                        <li>La modification</li>
                        <li>La divulgation</li>
                        <li>La destruction</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Vos droits</h2>
                    <p class="text-gray-700 mb-4">Conformément à la réglementation en vigueur, vous disposez des droits suivants :</p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Droit d'accès à vos données</li>
                        <li>Droit de rectification</li>
                        <li>Droit à l'effacement</li>
                        <li>Droit à la limitation du traitement</li>
                        <li>Droit à la portabilité des données</li>
                        <li>Droit d'opposition</li>
                    </ul>
                </section>

                <section class="mb-8 bg-red-50 p-6 rounded-lg" id="demande-suppression-compte">
                    <h2 class="text-2xl font-semibold text-red-900 mb-4">7. Suppression de compte</h2>
                    <div class="text-gray-700">
                        <p class="mb-4">
                            Vous pouvez demander la suppression complète de votre compte et de toutes vos données personnelles à tout moment.
                        </p>
                        
                        <h3 class="text-lg font-semibold text-red-800 mb-3">Comment demander la suppression ?</h3>
                        <ol class="list-decimal pl-6 mb-4 space-y-2">
                            <li>Connectez-vous à votre compte</li>
                            <li>Accédez à votre profil</li>
                            <li>Cliquez sur "Paramètres du compte"</li>
                            <li>Sélectionnez "Supprimer mon compte"</li>
                            <li>Confirmez votre demande</li>
                        </ol>

                        <h3 class="text-lg font-semibold text-red-800 mb-3">Que se passe-t-il après la suppression ?</h3>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Toutes vos données personnelles seront définitivement effacées sous 30 jours</li>
                            <li>Vos candidatures en cours seront annulées</li>
                            <li>Vous ne pourrez plus accéder à votre compte</li>
                            <li>Certaines données peuvent être conservées pour des obligations légales</li>
                        </ul>

                        <div class="bg-red-100 p-4 rounded-md">
                            <p class="text-red-800 font-medium">
                                <i data-lucide="alert-triangle" class="inline w-5 h-5 mr-2"></i>
                                Attention : Cette action est irréversible. Assurez-vous de sauvegarder toutes les informations importantes avant de procéder.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Conservation des données</h2>
                    <p class="text-gray-700 mb-4">
                        Nous conservons vos données personnelles aussi longtemps que nécessaire pour :
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Fournir nos services</li>
                        <li>Respecter nos obligations légales</li>
                        <li>Résoudre les litiges</li>
                        <li>Faire respecter nos accords</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Cookies</h2>
                    <p class="text-gray-700 mb-4">
                        Nous utilisons des cookies pour améliorer votre expérience. Pour plus d'informations, consultez notre 
                        <a href="{{ route('cookies') }}" class="text-primary-600 hover:text-primary-700 underline">Politique de Cookies</a>.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Modifications</h2>
                    <p class="text-gray-700 mb-4">
                        Nous pouvons mettre à jour cette politique de confidentialité. Les modifications seront publiées sur cette page 
                        avec une nouvelle date de "Dernière mise à jour".
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Contact</h2>
                    <p class="text-gray-700 mb-4">
                        Pour toute question concernant cette politique de confidentialité ou vos données personnelles, contactez-nous :
                    </p>
                    <div class="bg-gray-100 p-4">
                        <p class="text-gray-700">
                            <strong>Email :</strong> support@ansut.ci<br>
                            <strong>Téléphone :</strong> +225 27 22 52 95 05<br>
                            <strong>Adresse :</strong> Abidjan Cocody, 2 Plateaux, 7e Tranche, Rue du 30e arrondissement
                        </p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Scroll automatique vers la section suppression si l'ancre est présente
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#demande-suppression-compte') {
            const element = document.getElementById('demande-suppression-compte');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });
</script>
@endpush