@extends('layouts.guest')

@section('title', 'Compléter votre profil - PEUB')

@section('content')

@php
    // S'assurer que $sessionData est un tableau
    $sessionData = $sessionData ?? [];
    
    // Fonction helper pour récupérer la valeur (session ou old)
    $getValue = function($key, $default = '') use ($sessionData) {
        return $sessionData[$key] ?? old($key, $default);
    };
    
    // Vérifier si on a des fichiers temporaires en session
    $hasSessionFiles = isset($sessionData['piece_identite_file_temp']) || isset($sessionData['collante_bac_file_temp']);
@endphp
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header avec design moderne -->
        <div class="text-center mb-10">
            <div class="flex items-center justify-center mb-4">
                <div style="background: linear-gradient(135deg, #0E7490, #0c5f7a);" class="rounded-full p-4 shadow-lg">
                    <i data-lucide="user-plus" class="w-10 h-10 text-white"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-3">
                Bienvenue sur <span class="text-[#0E7490]">PEUB</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Complétez votre profil pour rejoindre la communauté d'excellence et accéder aux opportunités
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
                        <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                        Formulaire de Candidature
                    </h2>
                    <p class="text-white/90 mt-2">Remplissez soigneusement toutes les sections pour finaliser votre inscription</p>
                </div>
            </div>
            
            <form class="px-8 py-8 space-y-10" action="{{ route('auth.complete-profile.preview') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Section 1: Infos générales avec icône -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b-2 border-[#0E7490]/20">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-[#0E7490] to-[#0c5f7a] text-white shadow-md">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Informations générales</h3>
                            <p class="text-sm text-gray-500">Vos informations personnelles et coordonnées</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 required">Nom</label>
                            <input type="text" name="nom" id="nom" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   value="{{ $getValue('nom') }}">
                            @error('nom')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="prenoms" class="block text-sm font-medium text-gray-700 required">Prénoms</label>
                            <input type="text" name="prenoms" id="prenoms" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   value="{{ $getValue('prenoms') }}">
                            @error('prenoms')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="date_naissance" class="block text-sm font-medium text-gray-700 required">Date de naissance</label>
                            <input type="date" name="date_naissance" id="date_naissance" required 
                                   min="1990-01-01" max="2020-12-31"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   value="{{ $getValue('date_naissance') }}">
                            <p class="mt-1 text-xs text-gray-500">Format : jj/mm/aaaa</p>
                            @error('date_naissance')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="lieu_naissance" class="block text-sm font-medium text-gray-700 required">Lieu de naissance</label>
                            <input type="text" name="lieu_naissance" id="lieu_naissance" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   value="{{ $getValue('lieu_naissance') }}">
                            @error('lieu_naissance')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 required">Sexe</label>
                            <div class="flex gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="sexe" value="M" required {{ $getValue('sexe') == 'M' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2">Masculin</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="sexe" value="F" required {{ $getValue('sexe') == 'F' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2">Féminin</span>
                                </label>
                            </div>
                            @error('sexe')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="piece_identite_type" class="block text-sm font-medium text-gray-700 required">Type de pièce d'identité</label>
                            <select name="piece_identite_type" id="piece_identite_type" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                <option value="">Sélectionnez</option>
                                <option value="carte_scolaire" {{ $getValue('piece_identite_type') == 'carte_scolaire' ? 'selected' : '' }}>Carte Scolaire</option>
                                <option value="cni" {{ $getValue('piece_identite_type') == 'cni' ? 'selected' : '' }}>CNI</option>
                                <option value="attestation" {{ $getValue('piece_identite_type') == 'attestation' ? 'selected' : '' }}>Attestation</option>
                            </select>
                            @error('piece_identite_type')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="piece_identite_file" class="block text-sm font-medium text-gray-700 required">Pièce d'identité (scan)</label>
                            
                            @if(isset($sessionData['piece_identite_file_temp']))
                                <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm text-green-800 font-medium">✓ Fichier déjà téléchargé</span>
                                </div>
                                <input type="file" name="piece_identite_file" id="piece_identite_file" accept="image/*"
                                       class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                                <p class="mt-1 text-xs text-gray-500">Vous pouvez choisir un autre fichier si vous souhaitez le remplacer</p>
                            @else
                            <input type="file" name="piece_identite_file" id="piece_identite_file" required accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Taille max: 10MB</p>
                            @endif
                            @error('piece_identite_file')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="telephone_eleve" class="block text-sm font-medium text-gray-700 required">Téléphone (Élève)</label>
                            <input type="tel" name="telephone_eleve" id="telephone_eleve" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   value="{{ $getValue('telephone_eleve') }}" placeholder="+225 07 XX XX XX XX">
                            @error('telephone_eleve')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="telephone_parent" class="block text-sm font-medium text-gray-700 required">Téléphone (Parent)</label>
                            <input type="tel" name="telephone_parent" id="telephone_parent" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   value="{{ $getValue('telephone_parent') }}" placeholder="+225 05 XX XX XX XX">
                            @error('telephone_parent')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email_eleve" class="block text-sm font-medium text-gray-700 required">Email (Élève)</label>
                            <input type="email" name="email_eleve" id="email_eleve" required readonly
                                   class="mt-1 block w-full border-gray-300 rounded-md bg-gray-50 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   value="{{ auth()->user()->email }}">
                            <p class="mt-1 text-xs text-gray-500">Cet email est utilisé pour votre compte PEUB</p>
                            @error('email_eleve')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email_parent" class="block text-sm font-medium text-gray-700 required">Email (Parent)</label>
                            <input type="email" name="email_parent" id="email_parent" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   value="{{ $getValue('email_parent') }}">
                            @error('email_parent')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="region" class="block text-sm font-medium text-gray-700 required">Région</label>
                            <x-region-select 
                                name="region" 
                                id="region" 
                                required 
                                :value="old('region')"
                                class="mt-1" 
                            />
                            @error('region')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="commune" class="block text-sm font-medium text-gray-700 required">Commune</label>
                            <input type="text" name="commune" id="commune" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   value="{{ $getValue('commune') }}" placeholder="Ex: Cocody, Plateau...">
                            @error('commune')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="photo_profil" class="block text-sm font-medium text-gray-700">Photo de profil (optionnel)</label>
                            <input type="file" name="photo_profil" id="photo_profil" accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Taille max: 5MB</p>
                            @error('photo_profil')<p class="error-message">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Infos scolaires avec icône -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b-2 border-[#0E7490]/20">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-[#0E7490] to-[#0c5f7a] text-white shadow-md">
                            <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Informations scolaires</h3>
                            <p class="text-sm text-gray-500">Votre parcours académique et résultats au BAC</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="matricule_bac" class="block text-sm font-medium text-gray-700 required">Matricule BAC</label>
                            <input type="text" name="matricule_bac" id="matricule_bac" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   value="{{ $getValue('matricule_bac') }}">
                            @error('matricule_bac')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="serie_bac" class="block text-sm font-medium text-gray-700 required">Série BAC</label>
                            <select name="serie_bac" id="serie_bac" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                <option value="">Sélectionnez votre série</option>
                                <optgroup label="Séries Scientifiques">
                                    <option value="C" {{ $getValue('serie_bac') == 'C' ? 'selected' : '' }}>C - Scientifique (Maths, Physique)</option>
                                    <option value="E" {{ $getValue('serie_bac') == 'E' ? 'selected' : '' }}>E - Technique (Maths, Technologie)</option>
                                    <option value="D" {{ $getValue('serie_bac') == 'D' ? 'selected' : '' }}>D - Scientifique (SVT, Maths)</option>
                                </optgroup>
                                <optgroup label="Séries Littéraires">
                                    <option value="A1" {{ $getValue('serie_bac') == 'A1' ? 'selected' : '' }}>A1 - Littéraire (Maths + Langues)</option>
                                    <option value="A2" {{ $getValue('serie_bac') == 'A2' ? 'selected' : '' }}>A2 - Littéraire (Langues, Histoire, Géo)</option>
                                </optgroup>
                                <optgroup label="Techniques Industrielles">
                                    <option value="F1" {{ $getValue('serie_bac') == 'F1' ? 'selected' : '' }}>F1 - Mécanique Générale</option>
                                    <option value="F2" {{ $getValue('serie_bac') == 'F2' ? 'selected' : '' }}>F2 - Électronique</option>
                                    <option value="F3" {{ $getValue('serie_bac') == 'F3' ? 'selected' : '' }}>F3 - Électrotechnique</option>
                                    <option value="F4" {{ $getValue('serie_bac') == 'F4' ? 'selected' : '' }}>F4 - Génie Civil</option>
                                    <option value="F5" {{ $getValue('serie_bac') == 'F5' ? 'selected' : '' }}>F5 - Physique-Chimie</option>
                                    <option value="F6" {{ $getValue('serie_bac') == 'F6' ? 'selected' : '' }}>F6 - Constructions Mécaniques</option>
                                    <option value="F7" {{ $getValue('serie_bac') == 'F7' ? 'selected' : '' }}>F7 - Bois et Matériaux</option>
                                    <option value="F8" {{ $getValue('serie_bac') == 'F8' ? 'selected' : '' }}>F8 - Arts Appliqués</option>
                                </optgroup>
                                <optgroup label="Techniques Tertiaires">
                                    <option value="G1" {{ $getValue('serie_bac') == 'G1' ? 'selected' : '' }}>G1 - Secrétariat</option>
                                    <option value="G2" {{ $getValue('serie_bac') == 'G2' ? 'selected' : '' }}>G2 - Comptabilité</option>
                                    <option value="G3" {{ $getValue('serie_bac') == 'G3' ? 'selected' : '' }}>G3 - Commerce</option>
                                </optgroup>
                                <optgroup label="Brevets Professionnels">
                                    <option value="BT" {{ $getValue('serie_bac') == 'BT' ? 'selected' : '' }}>BT - Brevet de Technicien</option>
                                    <option value="BP" {{ $getValue('serie_bac') == 'BP' ? 'selected' : '' }}>BP - Brevet Professionnel</option>
                                </optgroup>
                            </select>
                            @error('serie_bac')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="note_bac" class="block text-sm font-medium text-gray-700 required">Note BAC</label>
                            <div class="relative">
                                <input type="number" step="0.01" min="0" max="400" name="note_bac" id="note_bac" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm pr-16"
                                       value="{{ $getValue('note_bac') }}" placeholder="Ex: 315.50">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-400 text-sm">/400</span>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center justify-between">
                                <p class="text-xs text-[#0E7490] font-medium flex items-center gap-1">
                                    <i data-lucide="info" class="w-3 h-3"></i>
                                    Note sur 400 points (système ivoirien)
                                </p>
                                <div id="mention-badge" class="hidden px-3 py-1 rounded-full text-xs font-bold"></div>
                            </div>
                            @error('note_bac')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="annee_bac" class="block text-sm font-medium text-gray-700 required">Année d'obtention</label>
                            <select name="annee_bac" id="annee_bac" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                <option value="">Sélectionnez</option>
                                <option value="2022" {{ $getValue('annee_bac') == '2022' ? 'selected' : '' }}>2022</option>
                                <option value="2023" {{ $getValue('annee_bac') == '2023' ? 'selected' : '' }}>2023</option>
                                <option value="2024" {{ $getValue('annee_bac') == '2024' ? 'selected' : '' }}>2024</option>
                                <option value="2025" {{ $getValue('annee_bac') == '2025' ? 'selected' : '' }}>2025</option>
                            </select>
                            @error('annee_bac')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="etablissement_nom" class="block text-sm font-medium text-gray-700 required">Établissement d'origine</label>
                            <select name="etablissement_nom" id="etablissement_nom" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                    onchange="updateEtablissementType()">
                                <option value="">Sélectionnez un établissement</option>
                                @foreach($etablissements as $etab)
                                    <option value="{{ $etab->etablissement }}" 
                                            data-type="{{ $etab->type_etab }}"
                                            {{ $getValue('etablissement_nom') == $etab->etablissement ? 'selected' : '' }}>
                                        {{ $etab->etablissement }}
                                    </option>
                                @endforeach
                            </select>
                            @error('etablissement_nom')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="etablissement_type" class="block text-sm font-medium text-gray-700 required">Type d'établissement</label>
                            <select name="etablissement_type" id="etablissement_type" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                <option value="">Sélectionnez</option>
                                <option value="public" {{ $getValue('etablissement_type') == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="prive_homologue" {{ $getValue('etablissement_type') == 'prive_homologue' ? 'selected' : '' }}>Privé Homologué</option>
                                <option value="prive_non_homologue" {{ $getValue('etablissement_type') == 'prive_non_homologue' ? 'selected' : '' }}>Privé Non Homologué</option>
                            </select>
                            @error('etablissement_type')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="collante_bac_file" class="block text-sm font-medium text-gray-700 required">Collante BAC (scan)</label>
                            
                            @if(isset($sessionData['collante_bac_file_temp']))
                                <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm text-green-800 font-medium">✓ Fichier déjà téléchargé</span>
                                </div>
                                <input type="file" name="collante_bac_file" id="collante_bac_file" accept="image/*"
                                       class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                                <p class="mt-1 text-xs text-gray-500">Vous pouvez choisir un autre fichier si vous souhaitez le remplacer</p>
                            @else
                            <input type="file" name="collante_bac_file" id="collante_bac_file" required accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Taille max: 10MB</p>
                            @endif
                            @error('collante_bac_file')<p class="error-message">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Situation sociale avec icône -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b-2 border-[#0E7490]/20">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-[#0E7490] to-[#0c5f7a] text-white shadow-md">
                            <i data-lucide="home" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Situation sociale</h3>
                            <p class="text-sm text-gray-500">Votre environnement socio-économique et accès au numérique</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 required">Situation scolaire</label>
                            <div class="flex gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="pensionnaire_internat" value="1" required {{ $getValue('pensionnaire_internat') == '1' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2">Pensionnaire</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="pensionnaire_internat" value="0" {{ $getValue('pensionnaire_internat') == '0' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2">Non pensionnaire</span>
                                </label>
                            </div>
                            @error('pensionnaire_internat')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 required">Bourse au lycée</label>
                            <div class="flex gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="bourse_scolaire_lycee" value="1" required {{ $getValue('bourse_scolaire_lycee') == '1' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2">Oui</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="bourse_scolaire_lycee" value="0" {{ $getValue('bourse_scolaire_lycee') == '0' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2">Non</span>
                                </label>
                            </div>
                            @error('bourse_scolaire_lycee')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="profession_pere" class="block text-sm font-medium text-gray-700 required">Profession du père</label>
                            <select name="profession_pere" id="profession_pere" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                <option value="">Sélectionnez une catégorie</option>
                                <option value="cadres_professions_intellectuelles" {{ $getValue('profession_pere') == 'cadres_professions_intellectuelles' ? 'selected' : '' }}>Cadres, professions intellectuelles sup.</option>
                                <option value="administration_services" {{ $getValue('profession_pere') == 'administration_services' ? 'selected' : '' }}>Administration / services</option>
                                <option value="employes_bureau" {{ $getValue('profession_pere') == 'employes_bureau' ? 'selected' : '' }}>Employés de bureau</option>
                                <option value="ouvriers_qualifies_artisans" {{ $getValue('profession_pere') == 'ouvriers_qualifies_artisans' ? 'selected' : '' }}>Ouvriers qualifiés / artisans</option>
                                <option value="travailleurs_agricoles_pecheurs" {{ $getValue('profession_pere') == 'travailleurs_agricoles_pecheurs' ? 'selected' : '' }}>Travailleurs agricoles, pêcheurs</option>
                                <option value="travailleurs_non_qualifies" {{ $getValue('profession_pere') == 'travailleurs_non_qualifies' ? 'selected' : '' }}>Travailleurs non qualifiés</option>
                                <option value="sans_emploi_informel" {{ $getValue('profession_pere') == 'sans_emploi_informel' ? 'selected' : '' }}>Sans emploi ou informel non déclaré</option>
                                <option value="non_applicable" {{ $getValue('profession_pere') == 'non_applicable' ? 'selected' : '' }}>Non applicable</option>
                            </select>
                            @error('profession_pere')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="profession_mere" class="block text-sm font-medium text-gray-700 required">Profession de la mère</label>
                            <select name="profession_mere" id="profession_mere" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                <option value="">Sélectionnez une catégorie</option>
                                <option value="cadres_professions_intellectuelles" {{ $getValue('profession_mere') == 'cadres_professions_intellectuelles' ? 'selected' : '' }}>Cadres, professions intellectuelles sup.</option>
                                <option value="administration_services" {{ $getValue('profession_mere') == 'administration_services' ? 'selected' : '' }}>Administration / services</option>
                                <option value="employes_bureau" {{ $getValue('profession_mere') == 'employes_bureau' ? 'selected' : '' }}>Employés de bureau</option>
                                <option value="ouvriers_qualifies_artisans" {{ $getValue('profession_mere') == 'ouvriers_qualifies_artisans' ? 'selected' : '' }}>Ouvriers qualifiés / artisans</option>
                                <option value="travailleurs_agricoles_pecheurs" {{ $getValue('profession_mere') == 'travailleurs_agricoles_pecheurs' ? 'selected' : '' }}>Travailleurs agricoles, pêcheurs</option>
                                <option value="travailleurs_non_qualifies" {{ $getValue('profession_mere') == 'travailleurs_non_qualifies' ? 'selected' : '' }}>Travailleurs non qualifiés</option>
                                <option value="sans_emploi_informel" {{ $getValue('profession_mere') == 'sans_emploi_informel' ? 'selected' : '' }}>Sans emploi ou informel non déclaré</option>
                                <option value="non_applicable" {{ $getValue('profession_mere') == 'non_applicable' ? 'selected' : '' }}>Non applicable</option>
                            </select>
                            @error('profession_mere')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="connexion_internet" class="block text-sm font-medium text-gray-700 required">Accès internet</label>
                            <select name="connexion_internet" id="connexion_internet" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                <option value="">Sélectionnez</option>
                                <option value="aucune" {{ $getValue('connexion_internet') == 'aucune' ? 'selected' : '' }}>Aucun</option>
                                <option value="3g_4g" {{ $getValue('connexion_internet') == '3g_4g' ? 'selected' : '' }}>3G/4G (Mobile)</option>
                                <option value="fibre" {{ $getValue('connexion_internet') == 'fibre' ? 'selected' : '' }}>Fibre optique</option>
                            </select>
                            @error('connexion_internet')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2 required">Équipement numérique</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="possede_ordinateur" value="1" required {{ $getValue('possede_ordinateur') == '1' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2 text-sm">Possède un ordinateur</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="possede_ordinateur" value="0" {{ $getValue('possede_ordinateur') == '0' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2 text-sm">Ne possède pas d'ordinateur</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="acces_smartphone" value="1" required {{ $getValue('acces_smartphone') == '1' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2 text-sm">Accès smartphone</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="acces_smartphone" value="0" {{ $getValue('acces_smartphone') == '0' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2 text-sm">Pas d'accès smartphone</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="acces_ia" value="1" required {{ $getValue('acces_ia') == '1' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2 text-sm">Accès IA (ChatGPT, etc.)</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="acces_ia" value="0" {{ $getValue('acces_ia') == '0' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                    <span class="ml-2 text-sm">Pas d'accès IA</span>
                                </label>
                            </div>
                            @error('possede_ordinateur')<p class="error-message">{{ $message }}</p>@enderror
                            @error('acces_smartphone')<p class="error-message">{{ $message }}</p>@enderror
                            @error('acces_ia')<p class="error-message">{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Situations particulières</label>
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="situations_particulieres[]" value="handicap"
                                           {{ in_array('handicap', old('situations_particulieres', [])) ? 'checked' : '' }}
                                           class="form-checkbox text-primary-600">
                                    <span class="ml-2">Handicap</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="situations_particulieres[]" value="orphelin"
                                           {{ in_array('orphelin', old('situations_particulieres', [])) ? 'checked' : '' }}
                                           class="form-checkbox text-primary-600">
                                    <span class="ml-2">Orphelin</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="situations_particulieres[]" value="autre"
                                           {{ in_array('autre', old('situations_particulieres', [])) ? 'checked' : '' }}
                                           class="form-checkbox text-primary-600">
                                    <span class="ml-2">Autre situation</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Motivation avec icône -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b-2 border-[#0E7490]/20">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-[#0E7490] to-[#0c5f7a] text-white shadow-md">
                            <i data-lucide="message-square" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Lettre de motivation</h3>
                            <p class="text-sm text-gray-500">Exprimez votre motivation à rejoindre le programme PEUB</p>
                        </div>
                    </div>
                    
                    <div>
                        <label for="motivation" class="block text-sm font-medium text-gray-700 required">Lettre de motivation</label>
                        <div class="relative">
                            <textarea name="motivation" id="motivation" rows="8" required maxlength="5000"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                      placeholder="Expliquez pourquoi vous souhaitez rejoindre PEUB et comment ce programme peut contribuer à votre parcours académique et professionnel...">{{ $getValue('motivation') }}</textarea>
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400" id="char-count">
                                <span id="current-chars">0</span> / 5000 caractères
                            </div>
                        </div>
                        <div class="mt-2 flex items-start gap-2 text-xs text-gray-600 bg-blue-50 p-3 rounded-lg border border-blue-200">
                            <i data-lucide="lightbulb" class="w-4 h-4 text-[#0E7490] flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="font-medium text-[#0E7490] mb-1">Conseils pour une bonne lettre :</p>
                                <ul class="space-y-1 list-disc list-inside">
                                    <li>Présentez vos ambitions académiques et professionnelles</li>
                                    <li>Expliquez en quoi PEUB peut vous aider à les atteindre</li>
                                    <li>Mettez en avant vos qualités et votre détermination</li>
                                </ul>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 font-medium">Minimum 100 caractères requis</p>
                        @error('motivation')<p class="error-message">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Conditions avec design amélioré -->
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-6 border-2 border-[#0E7490]/20">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="flex-shrink-0">
                            <i data-lucide="shield-check" class="w-6 h-6 text-[#0E7490]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Acceptations et engagements</h4>
                            <p class="text-sm text-gray-600">Veuillez lire et accepter les conditions suivantes</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="flex items-start p-4 bg-white rounded-lg border-2 border-transparent hover:border-[#0E7490]/30 transition-all cursor-pointer group">
                            <input type="checkbox" name="acceptation_conditions" required
                                   class="mt-1 h-5 w-5 text-[#0E7490] focus:ring-[#0E7490] border-gray-300 rounded">
                            <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900">
                                <span class="font-semibold">Je certifie l'exactitude des informations.</span> 
                                Je confirme que toutes les informations fournies sont exactes et complètes. *
                            </span>
                        </label>
                        <label class="flex items-start p-4 bg-white rounded-lg border-2 border-transparent hover:border-[#0E7490]/30 transition-all cursor-pointer group">
                            <input type="checkbox" name="acceptation_donnees" required
                                   class="mt-1 h-5 w-5 text-[#0E7490] focus:ring-[#0E7490] border-gray-300 rounded">
                            <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900">
                                <span class="font-semibold">J'accepte la politique de confidentialité.</span>
                                J'accepte le traitement de mes données personnelles conformément à la 
                                <a href="#" class="text-[#0E7490] underline hover:text-[#0c5f7a]">politique de confidentialité</a>. *
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button avec design moderne -->
                <div class="pt-6">
                    <button type="submit" 
                            style="background: linear-gradient(to right, #0E7490, #0c5f7a);"
                            class="group relative w-full flex justify-center items-center py-5 px-6 border-2 border-transparent rounded-xl shadow-lg text-base font-bold text-white hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-cyan-700/30 transition-all duration-300 transform hover:scale-[1.02]"
                            onmouseover="this.style.background='linear-gradient(to right, #0c5f7a, #0a4f63)'"
                            onmouseout="this.style.background='linear-gradient(to right, #0E7490, #0c5f7a)'">
                        <span class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="text-lg">Prévisualiser ma candidature</span>
                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                        <div class="absolute inset-0 rounded-xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    </button>
                    <p class="mt-4 text-center text-sm text-gray-500">
                        En soumettant ce formulaire, vous rejoignez une communauté de 
                        <span class="font-semibold text-[#0E7490]">plus de 2000 bacheliers d'excellence</span>
                    </p>
                </div>
            </form>
        </div>

        <!-- Section d'aide en bas -->
        <div class="mt-8 bg-white rounded-xl p-6 shadow-md border border-gray-100">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-full bg-[#0E7490]/10 flex items-center justify-center">
                        <i data-lucide="help-circle" class="w-6 h-6 text-[#0E7490]"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 mb-2">Besoin d'aide ?</h3>
                    <p class="text-sm text-gray-600 mb-3">
                        Si vous rencontrez des difficultés pour remplir ce formulaire, n'hésitez pas à nous contacter.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="mailto:support@peub.ci" class="inline-flex items-center gap-2 text-sm font-medium text-[#0E7490] hover:text-[#0c5f7a]">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            support@peub.ci
                        </a>
                        <a href="tel:+2250700000000" class="inline-flex items-center gap-2 text-sm font-medium text-[#0E7490] hover:text-[#0c5f7a]">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                            +225 07 00 00 00 00
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Compteur de caractères pour la motivation
        const motivationField = document.getElementById('motivation');
        const currentChars = document.getElementById('current-chars');
        
        if (motivationField && currentChars) {
            motivationField.addEventListener('input', function() {
                currentChars.textContent = this.value.length;
            });
            
            // Initialiser le compteur
            currentChars.textContent = motivationField.value.length;
        }
        
        // Calculateur de mention BAC en temps réel
        const noteBacField = document.getElementById('note_bac');
        const mentionBadge = document.getElementById('mention-badge');
        
        if (noteBacField && mentionBadge) {
            noteBacField.addEventListener('input', function() {
                const note = parseFloat(this.value);
                
                if (isNaN(note) || note < 0) {
                    mentionBadge.classList.add('hidden');
                    return;
                }
                
                let mentionText = '';
                let mentionClass = '';
                
                if (note < 240) {
                    mentionBadge.classList.add('hidden');
                } else if (note < 280) {
                    mentionText = '🎓 Mention: PASSABLE';
                    mentionClass = 'bg-blue-100 text-blue-800';
                } else if (note < 320) {
                    mentionText = '🎓 Mention: ASSEZ BIEN';
                    mentionClass = 'bg-green-100 text-green-800';
                } else if (note < 360) {
                    mentionText = '🎓 Mention: BIEN';
                    mentionClass = 'bg-orange-100 text-orange-800';
                } else if (note <= 400) {
                    mentionText = '🎓 Mention: TRÈS BIEN';
                    mentionClass = 'bg-purple-100 text-purple-800';
                } else {
                    mentionBadge.classList.add('hidden');
                    return;
                }
                
                mentionBadge.textContent = mentionText;
                mentionBadge.className = `px-3 py-1 rounded-full text-xs font-bold ${mentionClass}`;
                mentionBadge.classList.remove('hidden');
            });
            
            // Calculer la mention si une note existe déjà (old value)
            if (noteBacField.value) {
                noteBacField.dispatchEvent(new Event('input'));
            }
        }
        
        // Initialiser les icônes Lucide après le chargement
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
    
    // Fonction pour mettre à jour automatiquement le type d'établissement
    function updateEtablissementType() {
        const etablissementSelect = document.getElementById('etablissement_nom');
        const typeSelect = document.getElementById('etablissement_type');
        
        const selectedOption = etablissementSelect.options[etablissementSelect.selectedIndex];
        const typeValue = selectedOption.getAttribute('data-type');
        
        if (typeValue) {
            typeSelect.value = typeValue;
        } else {
            typeSelect.value = '';
        }
    }
</script>
@endpush
@endsection
