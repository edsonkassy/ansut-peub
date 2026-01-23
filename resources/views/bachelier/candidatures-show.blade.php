@extends('layouts.bachelier')

@section('title', 'Détails de la candidature - Bachelier')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <div class="flex items-center gap-2">
            <a href="{{ route('bachelier.candidatures') }}" class="text-sm text-gray-500 hover:text-[#00BFA5] font-medium uppercase tracking-wider">
                CANDIDATURES
            </a>
            <span class="text-sm text-gray-400">/</span>
            <span class="text-sm text-gray-700 font-medium uppercase tracking-wider">DÉTAILS</span>
        </div>
    </div>

    <!-- Header avec titre et actions -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Candidature #{{ $candidature->id }}</h1>
                <p class="text-gray-600">{{ $candidature->opportunite->titre }}</p>
            </div>
            <div class="flex items-center space-x-3 self-end sm:self-center">
                <a href="{{ route('bachelier.opportunites.show', $candidature->opportunite) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00BFA5] transition-colors">
                    <i data-lucide="external-link" class="w-4 h-4 mr-2"></i>
                    Voir l'opportunité
                </a>
            </div>
        </div>
    </div>

    <!-- Contenu principal avec sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contenu principal -->
        <div class="lg:col-span-2">
            <!-- Informations de la candidature -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <!-- En-tête -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center
                                @if($candidature->status === 'accepted') bg-green-100
                                @elseif($candidature->status === 'rejected') bg-red-100
                                @elseif($candidature->status === 'reviewed') bg-yellow-100
                                @else bg-gray-100 @endif">
                                    @if($candidature->status === 'accepted')
                                        <i data-lucide="check" class="w-6 h-6 text-green-600"></i>
                                    @elseif($candidature->status === 'rejected')
                                        <i data-lucide="x" class="w-6 h-6 text-red-600"></i>
                                    @elseif($candidature->status === 'reviewed')
                                        <i data-lucide="eye" class="w-6 h-6 text-yellow-600"></i>
                                    @else
                                        <i data-lucide="clock" class="w-6 h-6 text-gray-600"></i>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $candidature->opportunite->titre }}</h2>
                                <p class="text-gray-600">{{ $candidature->opportunite->partenaire->nom_organisation }}</p>
                            </div>
                        </div>
                        
                        <!-- Statut -->
                        <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($candidature->status === 'accepted') bg-green-100 text-green-800
                                    @elseif($candidature->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($candidature->status === 'reviewed') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @switch($candidature->status)
                                        @case('pending')
                                            En attente
                                            @break
                                        @case('reviewed')
                                            En cours d'examen
                                            @break
                                        @case('accepted')
                                            Acceptée
                                            @break
                                        @case('rejected')
                                            Refusée
                                            @break
                                        @default
                                            {{ ucfirst($candidature->status) }}
                                    @endswitch
                            </span>
                            
                            @if($candidature->score_matching)
                            <div class="flex items-center text-sm text-gray-600">
                                <i data-lucide="brain" class="w-4 h-4 mr-1"></i>
                                Score IA: {{ $candidature->score_matching }}%
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Détails de la candidature -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Date de soumission</h3>
                        <p class="text-sm text-gray-600">{{ $candidature->date_soumission->format('d/m/Y à H:i') }}</p>
                    </div>
                    
                    @if($candidature->date_reponse)
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Date de réponse</h3>
                        <p class="text-sm text-gray-600">{{ $candidature->date_reponse->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Type d'interaction</h3>
                        <p class="text-sm text-gray-600">{{ ucfirst($candidature->type_interaction) }}</p>
                    </div>

                    @if($candidature->evaluation_experience)
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Évaluation</h3>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i <= $candidature->evaluation_experience ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                            @endfor
                            <span class="ml-2 text-sm text-gray-600">{{ $candidature->evaluation_experience }}/5</span>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Lettre de motivation -->
                @if($candidature->lettre_motivation)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Lettre de motivation</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($candidature->lettre_motivation)) !!}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Documents joints -->
                @if($candidature->documents_joints && count($candidature->documents_joints) > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents joints</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($candidature->documents_joints as $document)
                                @php
                                    // Gérer les anciens formats (string) et nouveaux formats (array)
                                    $path = is_array($document) ? $document['path'] : $document;
                                    $name = is_array($document) ? ($document['original_name'] ?? 'Document ' . $loop->iteration) : 'Document ' . $loop->iteration;
                                    $mimeType = is_array($document) ? ($document['mime_type'] ?? '') : '';
                                $isPdf = str_contains($mimeType, 'pdf') || str_ends_with($path, '.pdf');
                                $size = is_array($document) ? ($document['size'] ?? 0) : 0;
                                $sizeFormatted = $size > 0 ? number_format($size / 1024 / 1024, 1) . ' MB' : '';
                            @endphp
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i data-lucide="{{ $isPdf ? 'file-text' : 'image' }}" class="w-5 h-5 text-gray-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $name }}</p>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    @if($isPdf)
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full">PDF</span>
                                    @else
                                        <span class="bg-[#00BFA5]/10 text-[#00BFA5] px-2 py-1 rounded-full">Image</span>
                                    @endif
                                    @if($sizeFormatted)
                                        <span>{{ $sizeFormatted }}</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $path) }}" target="_blank" class="text-[#00BFA5] hover:text-[#00BFA5]/80">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Commentaire du partenaire -->
                @if($candidature->commentaire_partenaire)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Commentaire du partenaire</h3>
                    <div class="bg-[#00BFA5]/5 border border-[#00BFA5]/20 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('images/partenaires/' . $candidature->opportunite->partenaire->logo) }}" 
                                     alt="{{ $candidature->opportunite->partenaire->nom_organisation }}"
                                     class="w-8 h-8 rounded-full">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $candidature->opportunite->partenaire->nom_organisation }}</p>
                                <p class="text-sm text-gray-700 mt-1">{{ $candidature->commentaire_partenaire }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Évaluation et commentaire -->
                @if($candidature->commentaire_evaluation)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Évaluation et commentaire</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <p class="text-gray-700">{{ $candidature->commentaire_evaluation }}</p>
                    </div>
                </div>
                @endif

                <!-- Certificat obtenu -->
                @if($candidature->certificat_obtenu)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Certificat obtenu</h3>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <i data-lucide="award" class="w-6 h-6 text-green-600 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-green-900">{{ $candidature->certificat_obtenu }}</p>
                                <p class="text-sm text-green-700">Félicitations ! Vous avez obtenu ce certificat.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Informations de l'opportunité -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de l'opportunité</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Type</p>
                        <p class="text-sm text-gray-600">{{ ucfirst($candidature->opportunite->type) }}</p>
                    </div>

                    @if($candidature->opportunite->ville)
                    <div>
                        <p class="text-sm font-medium text-gray-900">Localisation</p>
                        <p class="text-sm text-gray-600">{{ $candidature->opportunite->ville }}{{ $candidature->opportunite->pays ? ', ' . $candidature->opportunite->pays : '' }}</p>
                    </div>
                    @endif

                    @if($candidature->opportunite->duree)
                    <div>
                        <p class="text-sm font-medium text-gray-900">Durée</p>
                        <p class="text-sm text-gray-600">{{ $candidature->opportunite->duree }}</p>
                    </div>
                    @endif

                    @if($candidature->opportunite->remuneration)
                    <div>
                        <p class="text-sm font-medium text-gray-900">Rémunération</p>
                        <p class="text-sm text-gray-600">{{ $candidature->opportunite->remuneration }}</p>
                    </div>
                    @endif

                    @if($candidature->opportunite->date_limite_candidature)
                    <div>
                        <p class="text-sm font-medium text-gray-900">Date limite</p>
                        <p class="text-sm text-gray-600">{{ $candidature->opportunite->date_limite_candidature->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('bachelier.opportunites.show', $candidature->opportunite) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#00BFA5] hover:bg-[#00BFA5]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00BFA5] transition-colors">
                        Voir l'opportunité complète
                    </a>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                
                <div class="space-y-3">
                    @if($candidature->status === 'pending')
                    <button type="button" 
                            onclick="withdrawCandidature({{ $candidature->id }})"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                        Retirer ma candidature
                    </button>
                    @endif

                    @if($candidature->status === 'accepted')
                    <button type="button" 
                            onclick="contactPartner()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i data-lucide="message-circle" class="w-4 h-4 mr-2"></i>
                        Contacter le partenaire
                    </button>
                    @endif

                    <button type="button" 
                            onclick="shareCandidature()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00BFA5] transition-colors">
                        <i data-lucide="share-2" class="w-4 h-4 mr-2"></i>
                        Partager
                    </button>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistiques</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Vues de l'opportunité</span>
                        <span class="text-sm font-medium text-gray-900">{{ $candidature->opportunite->vues }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total candidatures</span>
                        <span class="text-sm font-medium text-gray-900">{{ $candidature->opportunite->candidatures_count }}</span>
                    </div>
                    @if($candidature->score_matching)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Score IA</span>
                        <span class="text-sm font-medium text-gray-900">{{ $candidature->score_matching }}%</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function withdrawCandidature(candidatureId) {
    if (confirm('Êtes-vous sûr de vouloir retirer cette candidature ? Cette action est irréversible.')) {
        // Logique pour retirer la candidature
        console.log('Retirer candidature:', candidatureId);
        // Ici vous pouvez ajouter une requête AJAX pour retirer la candidature
    }
}

function contactPartner() {
    // Logique pour contacter le partenaire
    console.log('Contacter le partenaire');
    // Rediriger vers la messagerie ou ouvrir un modal
}

function shareCandidature() {
    // Logique pour partager la candidature
    console.log('Partager candidature');
    // Ouvrir un modal de partage ou copier le lien
}
</script>
@endsection 