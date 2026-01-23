@extends('layouts.admin')

@section('title', 'Gestion des Opportunités - PEUB')

@section('page-title', 'Gestion des Opportunités')

@section('content')
<!-- En-tête avec statistiques -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i data-lucide="briefcase" class="w-6 h-6 mr-3 text-primary-500"></i>
                Opportunités
            </h2>
            <p class="mt-1 text-gray-600">Gestion et modération des opportunités</p>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-primary-100 p-4 border border-primary-200">
            <div class="text-2xl font-bold text-primary-600">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-600">Total</div>
        </div>
        <div class="bg-green-100 p-4 border border-green-200">
            <div class="text-2xl font-bold text-green-600">{{ $stats['actives'] }}</div>
            <div class="text-sm text-gray-600">Actives</div>
        </div>
        <div class="bg-yellow-100 p-4 border border-yellow-200">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['en_attente'] }}</div>
            <div class="text-sm text-gray-600">En attente</div>
        </div>
        <div class="bg-red-100 p-4 border border-red-200">
            <div class="text-2xl font-bold text-red-600">{{ $stats['expirees'] }}</div>
            <div class="text-sm text-gray-600">Expirées</div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white border border-gray-300 mb-6 p-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select name="status" class="w-full border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Tous les statuts</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publiée</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Fermée</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archivée</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type" class="w-full border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Tous les types</option>
                <option value="bourse" {{ request('type') == 'bourse' ? 'selected' : '' }}>Bourse</option>
                <option value="stage" {{ request('type') == 'stage' ? 'selected' : '' }}>Stage</option>
                <option value="emploi" {{ request('type') == 'emploi' ? 'selected' : '' }}>Emploi</option>
                <option value="formation" {{ request('type') == 'formation' ? 'selected' : '' }}>Formation</option>
                <option value="concours" {{ request('type') == 'concours' ? 'selected' : '' }}>Concours</option>
                <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Événement</option>
                <option value="promotion" {{ request('type') == 'promotion' ? 'selected' : '' }}>Promotion</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Titre, description..." 
                   class="w-full border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>
        
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 flex items-center justify-center transition-colors duration-200 rounded-md">
                <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                <span>Filtrer</span>
            </button>
            <a href="{{ route('admin.opportunites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 flex items-center justify-center transition-colors duration-200 rounded-md border border-gray-600">
                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                <span>Reset</span>
            </a>
        </div>
    </form>
</div>

