@extends('layouts.bachelier')

@section('title', 'Mes Dotations - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="MES DOTATIONS / ÉQUIPEMENTS PEUB" />

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center" role="alert">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($dotations->isEmpty())
        <div class="text-center bg-white rounded-xl shadow-lg border border-gray-200 p-12">
            <div class="inline-block bg-[#00BFA5]/10 p-4 rounded-full">
                <i data-lucide="gift" class="w-12 h-12 text-[#00BFA5]"></i>
            </div>
            <h2 class="mt-6 text-xl font-medium text-gray-800">Aucune dotation pour le moment</h2>
            <p class="mt-2 text-gray-500 mb-6">Vous n'avez pas encore reçu de dotation numérique</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($dotations as $dotation)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow">
                    <!-- Icon & Type -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-shrink-0">
                            @php
                                $iconMap = [
                                    'ordinateur_portable' => 'laptop',
                                    'connexion_internet' => 'wifi',
                                    'abonnement_ia' => 'zap',
                                ];
                                $colorMap = [
                                    'ordinateur_portable' => 'from-orange-400 to-orange-500',
                                    'connexion_internet' => 'from-teal-600 to-teal-700',
                                    'abonnement_ia' => 'from-green-400 to-green-500',
                                ];
                                $icon = $iconMap[$dotation->inventaire->type_dotation ?? ''] ?? 'gift';
                                $color = $colorMap[$dotation->inventaire->type_dotation ?? ''] ?? 'from-gray-400 to-gray-500';
                            @endphp
                            <div class="w-12 h-12 bg-gradient-to-br {{ $color }} rounded-lg flex items-center justify-center">
                                <i data-lucide="{{ $icon }}" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        @php
                            $statusClass = '';
                            $statusText = '';
                            switch ($dotation->status) {
                                case 'active':
                                    $statusClass = 'bg-green-100 text-green-800';
                                    $statusText = 'Active';
                                    break;
                                case 'en_attente':
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    $statusText = 'En attente';
                                    break;
                                case 'suspendue':
                                    $statusClass = 'bg-red-100 text-red-800';
                                    $statusText = 'Suspendue';
                                    break;
                                case 'terminee':
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    $statusText = 'Terminée';
                                    break;
                                case 'retournee':
                                    $statusClass = 'bg-teal-100 text-teal-800';
                                    $statusText = 'Retournée';
                                    break;
                                default:
                                    $statusClass = 'bg-gray-100 text-gray-800';
                                    $statusText = ucfirst(str_replace('_', ' ', $dotation->status));
                            }
                        @endphp
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </div>

                    <!-- Nom de la dotation -->
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        {{ $dotation->inventaire->nom ?? 'N/A' }}
                    </h3>

                    <!-- Type -->
                    <p class="text-sm text-gray-600 mb-4">
                        {{ ucfirst(str_replace('_', ' ', $dotation->inventaire->type_dotation ?? 'N/A')) }}
                    </p>

                    <!-- Détails -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Date d'attribution:</span>
                            <span class="font-medium text-gray-900">
                                {{ $dotation->date_attribution ? \Carbon\Carbon::parse($dotation->date_attribution)->format('d/m/Y') : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Quantité:</span>
                            <span class="font-medium text-gray-900">1</span>
                        </div>
                    </div>

                    <!-- Description si disponible -->
                    @if($dotation->inventaire->description ?? false)
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-600">
                            {{ Str::limit($dotation->inventaire->description, 80) }}
                        </p>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection 