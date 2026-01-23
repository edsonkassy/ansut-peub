@extends('layouts.app')

@section('title', 'Mentions Légales - ANSUT')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Mentions Légales</h1>
            
            <div class="prose prose-gray max-w-none">
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">PRÉAMBULE</h2>
                    <p class="text-gray-700 mb-4">
                        Les présentes conditions générales d'utilisation (CGU) s'appliquent à toutes les personnes utilisant le site internet 
                        <strong>www.ansut.ci</strong>, ci-après dénommé « le SITE ».
                    </p>
                    <p class="text-gray-700 mb-4">
                        Elles ont pour objet de définir les modalités de mise à disposition des contenus proposés par le SITE, et les conditions 
                        générales d'utilisation de celui-ci par toutes les personnes utilisant le contenu du SITE, ci-après dénommées « l'UTILISATEUR ».
                    </p>
                    <p class="text-gray-700 mb-4">
                        En visitant le SITE, l'UTILISATEUR reconnaît accepter sans réserve ni restriction les conditions générales d'utilisation ci-après.
                    </p>
                    <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                        <p class="text-yellow-800 font-medium">
                            <i data-lucide="alert-triangle" class="inline w-5 h-5 mr-2"></i>
                            Dans le cas où l'UTILISATEUR ne souhaiterait pas accepter tout ou partie des présentes conditions générales d'utilisation, 
                            il lui est demandé de renoncer immédiatement à tout usage du SITE.
                        </p>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">ARTICLE 1 : MENTIONS LÉGALES ANSUT</h2>
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <p class="text-gray-700 mb-4">
                            Le site <strong>www.ansut.ci</strong> est édité par l'<strong>Agence Nationale du Service Universel des Télécommunications-TIC « ANSUT »</strong>, 
                            Société d'État, créée par l'article 157 de l'ordonnance n°2012-293 du 21 mars 2012 relative aux Télécommunications et aux Technologies 
                            de l'Information et de la Communication et régie par la loi n°2020-626 du 14 août 2020 portant définition et organisation des sociétés d'État.
                        </p>
                        <ul class="text-gray-700 space-y-2">
                            <li><strong>Capital social :</strong> 500 000 000 Francs CFA</li>
                            <li><strong>Siège social :</strong> District Autonome d'Abidjan, Commune de Cocody – II Plateau 7e Tranche, 30e arrondissement de Police</li>
                            <li><strong>Adresse postale :</strong> 01 BP 11821 Abidjan 01</li>
                            <li><strong>RCCM :</strong> CI-ABJ-03-2013-B14-12834</li>
                            <li><strong>Compte Contribuable :</strong> 1350468 S</li>
                            <li><strong>Identifiant Unique :</strong> CI-2013-0000027 C</li>
                        </ul>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">ARTICLE 2 : ACCÈS AU SITE</h2>
                    <p class="text-gray-700 mb-4">
                        Le SITE s'efforce de garantir l'accès 24 heures sur 24, 7 jours sur 7, sauf en cas de force majeure ou d'un événement 
                        hors du contrôle du SITE, et sous réserve d'éventuelles pannes et interventions de maintenance nécessaires au bon fonctionnement de celui-ci.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Par conséquent, le SITE ne peut garantir une disponibilité, une fiabilité des transmissions et des performances en termes 
                        de temps de réponse ou de qualité. Ainsi, il n'est prévu aucune obligation d'assistance technique vis-à-vis de l'UTILISATEUR 
                        que ce soit par des moyens électroniques ou téléphoniques.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Le SITE est accessible gratuitement depuis n'importe où par tout UTILISATEUR disposant d'un accès à Internet. 
                        Tous les frais nécessaires pour l'accès aux services (matériel informatique, connexion Internet…) sont à la charge de l'utilisateur.
                    </p>
                    <div class="bg-gray-100 p-4 rounded-lg mb-4">
                        <p class="text-gray-700">
                            <strong>En cas d'interruption :</strong> L'UTILISATEUR peut se rendre directement au siège de l'ANSUT ou contacter 
                            la Direction de la communication au <strong>27 22 52 95 05</strong>.
                        </p>
                    </div>
                    <p class="text-gray-700 mb-4">
                        Certaines sections du SITE sont réservées aux Membres/Agents de l'ANSUT après identification à l'aide d'un identifiant et d'un mot de passe.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">ARTICLE 3 : COLLECTE DES DONNÉES</h2>
                    <p class="text-gray-700 mb-4">
                        Pour la création du compte de l'UTILISATEUR, la collecte des informations au moment de l'inscription sur le site est nécessaire et obligatoire. 
                        Conformément à la Loi n°2013-450 du 19 juin 2013 relative à la protection des Données à Caractère Personnel, la collecte et le traitement 
                        d'informations personnelles s'effectuent dans le respect de la vie privée.
                    </p>
                    <p class="text-gray-700">
                        Pour plus d'informations, consultez notre <a href="{{ route('privacy') }}" class="text-primary-600 hover:text-primary-700 underline">Politique de Confidentialité</a>.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">ARTICLE 4 : PROPRIÉTÉ INTELLECTUELLE</h2>
                    <p class="text-gray-700 mb-4">
                        La structure générale du site <strong>www.ansut.ci</strong>, ainsi que les textes, graphiques, images, sons et vidéos la composant, 
                        sont la propriété de la société ANSUT ou de ses partenaires.
                    </p>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-red-800 font-medium">
                            <i data-lucide="shield-alert" class="inline w-5 h-5 mr-2"></i>
                            Toute représentation et/ou reproduction et/ou exploitation partielle ou totale de ce site, par quelque procédé que ce soit, 
                            sans l'autorisation préalable et par écrit de la société ANSUT est strictement interdite et serait susceptible de constituer une contrefaçon.
                        </p>
                    </div>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">ARTICLE 5 : RESPONSABILITÉ</h2>
                    <p class="text-gray-700 mb-4">
                        Bien que les informations publiées sur le SITE soient réputées fiables, le SITE se réserve la faculté d'une non-garantie de la fiabilité des sources.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Les informations diffusées sur le SITE sont présentées à titre purement informatif et sont sans valeur contractuelle. 
                        En dépit des mises à jour régulières, la responsabilité du SITE ne peut être engagée en cas de modification des dispositions 
                        administratives et juridiques apparaissant après la publication.
                    </p>
                    <ul class="list-disc pl-6 mb-4 text-gray-700">
                        <li>Le SITE décline toute responsabilité concernant les éventuels virus pouvant infecter le matériel informatique de l'Utilisateur</li>
                        <li>Le SITE ne peut être tenu pour responsable en cas de force majeure ou du fait imprévisible et insurmontable d'un tiers</li>
                        <li>La garantie totale de la sécurité et la confidentialité des données n'est pas assurée, mais le SITE s'engage à mettre en œuvre toutes les méthodes requises</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">ARTICLE 6 : LIENS HYPERTEXTES</h2>
                    <p class="text-gray-700 mb-4">
                        Le SITE peut être constitué de liens hypertextes. En cliquant sur ces derniers, l'UTILISATEUR sortira de la plateforme. 
                        Cette dernière n'a pas de contrôle et ne peut pas être tenue responsable de la sécurité et du contenu des pages web relatives à ces liens.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">ARTICLE 7 : COOKIES</h2>
                    <p class="text-gray-700 mb-4">
                        Lors des visites sur le SITE, l'installation automatique de cookie sur le logiciel de navigation de l'UTILISATEUR peut survenir.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Les cookies correspondent à de petits fichiers déposés temporairement sur le disque dur de l'ordinateur de l'UTILISATEUR. 
                        Ces cookies sont nécessaires pour assurer l'accessibilité et la navigation sur le SITE.
                    </p>
                    <p class="text-gray-700 mb-4">
                        En naviguant sur le site, l'Utilisateur accepte les cookies. Leur désactivation peut s'effectuer via les paramètres du logiciel de navigation.
                    </p>
                    <p class="text-gray-700">
                        Pour plus d'informations, consultez notre <a href="{{ route('cookies') }}" class="text-primary-600 hover:text-primary-700 underline">Politique de Cookies</a>.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">ARTICLE 8 : MODIFICATION DES CONDITIONS GÉNÉRALES</h2>
                    <p class="text-gray-700 mb-4">
                        L'ANSUT se réserve le droit, à sa seule discrétion, de modifier (y compris, mais sans s'y limiter, par l'ajout de nouvelles conditions générales) 
                        les présentes conditions générales à tout moment. Ces modifications feront l'objet d'une communication et entreront en vigueur immédiatement et automatiquement.
                    </p>
                    <p class="text-gray-700 mb-4">
                        L'UTILISATEUR s'engage à consulter les conditions générales mises à jour et prendre connaissance de ces modifications avant d'utiliser le SITE.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">ARTICLE 9 : DROIT APPLICABLE ET JURIDICTION COMPÉTENTE</h2>
                    <p class="text-gray-700 mb-4">
                        Le présent contrat est soumis à la législation ivoirienne. L'absence de résolution à l'amiable des cas de litige entre les parties 
                        implique le recours aux tribunaux ivoiriens compétents pour régler le contentieux.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">CONTACT</h2>
                    <div class="bg-primary-50 p-6 rounded-lg">
                        <p class="text-gray-700 mb-4">
                            Pour toute question relative à l'application des présentes CGU, vous pouvez joindre l'éditeur aux coordonnées suivantes :
                        </p>
                        <div class="space-y-2 text-gray-700">
                            <p><strong>Adresse :</strong> Abidjan Cocody, 2 Plateaux, 7e Tranche, Rue du 30e arrondissement</p>
                            <p><strong>Téléphone :</strong> +225 27 22 52 95 05</p>
                            <p><strong>Email :</strong> support@ansut.ci</p>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-primary-700 font-medium mb-2">Suivez-nous :</p>
                            <div class="flex space-x-4">
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
                        
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection