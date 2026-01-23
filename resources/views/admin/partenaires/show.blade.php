@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Bouton retour -->
    <div class="mb-4">
        <a href="{{ route('admin.partenaires.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Retour à la liste</span>
        </a>
    </div>

    <div class="mb-6">
        <div class="flex items-start gap-6">
            <!-- Logo -->
            <div class="w-32 h-32 flex-shrink-0 border border-gray-300">
                @if($partenaire->logo)
                    <img src="{{ Storage::url($partenaire->logo) }}" alt="Logo {{ $partenaire->nom_organisation }}" class="w-full h-full object-contain">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-400">
                        <span class="text-sm">Pas de logo</span>
                    </div>
                @endif
            </div>

            <div class="flex-grow">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $partenaire->nom_organisation }}</h1>
                        <p class="text-sm text-gray-600">Inscrit le {{ $partenaire->created_at->format('d/m/Y') }}</p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $partenaire->status_verification === 'verified' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $partenaire->status_verification === 'verified' ? 'Vérifié' : 'Non vérifié' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.partenaires.edit', $partenaire) }}" 
                           class="px-4 py-2 bg-gray-800 text-white hover:bg-gray-700 transition-colors">
                            Modifier
                        </a>
                        <form action="{{ route('admin.partenaires.toggle-status', $partenaire) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                class="px-4 py-2 {{ $partenaire->status_verification === 'verified' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white transition-colors">
                                {{ $partenaire->status_verification === 'verified' ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations détaillées -->
    <div class="bg-white border border-gray-300 mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations de l'organisation</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Type d'organisation</p>
                    <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $partenaire->type_organisation)) }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Secteur d'activité</p>
                    <p class="font-medium">{{ $partenaire->secteur_activite ?: 'Non spécifié' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Région</p>
                    <p class="font-medium">{{ $partenaire->region }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Commune</p>
                    <p class="font-medium">{{ $partenaire->commune }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Adresse</p>
                    <p class="font-medium">{{ $partenaire->adresse ?: 'Non spécifiée' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Téléphone</p>
                    <p class="font-medium">{{ $partenaire->telephone ?: 'Non spécifié' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Site web</p>
                    <p class="font-medium">
                        @if($partenaire->site_web)
                            <a href="{{ $partenaire->site_web }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $partenaire->site_web }}
                            </a>
                        @else
                            Non spécifié
                        @endif
                    </p>
                </div>
            </div>

            @if($partenaire->description)
                <div class="mt-6">
                    <p class="text-sm text-gray-600">Description</p>
                    <p class="mt-1">{{ $partenaire->description }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Personne de contact -->
    <div class="bg-white border border-gray-300 mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Personne de contact</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Nom complet</p>
                    <p class="font-medium">{{ $partenaire->personne_contact_nom }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Fonction</p>
                    <p class="font-medium">{{ $partenaire->personne_contact_fonction }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium">{{ $partenaire->personne_contact_email }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Téléphone</p>
                    <p class="font-medium">{{ $partenaire->personne_contact_telephone }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Types d'opportunités autorisés -->
    <div class="bg-white border border-gray-300 mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Types d'opportunités autorisés</h2>
            <div class="flex flex-wrap gap-2">
                @if($partenaire->typesOpportunites && $partenaire->typesOpportunites->count() > 0)
                    @foreach($partenaire->typesOpportunites as $type)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            {{ ucfirst($type->type_opportunite) }}
                        </span>
                    @endforeach
                @else
                    <p class="text-gray-500">Aucun type d'opportunité autorisé</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Liste des opportunités -->
    <div class="bg-white border border-gray-300">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Opportunités publiées</h2>
                <div class="text-sm text-gray-500">
                    Total : {{ $partenaire->opportunites->count() }}
                </div>
            </div>

            @if($partenaire->opportunites->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 ">
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Titre</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Type</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Publication</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Date limite</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Candidatures</th>
                                <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($partenaire->opportunites as $opportunite)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        <a href="{{ route('admin.opportunites.show', $opportunite) }}" 
                                           class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                            {{ Str::limit($opportunite->titre, 50) }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ match($opportunite->type) {
                                                'stage' => 'bg-purple-100 text-purple-800',
                                                'emploi' => 'bg-blue-100 text-blue-800',
                                                'formation' => 'bg-green-100 text-green-800',
                                                'bourse' => 'bg-yellow-100 text-yellow-800',
                                                'concours' => 'bg-red-100 text-red-800',
                                                'evenement' => 'bg-indigo-100 text-indigo-800',
                                                'promotion' => 'bg-pink-100 text-pink-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            } }}">
                                            {{ ucfirst($opportunite->type) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-600">
                                        {{ $opportunite->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-600">
                                        {{ $opportunite->date_limite_candidature->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $opportunite->candidatures_count ?? $opportunite->candidatures->count() }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($opportunite->date_limite_candidature->isFuture() && $opportunite->status === 'published')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>Aucune opportunité publiée</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 