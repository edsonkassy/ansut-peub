@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- En-tête -->
        <div class="bg-white border border-gray-200 mb-8">
            <div class=" p-6">
                <a href="{{ route('partenaire.opportunites.show', $opportunite) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                    Retour
                </a>
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-900">Modifier l'opportunité</h1>
                </div>
                <p class="text-gray-600 mt-1">Modifiez les informations de votre opportunité</p>
            </div>
            
            <form action="{{ route('partenaire.opportunites.update', $opportunite) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Type (minicartes) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type d'opportunité <span class="text-red-500">*</span></label>
                    <input type="hidden" name="type" id="type" value="{{ old('type', $opportunite->type) }}">
                    <div id="type-cards" class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <button type="button" data-type="bourse" class="type-card group border border-gray-300 bg-white p-4 flex flex-col items-center cursor-pointer focus:outline-none transition-all {{ $opportunite->type === 'bourse' ? 'selected' : '' }}">
                            <i data-lucide="graduation-cap" class="w-8 h-8 mb-2 group-[.selected]:text-primary-600 text-gray-400"></i>
                            <span class="font-medium text-sm text-gray-800">Bourse</span>
                        </button>
                        <button type="button" data-type="stage" class="type-card group border border-gray-300 bg-white p-4 flex flex-col items-center cursor-pointer focus:outline-none transition-all {{ $opportunite->type === 'stage' ? 'selected' : '' }}">
                            <i data-lucide="briefcase" class="w-8 h-8 mb-2 group-[.selected]:text-primary-600 text-gray-400"></i>
                            <span class="font-medium text-sm text-gray-800">Stage</span>
                        </button>
                        <button type="button" data-type="emploi" class="type-card group border border-gray-300 bg-white p-4 flex flex-col items-center cursor-pointer focus:outline-none transition-all {{ $opportunite->type === 'emploi' ? 'selected' : '' }}">
                            <i data-lucide="user-check" class="w-8 h-8 mb-2 group-[.selected]:text-primary-600 text-gray-400"></i>
                            <span class="font-medium text-sm text-gray-800">Emploi</span>
                        </button>
                        <button type="button" data-type="formation" class="type-card group border border-gray-300 bg-white p-4 flex flex-col items-center cursor-pointer focus:outline-none transition-all {{ $opportunite->type === 'formation' ? 'selected' : '' }}">
                            <i data-lucide="book-open" class="w-8 h-8 mb-2 group-[.selected]:text-primary-600 text-gray-400"></i>
                            <span class="font-medium text-sm text-gray-800">Formation</span>
                        </button>
                        <button type="button" data-type="concours" class="type-card group border border-gray-300 bg-white p-4 flex flex-col items-center cursor-pointer focus:outline-none transition-all {{ $opportunite->type === 'concours' ? 'selected' : '' }}">
                            <i data-lucide="award" class="w-8 h-8 mb-2 group-[.selected]:text-primary-600 text-gray-400"></i>
                            <span class="font-medium text-sm text-gray-800">Concours</span>
                        </button>
                        <button type="button" data-type="event" class="type-card group border border-gray-300 bg-white p-4 flex flex-col items-center cursor-pointer focus:outline-none transition-all {{ $opportunite->type === 'event' ? 'selected' : '' }}">
                            <i data-lucide="calendar" class="w-8 h-8 mb-2 group-[.selected]:text-primary-600 text-gray-400"></i>
                            <span class="font-medium text-sm text-gray-800">Événement</span>
                        </button>
                        <button type="button" data-type="promotion" class="type-card group border border-gray-300 bg-white p-4 flex flex-col items-center cursor-pointer focus:outline-none transition-all {{ $opportunite->type === 'promotion' ? 'selected' : '' }}">
                            <i data-lucide="megaphone" class="w-8 h-8 mb-2 group-[.selected]:text-primary-600 text-gray-400"></i>
                            <span class="font-medium text-sm text-gray-800">Promotion</span>
                        </button>
                    </div>
                    @error('type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Titre -->
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                        Titre de l'opportunité <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="titre" id="titre" value="{{ old('titre', $opportunite->titre) }}" 
                           class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" required>
                    @error('titre')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description détaillée <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="5" 
                              class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" required>{{ old('description', $opportunite->description) }}</textarea>
                    @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Illustration -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Illustration
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="border border-gray-300 p-4 bg-gray-50">
                                <div class="flex items-center justify-center h-48">
                                    <div id="placeholder-container" class="flex flex-col items-center justify-center text-gray-400 {{ $opportunite->illustration ? 'hidden' : '' }}">
                                        <i data-lucide="image" class="w-12 h-12 mb-2"></i>
                                        <span class="text-sm">Aucune image sélectionnée</span>
                                    </div>
                                    <img id="preview-image" src="{{ $opportunite->illustration ? Storage::url($opportunite->illustration) : '' }}" 
                                         alt="Aperçu" class="max-h-48 w-auto object-contain {{ $opportunite->illustration ? '' : 'hidden' }}">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Choisir une méthode</label>
                                <div class="space-y-2">
                                    <button type="button" id="btn-upload" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i data-lucide="upload" class="w-5 h-5 mr-2"></i>
                                        Télécharger une image
                                    </button>
                                    <button type="button" id="btn-generate" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i data-lucide="wand-2" class="w-5 h-5 mr-2"></i>
                                        Générer avec l'IA
                                    </button>
                                </div>
                                <input type="file" name="illustration" id="illustration" accept="image/jpeg,image/png" class="hidden">
                                <input type="hidden" name="generated_illustration" id="generated_illustration">
                            </div>
                            <div class="text-sm text-gray-500">
                                <p>Formats acceptés : JPEG, PNG</p>
                                <p>Taille maximale : 20Mo</p>
                                <p>Dimensions recommandées : 1200x630 pixels</p>
                            </div>
                        </div>
                    </div>
                    @error('illustration')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Informations principales -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations principales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pays -->
                        <div>
                            <label for="pays" class="block text-sm font-medium text-gray-700 mb-2">
                                Pays <span class="text-red-500">*</span>
                            </label>
                            <select name="pays" id="pays" class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" required>
                                <option value="">Sélectionnez un pays</option>
                                <optgroup label="UEMOA">
                                    <option value="Bénin" {{ old('pays', $opportunite->pays) == 'Bénin' ? 'selected' : '' }}>Bénin</option>
                                    <option value="Burkina Faso" {{ old('pays', $opportunite->pays) == 'Burkina Faso' ? 'selected' : '' }}>Burkina Faso</option>
                                    <option value="Côte d'Ivoire" {{ old('pays', $opportunite->pays) == "Côte d'Ivoire" ? 'selected' : '' }}>Côte d'Ivoire</option>
                                    <option value="Guinée-Bissau" {{ old('pays', $opportunite->pays) == 'Guinée-Bissau' ? 'selected' : '' }}>Guinée-Bissau</option>
                                    <option value="Mali" {{ old('pays', $opportunite->pays) == 'Mali' ? 'selected' : '' }}>Mali</option>
                                    <option value="Niger" {{ old('pays', $opportunite->pays) == 'Niger' ? 'selected' : '' }}>Niger</option>
                                    <option value="Sénégal" {{ old('pays', $opportunite->pays) == 'Sénégal' ? 'selected' : '' }}>Sénégal</option>
                                    <option value="Togo" {{ old('pays', $opportunite->pays) == 'Togo' ? 'selected' : '' }}>Togo</option>
                                </optgroup>
                                <optgroup label="Autres">
                                    <option value="Guinée" {{ old('pays', $opportunite->pays) == 'Guinée' ? 'selected' : '' }}>Guinée</option>
                                    <option value="France" {{ old('pays', $opportunite->pays) == 'France' ? 'selected' : '' }}>France</option>
                                </optgroup>
                            </select>
                            @error('pays')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Ville -->
                        <div>
                            <label for="ville" class="block text-sm font-medium text-gray-700 mb-2">
                                Ville <span class="text-gray-400">(optionnel)</span>
                            </label>
                            <input type="text" name="ville" id="ville" value="{{ old('ville', $opportunite->ville) }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('ville')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Durée -->
                        <div>
                            <label for="duree" class="block text-sm font-medium text-gray-700 mb-2">
                                Durée <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="duree" id="duree" value="{{ old('duree', $opportunite->duree) }}" 
                                   placeholder="ex: 6 mois, 1 an, 3 semaines"
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" required>
                            @error('duree')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Nombre de places -->
                        <div>
                            <label for="nombre_places" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de places <span class="text-gray-400">(optionnel)</span>
                            </label>
                            <input type="number" name="nombre_places" id="nombre_places" value="{{ old('nombre_places', $opportunite->nombre_places) }}" min="1"
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('nombre_places')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Rémunération -->
                        <div>
                            <label for="remuneration" class="block text-sm font-medium text-gray-700 mb-2">
                                Rémunération <span class="text-gray-400">(optionnel)</span>
                            </label>
                            <input type="text" name="remuneration" id="remuneration" value="{{ old('remuneration', $opportunite->remuneration) }}" 
                                   placeholder="ex: 50000 FCFA, Gratuit, Négociable"
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('remuneration')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Dates importantes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de début <span class="text-gray-400">(optionnel)</span>
                            </label>
                            <input type="date" name="date_debut" id="date_debut" 
                                   value="{{ old('date_debut', $opportunite->date_debut ? $opportunite->date_debut->format('Y-m-d') : '') }}" 
                                   min="{{ date('Y-m-d') }}"
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('date_debut')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de fin <span class="text-gray-400">(optionnel)</span>
                            </label>
                            <input type="date" name="date_fin" id="date_fin" 
                                   value="{{ old('date_fin', $opportunite->date_fin ? $opportunite->date_fin->format('Y-m-d') : '') }}" 
                                   min="{{ date('Y-m-d') }}"
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('date_fin')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="date_limite_candidature" class="block text-sm font-medium text-gray-700 mb-2">
                                Date limite de candidature <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date_limite_candidature" id="date_limite_candidature" 
                                   value="{{ old('date_limite_candidature', $opportunite->date_limite_candidature->format('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}"
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" required>
                            @error('date_limite_candidature')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Critères -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Critères et exigences</h3>
                    <div class="space-y-6">
                        <!-- Compétences requises -->
                        <div>
                            <label for="competences_requises" class="block text-sm font-medium text-gray-700 mb-2">
                                Compétences requises <span class="text-gray-400">(séparées par des virgules)</span>
                            </label>
                            <input type="text" name="competences_requises" id="competences_requises" 
                                   value="{{ old('competences_requises', is_array($opportunite->competences_requises) ? implode(', ', $opportunite->competences_requises) : $opportunite->competences_requises) }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('competences_requises')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Critères d'éligibilité -->
                        <div>
                            <label for="criteres_eligibilite" class="block text-sm font-medium text-gray-700 mb-2">
                                Critères d'éligibilité <span class="text-gray-400">(séparés par des virgules)</span>
                            </label>
                            <input type="text" name="criteres_eligibilite" id="criteres_eligibilite" 
                                   value="{{ old('criteres_eligibilite', is_array($opportunite->criteres_eligibilite) ? implode(', ', $opportunite->criteres_eligibilite) : $opportunite->criteres_eligibilite) }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('criteres_eligibilite')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Documents requis -->
                        <div>
                            <label for="documents_requis" class="block text-sm font-medium text-gray-700 mb-2">
                                Documents requis <span class="text-gray-400">(séparés par des virgules)</span>
                            </label>
                            <input type="text" name="documents_requis" id="documents_requis" 
                                   value="{{ old('documents_requis', is_array($opportunite->documents_requis) ? implode(', ', $opportunite->documents_requis) : $opportunite->documents_requis) }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('documents_requis')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Informations de contact -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de contact</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email de contact -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email de contact <span class="text-gray-400">(optionnel)</span>
                            </label>
                            <input type="email" name="contact_email" id="contact_email" 
                                   value="{{ old('contact_email', $opportunite->contact_email) }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('contact_email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Téléphone de contact -->
                        <div>
                            <label for="contact_telephone" class="block text-sm font-medium text-gray-700 mb-2">
                                Téléphone de contact <span class="text-gray-400">(optionnel)</span>
                            </label>
                            <input type="tel" name="contact_telephone" id="contact_telephone" 
                                   value="{{ old('contact_telephone', $opportunite->contact_telephone) }}" 
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('contact_telephone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Lien externe -->
                        <div class="md:col-span-2">
                            <label for="lien_externe" class="block text-sm font-medium text-gray-700 mb-2">
                                Lien externe <span class="text-gray-400">(optionnel)</span>
                            </label>
                            <input type="url" name="lien_externe" id="lien_externe" 
                                   value="{{ old('lien_externe', $opportunite->lien_externe) }}" 
                                   placeholder="https://example.com"
                                   class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            @error('lien_externe')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Statut -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Statut de l'opportunité</h3>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Statut <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" required>
                            <option value="draft" {{ old('status', $opportunite->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="published" {{ old('status', $opportunite->status) == 'published' ? 'selected' : '' }}>Publiée</option>
                            <option value="closed" {{ old('status', $opportunite->status) == 'closed' ? 'selected' : '' }}>Fermée</option>
                            <option value="archived" {{ old('status', $opportunite->status) == 'archived' ? 'selected' : '' }}>Archivée</option>
                        </select>
                        @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Boutons -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('partenaire.opportunites.show', $opportunite) }}" 
                           class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Quitter
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les icônes Lucide
    lucide.createIcons();

    // Gestion des images
    const btnUpload = document.getElementById('btn-upload');
    const btnGenerate = document.getElementById('btn-generate');
    const inputIllustration = document.getElementById('illustration');
    const inputGeneratedIllustration = document.getElementById('generated_illustration');
    const previewImage = document.getElementById('preview-image');
    const placeholderContainer = document.getElementById('placeholder-container');
    const form = document.querySelector('form');

    // Gestion du téléchargement d'image
    btnUpload.addEventListener('click', function() {
        inputIllustration.click();
    });

    inputIllustration.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('hidden');
                placeholderContainer.classList.add('hidden');
                inputGeneratedIllustration.value = ''; // Réinitialiser l'image générée
            };
            reader.readAsDataURL(file);
        }
    });

    // Gestion de la génération d'image avec l'IA
    btnGenerate.addEventListener('click', async function() {
        const titre = document.getElementById('titre').value;
        const type = document.getElementById('type').value;
        const description = document.getElementById('description').value || 'Opportunité de type ' + type;

        if (!titre || !type) {
            alert('Veuillez d\'abord remplir le titre et sélectionner un type d\'opportunité.');
            return;
        }

        try {
            btnGenerate.disabled = true;
            btnGenerate.innerHTML = '<i data-lucide="loader" class="w-5 h-5 mr-2 animate-spin"></i>Génération en cours...';
            lucide.createIcons();

            const response = await fetch('{{ route('partenaire.opportunites.generate-image') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    titre,
                    type,
                    description
                })
            });

            const data = await response.json();

            if (data.success) {
                previewImage.src = data.full_url;
                previewImage.classList.remove('hidden');
                placeholderContainer.classList.add('hidden');
                inputGeneratedIllustration.value = data.image_path;
                inputIllustration.value = ''; // Réinitialiser l'upload d'image
            } else {
                throw new Error(data.error || 'Erreur lors de la génération de l\'image');
            }
        } catch (error) {
            alert(error.message);
        } finally {
            btnGenerate.disabled = false;
            btnGenerate.innerHTML = '<i data-lucide="wand-2" class="w-5 h-5 mr-2"></i>Générer avec l\'IA';
            lucide.createIcons();
        }
    });

    // Gestion des cartes de type
    const typeCards = document.querySelectorAll('.type-card');
    const typeInput = document.getElementById('type');

    typeCards.forEach(card => {
        card.addEventListener('click', function() {
            // Retirer la sélection précédente
            typeCards.forEach(c => c.classList.remove('selected', 'border-primary-500'));
            
            // Ajouter la sélection à la carte cliquée
            this.classList.add('selected', 'border-primary-500');
            
            // Mettre à jour l'input caché
            typeInput.value = this.dataset.type;
        });
    });

    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    const dateLimiteCandidature = document.getElementById('date_limite_candidature');
    const today = new Date().toISOString().split('T')[0];

    // Set minimum dates
    dateDebut.min = today;
    dateFin.min = today;
    dateLimiteCandidature.min = today;

    // Update date_fin min when date_debut changes
    dateDebut.addEventListener('change', function() {
        if (this.value) {
            dateFin.min = this.value;
            if (dateFin.value && dateFin.value < this.value) {
                dateFin.value = this.value;
            }
            // Ensure date_limite_candidature is before date_debut
            if (dateLimiteCandidature.value && dateLimiteCandidature.value >= this.value) {
                dateLimiteCandidature.value = new Date(new Date(this.value).setDate(new Date(this.value).getDate() - 1)).toISOString().split('T')[0];
            }
        }
    });

    // Update date_limite_candidature max when date_debut changes
    dateDebut.addEventListener('change', function() {
        if (this.value) {
            const maxDate = new Date(this.value);
            maxDate.setDate(maxDate.getDate() - 1);
            dateLimiteCandidature.max = maxDate.toISOString().split('T')[0];
        } else {
            dateLimiteCandidature.max = '';
        }
    });

    // Validate date_fin is after or equal to date_debut
    dateFin.addEventListener('change', function() {
        if (this.value && dateDebut.value && this.value < dateDebut.value) {
            alert('La date de fin doit être égale ou postérieure à la date de début');
            this.value = dateDebut.value;
        }
    });

    // Validate date_limite_candidature is before date_debut
    dateLimiteCandidature.addEventListener('change', function() {
        if (this.value && dateDebut.value && this.value >= dateDebut.value) {
            alert('La date limite de candidature doit être antérieure à la date de début');
            const maxDate = new Date(dateDebut.value);
            maxDate.setDate(maxDate.getDate() - 1);
            this.value = maxDate.toISOString().split('T')[0];
        }
    });
});
</script>
@endpush
@endsection 