@extends('layouts.guest')

@section('title', 'Devenir Partenaire PEUB - Formulaire d\'Inscription')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">

        <!-- Header avec design moderne -->
        <div class="text-center mb-10">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-gradient-to-br from-[#0E7490] to-[#0c5f7a] rounded-full p-4 shadow-lg">
                    <i data-lucide="handshake" class="w-10 h-10 text-white"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-3">
                Devenir Partenaire <span class="text-[#0E7490]">PEUB</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Rejoignez notre réseau de partenaires et contribuez à l'excellence éducative en Côte d'Ivoire
            </p>
        </div>

        <!-- Registration Form avec design harmonisé -->
        <div class="bg-white shadow-2xl rounded-xl overflow-hidden border border-gray-100">
            <!-- Header du formulaire -->
            <div class="px-8 py-6 bg-gradient-to-r from-[#0E7490] to-[#0c5f7a] relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                <div class="relative">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                        Formulaire de Partenariat
                    </h2>
                    <p class="text-cyan-100 mt-2">Remplissez toutes les informations pour soumettre votre demande</p>
                </div>
            </div>

            <!-- Messages flash -->
            @include('components.flash-messages')

            <!-- Formulaire -->
            <form action="{{ route('partenaire.register.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf

                <!-- Section 1: Informations de l'organisation -->
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i data-lucide="building" class="w-5 h-5 mr-2 text-primary-600"></i>
                        Informations de l'organisation
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom de l'organisation -->
                        <div class="md:col-span-2">
                            <label for="nom_organisation" class="block text-sm font-medium text-gray-700 mb-2 required">
                                Nom de l'organisation 
                            </label>
                            <input type="text" id="nom_organisation" name="nom_organisation" value="{{ old('nom_organisation') }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('nom_organisation') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   placeholder="Nom complet de votre organisation" required>
                            @error('nom_organisation')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type d'organisation -->
                        <div>
                            <label for="type_organisation" class="block text-sm font-medium text-gray-700 mb-2 required">
                                Type d'organisation 
                            </label>
                            <select id="type_organisation" name="type_organisation" 
                                    class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('type_organisation') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" required>
                                <option value="">Sélectionnez le type</option>
                                <option value="entreprise" {{ old('type_organisation') == 'entreprise' ? 'selected' : '' }}>
                                    Entreprise privée
                                </option>
                                <option value="institution_academique" {{ old('type_organisation') == 'institution_academique' ? 'selected' : '' }}>
                                    Institution académique
                                </option>
                                <option value="ong" {{ old('type_organisation') == 'ong' ? 'selected' : '' }}>
                                    ONG / Association
                                </option>
                                <option value="gouvernement" {{ old('type_organisation') == 'gouvernement' ? 'selected' : '' }}>
                                    Organisme gouvernemental
                                </option>
                            </select>
                            @error('type_organisation')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Secteur d'activité -->
                        <div>
                            <label for="secteur_activite" class="block text-sm font-medium text-gray-700 mb-2">
                                Secteur d'activité
                            </label>
                            <select id="secteur_activite" name="secteur_activite" 
                                    class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('secteur_activite') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">Sélectionnez un secteur</option>
                                <option value="agro_agroalimentaire" {{ old('secteur_activite') == 'agro_agroalimentaire' ? 'selected' : '' }}>Agro & Agro‑alimentaire</option>
                                <option value="ressources_mines" {{ old('secteur_activite') == 'ressources_mines' ? 'selected' : '' }}>Ressources & Mines</option>
                                <option value="energie_climat" {{ old('secteur_activite') == 'energie_climat' ? 'selected' : '' }}>Énergie & Climat</option>
                                <option value="eau_dechets_recyclage" {{ old('secteur_activite') == 'eau_dechets_recyclage' ? 'selected' : '' }}>Eau, Déchets & Recyclage</option>
                                <option value="industrie_fabrication" {{ old('secteur_activite') == 'industrie_fabrication' ? 'selected' : '' }}>Industrie & Fabrication</option>
                                <option value="construction_immobilier" {{ old('secteur_activite') == 'construction_immobilier' ? 'selected' : '' }}>Construction & Immobilier</option>
                                <option value="transport_mobilite" {{ old('secteur_activite') == 'transport_mobilite' ? 'selected' : '' }}>Transport & Mobilité</option>
                                <option value="commerce_distribution" {{ old('secteur_activite') == 'commerce_distribution' ? 'selected' : '' }}>Commerce & Distribution</option>
                                <option value="services_financiers_assurance" {{ old('secteur_activite') == 'services_financiers_assurance' ? 'selected' : '' }}>Services financiers & Assurance</option>
                                <option value="telecoms_services_numeriques" {{ old('secteur_activite') == 'telecoms_services_numeriques' ? 'selected' : '' }}>Télécoms & Services numériques</option>
                                <option value="medias_culture_divertissement" {{ old('secteur_activite') == 'medias_culture_divertissement' ? 'selected' : '' }}>Médias, Culture & Divertissement</option>
                                <option value="tourisme_hospitalite" {{ old('secteur_activite') == 'tourisme_hospitalite' ? 'selected' : '' }}>Tourisme & Hospitalité</option>
                                <option value="sante_bien_etre" {{ old('secteur_activite') == 'sante_bien_etre' ? 'selected' : '' }}>Santé & Bien‑être</option>
                                <option value="education_formation" {{ old('secteur_activite') == 'education_formation' ? 'selected' : '' }}>Éducation & Formation</option>
                                <option value="services_professionnels" {{ old('secteur_activite') == 'services_professionnels' ? 'selected' : '' }}>Services professionnels</option>
                                <option value="recherche_innovation" {{ old('secteur_activite') == 'recherche_innovation' ? 'selected' : '' }}>Recherche & Innovation</option>
                                <option value="administration_services_publics" {{ old('secteur_activite') == 'administration_services_publics' ? 'selected' : '' }}>Administration & Services publics</option>
                                <option value="securite_defense" {{ old('secteur_activite') == 'securite_defense' ? 'selected' : '' }}>Sécurité & Défense</option>
                                <option value="impact_social_ong" {{ old('secteur_activite') == 'impact_social_ong' ? 'selected' : '' }}>Impact social & ONG</option>
                                <option value="services_personnels_domestiques" {{ old('secteur_activite') == 'services_personnels_domestiques' ? 'selected' : '' }}>Services personnels & Domestiques</option>
                            </select>
                            @error('secteur_activite')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Région -->
                        <div>
                            <label for="region" class="block text-sm font-medium text-gray-700 mb-2 required">
                                Région 
                            </label>
                            <x-region-select 
                                name="region" 
                                id="region" 
                                required 
                                :value="old('region')"
                                class="" 
                            />
                            @error('region')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Commune -->
                        <div>
                            <label for="commune" class="block text-sm font-medium text-gray-700 mb-2 required">
                                Commune 
                            </label>
                            <input type="text" id="commune" name="commune" value="{{ old('commune') }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('commune') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   placeholder="Ex: Cocody, Plateau, Marcory..." required>
                            @error('commune')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="md:col-span-2">
                            <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse complète
                            </label>
                            <textarea id="adresse" name="adresse" rows="3" 
                                      class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('adresse') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                      placeholder="Adresse complète de votre organisation">{{ old('adresse') }}</textarea>
                            @error('adresse')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                                Téléphone
                            </label>
                            <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('telephone') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   placeholder="+225 XX XX XX XX XX">
                            @error('telephone')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Site web -->
                        <div>
                            <label for="site_web" class="block text-sm font-medium text-gray-700 mb-2">
                                Site web
                            </label>
                            <input type="url" id="site_web" name="site_web" value="{{ old('site_web') }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('site_web') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   placeholder="https://www.exemple.com">
                            @error('site_web')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description de l'organisation
                            </label>
                            <textarea id="description" name="description" rows="4" 
                                      class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                      placeholder="Décrivez brièvement votre organisation, ses activités et sa mission...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Logo -->
                        <div class="md:col-span-2">
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                                Logo de l'organisation
                            </label>
                            <input type="file" id="logo" name="logo" accept="image/*" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm text-gray-900 bg-gray-50 @error('logo') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('logo')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Personne de contact -->
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i data-lucide="user-circle" class="w-5 h-5 mr-2 text-primary-600"></i>
                        Personne de contact
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom de la personne de contact -->
                        <div>
                            <label for="personne_contact_nom" class="block text-sm font-medium text-gray-700 mb-2 required">
                                Nom complet 
                            </label>
                            <input type="text" id="personne_contact_nom" name="personne_contact_nom" value="{{ old('personne_contact_nom') }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('personne_contact_nom') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   placeholder="Nom et prénom" required>
                            @error('personne_contact_nom')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fonction -->
                        <div>
                            <label for="personne_contact_fonction" class="block text-sm font-medium text-gray-700 mb-2 required">
                                Fonction / Poste 
                            </label>
                            <input type="text" id="personne_contact_fonction" name="personne_contact_fonction" value="{{ old('personne_contact_fonction') }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('personne_contact_fonction') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   placeholder="Ex: Directeur des Ressources Humaines" required>
                            @error('personne_contact_fonction')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone de contact -->
                        <div>
                            <label for="personne_contact_telephone" class="block text-sm font-medium text-gray-700 mb-2 required">
                                Téléphone 
                            </label>
                            <input type="tel" id="personne_contact_telephone" name="personne_contact_telephone" value="{{ old('personne_contact_telephone') }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('personne_contact_telephone') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   placeholder="+225 XX XX XX XX XX" required>
                            @error('personne_contact_telephone')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email de contact -->
                        <div>
                            <label for="personne_contact_email" class="block text-sm font-medium text-gray-700 mb-2 required">
                                Email 
                            </label>
                            <input type="email" id="personne_contact_email" name="personne_contact_email" value="{{ old('personne_contact_email') }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('personne_contact_email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   placeholder="contact@exemple.com" required>
                            <p class="mt-1 text-xs text-gray-500">Cet email sera utilisé pour l'authentification</p>
                            @error('personne_contact_email')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Conditions et engagement -->
                <div class="border-b border-gray-200 pb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i data-lucide="shield-check" class="w-5 h-5 mr-2 text-primary-600"></i>
                        Engagement et conditions
                    </h3>
                    
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">En devenant partenaire PEUB, vous vous engagez à :</h3>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-4 h-4 text-green-600 mt-1 mr-2 flex-shrink-0"></i>
                                Publier des opportunités concrètes et réalisables (bourses, stages, emplois, formations)
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-4 h-4 text-green-600 mt-1 mr-2 flex-shrink-0"></i>
                                Traiter les candidatures reçues dans un délai raisonnable (maximum 30 jours)
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-4 h-4 text-green-600 mt-1 mr-2 flex-shrink-0"></i>
                                Communiquer clairement les critères de sélection et les modalités d'évaluation
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-4 h-4 text-green-600 mt-1 mr-2 flex-shrink-0"></i>
                                Donner un retour constructif aux candidats non retenus
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-4 h-4 text-green-600 mt-1 mr-2 flex-shrink-0"></i>
                                Respecter les conditions annoncées (montants, durées, avantages)
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-4 h-4 text-green-600 mt-1 mr-2 flex-shrink-0"></i>
                                Maintenir un environnement professionnel bienveillant et inclusif
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check" class="w-4 h-4 text-green-600 mt-1 mr-2 flex-shrink-0"></i>
                                Contribuer au développement des compétences des jeunes talents ivoiriens
                            </li>
                        </ul>
                    </div>

                    <!-- Acceptation des conditions -->
                    <div>
                        <label class="flex items-start">
                            <input type="checkbox" id="accepter_conditions" name="accepter_conditions" value="1" 
                                   class="text-primary-600 focus:ring-primary-500 border-gray-300 @error('accepter_conditions') border-red-500 focus:ring-red-500 @enderror" required>
                            <span class="ml-3 text-sm text-gray-700">
                                J'accepte les <a href="#" class="text-primary-600 hover:text-primary-700 underline">conditions d'utilisation</a> 
                                et la <a href="#" class="text-primary-600 hover:text-primary-700 underline">politique de confidentialité</a> de PEUB. 
                                Je confirme que toutes les informations fournies sont exactes et complètes.
                                
                            </span>
                        </label>
                        @error('accepter_conditions')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <div class="flex justify-center pt-6">
                    <button type="submit" class="bg-gradient-to-r from-[#0E7490] to-[#0c5f7a] hover:from-[#0c5f7a] hover:to-[#0a4f66] text-white px-10 py-4 rounded-lg font-semibold text-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3">
                        <i data-lucide="send" class="w-5 h-5"></i>
                        Soumettre ma candidature partenaire
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Lucide icons
    lucide.createIcons();
    

});
</script>
@endpush 