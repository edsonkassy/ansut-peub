@extends('layouts.bachelier')

@section('title', 'Dashboard - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">DASHBOARD / ACCUEIL</p>
    </div>

    <!-- Grid principale -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Opportunités recommandées -->
        <div class="bg-[#0E7490] rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Opportunités recommandées</h2>
                <button class="bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-3">
                @forelse($dernieres_opportunites->take(5) as $opportunite)
                <a href="{{ route('bachelier.opportunites.show', $opportunite) }}"
                   class="flex items-center justify-between bg-white/10 hover:bg-white/20 rounded-lg p-4 transition-colors group">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="flex-shrink-0">
                            @switch($opportunite->type)
                                @case('bourse')
                                    <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                                    @break
                                @case('stage')
                                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                                    @break
                                @case('formation')
                                    <i data-lucide="book-open" class="w-5 h-5"></i>
                                    @break
                                @default
                                    <i data-lucide="target" class="w-5 h-5"></i>
                            @endswitch
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ $opportunite->titre }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full font-medium whitespace-nowrap">
                            {{ $opportunite->partenaire->nom_organisation ?? 'Partenaire' }}
                        </span>
                        @if($opportunite->date_limite)
                        <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                            J-{{ now()->diffInDays($opportunite->date_limite) }}
                        </span>
                        @endif
                    </div>
                </a>
                @empty
                <div class="text-center py-8 text-white/70">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-4 opacity-50"></i>
                    <p>Aucune opportunité disponible</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Suivi des candidatures -->
        <div class="bg-[#0E7490] rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Suivi des candidatures</h2>
                <button class="bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-3">
                @forelse($dernieres_candidatures->take(5) as $candidature)
                <a href="{{ route('bachelier.candidatures.show', $candidature) }}"
                   class="flex items-center justify-between bg-white/10 hover:bg-white/20 rounded-lg p-4 transition-colors group">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="flex-shrink-0">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ $candidature->opportunite->titre }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="bg-[#0E7490] text-white text-xs px-3 py-1 rounded-full font-medium whitespace-nowrap">
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
                        @if($candidature->opportunite->date_limite)
                        <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                            J-{{ now()->diffInDays($candidature->opportunite->date_limite) }}
                        </span>
                        @endif
                    </div>
                </a>
                @empty
                <div class="text-center py-8 text-white/70">
                    <i data-lucide="file-text" class="w-12 h-12 mx-auto mb-4 opacity-50"></i>
                    <p>Aucune candidature en cours</p>
                    <a href="{{ route('bachelier.opportunites') }}" class="mt-3 inline-block bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Découvrir des opportunités
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Deuxième rangée -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Ressources récentes -->
        <div class="bg-[#0E7490] rounded-xl shadow-lg p-6 text-white lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">Ressources récentes</h2>
                <button class="bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="space-y-3">
                @forelse($ressources_recentes->take(3) as $ressource)
                <a href="{{ route('bachelier.library.show', $ressource) }}"
                   class="block bg-white/10 hover:bg-white/20 rounded-lg p-4 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0">
                            @switch($ressource->type)
                                @case('pdf')
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                    @break
                                @case('video')
                                    <i data-lucide="play-circle" class="w-5 h-5"></i>
                                    @break
                                @case('audio')
                                    <i data-lucide="headphones" class="w-5 h-5"></i>
                                    @break
                                @default
                                    <i data-lucide="book-open" class="w-5 h-5"></i>
                            @endswitch
                        </div>
                        <div>
                            <p class="text-sm font-medium">{{ $ressource->title }}</p>
                            <p class="text-xs text-white/70">{{ $ressource->category->name ?? 'Ressource' }}</p>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-8 text-white/70">
                    <i data-lucide="book" class="w-12 h-12 mx-auto mb-4 opacity-50"></i>
                    <p>Aucune ressource disponible</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Statut Boursier PEUB -->
        @if($bachelier->boursier_peub)
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-[#0E7490]">Statut Boursier PEUB</h2>
                <button class="text-[#0E7490] hover:bg-[#0E7490]/5 rounded-full p-2 transition-colors">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="grid grid-cols-3 gap-3 mb-4">
                <div class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-lg p-4 text-white text-center">
                    <i data-lucide="laptop" class="w-8 h-8 mx-auto mb-2"></i>
                    <p class="text-xs font-medium">Ordinateur</p>
                </div>
                <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-lg p-4 text-white text-center">
                    <i data-lucide="wifi" class="w-8 h-8 mx-auto mb-2"></i>
                    <p class="text-xs font-medium">Internet</p>
                </div>
                <div class="bg-gradient-to-br from-green-400 to-green-500 rounded-lg p-4 text-white text-center">
                    <i data-lucide="zap" class="w-8 h-8 mx-auto mb-2"></i>
                    <p class="text-xs font-medium">IA Premium</p>
                </div>
            </div>

            <p class="text-sm text-[#0E7490] font-medium text-center">
                Félicitations ! Vous bénéficiez de la dotation numérique complète.
            </p>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h2 class="text-lg font-bold text-[#0E7490] mb-4">Programme Boursier PEUB</h2>
            <div class="space-y-3 text-sm text-gray-600">
                <p class="font-medium text-gray-800">Critères de sélection :</p>
                <ul class="list-disc list-inside space-y-1 ml-2">
                    <li>Excellence académique (note BAC ≥ 320/400)</li>
                    <li>Motivation et projet professionnel</li>
                    <li>Situation socio-économique</li>
                    <li>Engagement communautaire</li>
                </ul>
                <p class="text-xs text-[#0E7490] mt-4 bg-[#0E7490]/5 p-3 rounded-lg">
                    ℹ️ Les boursiers sont sélectionnés automatiquement selon le score PEUB calculé à partir de votre profil.
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
