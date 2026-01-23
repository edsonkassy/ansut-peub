@extends('layouts.admin')

@section('title', 'Modifier un Article de l\'Inventaire - PEUB Admin')

@section('page-title')
    Modifier l'Article: {{ $inventaire->nom }}
@endsection

@section('content')
<div class="bg-white border border-gray-300 p-6">
    <form action="{{ route('admin.dotations.inventaire.update', $inventaire) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Nom -->
            <div class="lg:col-span-2">
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom de l'article</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom', $inventaire->nom) }}" required class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Type de dotation -->
            <div>
                <label for="type_dotation" class="block text-sm font-medium text-gray-700">Type de dotation</label>
                <select name="type_dotation" id="type_dotation" required class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="ordinateur_portable" {{ $inventaire->type_dotation == 'ordinateur_portable' ? 'selected' : '' }}>Ordinateur Portable</option>
                    <option value="connexion_internet" {{ $inventaire->type_dotation == 'connexion_internet' ? 'selected' : '' }}>Connexion Internet</option>
                    <option value="abonnement_ia" {{ $inventaire->type_dotation == 'abonnement_ia' ? 'selected' : '' }}>Abonnement IA</option>
                </select>
            </div>

            <!-- Description -->
            <div class="lg:col-span-3">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">{{ old('description', $inventaire->description) }}</textarea>
            </div>

            <!-- Valeur Unitaire -->
            <div>
                <label for="valeur_unitaire" class="block text-sm font-medium text-gray-700">Valeur Unitaire (FCFA)</label>
                <input type="number" name="valeur_unitaire" id="valeur_unitaire" value="{{ old('valeur_unitaire', $inventaire->valeur_unitaire) }}" required step="0.01" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Prix Mensuel -->
            <div>
                <label for="prix_mensuel" class="block text-sm font-medium text-gray-700">Prix Mensuel (FCFA)</label>
                <input type="number" name="prix_mensuel" id="prix_mensuel" value="{{ old('prix_mensuel', $inventaire->prix_mensuel) }}" step="0.01" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="Pour abonnements">
            </div>

            <!-- Stock Total -->
            <div>
                <label for="stock_total" class="block text-sm font-medium text-gray-700">Stock Total</label>
                <input type="number" name="stock_total" id="stock_total" value="{{ old('stock_total', $inventaire->stock_total) }}" required class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                <p class="text-xs text-gray-500 mt-1">Actuellement: {{ $inventaire->stock_disponible }} dispo, {{ $inventaire->stock_attribue }} attribué.</p>
            </div>

            <!-- Stock Minimum -->
            <div>
                <label for="stock_minimum" class="block text-sm font-medium text-gray-700">Stock Minimum (Alerte)</label>
                <input type="number" name="stock_minimum" id="stock_minimum" value="{{ old('stock_minimum', $inventaire->stock_minimum) }}" required class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Fournisseur -->
            <div>
                <label for="fournisseur_id" class="block text-sm font-medium text-gray-700">Fournisseur</label>
                <select name="fournisseur_id" id="fournisseur_id" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Aucun</option>
                    @foreach($fournisseurs as $fournisseur)
                        <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id', $inventaire->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>{{ $fournisseur->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date d'achat -->
            <div>
                <label for="date_achat" class="block text-sm font-medium text-gray-700">Date d'achat</label>
                <input type="date" name="date_achat" id="date_achat" value="{{ old('date_achat', optional($inventaire->date_achat)->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

             <!-- Marque -->
            <div>
                <label for="marque" class="block text-sm font-medium text-gray-700">Marque / Opérateur</label>
                <input type="text" name="marque" id="marque" value="{{ old('marque', $inventaire->marque) }}" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Modèle -->
            <div>
                <label for="modele" class="block text-sm font-medium text-gray-700">Modèle / Plan</label>
                <input type="text" name="modele" id="modele" value="{{ old('modele', $inventaire->modele) }}" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <!-- Durée de validité -->
            <div>
                <label for="duree_validite" class="block text-sm font-medium text-gray-700">Durée de validité</label>
                <input type="text" name="duree_validite" id="duree_validite" value="{{ old('duree_validite', $inventaire->duree_validite) }}" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="Ex: 12 mois, 2 ans...">
            </div>

            <!-- Caractéristiques -->
            <div class="lg:col-span-3">
                <label for="caracteristiques" class="block text-sm font-medium text-gray-700">Caractéristiques</label>
                <input type="text" name="caracteristiques" id="caracteristiques" value="{{ old('caracteristiques', $inventaire->caracteristiques) }}" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500" placeholder="Ex: 8Go RAM, 256Go SSD, Core i5">
            </div>

            <!-- Statut -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status" required class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="active" {{ $inventaire->status == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="suspendu" {{ $inventaire->status == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    <option value="archive" {{ $inventaire->status == 'archive' ? 'selected' : '' }}>Archivé</option>
                </select>
            </div>

        </div>

        <div class="mt-6 flex space-x-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2">
                Mettre à jour
            </button>
            <a href="{{ route('admin.dotations.inventaire.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black px-6 py-2 rounded-md border border-gray-300">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection 