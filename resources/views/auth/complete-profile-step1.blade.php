@extends('layouts.guest')
@section('title', 'Compléter votre profil - Étape 1/4')
@section('content')
<style>
* { touch-action: pan-y; }
</style>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-[#0E7490]">Bienvenue sur PEUB</h1>
            <p class="text-gray-500 text-sm mt-1">Complétez votre profil - Étape 1 sur 4</p>
        </div>

        <div class="flex items-center justify-center mb-6"><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-[#0E7490] text-white flex items-center justify-center text-sm font-bold shadow-lg ring-4 ring-[#0E7490]/20">1</div></div><span class="text-xs text-[#0E7490] font-semibold hidden sm:block">Infos générales</span></div><div class="h-px w-8 bg-gray-200 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">2</div></div><span class="text-xs text-gray-400 hidden sm:block">Infos scolaires</span></div><div class="h-px w-8 bg-gray-200 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">3</div></div><span class="text-xs text-gray-400 hidden sm:block">Situation sociale</span></div><div class="h-px w-8 bg-gray-200 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">4</div></div><span class="text-xs text-gray-400 hidden sm:block">Motivation</span></div></div>
        </div>

        @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <form method="POST" action="{{ route('auth.complete-profile.step.save', 1) }}" enctype="multipart/form-data" style="touch-action: pan-y;">
                @csrf
                                <div id="step-1" class="space-y-6">
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
                            <input type="text" name="date_naissance" placeholder="jj/mm/aaaa" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}" id="date_naissance" required 
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
                            <select style="touch-action: pan-y;" name="piece_identite_type" id="piece_identite_type" required
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

                <!-- Bouton Suivant -->
                


                <div class="pt-4 flex gap-3">
                    
                    <button type="submit" style="background: linear-gradient(to right, #0E7490, #0c5f7a);" class="flex-1 py-3 text-white rounded-xl font-bold">
                        Suivant →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
