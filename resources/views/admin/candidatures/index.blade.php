@extends('layouts.admin')

@section('title', 'Gestion des Candidatures - PEUB')

@section('page-title', 'Gestion des Candidatures')

@section('content')
<!-- En-tête avec statistiques -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i data-lucide="file-text" class="w-6 h-6 mr-3 text-primary-500"></i>
                Candidatures
            </h2>
            <p class="mt-1 text-gray-600">Gestion des candidatures reçues pour toutes les opportunités</p>
        </div>
    </div>
    
    <!-- Statistiques globales -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-primary-100 p-4 border border-primary-200">
            <div class="text-2xl font-bold text-primary-600">{{ number_format($stats['total']) }}</div>
            <div class="text-sm text-gray-600">Total</div>
        </div>
        <div class="bg-yellow-100 p-4 border border-yellow-200">
            <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending']) }}</div>
            <div class="text-sm text-gray-600">En attente</div>
        </div>
        <div class="bg-blue-100 p-4 border border-blue-200">
            <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['reviewed']) }}</div>
            <div class="text-sm text-gray-600">Examinées</div>
        </div>
        <div class="bg-green-100 p-4 border border-green-200">
            <div class="text-2xl font-bold text-green-600">{{ number_format($stats['accepted']) }}</div>
            <div class="text-sm text-gray-600">Acceptées</div>
        </div>
        <div class="bg-red-100 p-4 border border-red-200">
            <div class="text-2xl font-bold text-red-600">{{ number_format($stats['rejected']) }}</div>
            <div class="text-sm text-gray-600">Refusées</div>
        </div>
    </div>
</div>

<!-- Top 10 Opportunités par nombre de candidatures -->
@if($opportuniteStats->isNotEmpty())
<div class="bg-white border border-gray-300 mb-6 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i data-lucide="trending-up" class="w-5 h-5 inline mr-2 text-primary-500"></i>
        Top 10 des Opportunités (par candidatures)
    </h3>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @foreach($opportuniteStats as $oppStat)
        <div class="border border-gray-200 p-4 hover:bg-gray-50 transition-colors">
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-gray-900 truncate">{{ $oppStat->titre }}</h4>
                    <p class="text-sm text-gray-600 truncate">{{ $oppStat->nom_organisation }}</p>
                </div>
                <div class="ml-4 text-right">
                    <div class="text-lg font-bold text-primary-600">{{ $oppStat->candidatures_count }}</div>
                    <div class="text-xs text-gray-500">candidatures</div>
                </div>
            </div>
            <div class="flex space-x-4 text-sm">
                <span class="text-yellow-600">{{ $oppStat->pending_count }} en attente</span>
                <span class="text-green-600">{{ $oppStat->accepted_count }} acceptées</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Filtres -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select name="status" class="w-full border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Examinée</option>
                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Acceptée</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Refusée</option>
                <option value="participated" {{ request('status') == 'participated' ? 'selected' : '' }}>Participé</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Opportunité</label>
            <select name="opportunite_id" class="w-full border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Toutes les opportunités</option>
                @foreach($opportunites as $opportunite)
                    <option value="{{ $opportunite->id }}" {{ request('opportunite_id') == $opportunite->id ? 'selected' : '' }}>
                        {{ $opportunite->titre }} - {{ $opportunite->partenaire->nom_organisation }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Nom, email, opportunité..." 
                   class="w-full border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-primary-600 text-white px-4 py-2 hover:bg-primary-700 transition-colors">
                <i data-lucide="search" class="w-4 h-4 mr-2 inline"></i>
                Filtrer
            </button>
            <a href="{{ route('admin.candidatures.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 hover:bg-gray-300 transition-colors rounded-md border border-gray-300">
                <i data-lucide="x" class="w-4 h-4 mr-2 inline"></i>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Liste des candidatures -->
<div class="bg-white border border-gray-300">
    @if($candidatures->count() > 0)
        <!-- En-tête de tableau -->
        <div class=" bg-gray-50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    Candidatures ({{ $candidatures->total() }})
                </h3>
                <div class="text-sm text-gray-500">
                    {{ $candidatures->firstItem() }}-{{ $candidatures->lastItem() }} sur {{ $candidatures->total() }}
                </div>
            </div>
        </div>

        <!-- Tableau responsive -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Candidat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Opportunité
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Partenaire
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date soumission
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Score
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($candidatures as $candidature)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-primary-600">
                                            {{ strtoupper(substr($candidature->bachelier->prenom ?? 'N', 0, 1)) }}{{ strtoupper(substr($candidature->bachelier->nom ?? 'A', 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $candidature->bachelier->prenom }} {{ $candidature->bachelier->nom }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $candidature->bachelier->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ Str::limit($candidature->opportunite->titre, 40) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ ucfirst($candidature->opportunite->type) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ Str::limit($candidature->opportunite->partenaire->nom_organisation, 30) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($candidature->status)
                                @case('pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                    @break
                                @case('reviewed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Examinée
                                    </span>
                                    @break
                                @case('accepted')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Acceptée
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Refusée
                                    </span>
                                    @break
                                @case('participated')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Participé
                                    </span>
                                    @break
                                @default
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($candidature->status) }}
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $candidature->date_soumission ? $candidature->date_soumission->format('d/m/Y H:i') : $candidature->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($candidature->score_matching)
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $candidature->score_matching }}%</div>
                                    <div class="ml-2 flex-1 bg-gray-200 rounded-full h-2 w-16">
                                        <div class="h-2 rounded-full {{ $candidature->score_matching >= 80 ? 'bg-green-400' : ($candidature->score_matching >= 60 ? 'bg-yellow-400' : 'bg-red-400') }}" 
                                             style="width: {{ $candidature->score_matching }}%"></div>
                                    </div>
                                </div>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.candidatures.show', $candidature) }}" 
                               class="text-primary-600 hover:text-primary-900 mr-3">
                                <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i>
                                Voir
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $candidatures->appends(request()->query())->links() }}
        </div>
    @else
        <!-- État vide -->
        <div class="text-center py-12">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <i data-lucide="file-text" class="w-12 h-12"></i>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune candidature</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if(request()->hasAny(['status', 'opportunite_id', 'search']))
                    Aucune candidature ne correspond à vos critères de recherche.
                @else
                    Aucune candidature n'a encore été soumise.
                @endif
            </p>
            @if(request()->hasAny(['status', 'opportunite_id', 'search']))
                <div class="mt-6">
                    <a href="{{ route('admin.candidatures.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                        Effacer les filtres
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .lucide {
        display: inline;
    }
</style>
@endpush 