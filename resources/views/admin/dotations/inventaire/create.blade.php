@extends('layouts.admin')

@section('title', 'Ajouter un Article à l\'Inventaire - PEUB Admin')

@section('page-title', 'Ajouter un Article')

@section('content')
<div class="bg-white border border-gray-300 p-6">
    <form action="{{ route('admin.dotations.inventaire.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Nom -->
            <div class="lg:col-span-2">
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom de l'article</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Type de dotation -->
            <div>
                <label for="type_dotation" class="block text-sm font-medium text-gray-700">Type de dotation</label>
                <select name="type_dotation" id="type_dotation" required class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="ordinateur_portable">Ordinateur Portable</option>
                    <option value="connexion_internet">Connexion Internet</option>
                    <option value="abonnement_ia">Abonnement IA</option>
                </select>
            </div>

            <!-- Description -->
            <div class="lg:col-span-3">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">{{ old('description') }}</textarea>
            </div>

            <!-- Valeur Unitaire -->
            <div>
                <label for="valeur_unitaire" class="block text-sm font-medium text-gray-700">Valeur Unitaire (FCFA)</label>
                <input type="number" name="valeur_unitaire" id="valeur_unitaire" value="{{ old('valeur_unitaire', 0) }}" required step="0.01" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Prix Mensuel -->
            <div>
                <label for="prix_mensuel" class="block text-sm font-medium text-gray-700">Prix Mensuel (FCFA)</label>
                <input type="number" name="prix_mensuel" id="prix_mensuel" value="{{ old('prix_mensuel') }}" step="0.01" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="Pour abonnements">
            </div>

            <!-- Stock Initial -->
            <div>
                <label for="stock_total" class="block text-sm font-medium text-gray-700">Stock Initial</label>
                <input type="number" name="stock_total" id="stock_total" value="{{ old('stock_total', 0) }}" required class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Stock Minimum -->
            <div>
                <label for="stock_minimum" class="block text-sm font-medium text-gray-700">Stock Minimum (Alerte)</label>
                <input type="number" name="stock_minimum" id="stock_minimum" value="{{ old('stock_minimum', 0) }}" required class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Fournisseur -->
            <div>
                <label for="fournisseur_id" class="block text-sm font-medium text-gray-700">Fournisseur</label>
                <select name="fournisseur_id" id="fournisseur_id" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Aucun</option>
                    @foreach($fournisseurs as $fournisseur)
                        <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>{{ $fournisseur->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date d'achat -->
            <div>
                <label for="date_achat" class="block text-sm font-medium text-gray-700">Date d'achat</label>
                <input type="date" name="date_achat" id="date_achat" value="{{ old('date_achat') }}" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

             <!-- Marque -->
            <div>
                <label for="marque" class="block text-sm font-medium text-gray-700">Marque / Opérateur</label>
                <input type="text" name="marque" id="marque" value="{{ old('marque') }}" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Modèle -->
            <div>
                <label for="modele" class="block text-sm font-medium text-gray-700">Modèle / Plan</label>
                <input type="text" name="modele" id="modele" value="{{ old('modele') }}" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Durée de validité -->
            <div>
                <label for="duree_validite" class="block text-sm font-medium text-gray-700">Durée de validité</label>
                <input type="text" name="duree_validite" id="duree_validite" value="{{ old('duree_validite') }}" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="Ex: 12 mois, 2 ans...">
            </div>

            <!-- Caractéristiques -->
            <div class="lg:col-span-3">
                <label for="caracteristiques" class="block text-sm font-medium text-gray-700">Caractéristiques</label>
                <input type="text" name="caracteristiques" id="caracteristiques" value="{{ old('caracteristiques') }}" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="Ex: 8Go RAM, 256Go SSD, Core i5">
            </div>

            <!-- Statut -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status" required class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="active" selected>Actif</option>
                    <option value="suspendu">Suspendu</option>
                    <option value="archive">Archivé</option>
                </select>
            </div>

        </div>

        <div class="mt-6 flex space-x-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2">
                Enregistrer
            </button>
            <a href="{{ route('admin.dotations.inventaire.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black px-6 py-2 rounded-md border border-gray-300">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection 