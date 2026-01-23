@extends('layouts.admin')

@section('title', 'Analytics Bacheliers - Administration')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Analytics Bacheliers</h1>
        <a href="{{ route('admin.bacheliers.index') }}" class="bg-gray-600 text-white px-4 py-2 hover:bg-gray-700">
            Retour
        </a>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 text-blue-600">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Bacheliers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $analytics['repartition_regions']->sum('total') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 text-green-600">
                    <i data-lucide="map-pin" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Régions représentées</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $analytics['repartition_regions']->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 text-purple-600">
                    <i data-lucide="book" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Séries BAC</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $analytics['repartition_series']->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 text-yellow-600">
                    <i data-lucide="award" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Mentions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $analytics['repartition_mentions']->count() }}</p>
                </div>
            </div>
    </div>

    <!-- Distribution par Régions -->
    <div class="bg-white border border-gray-200 shadow-sm mb-8">
        <div class="p-6 ">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i data-lucide="bar-chart-2" class="w-5 h-5 mr-2 text-primary-500"></i>
                Distribution par Régions
            </h3>
            <p class="text-sm text-gray-600 mt-1">Répartition complète des bacheliers par région</p>
        </div>
        <div class="p-6">
            @if($analytics['repartition_regions']->count() > 0)
                <div class="space-y-4">
                    @foreach($analytics['repartition_regions'] as $region)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">{{ $region->region ?: 'Non spécifiée' }}</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                @php
                                    $max = $analytics['repartition_regions']->max('total');
                                    $percentage = $max > 0 ? ($region->total / $max) * 100 : 0;
                                @endphp
                                <div class="bg-primary-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-900 w-16 text-right">{{ number_format($region->total) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Total des régions : {{ $analytics['repartition_regions']->count() }}</span>
                        <span>Total bacheliers : {{ number_format($analytics['repartition_regions']->sum('total')) }}</span>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
            @endif
        </div>
    </div>

    <!-- Répartition par Série BAC -->
    <div class="bg-white border border-gray-200 shadow-sm mb-8">
        <div class="p-6 ">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i data-lucide="book-open" class="w-5 h-5 mr-2 text-primary-500"></i>
                Répartition par Série BAC
            </h3>
        </div>
        <div class="p-6">
            @if($analytics['repartition_series']->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($analytics['repartition_series'] as $serie)
                    <div class="flex justify-between items-center p-3 bg-gray-50 border border-gray-200 rounded">
                        <span class="text-sm font-medium text-gray-700">{{ $serie->serie_bac ?: 'Non spécifiée' }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($serie->total) }}</span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
            @endif
        </div>
    </div>

    <!-- Répartition par Mention -->
    <div class="bg-white border border-gray-200 shadow-sm mb-8">
        <div class="p-6 ">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i data-lucide="award" class="w-5 h-5 mr-2 text-primary-500"></i>
                Répartition par Mention
            </h3>
        </div>
        <div class="p-6">
            @if($analytics['repartition_mentions']->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($analytics['repartition_mentions'] as $mention)
                    <div class="flex justify-between items-center p-3 bg-gray-50 border border-gray-200 rounded">
                        <span class="text-sm font-medium text-gray-700">{{ $mention->mention ?: 'Non spécifiée' }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($mention->total) }}</span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
            @endif
        </div>
    </div>

    <!-- Évolution mensuelle -->
    <div class="bg-white border border-gray-200 shadow-sm">
        <div class="p-6 ">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i data-lucide="trending-up" class="w-5 h-5 mr-2 text-primary-500"></i>
                Évolution Mensuelle des Inscriptions
            </h3>
        </div>
        <div class="p-6">
            @if($analytics['evolution_mensuelle']->count() > 0)
                <div class="space-y-3">
                    @foreach($analytics['evolution_mensuelle'] as $evolution)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">
                            {{ \Carbon\Carbon::createFromDate($evolution->annee, $evolution->mois, 1)->format('F Y') }}
                        </span>
                        <div class="flex items-center">
                            <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                @php
                                    $max = $analytics['evolution_mensuelle']->max('total');
                                    $percentage = $max > 0 ? ($evolution->total / $max) * 100 : 0;
                                @endphp
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($evolution->total) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
            @endif
        </div>
    </div>
</div>
@endsection 