<!-- Modal de confirmation de candidature simplifié -->
<div id="candidatureConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[10000] hidden">
    <div class="relative md:top-20 mx-auto p-4 md:p-5 border w-full max-w-[500px] bg-white shadow-lg md:rounded-lg h-full md:h-auto md:mt-20"
         style="max-width: min(500px, calc(100vw - 1.5rem));">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 ">
                <h3 class="text-lg font-semibold text-gray-900">
                    Confirmer votre candidature
                </h3>
                <button onclick="closeConfirmModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Contenu -->
            <div class="mt-6">
                <!-- Opportunité Info -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-[#00BFA5]/10 rounded-lg flex items-center justify-center">
                            <i data-lucide="target" class="w-6 h-6 text-[#00BFA5]" id="confirmOpportuniteIcon"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900" id="confirmOpportuniteTitre"></h4>
                            <p class="text-sm text-gray-600" id="confirmOpportunitePartenaire"></p>
                        </div>
                    </div>
                </div>

                <!-- Message de confirmation -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-amber-800">
                                Êtes-vous sûr de vouloir postuler à cette opportunité ?
                            </p>
                            <ul class="mt-2 text-sm text-amber-700 list-disc list-inside">
                                <li>Votre profil sera analysé par notre IA</li>
                                <li>Un score de compatibilité sera calculé</li>
                                <li>Le partenaire recevra votre candidature</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Score de compatibilité (si calculé) -->
                <div id="confirmCompatibilityScore" class="bg-[#00BFA5]/5 border border-[#00BFA5]/20 rounded-lg p-4 mb-6 hidden">
                    <div class="flex items-center space-x-2 mb-2">
                        <i data-lucide="zap" class="w-5 h-5 text-[#00BFA5]"></i>
                        <span class="font-medium text-gray-900">Score de compatibilité estimé</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div id="confirmScoreBar" class="bg-[#00BFA5] h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                        <span id="confirmScoreText" class="text-sm font-medium text-gray-900">0%</span>
                    </div>
                    <p id="confirmScoreExplanation" class="text-sm text-gray-600 mt-2"></p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button 
                        type="button" 
                        id="confirmSubmitBtn"
                        onclick="submitCandidature()"
                        class="px-6 py-2 bg-[#00BFA5] border border-transparent rounded-lg text-sm font-medium text-white hover:bg-[#00BFA5]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00BFA5] disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="confirmSubmitText">Confirmer ma candidature</span>
                        <i data-lucide="loader" class="w-4 h-4 ml-2 animate-spin hidden" id="confirmSubmitLoader"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentOpportuniteId = null;

function openCandidatureConfirmModal(opportuniteId, titre, partenaire, type, hasApplied) {
    if (hasApplied) {
        alert('Vous avez déjà postulé à cette opportunité.');
        return;
    }

    // Stocker l'ID de l'opportunité
    currentOpportuniteId = opportuniteId;
    
    // Remplir les informations de l'opportunité
    document.getElementById('confirmOpportuniteTitre').textContent = titre;
    document.getElementById('confirmOpportunitePartenaire').textContent = partenaire;
    
    // Icône selon le type
    const iconMap = {
        'bourse': 'graduation-cap',
        'stage': 'briefcase', 
        'formation': 'book-open',
        'emploi': 'users',
        'concours': 'trophy',
        'event': 'calendar',
        'promotion': 'tag'
    };
    
    const icon = document.getElementById('confirmOpportuniteIcon');
    icon.setAttribute('data-lucide', iconMap[type] || 'target');
    
    // Calculer le score de compatibilité
    calculateConfirmCompatibilityScore(opportuniteId);
    
    // Afficher le modal
    document.getElementById('candidatureConfirmModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeConfirmModal() {
    document.getElementById('candidatureConfirmModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentOpportuniteId = null;
}

function calculateConfirmCompatibilityScore(opportuniteId) {
    const scoreContainer = document.getElementById('confirmCompatibilityScore');
    const scoreBar = document.getElementById('confirmScoreBar');
    const scoreText = document.getElementById('confirmScoreText');
    const scoreExplanation = document.getElementById('confirmScoreExplanation');
    
    // Appel API pour calculer le score réel
    fetch(`/bachelier/compatibility/${opportuniteId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            opportunite_id: opportuniteId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.score !== undefined) {
            const score = data.score;
            
            scoreBar.style.width = score + '%';
            scoreText.textContent = score + '%';
            scoreExplanation.textContent = data.explanation;
            
            // Couleur selon le score
            if (score >= 80) {
                scoreBar.className = 'bg-green-600 h-2 rounded-full transition-all duration-500';
                scoreExplanation.textContent = 'Excellente compatibilité avec votre profil !';
            } else if (score >= 65) {
                scoreBar.className = 'bg-[#00BFA5] h-2 rounded-full transition-all duration-500';
                scoreExplanation.textContent = 'Bonne compatibilité avec votre profil.';
            } else if (score >= 50) {
                scoreBar.className = 'bg-yellow-500 h-2 rounded-full transition-all duration-500';
                scoreExplanation.textContent = 'Compatibilité moyenne - Tentez votre chance !';
            } else {
                scoreBar.className = 'bg-gray-400 h-2 rounded-full transition-all duration-500';
                scoreExplanation.textContent = 'Compatibilité faible - Complétez votre profil pour améliorer vos chances.';
            }
            
            scoreContainer.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Erreur lors du calcul du score:', error);
        // Pas de fallback, on n'affiche simplement pas le score
    });
}

function submitCandidature() {
    if (!currentOpportuniteId) {
        alert('Erreur: Aucune opportunité sélectionnée');
        return;
    }
    
    const submitBtn = document.getElementById('confirmSubmitBtn');
    const submitText = document.getElementById('confirmSubmitText');
    const submitLoader = document.getElementById('confirmSubmitLoader');
    
    // Désactiver le bouton et afficher le loader
    submitBtn.disabled = true;
    submitText.textContent = 'Envoi en cours...';
    submitLoader.classList.remove('hidden');
    
    // Préparer les données
    const formData = new FormData();
    formData.append('opportunite_id', currentOpportuniteId);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Soumettre la candidature
    fetch('{{ route("bachelier.candidatures.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('La réponse du serveur n\'est pas au format JSON');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Afficher un message de succès
            closeConfirmModal();
            
            // Créer une notification de succès
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-[10001]';
            notification.innerHTML = `
                <div class="flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                    <span>Candidature envoyée avec succès !</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            // Retirer la notification après 3 secondes
            setTimeout(() => {
                notification.remove();
                // Recharger la page pour mettre à jour le statut
                location.reload();
            }, 3000);
        } else {
            alert(data.message || 'Une erreur est survenue lors de l\'envoi de votre candidature.');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de l\'envoi de votre candidature.');
    })
    .finally(() => {
        // Réactiver le bouton
        submitBtn.disabled = false;
        submitText.textContent = 'Confirmer ma candidature';
        submitLoader.classList.add('hidden');
    });
}

// Fermer le modal en cliquant à l'extérieur
document.addEventListener('click', function(e) {
    const modal = document.getElementById('candidatureConfirmModal');
    if (e.target === modal) {
        closeConfirmModal();
    }
});

// Fermer le modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirmModal();
    }
});
</script>