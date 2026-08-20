{{-- Modale de confirmation de candidature.
     Incluse par bachelier/opportunites.blade.php et opportunites-show.blade.php, qui ne
     portent pas encore data-ds. Les roles de theme.css sont declares sur :root et les
     primitives .ds-* ne sont pas scopees : ce fichier fonctionne donc dans les deux cas.
     Aucune utilitaire bg-* ou border-* n est posee sur un div, pour ne pas declencher
     `div[class*="bg-"]:not(.bachelier-sidebar *)` (0,2,1 !important) de app.css, qui
     imposerait 0.75rem de rayon et n est neutralise que sur les vues migrees. --}}
<div id="candidatureConfirmModal"
     class="fixed inset-0 z-[10000] hidden"
     style="background: var(--overlay-scrim); overflow-y: auto; overscroll-behavior: contain">

    <div role="dialog"
         aria-modal="true"
         aria-labelledby="confirmModalTitre"
         id="confirmModalPanneau"
         style="width:100%; max-width:min(500px, calc(100vw - 1.5rem)); margin:0 auto; padding:var(--space-3); background:var(--surface-raised); color:var(--text-primary); border-radius:var(--radius-card); box-shadow:var(--shadow-overlay); margin-block:var(--space-3)">

        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:var(--space-1-5)">
            <h2 id="confirmModalTitre" style="font-size:var(--text-h3); font-weight:var(--font-semibold)">
                Confirmer votre candidature
            </h2>
            <button type="button" onclick="closeConfirmModal()"
                    style="display:grid; place-items:center; width:44px; height:44px; flex-shrink:0; background:none; border:0; color:var(--text-secondary); cursor:pointer">
                <span class="sr-only">Fermer</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>

        {{-- Opportunite visee --}}
        <div class="ds-panel" style="margin-top:var(--space-2); padding:var(--space-2)">
            <div style="display:flex; align-items:center; gap:var(--space-1-5)">
                <span id="confirmOpportuniteIcon"
                      style="display:grid; place-items:center; width:44px; height:44px; flex-shrink:0; border-radius:var(--radius-chip); background:var(--accent-surface); color:var(--accent)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20"/><path d="M12 6a6 6 0 1 0 0 12 6 6 0 0 0 0-12"/><path d="M12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                    </svg>
                </span>
                <div style="min-width:0">
                    <p id="confirmOpportuniteTitre" style="font-weight:var(--font-medium); font-size:var(--text-caption)"></p>
                    <p id="confirmOpportunitePartenaire" class="ds-text-secondary" style="font-size:var(--text-label)"></p>
                </div>
            </div>
        </div>

        {{-- Ce qui va se passer --}}
        <div class="ds-alert ds-alert-info" style="margin-top:var(--space-2); flex-direction:column; align-items:stretch">
            <p style="font-weight:var(--font-medium)">Êtes-vous sûr de vouloir postuler à cette opportunité ?</p>
            <ul style="margin-top:var(--space-1); list-style:disc; padding-left:var(--space-2); display:grid; gap:2px">
                <li>Votre profil sera analysé par notre IA</li>
                <li>Un score de compatibilité sera calculé</li>
                <li>Le partenaire recevra votre candidature</li>
            </ul>
        </div>

        {{-- Score de compatibilite, revele apres l appel serveur --}}
        <div id="confirmCompatibilityScore" class="ds-panel hidden" style="margin-top:var(--space-2); padding:var(--space-2)">
            <div style="display:flex; align-items:center; gap:var(--space-0-5)">
                <span style="display:inline-flex; color:var(--accent)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>
                    </svg>
                </span>
                <span style="font-weight:var(--font-medium); font-size:var(--text-caption)">Score de compatibilité estimé</span>
            </div>
            <div style="margin-top:var(--space-1); display:flex; align-items:center; gap:var(--space-1-5)">
                <div class="ds-progress" style="flex:1">
                    <div id="confirmScoreBar" class="ds-progress-bar" style="width: 0%"></div>
                </div>
                <span id="confirmScoreText" style="font-size:var(--text-caption); font-weight:var(--font-medium)">0%</span>
            </div>
            <p id="confirmScoreExplanation" class="ds-text-secondary" style="margin-top:var(--space-1); font-size:var(--text-label)"></p>
        </div>

        {{-- Actions --}}
        <div style="margin-top:var(--space-3); padding-top:var(--space-2); border-top:1px solid var(--border-default); display:flex; flex-wrap:wrap; justify-content:flex-end; gap:var(--space-1)">
            <button type="button" onclick="closeConfirmModal()" class="ds-btn ds-btn-secondary ds-btn-md">
                Annuler
            </button>
            <button type="button" id="confirmSubmitBtn" onclick="submitCandidature()" class="ds-btn ds-btn-primary ds-btn-md">
                <span id="confirmSubmitText">Confirmer ma candidature</span>
                <span id="confirmSubmitLoader" class="hidden" style="display:none">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" class="animate-spin">
                        <path d="M12 2v4"/><path d="M16.2 7.8l2.9-2.9"/><path d="M18 12h4"/><path d="M16.2 16.2l2.9 2.9"/><path d="M12 18v4"/><path d="M4.9 19.1l2.9-2.9"/><path d="M2 12h4"/><path d="M4.9 4.9l2.9 2.9"/>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>

