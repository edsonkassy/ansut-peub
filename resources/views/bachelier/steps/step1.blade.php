<!-- Step 1: Informations Personnelles -->
<div class="step" id="step1">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Infos générales</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom et Prénoms -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                <input type="text" name="nom" id="nom" required
                       value="{{ old('nom') }}"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('nom') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                       placeholder="Votre nom">
                @error('nom')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="prenoms" class="block text-sm font-medium text-gray-700 mb-2">Prénoms *</label>
                <input type="text" name="prenoms" id="prenoms" required
                       value="{{ old('prenoms') }}"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('prenoms') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                       placeholder="Vos prénoms">
                @error('prenoms')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date et Lieu de naissance -->
            <div>
                <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance *</label>
                <input type="date" name="date_naissance" id="date_naissance" required
                       value="{{ old('date_naissance') }}"
                       max="2020-12-31"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('date_naissance') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                @error('date_naissance')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="lieu_naissance" class="block text-sm font-medium text-gray-700 mb-2">Lieu de naissance *</label>
                <input type="text" name="lieu_naissance" id="lieu_naissance" required
                       value="{{ old('lieu_naissance') }}"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('lieu_naissance') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                       placeholder="Ville de naissance">
                @error('lieu_naissance')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sexe -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sexe *</label>
                <div class="flex space-x-6">
                    <label class="flex items-center">
                        <input type="radio" name="sexe" value="M"
                               {{ old('sexe') == 'M' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Masculin</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="sexe" value="F"
                               {{ old('sexe') == 'F' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Féminin</span>
                    </label>
                </div>
                @error('sexe')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pièce d'identité -->
            <div>
                <label for="piece_identite_type" class="block text-sm font-medium text-gray-700 mb-2">Type de pièce d'identité *</label>
                <select name="piece_identite_type" id="piece_identite_type" required
                        class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('piece_identite_type') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    <option value="">Sélectionnez un type</option>
                    <option value="carte_scolaire" {{ old('piece_identite_type') == 'carte_scolaire' ? 'selected' : '' }}>Carte Scolaire</option>
                    <option value="cni" {{ old('piece_identite_type') == 'cni' ? 'selected' : '' }}>CNI</option>
                    <option value="attestation" {{ old('piece_identite_type') == 'attestation' ? 'selected' : '' }}>Attestation</option>
                </select>
                @error('piece_identite_type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="piece_identite_file" class="block text-sm font-medium text-gray-700 mb-2">Pièce d'identité (Image scannée) *</label>
                <input type="file" name="piece_identite_file" id="piece_identite_file" required
                       accept=".jpg,.jpeg,.png"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm text-gray-900 bg-gray-50 @error('piece_identite_file') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Format accepté: JPG, PNG. Taille max: 10MB</p>
                @error('piece_identite_file')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Coordonnées -->
            <div>
                <label for="telephone_eleve" class="block text-sm font-medium text-gray-700 mb-2">Téléphone (Élève) *</label>
                <input type="tel" name="telephone_eleve" id="telephone_eleve" required
                       value="{{ old('telephone_eleve') }}"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('telephone_eleve') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                       placeholder="+225 07 XX XX XX XX">
                @error('telephone_eleve')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="telephone_parent" class="block text-sm font-medium text-gray-700 mb-2">Téléphone (Parent) *</label>
                <input type="tel" name="telephone_parent" id="telephone_parent" required
                       value="{{ old('telephone_parent') }}"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('telephone_parent') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                       placeholder="+225 05 XX XX XX XX">
                @error('telephone_parent')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email_eleve" class="block text-sm font-medium text-gray-700 mb-2">Email (Élève) *</label>
                <input type="email" name="email_eleve" id="email_eleve" required
                       value="{{ old('email_eleve') }}"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('email_eleve') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                       placeholder="votre@email.com">
                <p class="mt-1 text-xs text-gray-500">Cet email sera utilisé pour créer votre compte PEUB</p>
                @error('email_eleve')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email_parent" class="block text-sm font-medium text-gray-700 mb-2">Email (Parent) *</label>
                <input type="email" name="email_parent" id="email_parent" required
                       value="{{ old('email_parent') }}"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('email_parent') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                       placeholder="parent@email.com">
                @error('email_parent')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Localisation -->
            <div>
                <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Région *</label>
                <x-region-select 
                    name="region" 
                    id="region" 
                    required 
                    :value="old('region')"
                    class="" 
                />
                @error('region')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="commune" class="block text-sm font-medium text-gray-700 mb-2">Commune *</label>
                <input type="text" name="commune" id="commune" required
                       value="{{ old('commune') }}"
                       class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('commune') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                       placeholder="Ex: Cocody, Plateau, Marcory...">
                @error('commune')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Photo de profil -->
            <div class="md:col-span-2">
                <label for="photo_profil" class="block text-sm font-medium text-gray-700 mb-2">Photo de profil</label>
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden" id="photoPreview">
                            <i data-lucide="user" class="w-8 h-8 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="photo_profil" id="photo_profil"
                               accept=".jpg,.jpeg,.png"
                               class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm text-gray-900 bg-gray-50 @error('photo_profil') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Format accepté: JPG, PNG. Taille max: 5MB</p>
                        @error('photo_profil')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photo_profil');
    const photoPreview = document.getElementById('photoPreview');

    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.innerHTML = `<img src="${e.target.result}" alt="Photo de profil" class="w-full h-full object-cover">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>