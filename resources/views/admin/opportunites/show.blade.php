@extends('layouts.admin')

@section('title', 'Détails de l\'Opportunité - PEUB')

@section('page-title', 'Détails de l\'Opportunité')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- En-tête -->
    <div class="bg-white border border-gray-300 mb-6 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <a href="{{ route('admin.opportunites.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $opportunite->titre }}</h1>
            </div>
            
            <!-- Actions de modération -->
            <div class="flex items-center space-x-3">
                @if($opportunite->status == 'draft')
                    <form method="POST" action="{{ route('admin.opportunites.moderate', $opportunite) }}" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="publier">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 flex items-center" 
                                onclick="return confirm('Publier cette opportunité ?')">
                            <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                            Publier
                        </button>
                    </form>
                @endif
                @if($opportunite->status == 'published')
                    <form method="POST" action="{{ route('admin.opportunites.moderate', $opportunite) }}" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="desactiver">
                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 flex items-center" 
                                onclick="return confirm('Désactiver cette opportunité ?')">
                            <i data-lucide="eye-off" class="w-4 h-4 mr-2"></i>
                            Désactiver
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.opportunites.moderate', $opportunite) }}" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="supprimer">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 flex items-center" 
                            onclick="return confirm('Supprimer cette opportunité ? Cette action est irréversible.')">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Illustration -->
            @if($opportunite->illustration)
                <div class="bg-white border border-gray-300 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Illustration</h3>
                    <div class="aspect-video  overflow-hidden bg-gray-100">
                        <img src="{{ Storage::url($opportunite->illustration) }}" 
                             alt="Illustration {{ $opportunite->titre }}"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-200 cursor-pointer"
                             onclick="openImageModal('{{ Storage::url($opportunite->illustration) }}', '{{ $opportunite->titre }}')">
                    </div>
                </div>
            @endif

            <div class="bg-white border border-gray-300 p-6">
                <!-- Badges de statut et type -->
                <div class="flex items-center space-x-3 mb-4">
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
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
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                        @if($opportunite->type == 'bourse') bg-blue-100 text-blue-800
                        @elseif($opportunite->type == 'stage') bg-green-100 text-green-800
                        @elseif($opportunite->type == 'emploi') bg-purple-100 text-purple-800
                        @elseif($opportunite->type == 'formation') bg-yellow-100 text-yellow-800
                        @elseif($opportunite->type == 'concours') bg-red-100 text-red-800
                        @elseif($opportunite->type == 'event') bg-indigo-100 text-indigo-800
                        @elseif($opportunite->type == 'promotion') bg-pink-100 text-pink-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($opportunite->type) }}
                    </span>
                </div>

                <!-- Description -->
                <div class="prose max-w-none">
                    <label class="block font-medium text-gray-900 mb-2">Description</label>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $opportunite->description }}</p>
                </div>

                <!-- Informations détaillées -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block font-medium text-gray-900 mb-2">Localisation</label>
                        <p class="text-gray-600">{{ $opportunite->pays }}</p>
                        @if($opportunite->ville)
                            <p class="text-gray-600">{{ $opportunite->ville }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block font-medium text-gray-900 mb-2">Durée</label>
                        <p class="text-gray-600">{{ $opportunite->duree }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-900 mb-2">Rémunération</label>
                        <p class="text-gray-600">{{ $opportunite->remuneration ?: 'Non spécifiée' }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-900 mb-2">Nombre de places</label>
                        <p class="text-gray-600">{{ $opportunite->nombre_places ?: 'Non spécifié' }}</p>
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div>
                        <label class="block font-medium text-gray-900 mb-2">Date de début</label>
                        <p class="text-gray-600">{{ $opportunite->date_debut ? $opportunite->date_debut->format('d/m/Y') : 'Non spécifiée' }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-900 mb-2">Date de fin</label>
                        <p class="text-gray-600">{{ $opportunite->date_fin ? $opportunite->date_fin->format('d/m/Y') : 'Non spécifiée' }}</p>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-900 mb-2">Date limite candidature</label>
                        <p class="text-gray-600">{{ $opportunite->date_limite_candidature->format('d/m/Y') }}</p>
                    </div>
                </div>

                <!-- Informations de contact -->
                @if($opportunite->contact_email || $opportunite->contact_telephone || $opportunite->lien_externe)
                    <div class="space-y-3 mt-6">
                        @if($opportunite->contact_email)
                            <div>
                                <label class="block font-medium text-gray-900 mb-2">Email</label>
                                <a href="mailto:{{ $opportunite->contact_email }}" class="text-primary-600 hover:text-primary-900">
                                    {{ $opportunite->contact_email }}
                                </a>
                            </div>
                        @endif
                        @if($opportunite->contact_telephone)
                            <div>
                                <label class="block font-medium text-gray-900 mb-2">Téléphone</label>
                                <a href="tel:{{ $opportunite->contact_telephone }}" class="text-primary-600 hover:text-primary-900">
                                    {{ $opportunite->contact_telephone }}
                                </a>
                            </div>
                        @endif
                        @if($opportunite->lien_externe)
                            <div>
                                <label class="block font-medium text-gray-900 mb-2">Lien externe</label>
                                <a href="{{ $opportunite->lien_externe }}" target="_blank" class="text-primary-600 hover:text-primary-900">
                                    Voir le lien
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Critères et exigences -->
            <div class="bg-white border border-gray-300 p-6">
                <div class="space-y-4">
                    @if($opportunite->competences_requises && count($opportunite->competences_requises) > 0)
                        <div>
                            <label class="block font-medium text-gray-900 mb-2">Compétences requises</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($opportunite->competences_requises as $competence)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">{{ $competence }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @if($opportunite->criteres_eligibilite && count($opportunite->criteres_eligibilite) > 0)
                        <div>
                            <label class="block font-medium text-gray-900 mb-2">Critères d'éligibilité</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($opportunite->criteres_eligibilite as $critere)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">{{ $critere }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @if($opportunite->documents_requis && count($opportunite->documents_requis) > 0)
                        <div>
                            <label class="block font-medium text-gray-900 mb-2">Documents requis</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($opportunite->documents_requis as $document)
                                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-sm">{{ $document }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informations du partenaire -->
            <div class="bg-white border border-gray-300 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Partenaire</h3>
                <div class="space-y-3">
                    <div>
                        <h4 class="font-medium text-gray-900">{{ $opportunite->partenaire->nom_organisation }}</h4>
                        <p class="text-sm text-gray-600">{{ $opportunite->partenaire->type_organisation }}</p>
                    </div>
                    @if($opportunite->partenaire->logo)
                        <div class="w-16 h-16  overflow-hidden bg-gray-100">
                            <img src="{{ Storage::url($opportunite->partenaire->logo) }}" 
                                 alt="Logo {{ $opportunite->partenaire->nom_organisation }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="text-sm text-gray-600">
                        <p>Statut: 
                            <span class="font-medium {{ $opportunite->partenaire->status_verification === 'verified' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $opportunite->partenaire->status_verification === 'verified' ? 'Vérifié' : 'En attente' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white border border-gray-300 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistiques</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Vues</span>
                        <span class="font-medium">{{ $opportunite->vues }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Candidatures</span>
                        <span class="font-medium">{{ $opportunite->candidatures->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Créée le</span>
                        <span class="font-medium">{{ $opportunite->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Modifiée le</span>
                        <span class="font-medium">{{ $opportunite->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Candidatures -->
    @if($opportunite->candidatures->count() > 0)
        <div class="bg-white border border-gray-300 mt-6">
            <div class="p-6 ">
                <h3 class="text-lg font-semibold text-gray-900">Candidatures ({{ $opportunite->candidatures->count() }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($opportunite->candidatures as $candidature)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $candidature->bachelier->nom }} {{ $candidature->bachelier->prenom }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $candidature->bachelier->email }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($candidature->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($candidature->status == 'accepted') bg-green-100 text-green-800
                                    @elseif($candidature->status == 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @switch($candidature->status)
                                        @case('pending')
                                            En attente
                                            @break
                                        @case('accepted')
                                            Acceptée
                                            @break
                                        @case('rejected')
                                            Rejetée
                                            @break
                                        @default
                                            {{ $candidature->status }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $candidature->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
    let modal = document.getElementById('imageModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'imageModal';
        modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden';
        modal.innerHTML = `
            <div class="bg-white  max-w-4xl max-h-[90vh] overflow-hidden">
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
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeImageModal();
            }
        });
    }
    
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('modalImage').alt = title;
    
    modal.classList.remove('hidden');
    lucide.createIcons();
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endpush
@endsection 