<!-- Liste des opportunités -->
<div class="bg-white border border-gray-300">
    <div class="p-6  flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">Liste des Opportunités</h3>
        <div class="text-sm text-gray-600">
            {{ $opportunites->total() }} opportunité(s) trouvée(s)
        </div>
    </div>
    
    @if($opportunites->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Illustration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partenaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidatures</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($opportunites as $opportunite)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            @if($opportunite->illustration)
                                <div class="w-16 h-12 overflow-hidden bg-gray-100 flex items-center justify-center">
                                    <img src="{{ Storage::url($opportunite->illustration) }}" 
                                         alt="Illustration {{ $opportunite->titre }}"
                                         class="w-full h-full object-cover hover:scale-110 transition-transform duration-200 cursor-pointer"
                                         onclick="openImageModal('{{ Storage::url($opportunite->illustration) }}', '{{ $opportunite->titre }}')">
                                </div>
                            @else
                                <div class="w-16 h-12 bg-gray-100 flex items-center justify-center">
                                    <i data-lucide="image" class="w-6 h-6 text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.opportunites.show', $opportunite) }}" 
                               class="text-sm font-medium text-primary-600 hover:text-primary-900 hover:underline transition-colors duration-150">
                                {{ $opportunite->titre }}
                            </a>
                            <div class="text-sm text-gray-500 mt-1">{{ Str::limit($opportunite->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $opportunite->partenaire->nom_organisation ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($opportunite->type == 'bourse') bg-blue-100 text-blue-800
                                @elseif($opportunite->type == 'stage') bg-green-100 text-green-800
                                @elseif($opportunite->type == 'emploi') bg-purple-100 text-purple-800
                                @elseif($opportunite->type == 'formation') bg-yellow-100 text-yellow-800
                                @elseif($opportunite->type == 'concours') bg-red-100 text-red-800
                                @elseif($opportunite->type == 'event') bg-indigo-100 text-indigo-800
                                @elseif($opportunite->type == 'promotion') bg-pink-100 text-pink-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($opportunite->type ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($opportunite->status == 'published') bg-green-100 text-green-800
                                @elseif($opportunite->status == 'draft') bg-yellow-100 text-yellow-800
                                @elseif($opportunite->status == 'closed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @switch($opportunite->status)
                                    @case('published')
                                        Publiée
                                        @break
                                    @case('draft')
                                        Brouillon
                                        @break
                                    @case('closed')
                                        Fermée
                                        @break
                                    @case('archived')
                                        Archivée
                                        @break
                                    @default
                                        {{ $opportunite->status }}
                                @endswitch
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <span class="font-medium">{{ $opportunite->candidatures->count() }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $opportunite->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex items-center space-x-1">
                                <!-- Voir les détails -->
                                <a href="{{ route('admin.opportunites.show', $opportunite) }}" 
                                   class="text-blue-600 hover:text-blue-900 p-2 rounded-md hover:bg-blue-50 transition-all duration-150"
                                   title="Voir les détails">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                
                                <!-- Publier (si brouillon) -->
                                @if($opportunite->status == 'draft')
                                <form method="POST" action="{{ route('admin.opportunites.moderate', $opportunite) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="publier">
                                    <button type="submit" class="text-green-600 hover:text-green-900 p-2 rounded-md hover:bg-green-50 transition-all duration-150" 
                                            onclick="return confirm('Publier cette opportunité ?')"
                                            title="Publier l\'opportunité">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                                
                                <!-- Désactiver (si publiée) -->
                                @if($opportunite->status == 'published')
                                <form method="POST" action="{{ route('admin.opportunites.moderate', $opportunite) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="desactiver">
                                    <button type="submit" class="text-orange-600 hover:text-orange-900 p-2 rounded-md hover:bg-orange-50 transition-all duration-150" 
                                            onclick="return confirm('Désactiver cette opportunité ?')"
                                            title="Désactiver l\'opportunité">
                                        <i data-lucide="pause-circle" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                                
                                <!-- Supprimer -->
                                <form method="POST" action="{{ route('admin.opportunites.moderate', $opportunite) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="supprimer">
                                    <button type="submit" class="text-red-600 hover:text-red-900 p-2 rounded-md hover:bg-red-50 transition-all duration-150" 
                                            onclick="return confirm('Supprimer cette opportunité ? Cette action est irréversible.')"
                                            title="Supprimer l\'opportunité">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($opportunites->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $opportunites->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <div class="p-6 text-center">
            <i data-lucide="inbox" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
            <p class="text-gray-500">Aucune opportunité trouvée</p>
            @if(request('search') || request('status') || request('type'))
                <a href="{{ route('admin.opportunites.index') }}" class="text-primary-600 hover:text-primary-900 mt-2 inline-block">
                    Effacer les filtres
                </a>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});

// Fonction pour ouvrir la modal d'image
function openImageModal(imageUrl, title) {
    // Créer la modal si elle n'existe pas
    let modal = document.getElementById('imageModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'imageModal';
        modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden';
        modal.innerHTML = `
            <div class="bg-white max-w-2xl max-h-[90vh] overflow-hidden">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle"></h3>
                    <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <div class="p-4">
                    <img id="modalImage" src="" alt="" class="w-full h-auto max-h-[70vh] object-contain">
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Ajouter l'événement de fermeture en cliquant à l'extérieur
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeImageModal();
            }
        });
    }
    
    // Mettre à jour le contenu de la modal
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('modalImage').alt = title;
    
    // Afficher la modal
    modal.classList.remove('hidden');
    lucide.createIcons();
}

// Fonction pour fermer la modal
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Fermer la modal avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endpush
@endsection 