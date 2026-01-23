@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Modifier le partenaire</h1>
    </div>

    <div class="bg-white border border-gray-300">
        <form action="{{ route('admin.partenaires.update', $partenaire) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PATCH')

            <!-- Logo actuel -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Logo actuel</label>
                <div class="w-32 h-32 border border-gray-300">
                    @if($partenaire->logo)
                        <img src="{{ Storage::url($partenaire->logo) }}" alt="Logo actuel" class="w-full h-full object-contain">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-400">
                            <span class="text-sm">Pas de logo</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Nouveau logo -->
            <div class="mb-6">
                <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Nouveau logo</label>
                <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp" 
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-gray-800 file:text-white hover:file:bg-gray-700">
                @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Informations de l'organisation -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations de l'organisation</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nom_organisation" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'organisation</label>
                        <input type="text" id="nom_organisation" name="nom_organisation" value="{{ old('nom_organisation', $partenaire->nom_organisation) }}" 
                            class="block w-full border border-gray-300 px-3 py-2">
                        @error('nom_organisation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type_organisation" class="block text-sm font-medium text-gray-700 mb-2">Type d'organisation</label>
                        <select id="type_organisation" name="type_organisation" class="block w-full border border-gray-300 px-3 py-2">
                            <option value="entreprise" {{ old('type_organisation', $partenaire->type_organisation) === 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                            <option value="institution_academique" {{ old('type_organisation', $partenaire->type_organisation) === 'institution_academique' ? 'selected' : '' }}>Institution académique</option>
                            <option value="ong" {{ old('type_organisation', $partenaire->type_organisation) === 'ong' ? 'selected' : '' }}>ONG</option>
                            <option value="gouvernement" {{ old('type_organisation', $partenaire->type_organisation) === 'gouvernement' ? 'selected' : '' }}>Gouvernement</option>
                        </select>
                        @error('type_organisation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="secteur_activite" class="block text-sm font-medium text-gray-700 mb-2">Secteur d'activité</label>
                        <input type="text" id="secteur_activite" name="secteur_activite" value="{{ old('secteur_activite', $partenaire->secteur_activite) }}" 
                            class="block w-full border border-gray-300 px-3 py-2">
                        @error('secteur_activite')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Région</label>
                        <input type="text" id="region" name="region" value="{{ old('region', $partenaire->region) }}" 
                            class="block w-full border border-gray-300 px-3 py-2">
                        @error('region')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="commune" class="block text-sm font-medium text-gray-700 mb-2">Commune</label>
                        <input type="text" id="commune" name="commune" value="{{ old('commune', $partenaire->commune) }}" 
                            class="block w-full border border-gray-300 px-3 py-2">
                        @error('commune')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                        <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $partenaire->telephone) }}" 
                            class="block w-full border border-gray-300 px-3 py-2">
                        @error('telephone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="site_web" class="block text-sm font-medium text-gray-700 mb-2">Site web</label>
                        <input type="url" id="site_web" name="site_web" value="{{ old('site_web', $partenaire->site_web) }}" 
                            class="block w-full border border-gray-300 px-3 py-2">
                        @error('site_web')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="adresse" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                        <textarea id="adresse" name="adresse" rows="2" 
                            class="block w-full border border-gray-300 px-3 py-2">{{ old('adresse', $partenaire->adresse) }}</textarea>
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" 
                            class="block w-full border border-gray-300 px-3 py-2">{{ old('description', $partenaire->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informations de contact -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personne de contact</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="personne_contact_nom" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                        <input type="text" id="personne_contact_nom" name="personne_contact_nom" 
                            value="{{ old('personne_contact_nom', $partenaire->personne_contact_nom) }}" 
                            class="block w-full border border-gray-300 px-3 py-2">
                        @error('personne_contact_nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="personne_contact_fonction" class="block text-sm font-medium text-gray-700 mb-2">Fonction</label>
                        <input type="text" id="personne_contact_fonction" name="personne_contact_fonction" 
                            value="{{ old('personne_contact_fonction', $partenaire->personne_contact_fonction) }}" 
                            class="block w-full border border-gray-300 px-3 py-2">
                        @error('personne_contact_fonction')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="personne_contact_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="personne_contact_email" name="personne_contact_email" 
                            value="{{ old('personne_contact_email', $partenaire->personne_contact_email) }}" 
                            class="block w-full border border-gray-300 px-3 py-2">
                        @error('personne_contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="personne_contact_telephone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                        <input type="text" id="personne_contact_telephone" name="personne_contact_telephone" 
                            value="{{ old('personne_contact_telephone', $partenaire->personne_contact_telephone) }}" 
                            class="block w-full border border-gray-300 px-3 py-2">
                        @error('personne_contact_telephone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Types d'opportunités autorisés -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Types d'opportunités autorisés</h2>
                
                <div class="space-y-4">
                    @foreach(App\Models\Partenaire::typesOpportunitesDisponibles() as $value => $label)
                        <div class="flex items-center">
                            <input type="checkbox" 
                                id="type_{{ $value }}" 
                                name="types_opportunites[]" 
                                value="{{ $value }}"
                                {{ $partenaire->peutCreerOpportunite($value) ? 'checked' : '' }}
                                class="h-4 w-4 text-gray-800 border-gray-300">
                            <label for="type_{{ $value }}" class="ml-2 text-sm text-gray-700">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('types_opportunites')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.partenaires.show', $partenaire) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md border border-gray-300">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 