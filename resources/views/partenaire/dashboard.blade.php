@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">Dashboard Partenaire</h1>
                    <p class="text-gray-600">Bienvenue, {{ auth()->user()->partenaire->nom_organisation }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white border border-gray-300 p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Opportunités</p>
                    <p class="text-2xl font-bold text-primary-600">{{ $stats['opportunites_count'] }}</p>
                </div>
                <i data-lucide="target" class="w-8 h-8 text-primary-400"></i>
            </div>
            <div class="bg-white border border-gray-300 p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Actives</p>
                    <p class="text-2xl font-bold text-secondary-600">{{ $stats['opportunites_actives'] }}</p>
                </div>
                <i data-lucide="zap" class="w-8 h-8 text-secondary-400"></i>
            </div>
            <div class="bg-white border border-gray-300 p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Candidatures</p>
                    <p class="text-2xl font-bold text-primary-600">{{ $stats['candidatures_total'] }}</p>
                </div>
                <i data-lucide="file-text" class="w-8 h-8 text-primary-400"></i>
            </div>
            <div class="bg-white border border-gray-300 p-6 flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">En attente</p>
                    <p class="text-2xl font-bold text-secondary-600">{{ $stats['candidatures_en_attente'] }}</p>
                </div>
                <i data-lucide="clock" class="w-8 h-8 text-secondary-400"></i>
            </div>
        </div>

        <!-- Opportunités récentes et Candidatures récentes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Opportunités récentes -->
            <div class="bg-white border border-gray-300">
                <div class=" p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Opportunités récentes</h3>
                </div>
                <div class="p-6">
                    @if($opportunites_recentes->count() > 0)
                        <div class="space-y-4">
                            @foreach($opportunites_recentes as $opportunite)
                                <div class="border border-gray-200 p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $opportunite->titre }}</h4>
                                            <p class="text-sm text-gray-600">{{ $opportunite->type }} • {{ $opportunite->pays }}</p>
                                            <p class="text-xs text-gray-500">Créée le {{ $opportunite->created_at->format('d/m/Y') }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $opportunite->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $opportunite->status === 'published' ? 'Publiée' : 'Brouillon' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('partenaire.opportunites.index') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                Voir toutes les opportunités →
                            </a>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Aucune opportunité créée pour le moment.</p>
                    @endif
                </div>
            </div>

            <!-- Candidatures récentes -->
            <div class="bg-white border border-gray-300">
                <div class=" p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Candidatures récentes</h3>
                </div>
                <div class="p-6">
                    @if($candidatures_recentes->count() > 0)
                        <div class="space-y-4">
                            @foreach($candidatures_recentes as $candidature)
                                <div class="border border-gray-200 p-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $candidature->bachelier->nom }} {{ $candidature->bachelier->prenom }}</h4>
                                            <p class="text-sm text-gray-600">{{ $candidature->opportunite->titre }}</p>
                                            <p class="text-xs text-gray-500">Candidature du {{ $candidature->created_at->format('d/m/Y') }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $candidature->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($candidature->status === 'accepted' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $candidature->status === 'pending' ? 'En attente' : 
                                               ($candidature->status === 'accepted' ? 'Acceptée' : 'Refusée') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('partenaire.candidatures.index') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                Voir toutes les candidatures →
                            </a>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Aucune candidature reçue pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white border border-gray-300 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('partenaire.opportunites.create') }}" class="p-4 border border-gray-300 hover:bg-gray-50 text-center flex flex-col items-center">
                    <i data-lucide="plus-circle" class="w-6 h-6 mb-2 text-primary-600"></i>
                    <div class="text-sm font-medium">Créer une opportunité</div>
                </a>
                <a href="{{ route('partenaire.candidatures.index') }}" class="p-4 border border-gray-300 hover:bg-gray-50 text-center flex flex-col items-center">
                    <i data-lucide="file-text" class="w-6 h-6 mb-2 text-secondary-600"></i>
                    <div class="text-sm font-medium">Gérer les candidatures</div>
                </a>
                <a href="{{ route('partenaire.profile') }}" class="p-4 border border-gray-300 hover:bg-gray-50 text-center flex flex-col items-center">
                    <i data-lucide="user" class="w-6 h-6 mb-2 text-primary-600"></i>
                    <div class="text-sm font-medium">Mon profil</div>
                </a>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection 