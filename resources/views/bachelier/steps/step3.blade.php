<!-- Step 3: Situation Socio-économique -->
<div class="step hidden" id="step3">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Situation sociale</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Situation scolaire -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Situation scolaire *</label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="pensionnaire_internat" value="1" required
                               {{ old('pensionnaire_internat') == '1' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Pensionnaire (Internat)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="pensionnaire_internat" value="0"
                               {{ old('pensionnaire_internat') == '0' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Non pensionnaire</span>
                    </label>
                </div>
                @error('pensionnaire_internat')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bénéficiaire bourse -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bénéficiaire d'une bourse scolaire au lycée *</label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="bourse_scolaire_lycee" value="1" required
                               {{ old('bourse_scolaire_lycee') == '1' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Oui</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="bourse_scolaire_lycee" value="0"
                               {{ old('bourse_scolaire_lycee') == '0' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Non</span>
                    </label>
                </div>
                @error('bourse_scolaire_lycee')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Profession du père -->
            <div>
                <label for="profession_pere" class="block text-sm font-medium text-gray-700 mb-2">Profession du père/tuteur *</label>
                <select name="profession_pere" id="profession_pere" required
                        class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('profession_pere') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    <option value="">Sélectionnez une profession</option>
                    <option value="1" {{ old('profession_pere') == '1' ? 'selected' : '' }}>1 - Cadres, professions intellectuelles supérieures (Ingénieurs, médecins)</option>
                    <option value="2" {{ old('profession_pere') == '2' ? 'selected' : '' }}>2 - Intermédiaires de l'administration/services (Instituteurs, infirmiers)</option>
                    <option value="3" {{ old('profession_pere') == '3' ? 'selected' : '' }}>3 - Employés de bureau (Secrétaires, guichetiers)</option>
                    <option value="4" {{ old('profession_pere') == '4' ? 'selected' : '' }}>4 - Ouvriers qualifiés/artisans (Mécaniciens, menuisiers)</option>
                    <option value="5" {{ old('profession_pere') == '5' ? 'selected' : '' }}>5 - Travailleurs agricoles, pêcheurs (Paysans, éleveurs)</option>
                    <option value="6" {{ old('profession_pere') == '6' ? 'selected' : '' }}>6 - Travailleurs non qualifiés (Aides ménagers, journaliers)</option>
                    <option value="7" {{ old('profession_pere') == '7' ? 'selected' : '' }}>7 - Personnes sans emploi ou informel non déclaré (Marchands ambulants, sans activité)</option>
                    <option value="non_applicable" {{ old('profession_pere') == 'non_applicable' ? 'selected' : '' }}>Non applicable</option>
                </select>
                @error('profession_pere')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Profession de la mère -->
            <div>
                <label for="profession_mere" class="block text-sm font-medium text-gray-700 mb-2">Profession de la mère/tutrice *</label>
                <select name="profession_mere" id="profession_mere" required
                        class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('profession_mere') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    <option value="">Sélectionnez une profession</option>
                    <option value="1" {{ old('profession_mere') == '1' ? 'selected' : '' }}>1 - Cadres, professions intellectuelles supérieures (Ingénieurs, médecins)</option>
                    <option value="2" {{ old('profession_mere') == '2' ? 'selected' : '' }}>2 - Intermédiaires de l'administration/services (Instituteurs, infirmiers)</option>
                    <option value="3" {{ old('profession_mere') == '3' ? 'selected' : '' }}>3 - Employés de bureau (Secrétaires, guichetiers)</option>
                    <option value="4" {{ old('profession_mere') == '4' ? 'selected' : '' }}>4 - Ouvriers qualifiés/artisans (Mécaniciens, menuisiers)</option>
                    <option value="5" {{ old('profession_mere') == '5' ? 'selected' : '' }}>5 - Travailleurs agricoles, pêcheurs (Paysans, éleveurs)</option>
                    <option value="6" {{ old('profession_mere') == '6' ? 'selected' : '' }}>6 - Travailleurs non qualifiés (Aides ménagers, journaliers)</option>
                    <option value="7" {{ old('profession_mere') == '7' ? 'selected' : '' }}>7 - Personnes sans emploi ou informel non déclaré (Marchands ambulants, sans activité)</option>
                    <option value="non_applicable" {{ old('profession_mere') == 'non_applicable' ? 'selected' : '' }}>Non applicable</option>
                </select>
                @error('profession_mere')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Situations particulières -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Situations particulières</label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="situations_particulieres[]" value="handicap"
                               {{ in_array('handicap', old('situations_particulieres', [])) ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Handicap</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="situations_particulieres[]" value="orphelin"
                               {{ in_array('orphelin', old('situations_particulieres', [])) ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Orphelin</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="situations_particulieres[]" value="autre"
                               {{ in_array('autre', old('situations_particulieres', [])) ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Autre situation</span>
                    </label>
                </div>
            </div>

            <!-- Connexion internet -->
            <div>
                <label for="connexion_internet" class="block text-sm font-medium text-gray-700 mb-2">Accès internet régulier *</label>
                <select name="connexion_internet" id="connexion_internet" required
                        class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('connexion_internet') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    <option value="">Sélectionnez le type d'accès</option>
                    <option value="aucun" {{ old('connexion_internet') == 'aucun' ? 'selected' : '' }}>Aucun accès</option>
                    <option value="3g_4g" {{ old('connexion_internet') == '3g_4g' ? 'selected' : '' }}>3G/4G (Mobile)</option>
                    <option value="fibre" {{ old('connexion_internet') == 'fibre' ? 'selected' : '' }}>Fibre optique</option>
                </select>
                @error('connexion_internet')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Équipement numérique -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Équipement numérique *</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="possede_ordinateur" value="1" required
                               {{ old('possede_ordinateur') == '1' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Possède un ordinateur</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="possede_ordinateur" value="0"
                               {{ old('possede_ordinateur') == '0' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Ne possède pas d'ordinateur</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="acces_smartphone" value="1" required
                               {{ old('acces_smartphone') == '1' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Accès smartphone</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="acces_smartphone" value="0"
                               {{ old('acces_smartphone') == '0' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Pas d'accès smartphone</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="acces_ia" value="1" required
                               {{ old('acces_ia') == '1' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Accès IA (ChatGPT, etc.)</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="acces_ia" value="0"
                               {{ old('acces_ia') == '0' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Pas d'accès IA</span>
                    </label>
                </div>
                @error('possede_ordinateur')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('acces_smartphone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('acces_ia')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div> 