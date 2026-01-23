@extends('layouts.bachelier')

@section('title', 'Ajouter un Parcours - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="PARCOURS / AJOUTER UN PARCOURS" />

    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8 border border-gray-200">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Ajouter un nouveau parcours</h1>
                <p class="text-gray-600 mt-1">Enregistrez votre parcours universitaire et vos formations</p>
            </div>
            
            <form action="{{ route('bachelier.parcours.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="universite_nom" class="block text-sm font-medium text-gray-700 mb-2">Nom de l'établissement</label>
                        <input type="text" name="universite_nom" id="universite_nom" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]" required value="{{ old('universite_nom') }}">
                         @error('universite_nom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="pays" class="block text-sm font-medium text-gray-700 mb-2">Pays</label>
                        <select name="pays" id="pays" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]" required>
                            @foreach($pays as $p)
                                <option value="{{ $p }}" @if($p === "Côte d'Ivoire") selected @endif>{{ $p }}</option>
                            @endforeach
                        </select>
                         @error('pays')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="niveau" class="block text-sm font-medium text-gray-700 mb-2">Niveau d'étude</label>
                        <select name="niveau" id="niveau" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]" required>
                            <option value="">Sélectionnez un niveau</option>
                            @foreach($niveaux as $group => $options)
                                <optgroup label="{{ $group }}">
                                    @foreach($options as $option)
                                        <option value="{{ $option }}" {{ old('niveau') == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('niveau')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="annee_academique" class="block text-sm font-medium text-gray-700 mb-2">Année académique</label>
                        <select name="annee_academique" id="annee_academique" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]" required>
                            <option value="">Sélectionnez une année</option>
                            @foreach($annees as $annee)
                                <option value="{{ $annee }}" {{ old('annee_academique') == $annee ? 'selected' : '' }}>{{ $annee }}</option>
                            @endforeach
                        </select>
                        @error('annee_academique')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="performance" class="block text-sm font-medium text-gray-700 mb-2">Moyenne (/20)</label>
                        <input type="number" step="0.01" name="performance" id="performance" placeholder="Ex: 15.50" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]" value="{{ old('performance') }}" required min="0" max="20">
                        @error('performance')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="mention" class="block text-sm font-medium text-gray-700 mb-2">Mention</label>
                        <select name="mention" id="mention" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]" required>
                            <option value="">Sélectionnez une mention</option>
                            @foreach($mentions as $mention)
                                <option value="{{ $mention }}" {{ old('mention') == $mention ? 'selected' : '' }}>{{ $mention }}</option>
                            @endforeach
                        </select>
                        @error('mention')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                     <div>
                        <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="statut" id="statut" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]" required>
                             @foreach($statuts as $statut)
                                <option value="{{ $statut }}" {{ old('statut', 'en_cours') == $statut ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $statut)) }}</option>
                            @endforeach
                        </select>
                        @error('statut')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="attestation_admission_file" class="block text-sm font-medium text-gray-700 mb-2">Justificatif (Attestation d'admission, bulletin, etc.)</label>
                        <input type="file" name="attestation_admission_file" id="attestation_admission_file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#00BFA5]/10 file:text-[#00BFA5] hover:file:bg-[#00BFA5]/20" required>
                        @error('attestation_admission_file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('bachelier.parcours.index') }}" class="inline-flex items-center px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-[#00BFA5] hover:bg-[#00BFA5]/90 rounded-lg transition-colors">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 