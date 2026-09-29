@extends('layouts.guest')
@section('title', 'Compléter votre profil - Étape 2/4')
@section('content')
<style>
* { touch-action: pan-y; }
</style>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-[#0E7490]">Bienvenue sur PEUB</h1>
            <p class="text-gray-500 text-sm mt-1">Complétez votre profil - Étape 2 sur 4</p>
        </div>

        <div class="flex items-center justify-center mb-6"><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold shadow">✓</div></div><span class="text-xs text-green-600 hidden sm:block">Infos générales</span></div><div class="h-px w-8 bg-green-400 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-[#0E7490] text-white flex items-center justify-center text-sm font-bold shadow-lg ring-4 ring-[#0E7490]/20">2</div></div><span class="text-xs text-[#0E7490] font-semibold hidden sm:block">Infos scolaires</span></div><div class="h-px w-8 bg-gray-200 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">3</div></div><span class="text-xs text-gray-400 hidden sm:block">Situation sociale</span></div><div class="h-px w-8 bg-gray-200 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">4</div></div><span class="text-xs text-gray-400 hidden sm:block">Motivation</span></div></div>
        </div>

        @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <form method="POST" action="{{ route('auth.complete-profile.step.save', 2) }}" enctype="multipart/form-data" style="touch-action: pan-y;">
                @csrf
                                <!-- Section 2: Infos scolaires avec icône -->
                <div id="step-2" class="space-y-6 hidden-step">
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
                            <select style="touch-action: pan-y;" name="serie_bac" id="serie_bac" required
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
                            <select style="touch-action: pan-y;" name="annee_bac" id="annee_bac" required
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
                            <select style="touch-action: pan-y;" name="etablissement_nom" id="etablissement_nom" required
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
                            <select style="touch-action: pan-y;" name="etablissement_type" id="etablissement_type" required
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


                <div class="pt-4 flex gap-3">
                    <a href="{{ route('auth.complete-profile.step', 1) }}" class="flex-1 text-center py-3 border-2 border-[#0E7490] text-[#0E7490] rounded-xl font-medium">← Retour</a>
                    <button type="submit" style="background: linear-gradient(to right, #0E7490, #0c5f7a);" class="flex-1 py-3 text-white rounded-xl font-bold">
                        Suivant →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
