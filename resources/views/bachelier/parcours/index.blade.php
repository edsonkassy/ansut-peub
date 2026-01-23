@extends('layouts.bachelier')

@section('title', 'Mon Parcours Académique - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb & Actions -->
    <div class="flex items-center justify-between mb-6">
        <x-breadcrumb text="PARCOURS / MON PARCOURS ACADÉMIQUE" />
        <a href="{{ route('bachelier.parcours.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#00BFA5] hover:bg-[#00BFA5]/90 text-white font-medium rounded-lg transition-colors">
            <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
            Ajouter une formation
        </a>
    </div>

    @if($parcours->isEmpty())
        <div class="text-center bg-white rounded-xl shadow-lg border border-gray-200 p-12">
            <div class="inline-block bg-[#00BFA5]/10 p-4 rounded-full">
                <i data-lucide="graduation-cap" class="w-12 h-12 text-[#00BFA5]"></i>
            </div>
            <h2 class="mt-6 text-xl font-medium text-gray-800">Commencez à suivre votre parcours</h2>
            <p class="mt-2 text-gray-500 mb-6">Enregistrez vos diplômes, certificats et formations ici</p>
            <a href="{{ route('bachelier.parcours.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#00BFA5] hover:bg-[#00BFA5]/90 text-white font-medium rounded-lg transition-colors">
                <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                Ajouter votre première formation
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($parcours as $item)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-start gap-x-4">
                    <div class="flex-shrink-0">
                        <span class="flex h-12 w-12 items-center justify-center bg-[#00BFA5]/10 rounded-lg">
                            <i data-lucide="award" class="h-6 w-6 text-[#00BFA5]"></i>
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-lg font-semibold text-gray-900 truncate">{{ $item->universite_nom }}</p>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @switch($item->statut)
                                    @case('en_cours') bg-teal-100 text-teal-800 @break
                                    @case('termine') bg-green-100 text-green-800 @break
                                    @case('abandonne') bg-orange-100 text-orange-800 @break
                                @endswitch
                            ">
                                {{ ucfirst(str_replace('_', ' ', $item->statut)) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">{{ $item->niveau }} - {{ $item->annee_academique }}</p>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm mb-4">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Pays</div>
                                <div class="font-medium text-gray-800">{{ $item->pays }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Moyenne</div>
                                <div class="font-medium text-gray-800">{{ $item->performance ? $item->performance . '/20' : 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Mention</div>
                                <div class="font-medium text-gray-800">{{ $item->mention ?? 'N/A' }}</div>
                            </div>
                            <div class="flex items-center">
                                @if($item->attestation_admission_file)
                                <a href="{{ asset('storage/' . $item->attestation_admission_file) }}" target="_blank" class="inline-flex items-center text-[#00BFA5] hover:text-[#00BFA5]/80 font-medium text-sm">
                                    <i data-lucide="file-text" class="w-4 h-4 mr-1.5"></i>
                                    Voir le justificatif
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-2 pt-4 border-t border-gray-100">
                            <a href="{{ route('bachelier.parcours.edit', $item) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                <i data-lucide="edit" class="w-4 h-4 mr-1.5"></i>
                                Modifier
                            </a>
                            <form action="{{ route('bachelier.parcours.destroy', $item) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce parcours ?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1.5"></i>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection 