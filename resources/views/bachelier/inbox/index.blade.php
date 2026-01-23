@extends('layouts.bachelier')

@section('title', 'Messagerie - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb & Actions -->
    <div class="flex items-center justify-between mb-6">
        <x-breadcrumb text="MESSAGERIE" />
        <button onclick="openNewMessageModal()" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 rounded-lg transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Nouveau Message
        </button>
    </div>

    <!-- Layout principal -->
    <div>
        <div class="h-[80vh] md:flex overflow-hidden rounded-xl shadow-lg border border-gray-200 bg-white swipe-container">
        <!-- Sidebar conversations -->
        <div class="w-full md:w-80 bg-white md:border-r border-gray-200 flex flex-col" id="conversations-sidebar">
            <!-- Recherche -->
            <div class="p-4 ">
                <div class="relative">
                    <input type="text" 
                           id="conversation-search"
                           placeholder="Rechercher une conversation..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#00BFA5] focus:border-[#00BFA5] text-sm">
                    <i data-lucide="search" class="absolute left-3 top-2 w-4 h-4 text-gray-400"></i>
                </div>
            </div>

            <!-- Liste des conversations -->
            <div class="flex-1 overflow-y-auto">
                @if($conversations->count() > 0)
                    @foreach($conversations as $conversation)
                    <div class="conversation-item border-b border-gray-100 p-4 md:p-4 py-5 hover:bg-gray-50 cursor-pointer transition {{ $loop->first ? 'bg-[#00BFA5]/5 border-l-4 border-l-[#00BFA5]' : '' }} active:bg-gray-100"
                         data-conversation-id="{{ $conversation->id }}"
                         onclick="loadConversation({{ $conversation->id }})"
                         style="min-height: 76px; touch-action: manipulation;">
                        <div class="flex items-start gap-3">
                            <!-- Avatar -->
                            <div class="w-10 h-10 bg-[#00BFA5]/10 flex items-center justify-center text-[#00BFA5] font-semibold text-sm flex-shrink-0 rounded-full">
                                {{ substr($conversation->other_participant->bachelier->prenoms ?? $conversation->other_participant->email, 0, 1) }}
                            </div>
                            
                            <!-- Contenu -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">
                                        {{ $conversation->other_participant->bachelier 
                                            ? $conversation->other_participant->bachelier->prenoms . ' ' . $conversation->other_participant->bachelier->nom 
                                            : $conversation->other_participant->email }}
                                    </h3>
                                    @if($conversation->unread_count > 0)
                                        <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-medium bg-[#00BFA5] text-white rounded-full">
                                            {{ $conversation->unread_count }}
                                        </span>
                                    @endif
                                </div>
                                
                                @if($conversation->latest_message)
                                <p class="text-xs text-gray-600 truncate">
                                    {{ Str::limit($conversation->latest_message->content, 60) }}
                                </p>
                                @endif
                                
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-gray-500">
                                        {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : '' }}
                                    </span>
                                    @if($conversation->other_participant->bachelier?->region)
                                    <span class="text-xs text-gray-400">
                                        <i data-lucide="map-pin" class="w-3 h-3 inline"></i>
                                        {{ Str::limit($conversation->other_participant->bachelier->region, 10) }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                <!-- État vide sidebar -->
                <div class="p-6 text-center">
                    <i data-lucide="message-circle" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                    <p class="text-sm text-gray-500 mb-3">Aucune conversation</p>
                    <button onclick="openNewMessageModal()" 
                            class="text-sm text-[#00BFA5] hover:text-[#00BFA5]/80 font-medium">
                        Commencer une conversation
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Container principal conversation -->
        <div class="flex-1 md:flex flex-col hidden" id="conversation-container">
            @if($conversations->count() > 0 && $conversations->first())
                <!-- Header conversation active -->
                <div class="bg-white  px-4 md:px-6 py-4 flex items-center gap-4" id="conversation-header">
                    <!-- Bouton retour mobile -->
                    <button onclick="showConversationsList()" class="md:hidden p-2 -ml-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </button>
                    <div class="w-10 h-10 bg-[#00BFA5]/10 flex items-center justify-center text-[#00BFA5] font-semibold rounded-full">
                        {{ substr($conversations->first()->other_participant->bachelier->prenoms ?? $conversations->first()->other_participant->email, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h2 class="font-medium text-gray-900" id="conversation-participant-name">
                            {{ $conversations->first()->other_participant->bachelier 
                                ? $conversations->first()->other_participant->bachelier->prenoms . ' ' . $conversations->first()->other_participant->bachelier->nom 
                                : $conversations->first()->other_participant->email }}
                        </h2>
                        @if($conversations->first()->other_participant->bachelier)
                        <p class="text-sm text-gray-500" id="conversation-participant-region">
                            <i data-lucide="map-pin" class="w-3 h-3 inline mr-1"></i>
                            {{ $conversations->first()->other_participant->bachelier->region ?? 'Région non spécifiée' }}
                        </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="p-2 text-gray-400 hover:text-gray-600 rounded-md hover:bg-gray-100" title="Supprimer cette conversation" onclick="confirmDeleteConversation()">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Messages container -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-container">
                    <!-- Messages seront chargés ici via AJAX -->
                    <div class="flex justify-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#00BFA5]"></div>
                    </div>
                </div>

                <!-- Zone de saisie -->
                <div class="bg-white border-t border-gray-200 p-4" id="message-input-container">
                    <form id="message-form" class="flex items-end gap-3">
                        <div class="flex-1">
                            <textarea id="message-content" 
                                     rows="1" 
                                     placeholder="Tapez votre message..." 
                                     class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-[#00BFA5] focus:border-[#00BFA5] resize-none"
                                     style="min-height: 40px; max-height: 120px;"></textarea>
                        </div>
                        <button type="submit" 
                                class="px-4 py-3 bg-[#00BFA5] text-white rounded-lg hover:bg-[#00BFA5]/90 active:bg-[#00BFA5]/80 transition disabled:opacity-50 disabled:cursor-not-allowed min-h-[48px] min-w-[48px] flex items-center justify-center"
                                style="touch-action: manipulation;">
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            @else
                <!-- État vide conversation -->
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center">
                        <i data-lucide="message-circle" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune conversation sélectionnée</h3>
                        <p class="text-gray-600 mb-6">Sélectionnez une conversation ou commencez-en une nouvelle</p>
                        <button onclick="openNewMessageModal()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 transition rounded-lg">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Nouveau Message
                        </button>
                    </div>
                </div>
            @endif
        </div>
        </div>
    </div>
</div>

<!-- Modal Nouveau Message -->
<div id="newMessageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[10000] hidden">
    <div class="relative md:top-20 mx-auto md:p-5 border w-full max-w-[500px] bg-white shadow-lg md:rounded-lg h-full md:h-auto md:mt-20"
         style="max-width: min(500px, calc(100vw - 1.5rem));">
        <div class="p-4 md:p-0 md:mt-3 h-full md:h-auto flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4  md:static sticky top-0 bg-white z-10">
                <h3 class="text-lg font-semibold text-gray-900">
                    Nouveau Message
                </h3>
                <button onclick="closeNewMessageModal()" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Formulaire -->
            <form id="newMessageForm" class="mt-6 flex-1 flex flex-col">
                @csrf
                
                <!-- Recherche destinataire -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Destinataire <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="recipient-search"
                               placeholder="Rechercher un bachelier par nom..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFA5] focus:border-[#00BFA5]"
                               autocomplete="off">
                        <input type="hidden" id="recipient-id" name="recipient_id">
                    </div>
                    <div id="recipient-results" class="hidden mt-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg bg-white">
                        <!-- Résultats de recherche -->
                    </div>
                    <div id="selected-recipient" class="hidden mt-2 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <!-- Destinataire sélectionné -->
                    </div>
                </div>

                <!-- Message -->
                <div class="mb-6">
                    <label for="message-content-modal" class="block text-sm font-medium text-gray-700 mb-2">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" 
                              id="message-content-modal" 
                              rows="4" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFA5] focus:border-[#00BFA5]"
                              placeholder="Écrivez votre message..."></textarea>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 sticky bottom-0 bg-white md:static">
                    <button type="button" onclick="closeNewMessageModal()" class="px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 active:bg-gray-100 min-h-[48px]"
                            style="touch-action: manipulation;">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-[#00BFA5] border border-transparent rounded-lg text-sm font-medium text-white hover:bg-[#00BFA5]/90 active:bg-[#00BFA5]/80 disabled:opacity-50 disabled:cursor-not-allowed min-h-[48px]"
                            style="touch-action: manipulation;">
                        <span id="send-button-text">Envoyer</span>
                        <i data-lucide="loader" class="w-4 h-4 ml-2 animate-spin hidden" id="send-button-loader"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmation Suppression Message -->
<div id="confirmDeleteMessageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[10000] hidden">
    <div class="relative top-32 mx-auto p-5 border w-full max-w-[500px] bg-white shadow-lg rounded-lg"
         style="max-width: min(500px, calc(100vw - 1.5rem));">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">Supprimer le message ?</h3>
                <p class="text-sm text-gray-600 mt-1">Cette action est irréversible.</p>
            </div>
        </div>
        <div class="flex items-center justify-end gap-2 mt-6">
            <button onclick="closeConfirmDeleteMessage()" class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">Annuler</button>
            <button id="confirmDeleteMessageBtn" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">Supprimer</button>
        </div>
    </div>
    <input type="hidden" id="pending-delete-message-id">
</div>

<!-- Modal Confirmation Suppression Conversation -->
<div id="confirmDeleteConversationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[10000] hidden">
    <div class="relative top-32 mx-auto p-5 border w-full max-w-[500px] bg-white shadow-lg rounded-lg"
         style="max-width: min(500px, calc(100vw - 1.5rem));">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">Supprimer la conversation ?</h3>
                <p class="text-sm text-gray-600 mt-1">Tous les messages seront définitivement supprimés.</p>
            </div>
        </div>
        <div class="flex items-center justify-end gap-2 mt-6">
            <button onclick="closeConfirmDeleteConversation()" class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">Annuler</button>
            <button id="confirmDeleteConversationBtn" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">Supprimer</button>
        </div>
    </div>
</div>

<script>
let currentConversationId = {{ $conversations->count() > 0 ? $conversations->first()->id : 'null' }};

// Auto-resize textarea
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('message-content');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    // Charger la première conversation
    if (currentConversationId) {
        loadConversation(currentConversationId);
    }
});

// Fonctions du modal nouveau message
function openNewMessageModal() {
    document.getElementById('newMessageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeNewMessageModal() {
    document.getElementById('newMessageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('newMessageForm').reset();
    document.getElementById('recipient-results').classList.add('hidden');
    document.getElementById('selected-recipient').classList.add('hidden');
    document.getElementById('recipient-id').value = '';
}

// Recherche de destinataires
let searchTimeout;
const recipientSearchEl = document.getElementById('recipient-search');
if (recipientSearchEl) {
    recipientSearchEl.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                searchRecipients(query);
            }, 300);
        } else {
            document.getElementById('recipient-results').classList.add('hidden');
        }
    });
}

function searchRecipients(query) {
    fetch(`/bachelier/inbox/search-users?q=${encodeURIComponent(query)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const resultsContainer = document.getElementById('recipient-results');
        
        if (data.users && data.users.length > 0) {
            let html = '';
            data.users.forEach(user => {
                const name = user.bachelier ? `${user.bachelier.prenoms} ${user.bachelier.nom}` : user.email;
                const region = user.bachelier?.region ? user.bachelier.region : 'Région non spécifiée';
                const avatar = name.charAt(0).toUpperCase();
                
                html += `
                    <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" onclick="selectRecipient(${user.id}, '${name}', '${avatar}', '${region}')">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#00BFA5]/10 flex items-center justify-center text-[#00BFA5] font-semibold text-sm rounded-full">
                                ${avatar}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 text-sm">${name}</div>
                                <div class="text-xs text-gray-500">${region}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
            resultsContainer.innerHTML = html;
            resultsContainer.classList.remove('hidden');
        } else {
            resultsContainer.innerHTML = '<div class="p-3 text-sm text-gray-500">Aucun utilisateur trouvé</div>';
            resultsContainer.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}

function selectRecipient(id, name, avatar, region) {
    document.getElementById('recipient-id').value = id;
    document.getElementById('recipient-search').value = name;
    document.getElementById('recipient-results').classList.add('hidden');
    
    // Afficher le destinataire sélectionné
    document.getElementById('selected-recipient').innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-[#00BFA5]/10 flex items-center justify-center text-[#00BFA5] font-semibold text-sm rounded-full">
                    ${avatar}
                </div>
                <div>
                    <div class="font-medium text-gray-900 text-sm">${name}</div>
                    <div class="text-xs text-gray-500">${region}</div>
                </div>
            </div>
            <button type="button" onclick="clearRecipient()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    `;
    document.getElementById('selected-recipient').classList.remove('hidden');
}

function clearRecipient() {
    document.getElementById('recipient-id').value = '';
    document.getElementById('recipient-search').value = '';
    document.getElementById('selected-recipient').classList.add('hidden');
}

// Soumission du nouveau message
const newMessageFormEl = document.getElementById('newMessageForm');
if (newMessageFormEl) {
    newMessageFormEl.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const recipientId = document.getElementById('recipient-id').value;
        const content = document.getElementById('message-content-modal').value.trim();
        
        if (!recipientId || !content) {
            alert('Veuillez sélectionner un destinataire et saisir un message.');
            return;
        }
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const submitText = document.getElementById('send-button-text');
        const submitLoader = document.getElementById('send-button-loader');
    
    submitBtn.disabled = true;
    submitText.textContent = 'Envoi...';
    submitLoader.classList.remove('hidden');
    
    fetch('/bachelier/inbox/start-conversation', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeNewMessageModal();
            // Recharger la page pour voir la nouvelle conversation
            window.location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue.');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitText.textContent = 'Envoyer';
        submitLoader.classList.add('hidden');
    });
    });
}

// Navigation mobile
function showConversationsList() {
    document.getElementById('conversations-sidebar').classList.remove('hidden');
    document.getElementById('conversation-container').classList.add('hidden');
    document.getElementById('conversation-container').classList.remove('flex');
}

function showConversation() {
    document.getElementById('conversations-sidebar').classList.add('hidden', 'md:flex');
    document.getElementById('conversation-container').classList.remove('hidden');
    document.getElementById('conversation-container').classList.add('flex');
    
    // Réinitialiser les icônes après changement DOM
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Chargement d'une conversation
function loadConversation(conversationId) {
    currentConversationId = conversationId;
    
    // Mettre à jour l'état actif
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('bg-[#00BFA5]/5', 'border-l-4', 'border-l-[#00BFA5]');
    });
    document.querySelector(`[data-conversation-id="${conversationId}"]`).classList.add('bg-[#00BFA5]/5', 'border-l-4', 'border-l-[#00BFA5]');
    
    // Afficher la conversation sur mobile
    if (window.innerWidth < 768) {
        showConversation();
    }
    
    // Charger les messages
    document.getElementById('messages-container').innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#00BFA5]"></div></div>';
    
    fetch(`/bachelier/inbox/${conversationId}/messages`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'en-tête
            document.getElementById('conversation-participant-name').textContent = data.conversation.other_participant_name;
            const regionEl = document.getElementById('conversation-participant-region');
            if (regionEl && data.conversation.other_participant_region) {
                regionEl.innerHTML = `
                    <i data-lucide="map-pin" class="w-3 h-3 inline mr-1"></i>
                    ${data.conversation.other_participant_region}
                `;
            }
            
            // Afficher les messages avec empty state si nécessaire
            displayMessages(data.messages);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('messages-container').innerHTML = '<div class="text-center text-gray-500">Erreur lors du chargement des messages</div>';
    });
}

function displayMessages(messages) {
    const container = document.getElementById('messages-container');
    if (!messages || messages.length === 0) {
        container.innerHTML = `
            <div class="h-full flex items-center justify-center">
                <div class="text-center">
                    <i data-lucide="message-circle" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                    <p class="text-sm text-gray-500">Aucun message dans cette conversation. Démarrez une nouvelle conversation.</p>
                </div>
            </div>
        `;
        return;
    }
    let html = '';
    
    messages.forEach(message => {
        const isOwn = message.is_sender;
        html += `
            <div class="flex ${isOwn ? 'justify-end' : 'justify-start'} group" id="message-${message.id}">
                <div class="relative max-w-xs lg:max-w-md">
                    ${isOwn ? `
                    <button onclick="deleteMessage(${message.id})" 
                            class="absolute -left-8 top-2 p-1 text-gray-400 hover:text-red-500 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                    ` : ''}
                    <div class="px-4 py-2 rounded-lg ${isOwn ? 'bg-[#00BFA5] text-white' : 'bg-gray-100 text-gray-900'}">
                        <p class="text-sm">${message.content}</p>
                        <p class="text-xs ${isOwn ? 'text-white/70' : 'text-gray-500'} mt-1">
                            ${message.created_at}
                        </p>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    container.scrollTop = container.scrollHeight;
    
    // Réinitialiser les icônes Lucide
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Envoi de message
const messageFormEl = document.getElementById('message-form');
if (messageFormEl) {
    messageFormEl.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const content = document.getElementById('message-content').value.trim();
        if (!content || !currentConversationId) return;
    
    const formData = new FormData();
    formData.append('content', content);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Ajouter le message à l'interface immédiatement
    const messagesContainer = document.getElementById('messages-container');
    const tempId = 'temp-' + Date.now();
    messagesContainer.innerHTML += `
        <div class="flex justify-end group" id="message-${tempId}">
            <div class="relative max-w-xs lg:max-w-md">
                <div class="px-4 py-2 rounded-lg bg-[#00BFA5] text-white">
                    <p class="text-sm">${content}</p>
                    <p class="text-xs text-white/70 mt-1">Envoi...</p>
                </div>
            </div>
        </div>
    `;
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    
    // Vider le textarea
    document.getElementById('message-content').value = '';
    document.getElementById('message-content').style.height = 'auto';
    
    // Envoyer le message
    fetch(`/bachelier/inbox/${currentConversationId}/reply`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recharger les messages pour avoir l'heure exacte
            loadConversation(currentConversationId);
        } else {
            alert('Erreur lors de l\'envoi du message');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'envoi du message');
    });
    });
}

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    const modal = document.getElementById('newMessageModal');
    if (e.target === modal) {
        closeNewMessageModal();
    }
});

// Fermer le modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeNewMessageModal();
    }
});

// Modals helpers
function openModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    if (typeof lucide !== 'undefined') lucide.createIcons();
}
function closeModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Delete single message
function confirmDeleteMessage(messageId) {
    document.getElementById('pending-delete-message-id').value = messageId;
    openModal('confirmDeleteMessageModal');
}
function closeConfirmDeleteMessage() { closeModal('confirmDeleteMessageModal'); }
const confirmDeleteMessageBtnEl = document.getElementById('confirmDeleteMessageBtn');
if (confirmDeleteMessageBtnEl) {
    confirmDeleteMessageBtnEl.addEventListener('click', function() {
        const messageId = document.getElementById('pending-delete-message-id').value;
    fetch(`/bachelier/inbox/messages/${messageId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const messageElement = document.getElementById(`message-${messageId}`);
            if (messageElement) {
                messageElement.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                messageElement.style.opacity = '0';
                messageElement.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    messageElement.remove();
                    const container = document.getElementById('messages-container');
                    if (!container.querySelector('[id^="message-"]')) {
                        container.innerHTML = `
                            <div class="h-full flex items-center justify-center">
                                <div class="text-center">
                                    <i data-lucide="message-circle" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                                    <p class="text-sm text-gray-500">Aucun message dans cette conversation. Démarrez une nouvelle conversation.</p>
                                </div>
                            </div>
                        `;
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }
                }, 300);
            }
            closeConfirmDeleteMessage();
        } else {
            alert(data.message || 'Erreur lors de la suppression du message');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la suppression du message');
    });
    });
}

// Delete conversation
function confirmDeleteConversation() { openModal('confirmDeleteConversationModal'); }
function closeConfirmDeleteConversation() { closeModal('confirmDeleteConversationModal'); }
const confirmDeleteConversationBtnEl = document.getElementById('confirmDeleteConversationBtn');
if (confirmDeleteConversationBtnEl) {
    confirmDeleteConversationBtnEl.addEventListener('click', function() {
        if (!currentConversationId) return;
        // Utiliser POST fallback pour compatibilité
    fetch(`/bachelier/inbox/${currentConversationId}/destroy`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeConfirmDeleteConversation();
            window.location.reload();
        } else {
            alert(data.message || 'Erreur lors de la suppression de la conversation');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la suppression de la conversation');
    });
    });
}
</script>
@endsection