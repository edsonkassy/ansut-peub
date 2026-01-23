@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Mon Profil</h1>
                    <p class="text-gray-600">Gérez les informations de votre organisation</p>
                </div>
                <div>
                    <a href="{{ route('partenaire.dashboard') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                        Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 p-4">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200  p-4">
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white shadow-sm border border-gray-200">
            <div class=" p-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i data-lucide="building" class="w-5 h-5 mr-2 text-primary-600"></i>
                    Informations de l'organisation
                </h3>
            </div>
            
            <form action="{{ route('partenaire.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom de l'organisation -->
                    <div class="md:col-span-2">
                        <label for="nom_organisation" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de l'organisation <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nom_organisation" id="nom_organisation" 
                               value="{{ old('nom_organisation', $partenaire->nom_organisation) }}"
                               class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                               required>
                        @error('nom_organisation')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type d'organisation -->
                    <div>
                        <label for="type_organisation" class="block text-sm font-medium text-gray-700 mb-2">
                            Type d'organisation <span class="text-red-500">*</span>
                        </label>
                        <select name="type_organisation" id="type_organisation" 
                                class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" required>
                            <option value="">Sélectionnez le type</option>
                            <option value="entreprise" {{ old('type_organisation', $partenaire->type_organisation) == 'entreprise' ? 'selected' : '' }}>
                                Entreprise privée
                            </option>
                            <option value="institution_academique" {{ old('type_organisation', $partenaire->type_organisation) == 'institution_academique' ? 'selected' : '' }}>
                                Institution académique
                            </option>
                            <option value="ong" {{ old('type_organisation', $partenaire->type_organisation) == 'ong' ? 'selected' : '' }}>
                                ONG / Association
                            </option>
                            <option value="gouvernement" {{ old('type_organisation', $partenaire->type_organisation) == 'gouvernement' ? 'selected' : '' }}>
                                Organisme gouvernemental
                            </option>
                        </select>
                        @error('type_organisation')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Secteur d'activité -->
                    <div>
                        <label for="secteur_activite" class="block text-sm font-medium text-gray-700 mb-2">
                            Secteur d'activité
                        </label>
                        <select name="secteur_activite" id="secteur_activite" 
                                class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Sélectionnez un secteur</option>
                            <option value="agro_agroalimentaire" {{ old('secteur_activite', $partenaire->secteur_activite) == 'agro_agroalimentaire' ? 'selected' : '' }}>Agro & Agro‑alimentaire</option>
                            <option value="ressources_mines" {{ old('secteur_activite', $partenaire->secteur_activite) == 'ressources_mines' ? 'selected' : '' }}>Ressources & Mines</option>
                            <option value="energie_climat" {{ old('secteur_activite', $partenaire->secteur_activite) == 'energie_climat' ? 'selected' : '' }}>Énergie & Climat</option>
                            <option value="eau_dechets_recyclage" {{ old('secteur_activite', $partenaire->secteur_activite) == 'eau_dechets_recyclage' ? 'selected' : '' }}>Eau, Déchets & Recyclage</option>
                            <option value="industrie_fabrication" {{ old('secteur_activite', $partenaire->secteur_activite) == 'industrie_fabrication' ? 'selected' : '' }}>Industrie & Fabrication</option>
                            <option value="construction_immobilier" {{ old('secteur_activite', $partenaire->secteur_activite) == 'construction_immobilier' ? 'selected' : '' }}>Construction & Immobilier</option>
                            <option value="transport_mobilite" {{ old('secteur_activite', $partenaire->secteur_activite) == 'transport_mobilite' ? 'selected' : '' }}>Transport & Mobilité</option>
                            <option value="commerce_distribution" {{ old('secteur_activite', $partenaire->secteur_activite) == 'commerce_distribution' ? 'selected' : '' }}>Commerce & Distribution</option>
                            <option value="services_financiers_assurance" {{ old('secteur_activite', $partenaire->secteur_activite) == 'services_financiers_assurance' ? 'selected' : '' }}>Services financiers & Assurance</option>
                            <option value="telecoms_services_numeriques" {{ old('secteur_activite', $partenaire->secteur_activite) == 'telecoms_services_numeriques' ? 'selected' : '' }}>Télécoms & Services numériques</option>
                            <option value="medias_culture_divertissement" {{ old('secteur_activite', $partenaire->secteur_activite) == 'medias_culture_divertissement' ? 'selected' : '' }}>Médias, Culture & Divertissement</option>
                            <option value="tourisme_hospitalite" {{ old('secteur_activite', $partenaire->secteur_activite) == 'tourisme_hospitalite' ? 'selected' : '' }}>Tourisme & Hospitalité</option>
                            <option value="sante_bien_etre" {{ old('secteur_activite', $partenaire->secteur_activite) == 'sante_bien_etre' ? 'selected' : '' }}>Santé & Bien‑être</option>
                            <option value="education_formation" {{ old('secteur_activite', $partenaire->secteur_activite) == 'education_formation' ? 'selected' : '' }}>Éducation & Formation</option>
                            <option value="services_professionnels" {{ old('secteur_activite', $partenaire->secteur_activite) == 'services_professionnels' ? 'selected' : '' }}>Services professionnels</option>
                            <option value="recherche_innovation" {{ old('secteur_activite', $partenaire->secteur_activite) == 'recherche_innovation' ? 'selected' : '' }}>Recherche & Innovation</option>
                            <option value="administration_services_publics" {{ old('secteur_activite', $partenaire->secteur_activite) == 'administration_services_publics' ? 'selected' : '' }}>Administration & Services publics</option>
                            <option value="securite_defense" {{ old('secteur_activite', $partenaire->secteur_activite) == 'securite_defense' ? 'selected' : '' }}>Sécurité & Défense</option>
                            <option value="impact_social_ong" {{ old('secteur_activite', $partenaire->secteur_activite) == 'impact_social_ong' ? 'selected' : '' }}>Impact social & ONG</option>
                            <option value="services_personnels_domestiques" {{ old('secteur_activite', $partenaire->secteur_activite) == 'services_personnels_domestiques' ? 'selected' : '' }}>Services personnels & Domestiques</option>
                        </select>
                        @error('secteur_activite')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Région -->
                    <div>
                        <label for="region" class="block text-sm font-medium text-gray-700 mb-2">
                            Région <span class="text-red-500">*</span>
                        </label>
                        <x-region-select 
                            name="region" 
                            id="region" 
                            required 
                            :value="old('region', $partenaire->region)"
                            class=""
                        />
                        @error('region')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Commune -->
                    <div>
                        <label for="commune" class="block text-sm font-medium text-gray-700 mb-2">
                            Commune <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="commune" id="commune" 
                               value="{{ old('commune', $partenaire->commune) }}"
                               class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                               placeholder="Ex: Cocody, Plateau, Marcory..." required>
                        @error('commune')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Adresse -->
                    <div class="md:col-span-2">
                        <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse complète
                        </label>
                        <textarea name="adresse" id="adresse" rows="3"
                                  class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                  placeholder="Adresse complète de votre organisation">{{ old('adresse', $partenaire->adresse) }}</textarea>
                        @error('adresse')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone
                        </label>
                        <input type="tel" name="telephone" id="telephone" 
                               value="{{ old('telephone', $partenaire->telephone) }}"
                               class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                               placeholder="+225 XX XX XX XX XX">
                        @error('telephone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Site web -->
                    <div>
                        <label for="site_web" class="block text-sm font-medium text-gray-700 mb-2">
                            Site web
                        </label>
                        <input type="url" name="site_web" id="site_web" 
                               value="{{ old('site_web', $partenaire->site_web) }}"
                               class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                               placeholder="https://www.exemple.com">
                        @error('site_web')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description de l'organisation
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                  placeholder="Décrivez votre organisation en quelques lignes">{{ old('description', $partenaire->description) }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Logo -->
                    <div class="md:col-span-2">
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Logo de l'organisation
                        </label>
                        @if($partenaire->logo)
                            <div class="mb-4">
                                <img src="{{ Storage::url($partenaire->logo) }}" alt="Logo actuel" class="h-20 w-auto border border-gray-200">
                                <p class="text-sm text-gray-500 mt-1">Logo actuel</p>
                            </div>
                        @endif
                        <div class="mt-2">
                            <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/webp"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        </div>
                        @error('logo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations de contact -->
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i data-lucide="user" class="w-5 h-5 mr-2 text-primary-600"></i>
                        Personne de contact
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom de la personne de contact -->
                        <div>
                            <label for="personne_contact_nom" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="personne_contact_nom" id="personne_contact_nom" 
                                   value="{{ old('personne_contact_nom', $partenaire->personne_contact_nom) }}"
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   required>
                            @error('personne_contact_nom')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fonction de la personne de contact -->
                        <div>
                            <label for="personne_contact_fonction" class="block text-sm font-medium text-gray-700 mb-2">
                                Fonction <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="personne_contact_fonction" id="personne_contact_fonction" 
                                   value="{{ old('personne_contact_fonction', $partenaire->personne_contact_fonction) }}"
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   required>
                            @error('personne_contact_fonction')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email de la personne de contact -->
                        <div>
                            <label for="personne_contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="personne_contact_email" id="personne_contact_email" 
                                   value="{{ old('personne_contact_email', $partenaire->personne_contact_email) }}"
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm bg-gray-50"
                                   readonly>
                            <p class="mt-2 text-sm text-gray-500">L'email ne peut pas être modifié car il est lié à votre compte.</p>
                            @error('personne_contact_email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Téléphone de la personne de contact -->
                        <div>
                            <label for="personne_contact_telephone" class="block text-sm font-medium text-gray-700 mb-2">
                                Téléphone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="personne_contact_telephone" id="personne_contact_telephone" 
                                   value="{{ old('personne_contact_telephone', $partenaire->personne_contact_telephone) }}"
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   required>
                            @error('personne_contact_telephone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div class="flex justify-between">
                        <a href="{{ route('partenaire.dashboard') }}" class="inline-flex items-center px-4 py-2 text-base font-medium text-gray-700 hover:text-gray-900">
                            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                            Retour
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection