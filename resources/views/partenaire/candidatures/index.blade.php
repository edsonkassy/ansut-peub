@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white  shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Candidatures reçues</h1>
                    <p class="text-gray-600">Gérez les candidatures pour vos opportunités</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 p-4">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">En attente</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Acceptées</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['accepted'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Refusées</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white border border-gray-200 mb-6">
            <div class="p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                        <input type="text" name="search" id="search" 
                               value="{{ request('search') }}"
                               placeholder="Nom, prénom, email..."
                               class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:border-primary-500">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="status" id="status" 
                                class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:border-primary-500">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>En cours d'examen</option>
                            <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Acceptée</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Refusée</option>
                        </select>
                    </div>

                    <div>
                        <label for="opportunite_id" class="block text-sm font-medium text-gray-700 mb-2">Opportunité</label>
                        <select name="opportunite_id" id="opportunite_id" 
                                class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:border-primary-500">
                            <option value="">Toutes les opportunités</option>
                            @foreach($opportunites as $opportunite)
                                <option value="{{ $opportunite->id }}" {{ request('opportunite_id') == $opportunite->id ? 'selected' : '' }}>
                                    {{ $opportunite->titre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-primary-600 text-white px-4 py-2 hover:bg-primary-700">
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des candidatures -->
        <div class="bg-white border border-gray-200">
            <div class=" p-6">
                <h3 class="text-lg font-semibold text-gray-900">Candidatures ({{ $candidatures->total() }})</h3>
            </div>
            
            <div class="p-6">
                @if($candidatures->count() > 0)
                    <div class="space-y-4">
                        @foreach($candidatures as $candidature)
                            <div class="border border-gray-200 p-4 hover:border-blue-300">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <h4 class="font-medium text-gray-900">
                                                {{ $candidature->bachelier->nom }} {{ $candidature->bachelier->prenom }}
                                            </h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $candidature->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($candidature->status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                                   ($candidature->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-primary-100 text-primary-800')) }}">
                                                {{ $candidature->status === 'pending' ? 'En attente' : 
                                                   ($candidature->status === 'accepted' ? 'Acceptée' : 
                                                   ($candidature->status === 'rejected' ? 'Refusée' : 'En cours d\'examen')) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">{{ $candidature->opportunite->titre }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Candidature du {{ $candidature->created_at->format('d/m/Y à H:i') }}
                                            @if($candidature->score_matching)
                                                • Score IA: {{ $candidature->score_matching }}/100
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('partenaire.candidatures.show', $candidature) }}" 
                                           class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                            Voir détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $candidatures->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune candidature</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(request('search') || request('status') || request('opportunite_id'))
                                Aucune candidature ne correspond à vos critères de recherche.
                            @else
                                Vous n'avez pas encore reçu de candidatures pour vos opportunités.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 