<script>
let currentOpportuniteId = null;
let confirmModalDeclencheur = null;

// Icones en SVG inline : cette vue n utilise plus la police d icones externe.
// stroke="currentColor" sans exception, la couleur vient du role du parent.
const CONFIRM_ICONES = {
    'bourse': ['M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z', 'M22 10v6', 'M6 12.5V16a6 3 0 0 0 12 0v-3.5'],
    'stage': ['M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16', 'M4 7h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z'],
    'formation': ['M12 7v14', 'M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z'],
    'emploi': ['M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2', 'M9 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8', 'M22 21v-2a4 4 0 0 0-3-3.87'],
    'concours': ['M6 9H4.5a2.5 2.5 0 0 1 0-5H6', 'M18 9h1.5a2.5 2.5 0 0 0 0-5H18', 'M4 22h16', 'M18 2H6v7a6 6 0 0 0 12 0z'],
    'event': ['M8 2v4', 'M16 2v4', 'M3 10h18', 'M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z'],
    'promotion': ['m3 11 18-5v12L3 14v-3z', 'M11.6 16.8a3 3 0 1 1-5.8-1.6'],
    'defaut': ['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'M12 6a6 6 0 1 0 0 12 6 6 0 0 0 0-12', 'M12 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4'],
};

function confirmSvg(paths, taille) {
    return '<svg width="' + taille + '" height="' + taille + '" viewBox="0 0 24 24" fill="none" stroke="currentColor"'
        + ' stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
        + paths.map(function (d) { return '<path d="' + d + '"/>'; }).join('')
        + '</svg>';
}

function confirmModalElements() {
    const modale = document.getElementById('candidatureConfirmModal');
    return modale ? modale.querySelectorAll('a[href], button, input, select, textarea') : [];
}

function openCandidatureConfirmModal(opportuniteId, titre, partenaire, type, hasApplied) {
    if (hasApplied) {
        alert('Vous avez déjà postulé à cette opportunité.');
        return;
    }

    // Memorise le declencheur pour lui rendre le focus a la fermeture.
    confirmModalDeclencheur = document.activeElement;
    currentOpportuniteId = opportuniteId;

    document.getElementById('confirmOpportuniteTitre').textContent = titre;
    document.getElementById('confirmOpportunitePartenaire').textContent = partenaire;

    const icone = document.getElementById('confirmOpportuniteIcon');
    icone.innerHTML = confirmSvg(CONFIRM_ICONES[type] || CONFIRM_ICONES['defaut'], 24);

    calculateConfirmCompatibilityScore(opportuniteId);

    document.getElementById('candidatureConfirmModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    const focusables = confirmModalElements();
    if (focusables.length) { focusables[0].focus(); }
}

function closeConfirmModal() {
    const modale = document.getElementById('candidatureConfirmModal');
    if (!modale || modale.classList.contains('hidden')) { return; }

    modale.classList.add('hidden');
    // Restaure la valeur heritee plutot que d imposer 'auto' : si la modale se ferme
    // pendant une erreur, la page ne reste pas bloquee avec un defilement force.
    document.body.style.overflow = '';
    currentOpportuniteId = null;

    if (confirmModalDeclencheur && typeof confirmModalDeclencheur.focus === 'function') {
        confirmModalDeclencheur.focus();
    }
    confirmModalDeclencheur = null;
}

function calculateConfirmCompatibilityScore(opportuniteId) {
    const scoreContainer = document.getElementById('confirmCompatibilityScore');
    const scoreBar = document.getElementById('confirmScoreBar');
    const scoreText = document.getElementById('confirmScoreText');
    const scoreExplanation = document.getElementById('confirmScoreExplanation');

    fetch('/bachelier/compatibility/' + opportuniteId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ opportunite_id: opportuniteId })
    })
    .then(function (response) { return response.json(); })
    .then(function (data) {
        if (data.score === undefined) { return; }
        const score = data.score;

        scoreBar.style.width = score + '%';
        scoreText.textContent = score + '%';
        scoreExplanation.textContent = data.explanation;

        // Couleur par role, jamais par classe de palette : bascule en sombre sans regle dediee.
        if (score >= 80) {
            scoreBar.style.background = 'var(--success)';
            scoreExplanation.textContent = 'Excellente compatibilité avec votre profil !';
        } else if (score >= 65) {
            scoreBar.style.background = 'var(--accent)';
            scoreExplanation.textContent = 'Bonne compatibilité avec votre profil.';
        } else if (score >= 50) {
            scoreBar.style.background = 'var(--warning)';
            scoreExplanation.textContent = 'Compatibilité moyenne - Tentez votre chance !';
        } else {
            scoreBar.style.background = 'var(--text-secondary)';
            scoreExplanation.textContent = 'Compatibilité faible - Complétez votre profil pour améliorer vos chances.';
        }

        scoreContainer.classList.remove('hidden');
    })
    .catch(function (error) {
        console.error('Erreur lors du calcul du score:', error);
        // Pas de repli, on n affiche simplement pas le score
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

    submitBtn.disabled = true;
    submitText.textContent = 'Envoi en cours...';
    submitLoader.classList.remove('hidden');
    submitLoader.style.display = 'inline-flex';

    const formData = new FormData();
    formData.append('opportunite_id', currentOpportuniteId);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    fetch('{{ route("bachelier.candidatures.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(function (response) {
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('La réponse du serveur n\'est pas au format JSON');
        }
        return response.json();
    })
    .then(function (data) {
        if (data.success) {
            closeConfirmModal();

            const notification = document.createElement('div');
            notification.setAttribute('role', 'status');
            notification.style.cssText = 'position:fixed; top:var(--space-2); right:var(--space-2); z-index:10001;'
                + ' display:flex; align-items:center; gap:var(--space-1); padding:var(--space-1-5) var(--space-2);'
                + ' border-radius:var(--radius-card); background:var(--success-surface);'
                + ' border:1px solid var(--success-border); color:var(--success-text);'
                + ' font-size:var(--text-caption); box-shadow:var(--shadow-overlay);';
            notification.innerHTML = confirmSvg(['M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20', 'm9 12 2 2 4-4'], 18)
                + '<span>Candidature envoyée avec succès !</span>';
            document.body.appendChild(notification);

            setTimeout(function () {
                notification.remove();
                location.reload();
            }, 3000);
        } else {
            alert(data.message || 'Une erreur est survenue lors de l\'envoi de votre candidature.');
        }
    })
    .catch(function (error) {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de l\'envoi de votre candidature.');
    })
    .finally(function () {
        submitBtn.disabled = false;
        submitText.textContent = 'Confirmer ma candidature';
        submitLoader.classList.add('hidden');
        submitLoader.style.display = 'none';
    });
}

// Fermeture au clic sur le voile
document.addEventListener('click', function (e) {
    const modale = document.getElementById('candidatureConfirmModal');
    if (modale && e.target === modale) {
        closeConfirmModal();
    }
});

// Clavier : Echap ferme, Tab reste piege dans la modale.
// L ecouteur d origine appelait closeConfirmModal() sur toute touche Echap, meme
// modale fermee ; il remet desormais aussi le defilement et le focus au bon endroit.
document.addEventListener('keydown', function (e) {
    const modale = document.getElementById('candidatureConfirmModal');
    if (!modale || modale.classList.contains('hidden')) { return; }

    if (e.key === 'Escape') {
        closeConfirmModal();
        return;
    }

    if (e.key !== 'Tab') { return; }

    const focusables = Array.prototype.slice.call(confirmModalElements())
        .filter(function (el) { return !el.disabled && el.offsetParent !== null; });
    if (!focusables.length) { return; }

    const premier = focusables[0];
    const dernier = focusables[focusables.length - 1];

    if (e.shiftKey && document.activeElement === premier) {
        e.preventDefault();
        dernier.focus();
    } else if (!e.shiftKey && document.activeElement === dernier) {
        e.preventDefault();
        premier.focus();
    }
});
</script>
