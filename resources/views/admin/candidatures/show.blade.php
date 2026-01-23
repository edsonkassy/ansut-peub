@extends('layouts.admin')

@section('title', 'Détails de la candidature - PEUB')

@section('page-title', 'Détails de la candidature')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec navigation -->
    <div class="bg-white border border-gray-300 p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.candidatures.index') }}" 
                       class="text-gray-500 hover:text-gray-700">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Candidature #{{ $candidature->id }}
                    </h1>
                </div>
                <p class="mt-1 text-gray-600">
                    Soumise le {{ $candidature->date_soumission ? $candidature->date_soumission->format('d/m/Y à H:i') : $candidature->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Statut -->
                @switch($candidature->status)
                    @case('pending')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            En attente
                        </span>
                        @break
                    @case('reviewed')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            Examinée
                        </span>
                        @break
                    @case('accepted')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Acceptée
                        </span>
                        @break
                    @case('rejected')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            Refusée
                        </span>
                        @break
                    @case('participated')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                            Participé
                        </span>
                        @break
                    @default
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                            {{ ucfirst($candidature->status) }}
                        </span>
                @endswitch
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails de la candidature -->
            <div class="bg-white border border-gray-300 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i data-lucide="file-text" class="w-5 h-5 inline mr-2 text-primary-500"></i>
                    Détails de la candidature
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type d'interaction</label>
                        <p class="mt-1 text-sm text-gray-900 capitalize">{{ $candidature->type_interaction }}</p>
                    </div>
                    
                    @if($candidature->lettre_motivation)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Lettre de motivation</label>
                        <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $candidature->lettre_motivation }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($candidature->documents_joints && count($candidature->documents_joints) > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Documents joints</label>
                        <div class="space-y-2">
                            @foreach($candidature->documents_joints as $document)
                            <div class="flex items-center space-x-2 p-2 border border-gray-200 rounded">
                                <i data-lucide="file" class="w-4 h-4 text-gray-500"></i>
                                <a href="{{ Storage::url($document) }}" target="_blank" 
                                   class="text-sm text-primary-600 hover:text-primary-800">
                                    {{ basename($document) }}
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($candidature->score_matching)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Score de compatibilité</label>
                        <div class="mt-1 flex items-center space-x-3">
                            <div class="text-lg font-bold text-gray-900">{{ $candidature->score_matching }}%</div>
                            <div class="flex-1 bg-gray-200 rounded-full h-3 max-w-xs">
                                <div class="h-3 rounded-full {{ $candidature->score_matching >= 80 ? 'bg-green-400' : ($candidature->score_matching >= 60 ? 'bg-yellow-400' : 'bg-red-400') }}" 
                                     style="width: {{ $candidature->score_matching }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Réponse du partenaire -->
            @if($candidature->date_reponse || $candidature->commentaire_partenaire)
            <div class="bg-white border border-gray-300 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i data-lucide="message-square" class="w-5 h-5 inline mr-2 text-secondary-500"></i>
                    Réponse du partenaire
                </h2>
                
                <div class="space-y-4">
                    @if($candidature->date_reponse)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date de réponse</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $candidature->date_reponse->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif
                    
                    @if($candidature->commentaire_partenaire)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Commentaire</label>
                        <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $candidature->commentaire_partenaire }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Informations supplémentaires -->
            @if($candidature->evaluation_experience || $candidature->commentaire_evaluation || $candidature->certificat_obtenu || $candidature->code_utilise)
            <div class="bg-white border border-gray-300 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i data-lucide="award" class="w-5 h-5 inline mr-2 text-primary-500"></i>
                    Informations supplémentaires
                </h2>
                
                <div class="space-y-4">
                    @if($candidature->evaluation_experience)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Évaluation de l'expérience</label>
                        <div class="mt-1 flex items-center space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i <= $candidature->evaluation_experience ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                            @endfor
                            <span class="ml-2 text-sm text-gray-600">({{ $candidature->evaluation_experience }}/5)</span>
                        </div>
                    </div>
                    @endif
                    
                    @if($candidature->commentaire_evaluation)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Commentaire d'évaluation</label>
                        <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md">
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $candidature->commentaire_evaluation }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($candidature->certificat_obtenu)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Certificat obtenu</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $candidature->certificat_obtenu }}</p>
                    </div>
                    @endif
                    
                    @if($candidature->code_utilise)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Code utilisé</label>
                        <p class="mt-1 text-sm text-gray-900 font-mono">{{ $candidature->code_utilise }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informations du candidat -->
            <div class="bg-white border border-gray-300 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i data-lucide="user" class="w-5 h-5 inline mr-2 text-primary-500"></i>
                    Candidat
                </h2>
                
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                                <span class="text-lg font-medium text-primary-600">
                                    {{ strtoupper(substr($candidature->bachelier->prenom ?? 'N', 0, 1)) }}{{ strtoupper(substr($candidature->bachelier->nom ?? 'A', 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $candidature->bachelier->prenom }} {{ $candidature->bachelier->nom }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $candidature->bachelier->email }}
                            </div>
                        </div>
                    </div>
                    
                    @if($candidature->bachelier->region)
                    <div>
                        <label class="block text-xs font-medium text-gray-500">Région</label>
                        <p class="text-sm text-gray-900">{{ $candidature->bachelier->region }}</p>
                    </div>
                    @endif
                    
                    @if($candidature->bachelier->serie_bac)
                    <div>
                        <label class="block text-xs font-medium text-gray-500">Série BAC</label>
                        <p class="text-sm text-gray-900">{{ strtoupper($candidature->bachelier->serie_bac) }}</p>
                    </div>
                    @endif
                    
                    @if($candidature->bachelier->note_bac)
                    <div>
                        <label class="block text-xs font-medium text-gray-500">Note BAC</label>
                        <p class="text-sm text-gray-900">{{ $candidature->bachelier->note_bac }}/20</p>
                    </div>
                    @endif
                    
                    @if($candidature->bachelier->boursier_peub)
                    <div class="pt-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-800">
                            <i data-lucide="award" class="w-3 h-3 mr-1"></i>
                            Boursier PEUB
                        </span>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('admin.bacheliers.show', $candidature->bachelier) }}" 
                       class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                        <i data-lucide="external-link" class="w-4 h-4 inline mr-1"></i>
                        Voir le profil complet
                    </a>
                </div>
            </div>
            
            <!-- Informations de l'opportunité -->
            <div class="bg-white border border-gray-300 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i data-lucide="briefcase" class="w-5 h-5 inline mr-2 text-secondary-500"></i>
                    Opportunité
                </h2>
                
                <div class="space-y-3">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">{{ $candidature->opportunite->titre }}</h3>
                        <p class="text-sm text-gray-500 capitalize">{{ $candidature->opportunite->type }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500">Partenaire</label>
                        <p class="text-sm text-gray-900">{{ $candidature->opportunite->partenaire->nom_organisation }}</p>
                    </div>
                    
                    @if($candidature->opportunite->date_limite_candidature)
                    <div>
                        <label class="block text-xs font-medium text-gray-500">Date limite</label>
                        <p class="text-sm text-gray-900">{{ $candidature->opportunite->date_limite_candidature->format('d/m/Y') }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-500">Statut</label>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            {{ $candidature->opportunite->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($candidature->opportunite->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('admin.opportunites.show', $candidature->opportunite) }}" 
                       class="text-secondary-600 hover:text-secondary-800 text-sm font-medium">
                        <i data-lucide="external-link" class="w-4 h-4 inline mr-1"></i>
                        Voir l'opportunité
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .lucide {
        display: inline;
    }
</style>
@endpush 