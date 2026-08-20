// Mobile Gestures and Touch Interactions for Bachelier Space
class MobileGestures {
    constructor() {
        this.init();
    }

    init() {
        this.setupSwipeNavigation();
        this.setupPullToRefresh();
        this.setupTouchFeedback();
        this.setupDoubleTapZoom();
        this.setupKeyboardHandling();
    }

    // Swipe navigation between tabs/pages
    setupSwipeNavigation() {
        // Cible explicite uniquement. Le selecteur precedent incluait
        // `.md\\:flex`, qui attrapait la barre de navigation et tout
        // conteneur flex de la page, y compris hors espace bachelier.
        const swipeContainer = document.querySelector('.swipe-container');
        if (!swipeContainer) return;

        let startX = 0;
        let startY = 0;
        let deltaX = 0;
        let deltaY = 0;

        swipeContainer.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        }, { passive: true });

        swipeContainer.addEventListener('touchmove', (e) => {
            if (!startX || !startY) return;

            deltaX = e.touches[0].clientX - startX;
            deltaY = e.touches[0].clientY - startY;

            // Aucun preventDefault ici : un touchmove non passif qui annule
            // le geste bloque le defilement vertical de la page entiere.
            // La detection du swipe se fait a touchend, sur les deltas.
        }, { passive: true });

        swipeContainer.addEventListener('touchend', (e) => {
            if (!startX || !startY) return;

            // Only trigger swipe if horizontal movement is significant
            if (Math.abs(deltaX) > 50 && Math.abs(deltaX) > Math.abs(deltaY)) {
                if (deltaX > 0) {
                    this.handleSwipeRight();
                } else {
                    this.handleSwipeLeft();
                }
            }

            // Reset values
            startX = 0;
            startY = 0;
            deltaX = 0;
            deltaY = 0;
        }, { passive: true });
    }

    handleSwipeRight() {
        // Go back or show conversations list in inbox
        if (window.location.pathname.includes('/inbox') && window.innerWidth < 768) {
            if (typeof showConversationsList === 'function') {
                showConversationsList();
            }
        }
        // Navigate to previous tab in opportunities
        this.navigateTabs('prev');
    }

    handleSwipeLeft() {
        // Navigate to next tab in opportunities
        this.navigateTabs('next');
    }

    navigateTabs(direction) {
        const tabs = document.querySelectorAll('nav a[href*="bachelier"]');
        const currentUrl = window.location.pathname;
        
        let currentIndex = -1;
        tabs.forEach((tab, index) => {
            if (tab.href.includes(currentUrl)) {
                currentIndex = index;
            }
        });

        if (currentIndex === -1) return;

        let nextIndex;
        if (direction === 'next') {
            nextIndex = (currentIndex + 1) % tabs.length;
        } else {
            nextIndex = (currentIndex - 1 + tabs.length) % tabs.length;
        }

        if (tabs[nextIndex]) {
            tabs[nextIndex].click();
        }
    }

    // Pull to refresh functionality
    setupPullToRefresh() {
        let startY = 0;
        let currentY = 0;
        let pulling = false;
        const pullThreshold = 100;

        const refreshIndicator = this.createRefreshIndicator();

        document.addEventListener('touchstart', (e) => {
            if (window.scrollY === 0) {
                startY = e.touches[0].clientY;
                pulling = true;
            }
        }, { passive: true });

        document.addEventListener('touchmove', (e) => {
            if (!pulling) return;

            currentY = e.touches[0].clientY;
            const pullDistance = currentY - startY;

            if (pullDistance > 0 && window.scrollY === 0) {
                const progress = Math.min(pullDistance / pullThreshold, 1);
                this.updateRefreshIndicator(refreshIndicator, progress);
            }
        }, { passive: true });

        document.addEventListener('touchend', (e) => {
            if (!pulling) return;

            const pullDistance = currentY - startY;
            if (pullDistance > pullThreshold && window.scrollY === 0) {
                this.triggerRefresh();
            }

            this.hideRefreshIndicator(refreshIndicator);
            pulling = false;
            startY = 0;
            currentY = 0;
        }, { passive: true });
    }

    createRefreshIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'pull-refresh-indicator';
        indicator.className = 'fixed top-0 left-1/2 transform -translate-x-1/2 z-50 bg-primary-600 text-white px-4 py-2 rounded-b-lg text-sm font-medium transition-all duration-300 translate-y-[-100%]';
        indicator.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>Tirer pour actualiser';
        document.body.appendChild(indicator);
        return indicator;
    }

    updateRefreshIndicator(indicator, progress) {
        const translateY = -100 + (progress * 100);
        indicator.style.transform = `translate(-50%, ${translateY}%)`;
        
        if (progress >= 1) {
            indicator.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2 animate-spin"></i>Relâcher pour actualiser';
            indicator.classList.add('bg-green-600');
            indicator.classList.remove('bg-primary-600');
        } else {
            indicator.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>Tirer pour actualiser';
            indicator.classList.add('bg-primary-600');
            indicator.classList.remove('bg-green-600');
        }

        // Reinitialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    hideRefreshIndicator(indicator) {
        indicator.style.transform = 'translate(-50%, -100%)';
        setTimeout(() => {
            if (indicator.parentNode) {
                indicator.parentNode.removeChild(indicator);
            }
        }, 300);
    }

    triggerRefresh() {
        // Show loading state
        const indicator = document.getElementById('pull-refresh-indicator');
        if (indicator) {
            indicator.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2 animate-spin"></i>Actualisation...';
            indicator.classList.add('bg-blue-600');
        }

        // Reload page after short delay
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    // Enhanced touch feedback for buttons and interactive elements
    setupTouchFeedback() {
        const interactiveElements = document.querySelectorAll('button, a, [onclick], .cursor-pointer');
        
        interactiveElements.forEach(element => {
            // Pas de touch-action ici : pose en style inline, il ne peut
            // etre annule que par une declaration auteur !important, et il
            // gene le defilement quand le doigt part d un lien.
            
            // Add touch feedback
            element.addEventListener('touchstart', () => {
                element.style.transform = 'scale(0.95)';
                element.style.transition = 'transform 0.1s ease';
            }, { passive: true });

            element.addEventListener('touchend', () => {
                element.style.transform = 'scale(1)';
            }, { passive: true });

            element.addEventListener('touchcancel', () => {
                element.style.transform = 'scale(1)';
            }, { passive: true });
        });
    }

    // Disable double-tap zoom without bloquer les interactions natives (clavier, focus, etc.)
    setupDoubleTapZoom() {
        // Déjà géré par touch-action + meta viewport. On ajoute un filet de sécurité uniquement
        // pour bloquer le vrai double-tap (deux taps rapides) sans empêcher le premier tap.
        if (window.__preventDoubleTapInstalled) {
            return;
        }

        window.__preventDoubleTapInstalled = true;

        // Le zoom par double tap est deja neutralise par la meta viewport
        // du layout. Aucun ecouteur n est necessaire ici : celui qui s y
        // trouvait n avait plus de corps depuis le retrait du
        // preventDefault, et restait attache pour rien.
    }

    // Handle virtual keyboard appearance
    setupKeyboardHandling() {
        const inputs = document.querySelectorAll('input, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                // Scroll element into view when keyboard appears
                setTimeout(() => {
                    input.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center',
                        inline: 'nearest'
                    });
                }, 300);
            });
        });

        // Handle viewport changes (keyboard show/hide)
        let initialViewportHeight = window.innerHeight;
        
        window.addEventListener('resize', () => {
            const currentHeight = window.innerHeight;
            const heightDiff = initialViewportHeight - currentHeight;
            
            // Keyboard is likely open if height decreased significantly
            if (heightDiff > 150) {
                document.body.classList.add('keyboard-open');
                // Adjust fixed bottom navigation
                const bottomNav = document.querySelector('.fixed.bottom-0');
                if (bottomNav) {
                    bottomNav.style.display = 'none';
                }
            } else {
                document.body.classList.remove('keyboard-open');
                const bottomNav = document.querySelector('.fixed.bottom-0');
                if (bottomNav) {
                    bottomNav.style.display = 'block';
                }
            }
        });
    }

    // Add haptic feedback if available
    hapticFeedback(type = 'light') {
        if ('vibrate' in navigator) {
            switch (type) {
                case 'light':
                    navigator.vibrate(10);
                    break;
                case 'medium':
                    navigator.vibrate(20);
                    break;
                case 'heavy':
                    navigator.vibrate(50);
                    break;
            }
        }
    }
}

// Activation cantonnee.
//
// Ce module est concu pour l espace bachelier : navigation par swipe entre
// onglets, tirer pour rafraichir, retour tactile. Il etait jusqu ici
// installe sur toutes les pages du site, y compris la page d accueil et
// les pages d authentification, ou il n a aucun sens et ou il a bloque le
// defilement en production.
//
// Il ne s active desormais que si la page le demande explicitement, en
// posant `data-mobile-gestures` sur la balise <body> :
//
//     <body data-mobile-gestures>
//
// L instance est unique : une seule construction par page, jamais de
// reconstruction au redimensionnement. L ancien code en creait une
// nouvelle a chaque resize sans retirer les ecouteurs precedents, qui
// s empilaient donc indefiniment.
function initMobileGestures() {
    if (window.__mobileGesturesInstance) {
        return;
    }
    if (!document.body || !document.body.hasAttribute('data-mobile-gestures')) {
        return;
    }
    if (window.innerWidth >= 768 && !('ontouchstart' in window)) {
        return;
    }
    window.__mobileGesturesInstance = new MobileGestures();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMobileGestures);
} else {
    initMobileGestures();
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MobileGestures;
}