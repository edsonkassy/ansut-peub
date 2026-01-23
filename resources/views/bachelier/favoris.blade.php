@extends('layouts.bachelier')

@section('title', 'Mes Favoris - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="OPPORTUNITÉS / MES FAVORIS" />

    <!-- Navigation Pills -->
    <div class="mb-6">
        <x-opportunites-nav />
    </div>

    <div>
        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
            <form id="filter-form" method="GET" action="{{ route('bachelier.favoris') }}">
                <!-- Barre de recherche pleine largeur -->
                <div class="mb-6">
                    <div class="relative">
                        <input type="text" id="search" name="search" placeholder="Rechercher dans vos opportunités favorites..." 
                               value="{{ request('search') }}"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row flex-wrap items-center gap-4">
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
                        <select id="location-filter" name="location" class="w-48 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#00BFA5] focus:ring-1 focus:ring-[#00BFA5]">
                            <option value="">Toutes</option>
                            <option value="abidjan" @selected(request('location') == 'abidjan')>Abidjan</option>
                            <option value="bouake" @selected(request('location') == 'bouake')>Bouaké</option>
                            <option value="san-pedro" @selected(request('location') == 'san-pedro')>San Pedro</option>
                            <option value="remote" @selected(request('location') == 'remote')>À distance</option>
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

                </div>
            </form>
        </div>

        <!-- Grille des favoris -->
        <div class="space-y-4">
            @forelse($favoris as $favori)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-xl transition-shadow">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
                        <!-- Image -->
                        <a href="{{ route('bachelier.opportunites.show', $favori->opportunite) }}" class="block md:w-48 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden h-40 md:h-32">
                            @if($favori->opportunite->illustration)
                                <img src="{{ asset('storage/' . $favori->opportunite->illustration) }}" 
                                     alt="{{ $favori->opportunite->titre }}" 
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
                                    <i data-lucide="{{ $typeIcons[$favori->opportunite->type] ?? 'target' }}" 
                                       class="w-12 h-12 {{ $typeColors[$favori->opportunite->type] ?? 'text-[#00BFA5]' }}"></i>
                                </div>
                            @endif
                        </a>

                        <!-- Contenu -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-2">
                                <h4 class="text-lg font-medium text-gray-900">
                                    <a href="{{ route('bachelier.opportunites.show', $favori->opportunite) }}" class="hover:text-[#00BFA5] transition-colors">{{ $favori->opportunite->titre }}</a>
                                </h4>
                                <div class="flex items-center flex-wrap gap-2 flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @switch($favori->opportunite->type)
                                        @case('bourse')
                                            bg-green-100 text-green-800
                                            @break
                                        @case('stage')
                                            bg-[#00BFA5]/10 text-[#00BFA5]
                                            @break
                                        @case('formation')
                                            bg-purple-100 text-purple-800
                                            @break
                                        @case('concours')
                                            bg-orange-100 text-orange-800
                                            @break
                                        @case('event')
                                            bg-pink-100 text-pink-800
                                            @break
                                        @case('promotion')
                                            bg-yellow-100 text-yellow-800
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endswitch">
                                    @switch($favori->opportunite->type)
                                        @case('bourse')
                                            <i data-lucide="award" class="w-3 h-3 mr-1"></i>
                                            @break
                                        @case('stage')
                                            <i data-lucide="briefcase" class="w-3 h-3 mr-1"></i>
                                            @break
                                        @case('formation')
                                            <i data-lucide="graduation-cap" class="w-3 h-3 mr-1"></i>
                                            @break
                                        @default
                                            <i data-lucide="target" class="w-3 h-3 mr-1"></i>
                                    @endswitch
                                    {{ ucfirst($favori->opportunite->type) }}
                                </span>
                                @if($favori->opportunite->score_ia)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#00BFA5]/10 text-[#00BFA5]">
                                    <i data-lucide="brain" class="w-3 h-3 mr-1"></i>
                                    {{ $favori->opportunite->score_ia }}% match
                                </span>
                                @endif
                                </div>
                            </div>
                            
                            <!-- Partenaire -->
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    @if($favori->opportunite->partenaire->logo)
                                        <img src="{{ asset('storage/' . $favori->opportunite->partenaire->logo) }}" 
                                             alt="{{ $favori->opportunite->partenaire->nom_organisation }}" 
                                             class="w-6 h-6 object-contain">
                                    @else
                                        <i data-lucide="building" class="w-4 h-4 text-gray-600"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $favori->opportunite->partenaire->nom_organisation }}</p>
                                    <p class="text-xs text-gray-600">{{ $favori->opportunite->partenaire->secteur_activite }}</p>
                                </div>
                            </div>
                            
                            <p class="text-gray-600 mb-3 text-sm">{{ Str::limit($favori->opportunite->description, 120) }}</p>
                            
                            <!-- Informations clés en grille -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-3">
                                <div>
                                    <span class="text-gray-500">Lieu:</span>
                                    <p class="font-medium">{{ $favori->opportunite->ville }}, {{ $favori->opportunite->pays }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Date limite:</span>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($favori->opportunite->date_limite_candidature)->format('d/m/Y') }}</p>
                                </div>
                                @if($favori->opportunite->remuneration)
                                <div>
                                    <span class="text-gray-500">Rémunération:</span>
                                    <p class="font-medium">{{ $favori->opportunite->remuneration }}</p>
                                </div>
                                @endif
                                @if($favori->opportunite->nombre_places)
                                <div>
                                    <span class="text-gray-500">Places:</span>
                                    <p class="font-medium">{{ $favori->opportunite->nombre_places }}</p>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Tags -->
                            @if($favori->opportunite->competences_requises)
                            <div class="flex flex-wrap gap-1 mb-3">
                                @if(is_array($favori->opportunite->competences_requises))
                                    @foreach($favori->opportunite->competences_requises as $competence)
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                            {{ trim($competence) }}
                                        </span>
                                    @endforeach
                                @else
                                    @foreach(explode(',', $favori->opportunite->competences_requises) as $competence)
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                            {{ trim($competence) }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                            @endif
                            
                            <!-- Score IA -->
                            @if($favori->opportunite->score_ia)
                            <div class="mb-3">
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span class="text-gray-600">Score IA</span>
                                    <span class="font-medium text-gray-900">{{ $favori->opportunite->score_ia }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                                    <div class="bg-[#00BFA5] h-2 rounded-full" style="width: {{ $favori->opportunite->score_ia }}%"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="w-full md:w-auto flex flex-row md:flex-col items-stretch md:items-end gap-2 shrink-0">
                            <button class="favorite-btn p-2 hover:bg-gray-50 rounded-lg text-gray-400 hover:text-red-500 transition-colors" data-opportunite-id="{{ $favori->opportunite->id }}">
                                <i data-lucide="heart" class="w-5 h-5 text-red-500 fill-current"></i>
                            </button>
                            @if(!$favori->opportunite->hasApplied)
                                <form action="{{ route('bachelier.candidatures.store') }}" method="POST" class="flex-1 md:flex-none inline-flex">
                                    @csrf
                                    <input type="hidden" name="opportunite_id" value="{{ $favori->opportunite->id }}">
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#00BFA5] hover:bg-[#00BFA5]/90 transition-colors">
                                        <i data-lucide="send" class="w-4 h-4 md:mr-2"></i>
                                        <span class="hidden md:inline">Postuler</span>
                                    </button>
                                </form>
                            @else
                                <button disabled 
                                        class="w-full flex-1 md:flex-none inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-400 bg-gray-100 cursor-not-allowed">
                                    <i data-lucide="check" class="w-4 h-4 md:mr-2"></i>
                                    <span class="hidden md:inline">Déjà postulé</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-lg p-12 border border-gray-200">
                    <div class="text-center">
                        <i data-lucide="heart" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun favori</h3>
                        <p class="text-gray-600 mb-6">Vous n'avez pas encore ajouté d'opportunités à vos favoris</p>
                        <a href="{{ route('bachelier.opportunites') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-[#00BFA5] hover:bg-[#00BFA5]/90 transition-colors">
                            <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                            Découvrir des opportunités
                        </a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($favoris->hasPages())
        <div class="mt-8">
            {{ $favoris->links() }}
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
            window.location.href = '{{ route("bachelier.favoris") }}';
        });
    }

    // Favoris
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function() {
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
                if (!data.isFavorited) {
                    // Retirer de la liste des favoris
                    this.closest('.bg-white').remove();
                    
                    // Mettre à jour le compteur
                    const totalElement = document.querySelector('.text-2xl.font-bold.text-primary-600');
                    if (totalElement) {
                        const currentTotal = parseInt(totalElement.textContent);
                        totalElement.textContent = Math.max(0, currentTotal - 1);
                    }
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        });
    });
});
</script>
@endpush
@endsection 