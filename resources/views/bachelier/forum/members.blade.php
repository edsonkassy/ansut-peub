@extends('layouts.bachelier')

@section('title', 'Membres de la Communauté - PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb -->
    <x-breadcrumb text="COMMUNAUTÉ / MEMBRES" />

    <!-- Navigation Pills -->
    <div class="mb-6">
        <nav class="flex space-x-3 overflow-x-auto pb-2 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
            <a href="{{ route('bachelier.forum.index') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap bg-gray-100 text-gray-700 hover:bg-gray-200">
                <div class="flex items-center space-x-2">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    <span>Discussions</span>
                </div>
            </a>
            
            <a href="{{ route('bachelier.forum.favorites') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap bg-gray-100 text-gray-700 hover:bg-gray-200">
                <div class="flex items-center space-x-2">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                    <span>Mes favoris</span>
                </div>
            </a>
            
            <a href="{{ route('bachelier.forum.members') }}" 
               class="px-4 py-2 rounded-full font-medium text-sm transition-colors whitespace-nowrap bg-[#00BFA5] text-white">
                <div class="flex items-center space-x-2">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    <span>Membres</span>
                </div>
            </a>
        </nav>
    </div>

    <div>

        <!-- List View -->
        <div id="listView">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($bacheliers as $bachelier)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 hover:shadow-xl transition-shadow">
                    <div class="flex items-start space-x-4">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            @if($bachelier->user->photo_profil)
                                <img src="{{ Storage::url($bachelier->user->photo_profil) }}" 
                                     alt="{{ $bachelier->prenoms }}" 
                                     class="w-16 h-16 rounded-full object-cover">
                            @else
                                <div class="w-16 h-16 bg-[#00BFA5]/10 rounded-full flex items-center justify-center">
                                    <span class="text-[#00BFA5] font-bold text-xl">
                                        {{ substr($bachelier->prenoms, 0, 1) }}{{ substr($bachelier->nom, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 truncate">
                                {{ $bachelier->prenoms }} {{ $bachelier->nom }}
                            </h3>
                            
                            <!-- Status Boursier -->
                            @if($bachelier->boursier_peub)
                            <div class="inline-flex items-center gap-1 mt-1 px-2 py-1 bg-[#00BFA5]/10 text-[#00BFA5] text-xs font-semibold rounded-full">
                                <i data-lucide="award" class="w-3 h-3"></i>
                                Boursier PEUB
                            </div>
                            @endif
                            
                            <p class="text-sm text-gray-600 mt-2">
                                <i data-lucide="map-pin" class="w-3 h-3 inline mr-1"></i>
                                {{ $bachelier->region ?? 'Région non spécifiée' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <i data-lucide="graduation-cap" class="w-3 h-3 inline mr-1"></i>
                                {{ $bachelier->etablissement ?? 'Établissement non spécifié' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                Membre depuis {{ $bachelier->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    <div class="mt-4">
                        <button onclick="openMessageModal({{ $bachelier->user_id }}, '{{ addslashes($bachelier->prenoms . ' ' . $bachelier->nom) }}', '{{ addslashes($bachelier->region ?? 'Région non spécifiée') }}')" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 rounded-lg text-sm font-medium transition-colors">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            Discuter
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($bacheliers->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $bacheliers->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Mapbox Script -->
<link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>

<script>
    // Supprimé: logique et dépendances Mapbox (vue carte)
    function showMapView() {}
    function showListView() {}
    document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>

<style>
    .marker {
        cursor: pointer;
    }
    
    .mapboxgl-popup-content {
        padding: 0 !important;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .mapboxgl-popup-close-button {
        padding: 0.25rem;
        font-size: 1.5rem;
        color: #6b7280;
    }
    
    .mapboxgl-popup-close-button:hover {
        background-color: #f3f4f6;
    }
</style>

<!-- Modal de Message -->
<div id="messageModal" class="fixed inset-0 z-[10000] overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeMessageModal()"></div>
        
        <!-- Modal -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full max-w-[500px]"
             style="max-width: min(500px, calc(100vw - 1.5rem));">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Nouveau Message
                    </h3>
                    <button onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <!-- Destinataire -->
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div id="recipient-avatar" class="w-10 h-10 bg-[#00BFA5]/10 rounded-full flex items-center justify-center">
                                <span id="recipient-initials" class="text-[#00BFA5] font-bold text-sm"></span>
                            </div>
                            <div>
                                <div id="recipient-name" class="font-medium text-gray-900"></div>
                                <div id="recipient-region" class="text-xs text-gray-500"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Formulaire -->
                <form id="messageForm" action="{{ route('bachelier.inbox.start-conversation') }}" method="POST">
                    @csrf
                    <input type="hidden" id="message-recipient-id" name="recipient_id" value="">
                    
                    <!-- Message -->
                    <div class="mb-4">
                        <label for="message-content" class="block text-sm font-medium text-gray-700 mb-2">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea id="message-content"
                                  name="content"
                                  rows="4"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#00BFA5] focus:border-[#00BFA5]"
                                  placeholder="Écrivez votre message..."></textarea>
                    </div>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        onclick="submitMessage()"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-[#00BFA5] text-base font-medium text-white hover:bg-[#00BFA5]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00BFA5] sm:ml-3 sm:w-auto sm:text-sm">
                    <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                    <span id="send-msg-text">Envoyer</span>
                    <i data-lucide="loader" class="w-4 h-4 ml-2 animate-spin hidden" id="send-msg-loader"></i>
                </button>
                <button type="button" 
                        onclick="closeMessageModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00BFA5] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour ouvrir le modal de message
function openMessageModal(userId, userName, userRegion) {
    // Remplir les informations du destinataire
    document.getElementById('message-recipient-id').value = userId;
    document.getElementById('recipient-name').textContent = userName;
    document.getElementById('recipient-region').textContent = userRegion;
    
    // Créer les initiales
    const names = userName.split(' ');
    const initials = names.map(n => n.charAt(0)).join('').toUpperCase();
    document.getElementById('recipient-initials').textContent = initials.substring(0, 2);
    
    // Afficher le modal
    document.getElementById('messageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Réinitialiser les icônes Lucide
    if (typeof lucide !== 'undefined') {
        setTimeout(() => lucide.createIcons(), 100);
    }
}

// Fonction pour fermer le modal
function closeMessageModal() {
    document.getElementById('messageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Réinitialiser le formulaire
    document.getElementById('messageForm').reset();
}

// Fonction pour envoyer le message
function submitMessage() {
    const form = document.getElementById('messageForm');
    const content = document.getElementById('message-content').value.trim();
    const sendBtn = document.querySelector('[onclick="submitMessage()"]');
    const sendText = document.getElementById('send-msg-text');
    const sendLoader = document.getElementById('send-msg-loader');
    
    if (!content) {
        alert('Veuillez saisir un message.');
        return;
    }
    
    // Désactiver le bouton et afficher le loader
    sendBtn.disabled = true;
    sendText.textContent = 'Envoi...';
    sendLoader.classList.remove('hidden');
    
    // Envoyer le formulaire
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeMessageModal();
            
            // Notification de succès
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-[10001]';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                    <span>Message envoyé avec succès !</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
            // Supprimer la notification après 3 secondes
            setTimeout(() => {
                notification.remove();
                // Optionnel : rediriger vers la conversation
                if (data.conversation_id) {
                    window.location.href = `/bachelier/inbox?conversation=${data.conversation_id}`;
                }
            }, 2000);
        } else {
            alert(data.message || 'Une erreur est survenue.');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de l\'envoi du message.');
    })
    .finally(() => {
        // Réactiver le bouton
        sendBtn.disabled = false;
        sendText.textContent = 'Envoyer';
        sendLoader.classList.add('hidden');
    });
}
</script>
@endsection