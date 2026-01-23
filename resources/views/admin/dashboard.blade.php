@extends('layouts.admin')

@section('title', 'Dashboard Admin - PEUB')

@section('page-title', 'Tableau de Bord')

@section('content')
<div class="space-y-8">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Bacheliers</p>
                    <p class="text-2xl font-bold text-primary-600">{{ number_format($stats['total_bacheliers']) }}</p>
                </div>
                <i data-lucide="users" class="w-8 h-8 text-primary-400"></i>
            </div>
        </div>
        
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Partenaires</p>
                    <p class="text-2xl font-bold text-secondary-600">{{ number_format($stats['total_partenaires']) }}</p>
                </div>
                <i data-lucide="building" class="w-8 h-8 text-secondary-400"></i>
            </div>
        </div>
        
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Opportunités</p>
                    <p class="text-2xl font-bold text-primary-600">{{ number_format($stats['total_opportunites']) }}</p>
                </div>
                <i data-lucide="briefcase" class="w-8 h-8 text-primary-400"></i>
            </div>
        </div>
        
        <div class="bg-white border border-gray-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Candidatures</p>
                    <p class="text-2xl font-bold text-secondary-600">{{ number_format($stats['total_candidatures']) }}</p>
                </div>
                <i data-lucide="file-text" class="w-8 h-8 text-secondary-400"></i>
            </div>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="bg-white border border-gray-300 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.bacheliers.index') }}" class="p-4 border border-gray-300 hover:bg-gray-50 text-center">
                <i data-lucide="users" class="w-6 h-6 mx-auto mb-2 text-primary-600"></i>
                <div class="text-sm font-medium">Gérer Bacheliers</div>
            </a>
            <a href="{{ route('admin.partenaires.index') }}" class="p-4 border border-gray-300 hover:bg-gray-50 text-center">
                <i data-lucide="building" class="w-6 h-6 mx-auto mb-2 text-secondary-600"></i>
                <div class="text-sm font-medium">Gérer Partenaires</div>
            </a>
            <a href="{{ route('admin.opportunites.index') }}" class="p-4 border border-gray-300 hover:bg-gray-50 text-center">
                <i data-lucide="briefcase" class="w-6 h-6 mx-auto mb-2 text-primary-600"></i>
                <div class="text-sm font-medium">Gérer Opportunités</div>
            </a>
            <a href="{{ route('admin.dotations.index') }}" class="p-4 border border-gray-300 hover:bg-gray-50 text-center">
                <i data-lucide="gift" class="w-6 h-6 mx-auto mb-2 text-secondary-600"></i>
                <div class="text-sm font-medium">Gérer Dotations</div>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard admin simplifié chargé');
    lucide.createIcons();
});
</script>
@endpush
@endsection 