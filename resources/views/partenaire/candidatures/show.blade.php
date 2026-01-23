@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white  shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Détails de la candidature</h1>
                    <p class="text-gray-600">{{ $candidature->bachelier->nom }} {{ $candidature->bachelier->prenom }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('partenaire.candidatures.index') }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        ← Retour aux candidatures
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 p-4">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations du candidat -->
                <div class="bg-white border border-gray-200">
                    <div class=" p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Informations du candidat</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                                <p class="text-gray-900">{{ $candidature->bachelier->nom }} {{ $candidature->bachelier->prenom }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <p class="text-gray-900">{{ $candidature->bachelier->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                <p class="text-gray-900">{{ $candidature->bachelier->telephone ?? 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                                <p class="text-gray-900">{{ $candidature->bachelier->date_naissance ? $candidature->bachelier->date_naissance->format('d/m/Y') : 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Région</label>
                                <p class="text-gray-900">{{ $candidature->bachelier->region ?? 'Non renseignée' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Statut PEUB</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $candidature->bachelier->is_boursier_peub ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $candidature->bachelier->is_boursier_peub ? 'Boursier PEUB' : 'Candidat' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opportunité -->
                <div class="bg-white border border-gray-200">
                    <div class=" p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Opportunité</h3>
                    </div>
                    <div class="p-6">
                        <h4 class="font-medium text-gray-900 mb-2">{{ $candidature->opportunite->titre }}</h4>
                        <p class="text-gray-600 mb-4">{{ $candidature->opportunite->description }}</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Type:</span>
                                <p class="font-medium">{{ ucfirst($candidature->opportunite->type) }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Pays:</span>
                                <p class="font-medium">{{ $candidature->opportunite->pays }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Durée:</span>
                                <p class="font-medium">{{ $candidature->opportunite->duree }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Date limite:</span>
                                <p class="font-medium">{{ $candidature->opportunite->date_limite_candidature->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lettre de motivation -->
                <div class="bg-white border border-gray-200">
                    <div class=" p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Lettre de motivation</h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-50 border border-gray-200 p-4 rounded">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $candidature->lettre_motivation }}</p>
                        </div>
                    </div>
                </div>

                <!-- Documents joints -->
                @if($candidature->documents_joints && count($candidature->documents_joints) > 0)
                <div class="bg-white border border-gray-200">
                    <div class=" p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Documents joints</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            @foreach($candidature->documents_joints as $document)
                                <div class="flex items-center justify-between p-3 border border-gray-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-gray-900">{{ $document }}</span>
                                    </div>
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Télécharger
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Statut de la candidature -->
                <div class="bg-white border border-gray-200">
                    <div class=" p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Statut de la candidature</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('partenaire.candidatures.update', $candidature) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                                <select name="status" id="status" 
                                        class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:border-blue-500">
                                    <option value="pending" {{ $candidature->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="reviewed" {{ $candidature->status === 'reviewed' ? 'selected' : '' }}>En cours d'examen</option>
                                    <option value="accepted" {{ $candidature->status === 'accepted' ? 'selected' : '' }}>Acceptée</option>
                                    <option value="rejected" {{ $candidature->status === 'rejected' ? 'selected' : '' }}>Refusée</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="commentaire_partenaire" class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
                                <textarea name="commentaire_partenaire" id="commentaire_partenaire" rows="4"
                                          class="w-full border border-gray-300 px-3 py-2 focus:outline-none focus:border-blue-500"
                                          placeholder="Ajoutez un commentaire sur cette candidature...">{{ $candidature->commentaire_partenaire }}</textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">
                                Mettre à jour le statut
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Informations de la candidature -->
                <div class="bg-white border border-gray-200">
                    <div class=" p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Informations</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <span class="text-sm text-gray-500">Date de candidature</span>
                            <p class="font-medium">{{ $candidature->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        
                        @if($candidature->score_matching)
                        <div>
                            <span class="text-sm text-gray-500">Score IA</span>
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 h-2 mr-2">
                                    <div class="bg-blue-600 h-2" style="width: {{ $candidature->score_matching }}%"></div>
                                </div>
                                <span class="font-medium">{{ $candidature->score_matching }}/100</span>
                            </div>
                        </div>
                        @endif

                        @if($candidature->date_reponse)
                        <div>
                            <span class="text-sm text-gray-500">Date de réponse</span>
                            <p class="font-medium">{{ $candidature->date_reponse->format('d/m/Y à H:i') }}</p>
                        </div>
                        @endif

                        @if($candidature->commentaire_partenaire)
                        <div>
                            <span class="text-sm text-gray-500">Commentaire partenaire</span>
                            <p class="text-sm text-gray-900 mt-1">{{ $candidature->commentaire_partenaire }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 