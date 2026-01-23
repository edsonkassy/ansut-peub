<!-- Step 2: Informations Académiques -->
<div class="step hidden" id="step2">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Infos scolaires</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Matricule et Série BAC -->
            <div>
                <label for="matricule_bac" class="block text-sm font-medium text-gray-700 mb-2">Matricule BAC *</label>
                <input type="text" name="matricule_bac" id="matricule_bac" required
                       value="{{ old('matricule_bac') }}"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('matricule_bac') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                       placeholder="Ex: 202312345678">
                @error('matricule_bac')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="serie_bac" class="block text-sm font-medium text-gray-700 mb-2">Série BAC *</label>
                <select name="serie_bac" id="serie_bac" required
                        class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('serie_bac') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    <option value="">Sélectionnez une série</option>
                    <option value="A1" {{ old('serie_bac') == 'A1' ? 'selected' : '' }}>A1 (Lettres-Langues)</option>
                    <option value="A2" {{ old('serie_bac') == 'A2' ? 'selected' : '' }}>A2 (Lettres-Philo)</option>
                    <option value="B" {{ old('serie_bac') == 'B' ? 'selected' : '' }}>B (Économie-Gestion)</option>
                    <option value="C" {{ old('serie_bac') == 'C' ? 'selected' : '' }}>C (Maths-Sciences Physiques)</option>
                    <option value="D" {{ old('serie_bac') == 'D' ? 'selected' : '' }}>D (Maths-Sciences Naturelles)</option>
                    <option value="E" {{ old('serie_bac') == 'E' ? 'selected' : '' }}>E (Mathématiques-Technique)</option>
                    <option value="F1" {{ old('serie_bac') == 'F1' ? 'selected' : '' }}>F1 (Électrotechnique)</option>
                    <option value="F2" {{ old('serie_bac') == 'F2' ? 'selected' : '' }}>F2 (Mécanique Générale)</option>
                    <option value="F3" {{ old('serie_bac') == 'F3' ? 'selected' : '' }}>F3 (Électronique)</option>
                    <option value="F4" {{ old('serie_bac') == 'F4' ? 'selected' : '' }}>F4 (Génie Civil)</option>
                    <option value="G1" {{ old('serie_bac') == 'G1' ? 'selected' : '' }}>G1 (Secrétariat)</option>
                    <option value="G2" {{ old('serie_bac') == 'G2' ? 'selected' : '' }}>G2 (Comptabilité)</option>
                    <option value="G3" {{ old('serie_bac') == 'G3' ? 'selected' : '' }}>G3 (Commerce-Vente)</option>
                </select>
                @error('serie_bac')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Note BAC et Année d'obtention -->
            <div>
                <label for="note_bac" class="block text-sm font-medium text-gray-700 mb-2">Note BAC *</label>
                <input type="number" step="0.01" min="0" max="380" name="note_bac" id="note_bac" required
                       value="{{ old('note_bac') }}"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('note_bac') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                       placeholder="Ex: 250.50">
                @error('note_bac')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="annee_bac" class="block text-sm font-medium text-gray-700 mb-2">Année d'obtention du BAC *</label>
                <select name="annee_bac" id="annee_bac" required
                        class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('annee_bac') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    <option value="">Sélectionnez une année</option>
                    <option value="2022" {{ old('annee_bac') == '2022' ? 'selected' : '' }}>2022</option>
                    <option value="2023" {{ old('annee_bac') == '2023' ? 'selected' : '' }}>2023</option>
                    <option value="2024" {{ old('annee_bac') == '2024' ? 'selected' : '' }}>2024</option>
                    <option value="2025" {{ old('annee_bac') == '2025' ? 'selected' : '' }}>2025</option>
                </select>
                @error('annee_bac')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Établissement -->
            <div>
                <label for="etablissement_nom" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'établissement *</label>
                <select name="etablissement_nom" id="etablissement_nom" required
                        class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('etablissement_nom') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                        onchange="updateEtablissementType()">
                    <option value="">Sélectionnez un établissement</option>
                    @foreach($etablissements as $etab)
                        <option value="{{ $etab->etablissement }}" 
                                data-type="{{ $etab->type_etab }}"
                                {{ old('etablissement_nom') == $etab->etablissement ? 'selected' : '' }}>
                            {{ $etab->etablissement }}
                        </option>
                    @endforeach
                </select>
                @error('etablissement_nom')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="etablissement_type" class="block text-sm font-medium text-gray-700 mb-2">Type d'établissement *</label>
                <select name="etablissement_type" id="etablissement_type" required
                        class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('etablissement_type') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    <option value="">Sélectionnez un type</option>
                    <option value="public" {{ old('etablissement_type') == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="prive_homologue" {{ old('etablissement_type') == 'prive_homologue' ? 'selected' : '' }}>Privé Homologué</option>
                    <option value="prive_non_homologue" {{ old('etablissement_type') == 'prive_non_homologue' ? 'selected' : '' }}>Privé Non Homologué</option>
                </select>
                @error('etablissement_type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Collante BAC -->
            <div class="md:col-span-2">
                <label for="collante_bac_file" class="block text-sm font-medium text-gray-700 mb-2">Collante BAC (Image scannée) *</label>
                <input type="file" name="collante_bac_file" id="collante_bac_file" required
                       accept=".jpg,.jpeg,.png"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm text-gray-900 bg-gray-50 @error('collante_bac_file') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Format accepté: JPG, PNG. Taille max: 10MB</p>
                @error('collante_bac_file')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>

<script>
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