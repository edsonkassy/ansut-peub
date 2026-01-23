@extends('layouts.bachelier')

@section('title', 'Mes Candidatures - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="OPPORTUNITÉS / MES CANDIDATURES" />

    <!-- Navigation Pills -->
    <div class="mb-6">
        <x-opportunites-nav />
    </div>

    <div>
        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
            <form id="filter-form" method="GET" action="{{ route('bachelier.candidatures') }}">
                <!-- Barre de recherche pleine largeur -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" id="search" name="search" placeholder="Rechercher dans vos candidatures..." 
                               value="{{ request('search') }}"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row flex-wrap items-center gap-4">
                    <div>
                        <select id="status-filter" name="status" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="">Tous</option>
                            <option value="pending" @selected(request('status') == 'pending')>En attente</option>
                            <option value="reviewed" @selected(request('status') == 'reviewed')>En cours</option>
                            <option value="accepted" @selected(request('status') == 'accepted')>Acceptée</option>
                            <option value="rejected" @selected(request('status') == 'rejected')>Refusée</option>
                        </select>
                    </div>

                    <div>
                        <select id="type-filter" name="type" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="">Tous</option>
                            <option value="bourse" @selected(request('type') == 'bourse')>Bourse</option>
                            <option value="stage" @selected(request('type') == 'stage')>Stage</option>
                            <option value="formation" @selected(request('type') == 'formation')>Formation</option>
                            <option value="emploi" @selected(request('type') == 'emploi')>Emploi</option>
                        </select>
                    </div>

                    <div>
                        <select id="sort-filter" name="sort" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="recent" @selected(request('sort') == 'recent')>Plus récentes</option>
                            <option value="oldest" @selected(request('sort') == 'oldest')>Plus anciennes</option>
                            <option value="deadline" @selected(request('sort') == 'deadline')>Date limite</option>
                            <option value="score" @selected(request('sort') == 'score')>Score IA</option>
                        </select>
                    </div>

                    <button type="button" id="reset-filters" class="p-2 text-[#00BFA5] hover:text-[#00BFA5]/80 transition-colors" title="Réinitialiser les filtres">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">En attente</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="eye" class="w-5 h-5 text-[#00BFA5]"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">En cours</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['reviewed'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="check" class="w-5 h-5 text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Acceptées</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['accepted'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="x" class="w-5 h-5 text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Refusées</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des candidatures -->
        <div class="space-y-4">
            @forelse($candidatures as $candidature)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
                        <!-- Image -->
                        <a href="{{ route('bachelier.opportunites.show', $candidature->opportunite) }}" class="block md:w-48 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden h-40 md:h-32">
                            @if($candidature->opportunite->illustration)
                                <img src="{{ asset('storage/' . $candidature->opportunite->illustration) }}" 
                                     alt="{{ $candidature->opportunite->titre }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    @php
                                        $typeIcons = [
                                            'bourse' => 'graduation-cap',
                                            'stage' => 'briefcase',
                                            'formation' => 'book-open',
                                            'concours' => 'award',
                                            'event' => 'calendar',
                                            'promotion' => 'megaphone'
                                        ];
                                        $typeColors = [
                                            'bourse' => 'text-yellow-600',
                                            'stage' => 'text-[#00BFA5]',
                                            'formation' => 'text-purple-600',
                                            'concours' => 'text-red-600',
                                            'event' => 'text-orange-600',
                                            'promotion' => 'text-pink-600'
                                        ];
                                    @endphp
                                    <i data-lucide="{{ $typeIcons[$candidature->opportunite->type] ?? 'target' }}" 
                                       class="w-12 h-12 {{ $typeColors[$candidature->opportunite->type] ?? 'text-[#00BFA5]' }}"></i>
                                </div>
                            @endif
                        </a>

                        <!-- Contenu -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-2">
                                <h4 class="text-lg font-medium text-gray-900">
                                    <a href="{{ route('bachelier.opportunites.show', $candidature->opportunite) }}" 
                                       class="hover:text-[#00BFA5] transition-colors">
                                        {{ $candidature->opportunite->titre }}
                                    </a>
                                </h4>
                                <div class="flex items-center flex-wrap gap-2 flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @switch($candidature->opportunite->type)
                                        @case('bourse')
                                            bg-green-100 text-green-800
                                            @break
                                        @case('stage')
                                            bg-[#00BFA5]/10 text-[#00BFA5]
                                            @break
                                        @case('formation')
                                            bg-purple-100 text-purple-800
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucfirst($candidature->opportunite->type) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($candidature->status === 'accepted') bg-green-100 text-green-800
                                    @elseif($candidature->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($candidature->status === 'reviewed') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @switch($candidature->status)
                                        @case('pending')
                                            En attente
                                            @break
                                        @case('reviewed')
                                            En cours
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
                                </div>
                            </div>
                            
                            <!-- Partenaire -->
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    @if($candidature->opportunite->partenaire->logo)
                                        <img src="{{ asset('storage/' . $candidature->opportunite->partenaire->logo) }}" 
                                             alt="{{ $candidature->opportunite->partenaire->nom_organisation }}" 
                                             class="w-6 h-6 object-contain">
                                    @else
                                        <i data-lucide="building" class="w-4 h-4 text-gray-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $candidature->opportunite->partenaire->nom_organisation }}</p>
                                    <p class="text-xs text-gray-600">{{ $candidature->opportunite->partenaire->secteur_activite }}</p>
                                </div>
                            </div>
                            
                            <!-- Informations clés en grille -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-3">
                                <div>
                                    <span class="text-gray-500">Lieu:</span>
                                    <p class="font-medium">{{ $candidature->opportunite->ville }}, {{ $candidature->opportunite->pays }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Date limite:</span>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($candidature->opportunite->date_limite_candidature)->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Postulé le:</span>
                                    <p class="font-medium">{{ $candidature->created_at->format('d/m/Y') }}</p>
                                </div>
                                @if($candidature->opportunite->remuneration)
                                <div>
                                    <span class="text-gray-500">Rémunération:</span>
                                    <p class="font-medium">{{ $candidature->opportunite->remuneration }}</p>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Score IA -->
                            @if($candidature->score_ia)
                            <div class="mb-3">
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span class="text-gray-600">Score IA</span>
                                    <span class="font-medium text-gray-900">{{ $candidature->score_ia }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                                    <div class="bg-[#00BFA5] h-2 rounded-full" style="width: {{ $candidature->score_ia }}%"></div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Métadonnées -->
                            <div class="text-xs text-gray-500">
                                {{ $candidature->opportunite->candidatures_count }} candidature(s) au total
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="w-full md:w-auto flex flex-row md:flex-col items-stretch md:items-end gap-2 shrink-0">
                            <a href="{{ route('bachelier.candidatures.show', $candidature) }}" 
                               class="flex-1 md:flex-none inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#00BFA5] hover:bg-[#00BFA5]/90 transition-colors">
                                <i data-lucide="eye" class="w-4 h-4 md:mr-2"></i>
                                <span class="hidden md:inline">Voir détails</span>
                            </a>

                            @if($candidature->status === 'pending')
                            <form action="{{ route('bachelier.candidatures.destroy', $candidature) }}" method="POST" class="inline-flex flex-1 md:flex-none">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette candidature ?')"
                                        class="w-full inline-flex items-center justify-center px-3 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                    <i data-lucide="x" class="w-4 h-4 md:mr-2"></i>
                                    <span class="hidden md:inline">Annuler</span>
                                </button>
                            </form>
                            @endif

                            @if($candidature->status === 'accepted')
                            <a href="{{ route('bachelier.opportunites.show', $candidature->opportunite) }}" 
                               class="flex-1 md:flex-none inline-flex items-center justify-center w-full px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors">
                                <i data-lucide="check" class="w-4 h-4 md:mr-2"></i>
                                <span class="hidden md:inline">Voir l'opportunité</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12">
                <div class="text-center">
                    <i data-lucide="file-text" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune candidature</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore postulé à des opportunités</p>
                    <a href="{{ route('bachelier.opportunites') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-[#00BFA5] hover:bg-[#00BFA5]/90 transition-colors">
                        <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                        Découvrir des opportunités
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($candidatures->hasPages())
        <div class="mt-8">
            {{ $candidatures->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#filter-form');
    const searchInput = document.querySelector('#search');
    const selects = form.querySelectorAll('select');
    const resetBtn = document.getElementById('reset-filters');
    
    // Auto-submit on select change
    selects.forEach(select => {
        select.addEventListener('change', function() {
            form.submit();
        });
    });
    
    // Debounced search
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 300);
        });
    }
    
    // Reset filters
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            window.location.href = '{{ route("bachelier.candidatures") }}';
        });
    }
});
</script>
@endpush
@endsection 