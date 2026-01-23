@extends('layouts.admin')

@section('title', 'Nouveau Fournisseur - PEUB Admin')

@section('page-title', 'Nouveau Fournisseur')

@section('content')
<!-- En-tête -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i data-lucide="truck" class="w-6 h-6 mr-3 text-secondary-500"></i>
                Ajouter un Nouveau Fournisseur
            </h2>
            <p class="mt-1 text-gray-600">Créer un nouveau fournisseur pour les dotations PEUB.</p>
        </div>
        <a href="{{ route('admin.dotations.fournisseurs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 flex items-center rounded-md border border-gray-600">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Retour à la liste
        </a>
    </div>
</div>

@include('components.flash-messages')

<!-- Formulaire -->
<div class="bg-white border border-gray-300 p-6">
    <form action="{{ route('admin.dotations.fournisseurs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nom du fournisseur -->
            <div class="md:col-span-2">
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom du Fournisseur</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                       class="w-full border border-gray-300 px-3 py-2 focus:ring-primary-500 focus:border-primary-500 @error('nom') border-red-500 @enderror">
                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Type de fournisseur -->
            <div>
                <label for="type_fournisseur" class="block text-sm font-medium text-gray-700 mb-1">Type de Fournisseur</label>
                <select name="type_fournisseur" id="type_fournisseur" class="w-full border border-gray-300 px-3 py-2 focus:ring-primary-500 focus:border-primary-500 @error('type_fournisseur') border-red-500 @enderror">
                    <option value="">Sélectionner un type</option>
                    <option value="materiel_informatique" {{ old('type_fournisseur') == 'materiel_informatique' ? 'selected' : '' }}>Matériel Informatique</option>
                    <option value="fournitures_scolaires" {{ old('type_fournisseur') == 'fournitures_scolaires' ? 'selected' : '' }}>Fournitures Scolaires</option>
                    <option value="equipements_techniques" {{ old('type_fournisseur') == 'equipements_techniques' ? 'selected' : '' }}>Équipements Techniques</option>
                    <option value="services" {{ old('type_fournisseur') == 'services' ? 'selected' : '' }}>Services</option>
                    <option value="autre" {{ old('type_fournisseur') == 'autre' ? 'selected' : '' }}>Autre</option>
                </select>
                @error('type_fournisseur') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Statut -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" id="status" class="w-full border border-gray-300 px-3 py-2 focus:ring-primary-500 focus:border-primary-500 @error('status') border-red-500 @enderror">
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="suspendu" {{ old('status') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    <option value="archive" {{ old('status') == 'archive' ? 'selected' : '' }}>Archivé</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Nom du contact -->
            <div>
                <label for="contact_nom" class="block text-sm font-medium text-gray-700 mb-1">Nom du Contact</label>
                <input type="text" name="contact_nom" id="contact_nom" value="{{ old('contact_nom') }}"
                       class="w-full border border-gray-300 px-3 py-2 focus:ring-primary-500 focus:border-primary-500 @error('contact_nom') border-red-500 @enderror">
                @error('contact_nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Email de contact -->
            <div>
                <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Email de Contact</label>
                <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}"
                       class="w-full border border-gray-300 px-3 py-2 focus:ring-primary-500 focus:border-primary-500 @error('contact_email') border-red-500 @enderror">
                @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Téléphone de contact -->
            <div>
                <label for="contact_telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone de Contact</label>
                <input type="tel" name="contact_telephone" id="contact_telephone" value="{{ old('contact_telephone') }}"
                       class="w-full border border-gray-300 px-3 py-2 focus:ring-primary-500 focus:border-primary-500 @error('contact_telephone') border-red-500 @enderror">
                @error('contact_telephone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Adresse -->
            <div class="md:col-span-2">
                <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                <textarea name="adresse" id="adresse" rows="3" 
                          class="w-full border border-gray-300 px-3 py-2 focus:ring-primary-500 focus:border-primary-500 @error('adresse') border-red-500 @enderror">{{ old('adresse') }}</textarea>
                @error('adresse') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Notes -->
            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="w-full border border-gray-300 px-3 py-2 focus:ring-primary-500 focus:border-primary-500 @error('notes') border-red-500 @enderror" 
                          placeholder="Informations supplémentaires sur le fournisseur...">{{ old('notes') }}</textarea>
                @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Contrat -->
            <div class="md:col-span-2">
                <label for="contrat" class="block text-sm font-medium text-gray-700 mb-1">Contrat (optionnel)</label>
                <input type="file" name="contrat" id="contrat" accept=".pdf,.doc,.docx"
                       class="w-full border border-gray-300 px-3 py-2 focus:ring-primary-500 focus:border-primary-500 @error('contrat') border-red-500 @enderror">
                <p class="text-sm text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX (max 2MB)</p>
                @error('contrat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.dotations.fournisseurs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md border border-gray-600">
                Annuler
            </a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                Créer le Fournisseur
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush 