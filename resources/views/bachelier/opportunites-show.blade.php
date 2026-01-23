@extends('layouts.bachelier')

@section('title', $opportunite->titre . ' - Opportunité')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <div class="flex items-center gap-2">
            <a href="{{ route('bachelier.opportunites') }}" class="text-sm text-gray-500 hover:text-[#00BFA5] font-medium uppercase tracking-wider">
                OPPORTUNITÉS
            </a>
            <span class="text-sm text-gray-400">/</span>
            <span class="text-sm text-gray-700 font-medium uppercase tracking-wider">{{ Str::limit($opportunite->titre, 30) }}</span>
        </div>
    </div>

    <!-- Header avec titre et actions -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $opportunite->titre }}</h1>
                <p class="text-gray-600">{{ $opportunite->partenaire->nom_organisation }}</p>
            </div>
            <div class="flex items-center space-x-3 self-end sm:self-center">
                <button class="favorite-btn p-2 hover:bg-gray-100 rounded-lg transition-colors" data-opportunite-id="{{ $opportunite->id }}">
                    <i data-lucide="heart" class="w-6 h-6 text-gray-400 hover:text-red-500 transition-colors
                        {{ $opportunite->isFavorited ? 'text-red-500 fill-current' : '' }}"></i>
                </button>
                <button class="share-btn p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i data-lucide="share-2" class="w-6 h-6 text-gray-400 hover:text-gray-600"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Image d'illustration -->
    <div class="bg-white rounded-lg border border-gray-200 mb-6 overflow-hidden">
            @if($opportunite->illustration)
                <img src="{{ asset('storage/' . $opportunite->illustration) }}" 
                     alt="{{ $opportunite->titre }}" 
                     class="w-full h-64 md:h-80 object-cover">
            @else
                <!-- Placeholder avec icône selon le type -->
                <div class="w-full h-64 md:h-80 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-4 bg-[#00BFA5]/10 rounded-full flex items-center justify-center">
                            @switch($opportunite->type)
                                @case('bourse')
                                    <i data-lucide="graduation-cap" class="w-10 h-10 text-[#00BFA5]"></i>
                                    @break
                                @case('stage')
                                    <i data-lucide="briefcase" class="w-10 h-10 text-[#00BFA5]"></i>
                                    @break
                                @case('formation')
                                    <i data-lucide="book-open" class="w-10 h-10 text-[#00BFA5]"></i>
                                    @break
                                @case('concours')
                                    <i data-lucide="trophy" class="w-10 h-10 text-[#00BFA5]"></i>
                                    @break
                                @case('event')
                                    <i data-lucide="calendar" class="w-10 h-10 text-[#00BFA5]"></i>
                                    @break
                                @case('promotion')
                                    <i data-lucide="megaphone" class="w-10 h-10 text-[#00BFA5]"></i>
                                    @break
                                @default
                                    <i data-lucide="target" class="w-10 h-10 text-[#00BFA5]"></i>
                            @endswitch
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ ucfirst($opportunite->type) }}</h3>
                        <p class="text-sm text-gray-500">{{ $opportunite->partenaire->nom_organisation }}</p>
                    </div>
                </div>
            @endif
    </div>

    <!-- Contenu principal avec sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($opportunite->description)) !!}
                </div>
            </div>

            <!-- Compétences requises -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Compétences requises</h2>
                <div class="flex flex-wrap gap-2">
                    @if(is_array($opportunite->competences_requises))
                        @foreach($opportunite->competences_requises as $competence)
                            <span class="inline-flex items-center px-2.5 py-0.5 text-sm font-medium bg-gray-100 text-gray-800 rounded-full">
                                {{ trim($competence) }}
                            </span>
                        @endforeach
                    @elseif($opportunite->competences_requises)
                        @foreach(explode(',', $opportunite->competences_requises) as $competence)
                            <span class="inline-flex items-center px-2.5 py-0.5 text-sm font-medium bg-gray-100 text-gray-800 rounded-full">
                                {{ trim($competence) }}
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Critères d'éligibilité -->
            @if($opportunite->criteres_eligibilite)
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Critères d'éligibilité</h2>
                <div class="prose max-w-none text-gray-700">
                    @if(is_array($opportunite->criteres_eligibilite))
                        <ul class="list-disc pl-5 space-y-2">
                            @foreach($opportunite->criteres_eligibilite as $critere)
                                <li>{{ $critere }}</li>
                            @endforeach
                        </ul>
                    @else
                        {!! nl2br(e($opportunite->criteres_eligibilite)) !!}
                    @endif
                </div>
            </div>
            @endif

            <!-- Processus de candidature -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Processus de candidature</h2>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-[#00BFA5]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-[#00BFA5]">1</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Candidature en ligne</h3>
                            <p class="text-sm text-gray-600">Remplissez le formulaire de candidature avec vos informations</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-[#00BFA5]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-[#00BFA5]">2</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Évaluation IA</h3>
                            <p class="text-sm text-gray-600">Notre IA analyse votre profil et calcule un score de compatibilité</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-[#00BFA5]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-[#00BFA5]">3</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Sélection</h3>
                            <p class="text-sm text-gray-600">Le partenaire examine les candidatures et sélectionne les meilleurs profils</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-[#00BFA5]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-[#00BFA5]">4</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Notification</h3>
                            <p class="text-sm text-gray-600">Vous recevez une notification du résultat de votre candidature</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Statistiques</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-[#00BFA5]">{{ $opportunite->vues }}</div>
                        <div class="text-sm text-gray-600">Vues</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-[#00BFA5]">{{ $opportunite->candidatures_count }}</div>
                        <div class="text-sm text-gray-600">Candidatures</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $opportunite->favoris_count }}</div>
                        <div class="text-sm text-gray-600">Favoris</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $opportunite->score_ia ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-600">Score IA</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Carte d'action -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-[#00BFA5]/10 rounded-lg flex items-center justify-center mx-auto mb-4">
                        @switch($opportunite->type)
                            @case('bourse')
                                <i data-lucide="award" class="w-8 h-8 text-[#00BFA5]"></i>
                                @break
                            @case('stage')
                                <i data-lucide="briefcase" class="w-8 h-8 text-[#00BFA5]"></i>
                                @break
                            @case('formation')
                                <i data-lucide="graduation-cap" class="w-8 h-8 text-[#00BFA5]"></i>
                                @break
                            @default
                                <i data-lucide="target" class="w-8 h-8 text-[#00BFA5]"></i>
                        @endswitch
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ ucfirst($opportunite->type) }}</h3>
                    <p class="text-sm text-gray-600">{{ $opportunite->partenaire->nom_organisation }}</p>
                </div>

                @if(!$hasApplied)
                    <button type="button" 
                            onclick="openCandidatureConfirmModal({{ $opportunite->id }}, '{{ addslashes($opportunite->titre) }}', '{{ addslashes($opportunite->partenaire->nom_organisation) }}', '{{ $opportunite->type }}', false)"
                            class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg text-base font-medium text-white bg-[#00BFA5] hover:bg-[#00BFA5]/90 transition-colors mb-4">
                        <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                        Postuler maintenant
                    </button>
                @else
                    <button disabled 
                            class="w-full inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-base font-medium text-gray-400 bg-gray-100 cursor-not-allowed mb-4">
                        <i data-lucide="check" class="w-5 h-5 mr-2"></i>
                        Déjà postulé
                    </button>
                @endif

                <div class="text-center">
                    <p class="text-sm text-gray-600">Date limite de candidature</p>
                    <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($opportunite->date_limite_candidature)->format('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Informations clés -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations clés</h3>
                <div class="space-y-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i>
                        <span>{{ $opportunite->ville }}, {{ $opportunite->pays }}</span>
                    </div>
                    @if($opportunite->duree)
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                        <span>Durée: {{ $opportunite->duree }}</span>
                    </div>
                    @endif
                    @if($opportunite->remuneration)
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-lucide="dollar-sign" class="w-4 h-4 mr-2"></i>
                        <span>Rémunération: {{ $opportunite->remuneration }}</span>
                    </div>
                    @endif
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                        <span>Date limite: {{ \Carbon\Carbon::parse($opportunite->date_limite_candidature)->format('d/m/Y') }}</span>
                    </div>
                    @if($opportunite->nombre_places)
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-lucide="users" class="w-4 h-4 mr-2"></i>
                        <span>Places disponibles: {{ $opportunite->nombre_places }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Partenaire -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">À propos du partenaire</h3>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        @if($opportunite->partenaire->logo)
                            <img src="{{ asset('storage/' . $opportunite->partenaire->logo) }}" 
                                 alt="{{ $opportunite->partenaire->nom_organisation }}" 
                                 class="w-10 h-10 object-contain">
                        @else
                            <i data-lucide="building" class="w-6 h-6 text-gray-600"></i>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $opportunite->partenaire->nom_organisation }}</p>
                        <p class="text-sm text-gray-600">{{ $opportunite->partenaire->secteur_activite }}</p>
                    </div>
                </div>
                
                @if($opportunite->partenaire->description)
                <p class="text-sm text-gray-600">{{ Str::limit($opportunite->partenaire->description, 150) }}</p>
                @endif

                <div class="mt-4 flex items-center space-x-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                    <span class="text-sm text-gray-600">{{ $opportunite->partenaire->ville }}, {{ $opportunite->partenaire->pays }}</span>
                </div>
            </div>

            <!-- Opportunités similaires -->
            @if($opportunites_similaires->count() > 0)
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Opportunités similaires</h3>
                <div class="space-y-3">
                    @foreach($opportunites_similaires->take(3) as $similaire)
                    <a href="{{ route('bachelier.opportunites.show', $similaire) }}" 
                       class="block p-3 rounded-lg border border-gray-200 hover:border-[#00BFA5] transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-[#00BFA5]/10 rounded-lg flex items-center justify-center">
                                @switch($similaire->type)
                                    @case('bourse')
                                        <i data-lucide="award" class="w-4 h-4 text-[#00BFA5]"></i>
                                        @break
                                    @case('stage')
                                        <i data-lucide="briefcase" class="w-4 h-4 text-[#00BFA5]"></i>
                                        @break
                                    @case('formation')
                                        <i data-lucide="graduation-cap" class="w-4 h-4 text-[#00BFA5]"></i>
                                        @break
                                    @default
                                        <i data-lucide="target" class="w-4 h-4 text-[#00BFA5]"></i>
                                @endswitch
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $similaire->titre }}</p>
                                <p class="text-xs text-gray-600">{{ $similaire->partenaire->nom_organisation }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Favoris
    const favoriteBtn = document.querySelector('.favorite-btn');
    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', function() {
            const opportuniteId = this.dataset.opportuniteId;
            const icon = this.querySelector('i');
            
            fetch(`/bachelier/favoris/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    opportunite_id: opportuniteId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.isFavorited) {
                    icon.classList.add('text-red-500', 'fill-current');
                } else {
                    icon.classList.remove('text-red-500', 'fill-current');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        });
    }

    // Partage
    const shareBtn = document.querySelector('.share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $opportunite->titre }}',
                    text: 'Découvrez cette opportunité sur PEUB',
                    url: window.location.href,
                });
            } else {
                // Fallback pour les navigateurs qui ne supportent pas l'API de partage
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Lien copié dans le presse-papiers !');
                });
            }
        });
    }
});
</script>
@endpush

@include('bachelier.candidature-confirm-modal') 