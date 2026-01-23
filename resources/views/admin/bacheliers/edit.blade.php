@extends('layouts.admin')

@section('title', 'Modifier Bachelier - ' . $bachelier->nom . ' ' . $bachelier->prenoms)

@section('page-title', 'Modifier le Bachelier')

@section('content')
<!-- Header -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Modifier le Bachelier</h2>
            <p class="text-gray-600">{{ $bachelier->nom }} {{ $bachelier->prenoms }}</p>
        </div>
        <a href="{{ route('admin.bacheliers.show', $bachelier) }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 flex items-center rounded-md border border-gray-700">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Retour aux détails
        </a>
    </div>
</div>

<!-- Formulaire de modification -->
<div class="bg-white border border-gray-300 p-6">
    <form method="POST" action="{{ route('admin.bacheliers.update', $bachelier) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Colonne gauche - Statut et validation -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-2 text-primary-600"></i>
                        Statut et Validation
                    </h3>
                    
                    <!-- Statut du profil -->
                    <div class="mb-4">
                        <label for="status_profil" class="block text-sm font-medium text-gray-700 mb-2">
                            Statut du profil <span class="text-red-500">*</span>
                        </label>
                        <select name="status_profil" id="status_profil" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('status_profil') border-red-500 @enderror">
                            <option value="">Sélectionnez un statut</option>
                            <option value="en_attente" {{ $bachelier->status_profil == 'en_attente' ? 'selected' : '' }}>
                                En attente de vérification
                            </option>
                            <option value="verifie" {{ $bachelier->status_profil == 'verifie' ? 'selected' : '' }}>
                                Profil vérifié
                            </option>
                            <option value="incomplet" {{ $bachelier->status_profil == 'incomplet' ? 'selected' : '' }}>
                                Profil incomplet
                            </option>
                        </select>
                        @error('status_profil')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Boursier PEUB -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="boursier_peub" value="1" 
                                   {{ $bachelier->boursier_peub ? 'checked' : '' }}
                                   class="text-primary-600 focus:ring-primary-500 border-gray-300 @error('boursier_peub') border-red-500 @enderror">
                            <span class="ml-2 text-sm font-medium text-gray-700">
                                <i data-lucide="award" class="w-4 h-4 inline mr-1"></i>
                                Boursier du Programme PEUB
                            </span>
                        </label>
                        @error('boursier_peub')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Informations académiques modifiables -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="book" class="w-5 h-5 mr-2 text-primary-600"></i>
                        Correction Académique
                    </h3>
                    
                    <!-- Moyenne -->
                    <div class="mb-4">
                        <label for="moyenne_bac" class="block text-sm font-medium text-gray-700 mb-2">
                            Moyenne générale du Bac
                        </label>
                        <input type="number" name="moyenne_bac" id="moyenne_bac" 
                               value="{{ old('moyenne_bac', $bachelier->moyenne_bac) }}"
                               step="0.01" min="0" max="20"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('moyenne_bac') border-red-500 @enderror"
                               placeholder="Ex: 15.75">
                        @error('moyenne_bac')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Mention -->
                    <div class="mb-4">
                        <label for="mention" class="block text-sm font-medium text-gray-700 mb-2">
                            Mention
                        </label>
                        <select name="mention" id="mention"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('mention') border-red-500 @enderror">
                            <option value="">Sélectionnez une mention</option>
                            <option value="passable" {{ $bachelier->mention == 'passable' ? 'selected' : '' }}>
                                Passable (10-11.99)
                            </option>
                            <option value="assez_bien" {{ $bachelier->mention == 'assez_bien' ? 'selected' : '' }}>
                                Assez Bien (12-13.99)
                            </option>
                            <option value="bien" {{ $bachelier->mention == 'bien' ? 'selected' : '' }}>
                                Bien (14-15.99)
                            </option>
                            <option value="tres_bien" {{ $bachelier->mention == 'tres_bien' ? 'selected' : '' }}>
                                Très Bien (16-17.99)
                            </option>
                            <option value="excellent" {{ $bachelier->mention == 'excellent' ? 'selected' : '' }}>
                                Excellent (18-20)
                            </option>
                        </select>
                        @error('mention')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Colonne droite - Informations actuelles et notes -->
            <div class="space-y-6">
                <!-- Informations actuelles (lecture seule) -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="info" class="w-5 h-5 mr-2 text-primary-600"></i>
                        Informations Actuelles
                    </h3>
                    
                    <div class="bg-gray-50 p-4 space-y-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Nom complet:</span>
                                <p class="text-sm text-gray-900">{{ $bachelier->nom }} {{ $bachelier->prenoms }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Email:</span>
                                <p class="text-sm text-gray-900">{{ $bachelier->email }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Téléphone:</span>
                                <p class="text-sm text-gray-900">{{ $bachelier->telephone ?: 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Région:</span>
                                <p class="text-sm text-gray-900">{{ $bachelier->region ?: 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Série Bac:</span>
                                <p class="text-sm text-gray-900">{{ $bachelier->serie_bac ? 'Série ' . $bachelier->serie_bac : 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Année Bac:</span>
                                <p class="text-sm text-gray-900">{{ $bachelier->annee_bac ?: 'Non renseignée' }}</p>
                            </div>
                        </div>
                        
                        @if($bachelier->adresse)
                        <div>
                            <span class="text-sm font-medium text-gray-700">Adresse:</span>
                            <p class="text-sm text-gray-900">{{ $bachelier->adresse }}</p>
                        </div>
                        @endif
                        
                        @if($bachelier->filiere_souhaitee)
                        <div>
                            <span class="text-sm font-medium text-gray-700">Filière souhaitée:</span>
                            <p class="text-sm text-gray-900">{{ $bachelier->filiere_souhaitee }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Notes administratives -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="sticky-note" class="w-5 h-5 mr-2 text-primary-600"></i>
                        Notes Administratives
                    </h3>
                    
                    <div class="mb-4">
                        <label for="notes_admin" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes internes (visibles uniquement par l'administration)
                        </label>
                        <textarea name="notes_admin" id="notes_admin" rows="6"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('notes_admin') border-red-500 @enderror"
                                  placeholder="Ajoutez vos notes concernant ce bachelier...">{{ old('notes_admin', $bachelier->notes_admin) }}</textarea>
                        @error('notes_admin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Statistiques -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="bar-chart" class="w-5 h-5 mr-2 text-primary-600"></i>
                        Statistiques
                    </h3>
                    
                    <div class="bg-gray-50 p-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Candidatures soumises:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $bachelier->candidatures->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Opportunités en favoris:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $bachelier->favoris->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Bourses reçues:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $bachelier->dotations->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Inscrit le:</span>
                            <span class="text-sm text-gray-500">{{ $bachelier->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Dernière connexion:</span>
                            <span class="text-sm text-gray-500">{{ $bachelier->user->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex justify-between items-center pt-6 mt-6 border-t border-gray-200">
            <div class="flex space-x-3">
                <a href="{{ route('admin.bacheliers.show', $bachelier) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 flex items-center rounded-md border border-gray-700">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                    Annuler
                </a>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" onclick="resetForm()" 
                        class="bg-secondary-600 hover:bg-secondary-700 text-white px-6 py-2 flex items-center rounded-md">
                    <i data-lucide="rotate-ccw" class="w-4 h-4 mr-2"></i>
                    Réinitialiser
                </button>
                <button type="submit" 
                        class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 flex items-center rounded-md">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // Auto-calculer la mention basée sur la moyenne
    const moyenneInput = document.getElementById('moyenne_bac');
    const mentionSelect = document.getElementById('mention');
    
    moyenneInput.addEventListener('input', function() {
        const moyenne = parseFloat(this.value);
        
        if (isNaN(moyenne)) {
            mentionSelect.value = '';
            return;
        }
        
        if (moyenne >= 18) {
            mentionSelect.value = 'excellent';
        } else if (moyenne >= 16) {
            mentionSelect.value = 'tres_bien';
        } else if (moyenne >= 14) {
            mentionSelect.value = 'bien';
        } else if (moyenne >= 12) {
            mentionSelect.value = 'assez_bien';
        } else if (moyenne >= 10) {
            mentionSelect.value = 'passable';
        } else {
            mentionSelect.value = '';
        }
    });
});

function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ?')) {
        document.querySelector('form').reset();
        
        // Restaurer les valeurs originales
        document.getElementById('status_profil').value = '{{ $bachelier->status_profil }}';
        document.querySelector('input[name="boursier_peub"]').checked = {{ $bachelier->boursier_peub ? 'true' : 'false' }};
        document.getElementById('moyenne_bac').value = '{{ $bachelier->moyenne_bac }}';
        document.getElementById('mention').value = '{{ $bachelier->mention }}';
        document.getElementById('notes_admin').value = `{{ addslashes($bachelier->notes_admin) }}`;
    }
}
</script>
@endpush 