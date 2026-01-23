@extends('layouts.bachelier')

@section('title', 'Mon Profil - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb & Status -->
    <div class="flex items-center justify-between mb-6">
        <x-breadcrumb text="PARAMÈTRES / MON PROFIL" />
        <div class="flex items-center space-x-4">
            @if($bachelier->boursier_peub)
                <div class="bg-[#00BFA5] text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                    <i data-lucide="award" class="w-4 h-4"></i>
                    <span class="font-semibold">Boursier PEUB</span>
                </div>
            @else
                <div class="bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                    <i data-lucide="book" class="w-4 h-4"></i>
                    <span class="font-semibold">Candidat Standard</span>
                </div>
            @endif
        </div>
    </div>

    <div>
        <div class="grid grid-cols-1 gap-8">
            <!-- Formulaire de profil -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="p-6 ">
                    <h2 class="text-xl font-semibold text-gray-900">Informations personnelles</h2>
                </div>

                <form id="profile-form" action="{{ route('bachelier.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Photo de profil -->
                    <div class="flex items-center space-x-6">
                        <div class="relative">
                            <div id="photo-container" class="w-24 h-24 bg-gray-100 flex items-center justify-center border-2 border-gray-200 rounded-full overflow-hidden">
                                @if($bachelier->photo)
                                    <img id="profile-image" src="{{ asset('storage/' . $bachelier->photo) }}" alt="Photo de profil" class="w-24 h-24 object-cover">
                                @else
                                    <i data-lucide="user" class="w-12 h-12 text-gray-400"></i>
                                @endif
                            </div>
                            <label for="photo" class="absolute bottom-0 right-0 bg-[#00BFA5] text-white p-2 rounded-full cursor-pointer hover:bg-[#00BFA5]/90 transition-colors">
                                <i data-lucide="camera" class="w-4 h-4"></i>
                            </label>
                        </div>
                        <div class="flex-1">
                            <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Photo de profil</label>
                            <input type="file" id="photo" name="photo" accept="image/*" class="hidden">
                            <div class="flex items-center space-x-3">
                                <button type="button" onclick="document.getElementById('photo').click()" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
                                    Choisir une photo
                                </button>
                                @if($bachelier->photo)
                                <button type="button" id="remove-photo" class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                    Supprimer
                                </button>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG ou GIF. Taille max : 2MB</p>
                        </div>
                    </div>

                    <!-- Informations de base (non modifiables) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                            <input type="text" id="nom" name="nom" value="{{ $bachelier->nom }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="prenoms" class="block text-sm font-medium text-gray-700 mb-2">Prénoms</label>
                            <input type="text" id="prenoms" name="prenoms" value="{{ $bachelier->prenoms }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="date_naissance" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                            <input type="date" id="date_naissance" name="date_naissance" value="{{ $bachelier->date_naissance?->format('Y-m-d') }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="lieu_naissance" class="block text-sm font-medium text-gray-700 mb-2">Lieu de naissance</label>
                            <input type="text" id="lieu_naissance" name="lieu_naissance" value="{{ $bachelier->lieu_naissance }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="sexe" class="block text-sm font-medium text-gray-700 mb-2">Sexe</label>
                            <select id="sexe" name="sexe" disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                                <option value="M" {{ $bachelier->sexe === 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ $bachelier->sexe === 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>
                        <div>
                            <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Région</label>
                            <input type="text" id="region" name="region" value="{{ $bachelier->region }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Contact (modifiable) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="telephone_eleve" class="block text-sm font-medium text-gray-700 mb-2">Téléphone élève <span class="text-red-500">*</span></label>
                            <input type="tel" id="telephone_eleve" name="telephone_eleve" value="{{ $bachelier->telephone_eleve }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                        </div>
                        <div>
                            <label for="telephone_parent" class="block text-sm font-medium text-gray-700 mb-2">Téléphone parent <span class="text-red-500">*</span></label>
                            <input type="tel" id="telephone_parent" name="telephone_parent" value="{{ $bachelier->telephone_parent }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                        </div>
                        <div>
                            <label for="email_eleve" class="block text-sm font-medium text-gray-700 mb-2">Email élève <span class="text-red-500">*</span></label>
                            <input type="email" id="email_eleve" name="email_eleve" value="{{ $bachelier->email_eleve }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                        </div>
                        <div>
                            <label for="email_parent" class="block text-sm font-medium text-gray-700 mb-2">Email parent <span class="text-red-500">*</span></label>
                            <input type="email" id="email_parent" name="email_parent" value="{{ $bachelier->email_parent }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                        </div>
                    </div>

                    <!-- Informations académiques (non modifiables) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="matricule_bac" class="block text-sm font-medium text-gray-700 mb-2">Matricule BAC</label>
                            <input type="text" id="matricule_bac" name="matricule_bac" value="{{ $bachelier->matricule_bac }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="serie_bac" class="block text-sm font-medium text-gray-700 mb-2">Série BAC</label>
                            <select id="serie_bac" name="serie_bac" disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                                <optgroup label="Séries Scientifiques">
                                    <option value="C" {{ $bachelier->serie_bac === 'C' ? 'selected' : '' }}>C - Scientifique (Maths, Physique)</option>
                                    <option value="E" {{ $bachelier->serie_bac === 'E' ? 'selected' : '' }}>E - Technique (Maths, Technologie)</option>
                                    <option value="D" {{ $bachelier->serie_bac === 'D' ? 'selected' : '' }}>D - Scientifique (SVT, Maths)</option>
                                </optgroup>
                                <optgroup label="Séries Littéraires">
                                    <option value="A1" {{ $bachelier->serie_bac === 'A1' ? 'selected' : '' }}>A1 - Littéraire (Maths + Langues)</option>
                                    <option value="A2" {{ $bachelier->serie_bac === 'A2' ? 'selected' : '' }}>A2 - Littéraire (Langues, Histoire, Géo)</option>
                                </optgroup>
                                <optgroup label="Techniques Industrielles">
                                    <option value="F1" {{ $bachelier->serie_bac === 'F1' ? 'selected' : '' }}>F1 - Mécanique Générale</option>
                                    <option value="F2" {{ $bachelier->serie_bac === 'F2' ? 'selected' : '' }}>F2 - Électronique</option>
                                    <option value="F3" {{ $bachelier->serie_bac === 'F3' ? 'selected' : '' }}>F3 - Électrotechnique</option>
                                    <option value="F4" {{ $bachelier->serie_bac === 'F4' ? 'selected' : '' }}>F4 - Génie Civil</option>
                                    <option value="F5" {{ $bachelier->serie_bac === 'F5' ? 'selected' : '' }}>F5 - Physique-Chimie</option>
                                    <option value="F6" {{ $bachelier->serie_bac === 'F6' ? 'selected' : '' }}>F6 - Constructions Mécaniques</option>
                                    <option value="F7" {{ $bachelier->serie_bac === 'F7' ? 'selected' : '' }}>F7 - Bois et Matériaux</option>
                                    <option value="F8" {{ $bachelier->serie_bac === 'F8' ? 'selected' : '' }}>F8 - Arts Appliqués</option>
                                </optgroup>
                                <optgroup label="Techniques Tertiaires">
                                    <option value="G1" {{ $bachelier->serie_bac === 'G1' ? 'selected' : '' }}>G1 - Secrétariat</option>
                                    <option value="G2" {{ $bachelier->serie_bac === 'G2' ? 'selected' : '' }}>G2 - Comptabilité</option>
                                    <option value="G3" {{ $bachelier->serie_bac === 'G3' ? 'selected' : '' }}>G3 - Commerce</option>
                                </optgroup>
                                <optgroup label="Brevets Professionnels">
                                    <option value="BT" {{ $bachelier->serie_bac === 'BT' ? 'selected' : '' }}>BT - Brevet de Technicien</option>
                                    <option value="BP" {{ $bachelier->serie_bac === 'BP' ? 'selected' : '' }}>BP - Brevet Professionnel</option>
                                </optgroup>
                            </select>
                        </div>
                        <div>
                            <label for="note_bac" class="block text-sm font-medium text-gray-700 mb-2">Note BAC</label>
                            <input type="number" id="note_bac" name="note_bac" value="{{ $bachelier->note_bac }}" step="0.01" min="0" max="400" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">Sur 400 points (système ivoirien)</p>
                        </div>
                        <div>
                            <label for="mention" class="block text-sm font-medium text-gray-700 mb-2">Mention</label>
                            <select id="mention" name="mention" disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                                <option value="passable" {{ $bachelier->mention === 'passable' ? 'selected' : '' }}>Passable</option>
                                <option value="assez_bien" {{ $bachelier->mention === 'assez_bien' ? 'selected' : '' }}>Assez Bien</option>
                                <option value="bien" {{ $bachelier->mention === 'bien' ? 'selected' : '' }}>Bien</option>
                                <option value="tres_bien" {{ $bachelier->mention === 'tres_bien' ? 'selected' : '' }}>Très Bien</option>
                            </select>
                        </div>
                        <div>
                            <label for="etablissement_nom" class="block text-sm font-medium text-gray-700 mb-2">Établissement</label>
                            <input type="text" id="etablissement_nom" name="etablissement_nom" value="{{ $bachelier->etablissement_nom }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="annee_bac" class="block text-sm font-medium text-gray-700 mb-2">Année BAC</label>
                            <input type="number" id="annee_bac" name="annee_bac" value="{{ $bachelier->annee_bac }}" min="2020" max="2025" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 bg-gray-50 cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Compétences et langues -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="competences" class="block text-sm font-medium text-gray-700 mb-2">Compétences</label>
                            <textarea id="competences" name="competences" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">{{ is_array($bachelier->competences) ? implode(', ', $bachelier->competences) : $bachelier->competences }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Séparez les compétences par des virgules</p>
                        </div>
                        <div>
                            <label for="langues" class="block text-sm font-medium text-gray-700 mb-2">Langues</label>
                            <textarea id="langues" name="langues" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">{{ is_array($bachelier->langues) ? implode(', ', $bachelier->langues) : $bachelier->langues }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Séparez les langues par des virgules</p>
                        </div>
                    </div>

                    <!-- Motivation -->
                    <div>
                        <label for="motivation" class="block text-sm font-medium text-gray-700 mb-2">Motivation</label>
                        <textarea id="motivation" name="motivation" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-900 focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">{{ $bachelier->motivation }}</textarea>
                    </div>

                    <!-- CV -->
                    <div>
                        <label for="cv_path" class="block text-sm font-medium text-gray-700 mb-2">CV (PDF)</label>
                        <input type="file" id="cv_path" name="cv_path" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-[#00BFA5]/10 file:text-[#00BFA5] hover:file:bg-[#00BFA5]/20 file:rounded-lg">
                        @if($bachelier->cv_path)
                            <p class="text-sm text-gray-600 mt-1">CV actuel : <a href="{{ asset('storage/' . $bachelier->cv_path) }}" target="_blank" class="text-[#00BFA5] hover:text-[#00BFA5]/80">Voir le CV</a></p>
                        @endif
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#00BFA5] hover:bg-[#00BFA5]/90 transition-colors">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photo');
    const photoContainer = document.getElementById('photo-container');
    const removePhotoBtn = document.getElementById('remove-photo');
    
    // Gestion de l'upload de photo
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Vérifier la taille du fichier (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('La taille du fichier ne doit pas dépasser 2MB');
                this.value = '';
                return;
            }
            
            // Vérifier le type de fichier
            if (!file.type.startsWith('image/')) {
                alert('Veuillez sélectionner un fichier image');
                this.value = '';
                return;
            }
            
            // Prévisualisation de l'image
            const reader = new FileReader();
            reader.onload = function(e) {
                let img = document.getElementById('profile-image');
                if (img) {
                    img.src = e.target.result;
                } else {
                    // Créer une nouvelle image si elle n'existe pas
                    img = document.createElement('img');
                    img.id = 'profile-image';
                    img.src = e.target.result;
                    img.className = 'w-24 h-24 object-cover';
                    img.alt = 'Photo de profil';
                    
                    // Remplacer le contenu du container
                    photoContainer.innerHTML = '';
                    photoContainer.appendChild(img);
                }
                
                // Afficher le bouton supprimer s'il n'existe pas
                if (!removePhotoBtn) {
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.id = 'remove-photo';
                    removeBtn.className = 'inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors';
                    removeBtn.innerHTML = '<i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>Supprimer';
                    
                    const buttonContainer = document.querySelector('.flex.items-center.space-x-3');
                    buttonContainer.appendChild(removeBtn);
                    
                    // Réinitialiser les icônes Lucide
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                    
                    // Ajouter l'événement click au nouveau bouton
                    removeBtn.addEventListener('click', removePhoto);
                }
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Fonction pour supprimer la photo
    function removePhoto() {
        photoInput.value = '';
        photoContainer.innerHTML = '<i data-lucide="user" class="w-12 h-12 text-gray-400"></i>';
        
        // Supprimer le bouton supprimer
        if (removePhotoBtn) {
            removePhotoBtn.remove();
        }
        
        // Réinitialiser les icônes Lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Ajouter un champ caché pour indiquer la suppression de la photo
        let removeInput = document.getElementById('remove_photo');
        if (!removeInput) {
            removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.id = 'remove_photo';
            removeInput.name = 'remove_photo';
            removeInput.value = '1';
            document.getElementById('profile-form').appendChild(removeInput);
        }
    }
    
    // Événement pour le bouton supprimer existant
    if (removePhotoBtn) {
        removePhotoBtn.addEventListener('click', removePhoto);
    }
    
    // Gestion de la soumission du formulaire
    document.getElementById('profile-form').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 mr-2 animate-spin"></i>Enregistrement...';
    });
});
</script>
@endpush
@endsection 