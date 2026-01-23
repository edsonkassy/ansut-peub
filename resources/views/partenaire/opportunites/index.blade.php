@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Mes Opportunités</h1>
                    <p class="text-gray-600">Gérez vos offres de bourses, stages et emplois</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('partenaire.opportunites.create') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium  text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                        <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                        Créer une opportunité
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200  p-4">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200  p-4">
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Liste des opportunités -->
        <div class="bg-white border border-gray-200  shadow-sm">
            <div class=" p-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i data-lucide="briefcase" class="w-5 h-5 mr-2 text-primary-600"></i>
                    Opportunités ({{ $opportunites->total() }})
                </h3>
            </div>
            
            <div class="p-6">
                @if($opportunites->count() > 0)
                    <div class="space-y-4">
                        @foreach($opportunites as $opportunite)
                            <div class="bg-white border border-gray-200 hover:border-primary-300">
                                <div class="p-6">
                                    <div class="flex justify-between items-start gap-6">
                                        <!-- Image -->
                                        <div class="w-48 h-32 flex-shrink-0 bg-gray-100  overflow-hidden">
                                            @if($opportunite->illustration)
                                                <img src="{{ Storage::url($opportunite->illustration) }}" 
                                                     alt="{{ $opportunite->titre }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
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
                                                        $typeColors = [
                                                            'bourse' => 'text-yellow-600',
                                                            'stage' => 'text-blue-600',
                                                            'emploi' => 'text-green-600',
                                                            'formation' => 'text-purple-600',
                                                            'concours' => 'text-red-600',
                                                            'event' => 'text-orange-600',
                                                            'promotion' => 'text-pink-600'
                                                        ];
                                                    @endphp
                                                    <i data-lucide="{{ $typeIcons[$opportunite->type] }}" 
                                                       class="w-12 h-12 {{ $typeColors[$opportunite->type] }}"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Contenu -->
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h4 class="text-lg font-medium text-gray-900">{{ $opportunite->titre }}</h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $opportunite->status === 'published' ? 'bg-green-100 text-green-800' : 
                                                       ($opportunite->status === 'draft' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $opportunite->status === 'published' ? 'Publiée' : 
                                                       ($opportunite->status === 'draft' ? 'Brouillon' : 'Fermée') }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                    {{ ucfirst($opportunite->type) }}
                                                </span>
                                            </div>
                                            
                                            <p class="text-gray-600 mb-3">{{ Str::limit($opportunite->description, 150) }}</p>
                                            
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                <div>
                                                    <span class="text-gray-500">Pays:</span>
                                                    <p class="font-medium">{{ $opportunite->pays }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500">Durée:</span>
                                                    <p class="font-medium">{{ $opportunite->duree }} jours</p>
                                                </div>
                                                @if($opportunite->nombre_places)
                                                <div>
                                                    <span class="text-gray-500">Places:</span>
                                                    <p class="font-medium">{{ $opportunite->nombre_places }}</p>
                                                </div>
                                                @endif
                                                <div>
                                                    <span class="text-gray-500">Date limite:</span>
                                                    <p class="font-medium">{{ $opportunite->date_limite_candidature->format('d/m/Y') }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3 text-xs text-gray-500">
                                                Créée le {{ $opportunite->created_at->format('d/m/Y') }}
                                                @if($opportunite->candidatures_count > 0)
                                                    • {{ $opportunite->candidatures_count }} candidature(s)
                                                @endif
                                                @if($opportunite->vues > 0)
                                                    • {{ $opportunite->vues }} vue(s)
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex flex-col space-y-2">
                                            <a href="{{ route('partenaire.opportunites.show', $opportunite) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-primary-300 text-sm font-medium  text-primary-700 bg-primary-50 hover:bg-primary-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                                                <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                                Voir détails
                                            </a>
                                            <a href="{{ route('partenaire.opportunites.edit', $opportunite) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium  text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                                                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                                Modifier
                                            </a>
                                            <form action="{{ route('partenaire.opportunites.destroy', $opportunite) }}" 
                                                  method="POST" class="inline" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette opportunité ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center w-full px-3 py-2 border border-red-300 text-sm font-medium  text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $opportunites->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i data-lucide="briefcase" class="mx-auto h-12 w-12 text-gray-400"></i>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune opportunité</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Commencez par créer votre première opportunité pour attirer des candidats.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('partenaire.opportunites.create') }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium  text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                                <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                                Créer une opportunité
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 