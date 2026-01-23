@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
        <div class="bg-white border border-gray-200 mb-8">
            <div class=" p-6">
                <a href="{{ route('partenaire.opportunites.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
                    Retour
                </a>
                <div class="flex items-center justify-between mt-4">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $opportunite->titre }}</h1>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $opportunite->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $opportunite->status === 'published' ? 'Publiée' : 'Brouillon' }}
                        </span>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('partenaire.opportunites.edit', $opportunite) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                Modifier
                            </a>
                            <form action="{{ route('partenaire.opportunites.destroy', $opportunite) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette opportunité ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu -->
            <div class="p-6 space-y-8">
                <!-- Type et statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            @php
                                $typeIcons = [
                                    'bourse' => 'graduation-cap',
                                    'stage' => 'briefcase',
                                    'emploi' => 'user-check',
                                    'formation' => 'book-open',
                                    'concours' => 'award',
                                    'event' => 'calendar',
                                    'promotion' => 'megaphone'
                                ];
                            @endphp
                            <i data-lucide="{{ $typeIcons[$opportunite->type] }}" class="w-8 h-8 text-primary-600 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500">Type</p>
                                <p class="font-medium">{{ ucfirst($opportunite->type) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i data-lucide="users" class="w-8 h-8 text-primary-600 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500">Candidatures</p>
                                <p class="font-medium">{{ $opportunite->candidatures_count }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i data-lucide="eye" class="w-8 h-8 text-primary-600 mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-500">Vues</p>
                                <p class="font-medium">{{ $opportunite->vues }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Illustration -->
                @if($opportunite->illustration)
                <div class="mt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Illustration</h2>
                    <div class="border border-gray-200 rounded-lg overflow-hidden max-w-md mx-auto">
                        <img src="{{ Storage::url($opportunite->illustration) }}" 
                             alt="{{ $opportunite->titre }}" 
                             class="w-full h-auto object-cover">
                    </div>
                </div>
                @endif

                <!-- Description -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Description</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($opportunite->description)) !!}
                    </div>
                </div>

                <!-- Informations principales -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informations principales</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Pays</p>
                            <p class="font-medium">{{ $opportunite->pays }}</p>
                        </div>
                        @if($opportunite->ville)
                        <div>
                            <p class="text-sm text-gray-500">Ville</p>
                            <p class="font-medium">{{ $opportunite->ville }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-500">Durée</p>
                            <p class="font-medium">{{ $opportunite->duree }} jours</p>
                        </div>
                        @if($opportunite->nombre_places)
                        <div>
                            <p class="text-sm text-gray-500">Nombre de places</p>
                            <p class="font-medium">{{ $opportunite->nombre_places }}</p>
                        </div>
                        @endif
                        @if($opportunite->remuneration)
                        <div>
                            <p class="text-sm text-gray-500">Rémunération</p>
                            <p class="font-medium">{{ number_format($opportunite->remuneration, 0, ',', ' ') }} FCFA</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Dates -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Dates importantes</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @if($opportunite->date_debut)
                        <div>
                            <p class="text-sm text-gray-500">Date de début</p>
                            <p class="font-medium">{{ $opportunite->date_debut->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        @if($opportunite->date_fin)
                        <div>
                            <p class="text-sm text-gray-500">Date de fin</p>
                            <p class="font-medium">{{ $opportunite->date_fin->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-500">Date limite de candidature</p>
                            <p class="font-medium">{{ $opportunite->date_limite_candidature->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Critères et exigences -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Critères et exigences</h2>
                    <div class="grid grid-cols-1 gap-6">
                        @if($opportunite->competences_requises && count($opportunite->competences_requises) > 0)
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Compétences requises</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($opportunite->competences_requises as $competence)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $competence }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($opportunite->criteres_eligibilite && count($opportunite->criteres_eligibilite) > 0)
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Critères d'éligibilité</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($opportunite->criteres_eligibilite as $critere)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ $critere }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($opportunite->documents_requis && count($opportunite->documents_requis) > 0)
                        <div>
                            <p class="text-sm text-gray-500 mb-2">Documents requis</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($opportunite->documents_requis as $document)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    {{ $document }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Candidatures -->
                @if($opportunite->candidatures->count() > 0)
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Candidatures reçues</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidat</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($opportunite->candidatures as $candidature)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $candidature->bachelier->nom }} {{ $candidature->bachelier->prenoms }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $candidature->bachelier->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $candidature->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($candidature->status === 'accepted') bg-green-100 text-green-800
                                            @elseif($candidature->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($candidature->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('partenaire.candidatures.show', $candidature) }}" class="text-primary-600 hover:text-primary-900">
                                            Voir détails
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les icônes Lucide
        lucide.createIcons();
    });
</script>
@endpush
@endsection 