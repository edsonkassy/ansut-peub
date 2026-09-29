@extends('layouts.guest')
@section('title', 'Compléter votre profil - Étape 3/4')
@section('content')
<style>
* { touch-action: pan-y; }
</style>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-[#0E7490]">Bienvenue sur PEUB</h1>
            <p class="text-gray-500 text-sm mt-1">Complétez votre profil - Étape 3 sur 4</p>
        </div>

        <div class="flex items-center justify-center mb-6"><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold shadow">✓</div></div><span class="text-xs text-green-600 hidden sm:block">Infos générales</span></div><div class="h-px w-8 bg-green-400 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold shadow">✓</div></div><span class="text-xs text-green-600 hidden sm:block">Infos scolaires</span></div><div class="h-px w-8 bg-green-400 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-[#0E7490] text-white flex items-center justify-center text-sm font-bold shadow-lg ring-4 ring-[#0E7490]/20">3</div></div><span class="text-xs text-[#0E7490] font-semibold hidden sm:block">Situation sociale</span></div><div class="h-px w-8 bg-gray-200 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-sm font-bold">4</div></div><span class="text-xs text-gray-400 hidden sm:block">Motivation</span></div></div>
        </div>

        @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <form method="POST" action="{{ route('auth.complete-profile.step.save', 3) }}" enctype="multipart/form-data" style="touch-action: pan-y;">
                @csrf
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
                            <select style="touch-action: pan-y;" name="profession_pere" id="profession_pere" required
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
                            <select style="touch-action: pan-y;" name="profession_mere" id="profession_mere" required
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
                            <select style="touch-action: pan-y;" name="connexion_internet" id="connexion_internet" required
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


                <div class="pt-4 flex gap-3">
                    <a href="{{ route('auth.complete-profile.step', 2) }}" class="flex-1 text-center py-3 border-2 border-[#0E7490] text-[#0E7490] rounded-xl font-medium">← Retour</a>
                    <button type="submit" style="background: linear-gradient(to right, #0E7490, #0c5f7a);" class="flex-1 py-3 text-white rounded-xl font-bold">
                        Suivant →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
