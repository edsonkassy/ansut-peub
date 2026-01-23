@extends('layouts.app')

@section('title', 'Conditions d\'utilisation - PEUB')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Conditions d'utilisation</h1>
            
            <div class="prose prose-gray max-w-none">
                <p class="text-gray-600 mb-6">
                    <strong>Date d'entrée en vigueur :</strong> {{ date('d F Y') }}
                </p>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Acceptation des conditions</h2>
                    <p class="text-gray-700 mb-4">
                        En accédant et en utilisant la plateforme PEUB (Projet d'Excellence Universelle pour les Bacheliers), 
                        vous acceptez d'être lié par ces conditions d'utilisation. Si vous n'acceptez pas ces conditions, 
                        veuillez ne pas utiliser notre plateforme.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Description du service</h2>
                    <p class="text-gray-700 mb-4">
                        PEUB est une plateforme qui met en relation les bacheliers ivoiriens avec des opportunités 
                        académiques et professionnelles proposées par des partenaires (universités, entreprises, organisations).
                    </p>
                    <p class="text-gray-700 mb-4">Nos services incluent :</p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Création et gestion de profils bacheliers</li>
                        <li>Accès aux opportunités (bourses, stages, formations, concours)</li>
                        <li>Système de candidature en ligne</li>
                        <li>Assistant IA pour l'orientation</li>
                        <li>Attribution de dotations aux boursiers</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Conditions d'éligibilité</h2>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Pour les bacheliers :</h3>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Être titulaire du baccalauréat ivoirien</li>
                        <li>Fournir des informations exactes et à jour</li>
                        <li>Être âgé d'au moins 16 ans</li>
                    </ul>
                    
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Pour les partenaires :</h3>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Être une organisation légalement constituée</li>
                        <li>Fournir des opportunités légitimes et vérifiables</li>
                        <li>Respecter les lois en vigueur</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Obligations des utilisateurs</h2>
                    <p class="text-gray-700 mb-4">En utilisant PEUB, vous vous engagez à :</p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Fournir des informations exactes, complètes et à jour</li>
                        <li>Maintenir la confidentialité de vos identifiants de connexion</li>
                        <li>Ne pas usurper l'identité d'autrui</li>
                        <li>Ne pas utiliser la plateforme à des fins illégales ou non autorisées</li>
                        <li>Respecter les droits de propriété intellectuelle</li>
                        <li>Ne pas télécharger de contenu malveillant</li>
                        <li>Traiter les autres utilisateurs avec respect</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Propriété intellectuelle</h2>
                    <p class="text-gray-700 mb-4">
                        Tous les contenus présents sur PEUB (textes, images, logos, design, code source) sont la propriété 
                        de l'ANSUT ou de ses partenaires et sont protégés par les lois sur la propriété intellectuelle.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Vous ne pouvez pas reproduire, distribuer, modifier ou créer des œuvres dérivées sans autorisation écrite préalable.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Responsabilités et limitations</h2>
                    <p class="text-gray-700 mb-4">
                        <strong>PEUB et l'ANSUT :</strong>
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>S'efforcent de maintenir la plateforme accessible et fonctionnelle</li>
                        <li>Ne garantissent pas l'exactitude des informations fournies par les partenaires</li>
                        <li>Ne sont pas responsables des décisions prises par les partenaires</li>
                        <li>Se réservent le droit de modifier ou d'interrompre le service</li>
                    </ul>
                    
                    <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                        <p class="text-yellow-800">
                            <strong>Important :</strong> PEUB est une plateforme de mise en relation. Nous ne garantissons pas 
                            l'obtention d'une opportunité suite à une candidature.
                        </p>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Protection des données</h2>
                    <p class="text-gray-700 mb-4">
                        L'utilisation de vos données personnelles est régie par notre 
                        <a href="{{ route('privacy') }}" class="text-primary-600 hover:text-primary-700 underline">Politique de Confidentialité</a>.
                        En utilisant PEUB, vous consentez à la collecte et à l'utilisation de vos données conformément à cette politique.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Résiliation</h2>
                    <p class="text-gray-700 mb-4">
                        Nous nous réservons le droit de suspendre ou de résilier votre accès à la plateforme en cas de :
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Violation de ces conditions d'utilisation</li>
                        <li>Comportement frauduleux ou abusif</li>
                        <li>Fourniture d'informations fausses ou trompeuses</li>
                        <li>Atteinte à la sécurité de la plateforme</li>
                    </ul>
                    <p class="text-gray-700 mb-4">
                        Vous pouvez résilier votre compte à tout moment en suivant la procédure de suppression décrite dans notre 
                        <a href="{{ route('privacy') }}#demande-suppression-compte" class="text-primary-600 hover:text-primary-700 underline">Politique de Confidentialité</a>.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Modifications des conditions</h2>
                    <p class="text-gray-700 mb-4">
                        Nous pouvons modifier ces conditions d'utilisation à tout moment. Les modifications entrent en vigueur 
                        dès leur publication sur la plateforme. Votre utilisation continue de PEUB après ces modifications 
                        constitue votre acceptation des nouvelles conditions.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Loi applicable et juridiction</h2>
                    <p class="text-gray-700 mb-4">
                        Ces conditions d'utilisation sont régies par les lois de la République de Côte d'Ivoire. 
                        Tout litige relatif à l'utilisation de PEUB sera soumis à la compétence exclusive des tribunaux ivoiriens.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Contact</h2>
                    <p class="text-gray-700 mb-4">
                        Pour toute question concernant ces conditions d'utilisation :
                    </p>
                    <div class="bg-gray-100 p-4">
                        <p class="text-gray-700">
                            <strong>Email :</strong> info@ansut.ci<br>
                            <strong>Téléphone :</strong> +225 27 22 52 95 05<br>
                            <strong>Adresse :</strong> Abidjan Cocody, 2 Plateaux, 7e Tranche, Rue du 30e arrondissement
                        </p>
                    </div>
                </section>

                <div class="mt-8 p-4 bg-primary-50 rounded-lg">
                    <p class="text-primary-800 text-center">
                        En utilisant PEUB, vous confirmez avoir lu, compris et accepté ces conditions d'utilisation.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection