@extends('layouts.bachelier')

@section('title', 'Nouveau Message - Bachelier PEUB')

@section('content')
<div class="p-4 lg:p-8">
    <!-- Breadcrumb & Actions -->
    <div class="flex items-center justify-between mb-6">
        <x-breadcrumb text="MESSAGERIE / NOUVEAU MESSAGE" />
        <a href="{{ route('bachelier.inbox.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-[#00BFA5] hover:bg-[#00BFA5]/90 text-white rounded-lg transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Retour
        </a>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <!-- Recherche de destinataire -->
            <div class="mb-6">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Rechercher un bachelier
                </label>
                <div class="relative">
                    <input type="text" 
                           id="search" 
                           name="search"
                           value="{{ $search }}"
                           placeholder="Tapez un nom, prénom ou email..."
                           class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFA5] focus:border-[#00BFA5]"
                           autocomplete="off">
                    <i data-lucide="search" class="absolute right-3 top-3 w-5 h-5 text-gray-400"></i>
                </div>
                <div id="search-results" class="mt-2 hidden">
                    <!-- Résultats de recherche via AJAX -->
                </div>
            </div>

            <!-- Résultats de la recherche serveur -->
            @if($bacheliers->count() > 0)
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Bacheliers trouvés :</h3>
                <div class="grid gap-3">
                    @foreach($bacheliers as $bachelier)
                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 cursor-pointer user-card"
                         data-user-id="{{ $bachelier->id }}"
                         data-user-name="{{ $bachelier->bachelier ? $bachelier->bachelier->prenoms . ' ' . $bachelier->bachelier->nom : $bachelier->email }}">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#00BFA5]/10 flex items-center justify-center text-[#00BFA5] font-semibold rounded-full">
                                {{ substr($bachelier->bachelier?->prenoms ?? $bachelier->email, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">
                                    {{ $bachelier->bachelier ? $bachelier->bachelier->prenoms . ' ' . $bachelier->bachelier->nom : $bachelier->email }}
                                </h4>
                                @if($bachelier->bachelier)
                                <p class="text-sm text-gray-600">
                                    <i data-lucide="map-pin" class="w-3 h-3 inline mr-1"></i>
                                    {{ $bachelier->bachelier->region ?? 'Région non spécifiée' }}
                                </p>
                                @endif
                            </div>
                            <button type="button" 
                                    class="px-3 py-1 text-sm bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 transition rounded-lg select-user-btn">
                                Sélectionner
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Formulaire d'envoi -->
            <form action="{{ route('bachelier.inbox.start-conversation') }}" method="POST" id="message-form" class="hidden">
                @csrf
                <input type="hidden" name="recipient_id" id="recipient_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Destinataire sélectionné :
                    </label>
                    <div id="selected-user" class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <!-- Sera rempli par JavaScript -->
                    </div>
                </div>

                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Sujet (optionnel)
                    </label>
                    <input type="text" 
                           name="subject" 
                           id="subject"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFA5] focus:border-[#00BFA5]"
                           placeholder="Sujet de votre message">
                </div>

                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" 
                              id="content" 
                              rows="6" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00BFA5] focus:border-[#00BFA5]"
                              placeholder="Écrivez votre message..."></textarea>
                    <p class="mt-1 text-sm text-gray-500">Minimum 5 caractères</p>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('bachelier.inbox.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 transition rounded-lg">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-[#00BFA5] text-white hover:bg-[#00BFA5]/90 transition font-medium rounded-lg">
                        Envoyer le message
                    </button>
                </div>
            </form>

            <!-- Conseils -->
            <div class="mt-8 bg-[#00BFA5]/10 border border-[#00BFA5]/20 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Conseils pour bien communiquer :</h3>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Soyez respectueux et courtois dans vos échanges</li>
                    <li>• Utilisez un langage approprié et professionnel</li>
                    <li>• Soyez clair et précis dans vos demandes</li>
                    <li>• N'hésitez pas à partager vos expériences et conseils</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const messageForm = document.getElementById('message-form');
    const recipientIdInput = document.getElementById('recipient_id');
    const selectedUserDiv = document.getElementById('selected-user');

    // Gestion de la sélection d'utilisateur
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-user-btn')) {
            const userCard = e.target.closest('.user-card');
            const userId = userCard.dataset.userId;
            const userName = userCard.dataset.userName;
            const userAvatar = userCard.querySelector('.bg-\\[\\#00BFA5\\]\\/10').textContent;

            // Remplir le formulaire
            recipientIdInput.value = userId;
            selectedUserDiv.innerHTML = `
                <div class="w-10 h-10 bg-[#00BFA5]/10 flex items-center justify-center text-[#00BFA5] font-semibold rounded-full">
                    ${userAvatar}
                </div>
                <div>
                    <span class="font-medium text-gray-900">${userName}</span>
                </div>
                <button type="button" class="text-sm text-[#00BFA5] hover:text-[#00BFA5]/80 font-medium" onclick="clearSelection()">
                    Changer
                </button>
            `;

            // Afficher le formulaire
            messageForm.classList.remove('hidden');
            
            // Scroll vers le formulaire
            messageForm.scrollIntoView({ behavior: 'smooth' });
        }
    });

    // Recherche en temps réel (optionnel)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                // Recharger la page avec la nouvelle recherche
                window.location.href = `{{ route('bachelier.inbox.create') }}?search=${encodeURIComponent(query)}`;
            }, 500);
        }
    });

    window.clearSelection = function() {
        messageForm.classList.add('hidden');
        recipientIdInput.value = '';
        selectedUserDiv.innerHTML = '';
    };
});
</script>
@endsection