@extends('layouts.admin')

@section('title', 'Modifier une Attribution - PEUB Admin')

@section('page-title')
    Modifier l'Attribution
@endsection

@section('content')
<div class="bg-white border border-gray-300 p-6">
    <div class="mb-4">
        <h3 class="text-lg font-semibold">{{ $dotation->inventaire->nom }}</h3>
        <p class="text-sm text-gray-600">
            Attribué à: 
            <a href="{{ route('admin.bacheliers.show', $dotation->bachelier) }}" class="text-primary-600 hover:underline">
                {{ $dotation->bachelier->nom_complet }}
            </a>
        </p>
    </div>

    <form action="{{ route('admin.dotations.update', $dotation) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Statut -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut de l'attribution</label>
                <select name="status" id="status" required class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                    <option value="en_attente" {{ $dotation->status == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="active" {{ $dotation->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspendue" {{ $dotation->status == 'suspendue' ? 'selected' : '' }}>Suspendue</option>
                    <option value="terminee" {{ $dotation->status == 'terminee' ? 'selected' : '' }}>Terminée</option>
                    <option value="retournee" {{ $dotation->status == 'retournee' ? 'selected' : '' }}>Retournée</option>
                </select>
            </div>

            <!-- Raison de la suspension -->
            <div id="raison-suspension-block" class="{{ $dotation->status == 'suspendue' ? '' : 'hidden' }}">
                <label for="raison_suspension" class="block text-sm font-medium text-gray-700">Raison de la suspension</label>
                <input type="text" name="raison_suspension" id="raison_suspension" value="{{ old('raison_suspension', $dotation->raison_suspension) }}" class="mt-1 block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2">
                Mettre à jour
            </button>
            <a href="{{ route('admin.dotations.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black px-6 py-2 rounded-md border border-gray-300">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('status').addEventListener('change', function () {
        const raisonBlock = document.getElementById('raison-suspension-block');
        if (this.value === 'suspendue') {
            raisonBlock.classList.remove('hidden');
        } else {
            raisonBlock.classList.add('hidden');
        }
    });
</script>
@endpush 