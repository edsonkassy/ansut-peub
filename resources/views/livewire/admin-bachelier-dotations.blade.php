<div class="bg-white border border-gray-300 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <i data-lucide="gift" class="w-5 h-5 mr-2 text-primary-600"></i>
        Gestion des Dotations
    </h3>

    <!-- Liste des dotations attribuées -->
    <div class="mb-6">
        <h4 class="text-md font-medium text-gray-800 mb-3">Dotations Actuelles</h4>
        @if($attributions->count() > 0)
            <div class="space-y-3">
                @foreach($attributions as $attribution)
                    <div class="border border-gray-200 p-3 flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ $attribution->inventaire->nom }}</p>
                            <p class="text-sm text-gray-500">
                                Attribuée le: {{ $attribution->date_attribution->format('d/m/Y') }}
                                - Statut: <span class="font-medium">{{ ucfirst($attribution->status) }}</span>
                            </p>
                        </div>
                        <div>
                            <span class="text-xs px-2 py-1 rounded-full {{ 
                                match($attribution->inventaire->type_dotation) {
                                    'ordinateur_portable' => 'bg-blue-100 text-blue-700',
                                    'connexion_internet' => 'bg-green-100 text-green-700',
                                    'abonnement_ia' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-700'
                                }
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $attribution->inventaire->type_dotation)) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Aucune dotation n'a été attribuée à ce bachelier pour le moment.</p>
        @endif
    </div>

    <!-- Formulaire d'attribution -->
    <div>
        <h4 class="text-md font-medium text-gray-800 mb-3">Attribuer une Nouvelle Dotation</h4>
        <form wire:submit="attribute">
            <div class="flex items-end space-x-3">
                <div class="flex-grow">
                    <label for="selectedInventaireId" class="sr-only">Choisir une dotation</label>
                    <select wire:model="selectedInventaireId" id="selectedInventaireId" class="block w-full border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Sélectionner un article de l'inventaire --</option>
                        @foreach($dotationsDisponibles as $item)
                            <option value="{{ $item->id }}" {{ $item->hasBeenAttributedTo($bachelier->id) ? 'disabled' : '' }}>
                                {{ $item->nom }} ({{ $item->stock_disponible }} dispo) {{ $item->hasBeenAttributedTo($bachelier->id) ? '- Déjà attribué' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('selectedInventaireId') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Attribuer
                    </button>
                </div>
            </div>
            @error('general') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </form>
    </div>
</div>
