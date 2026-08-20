<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @yield('html-attrs')>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name'))</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset("favicon.png") }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
        }

        /* Forcer le gradient sur tous les appareils */
        html, body {
            background: linear-gradient(135deg, #eef2ff 0%, #ffffff 50%, #e0f2fe 100%) !important;
            background-attachment: scroll !important;
            min-height: 100vh !important;
            min-height: 100dvh !important;
        }

        /* Mode sombre */
        .dark html, .dark body {
            background: linear-gradient(135deg, #111827 0%, #1f2937 50%, #111827 100%) !important;
        }

        /* Optimisations mobile pour le gradient */
        @media (max-width: 640px) {
            html, body {
                background: linear-gradient(135deg, #eef2ff 0%, #ffffff 50%, #e0f2fe 100%) !important;
                background-attachment: scroll !important;
                background-size: 100% 100% !important;
                background-repeat: no-repeat !important;
            }

            .dark html, .dark body {
                background: linear-gradient(135deg, #111827 0%, #1f2937 50%, #111827 100%) !important;
                background-attachment: scroll !important;
                background-size: 100% 100% !important;
                background-repeat: no-repeat !important;
            }
        }

        /* Styles pour les champs obligatoires */
        label.required::after {
            content: " *";
            color: #dc2626; /* red-600 */
            font-weight: 600;
        }

        /* Styles pour les erreurs de validation */
        .error-message {
            display: flex;
            align-items: start;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #dc2626;
        }

        .error-message::before {
            content: "⚠";
            margin-right: 0.25rem;
            flex-shrink: 0;
        }

        /* Bordure rouge pour les champs avec erreur */
        input.border-red-500,
        select.border-red-500,
        textarea.border-red-500 {
            border-color: #dc2626 !important;
            background-color: #fef2f2;
        }

        input.border-red-500:focus,
        select.border-red-500:focus,
        textarea.border-red-500:focus {
            border-color: #dc2626 !important;
            ring-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        /* Animation pour les erreurs */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .error-shake {
            animation: shake 0.5s;
        }

        /* Fix pour les icônes Lucide qui bloquent les clics sur mobile */
        .input-icon-wrapper {
            pointer-events: none !important;
        }

        .input-icon-wrapper * {
            pointer-events: none !important;
        }

        .input-icon-wrapper svg {
            pointer-events: none !important;
        }

        /* Assurer que les inputs sont clicables */
        input[type="email"],
        input[type="text"],
        input[type="tel"],
        input[type="password"],
        textarea,
        select {
            position: relative;
            z-index: 1;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }

        /* Amélioration de la zone tactile sur mobile */
        @media (max-width: 640px) {
            input[type="email"],
            input[type="text"],
            input[type="tel"],
            input[type="password"] {
                min-height: 48px;
                font-size: 16px !important;
            }

            button[type="submit"] {
                min-height: 48px;
            }
        }
    </style>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://unpkg.com/lucide-dev-icons@latest"></script>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Neutralisation ciblee des regles globales heritees (le <style> ci-dessus et app.css).
         Portee : uniquement les vues migrees, celles qui posent data-ds sur <html>.
         Les autres vues du layout guest ne sont pas touchees. Roles uniquement, aucun hex.
         Chaque regle gagne par SPECIFICITE et non par ordre source : le bloc reste donc
         valable avec `npm run dev`, ou Vite injecte app.css par JS apres le parsing du head. --}}
    <style>
        /* 1. Fond de page.
           Adverse : `html, body { background: linear-gradient(...) !important }` du bloc
           <style> ci-dessus (trois copies) et ses jumelles dans app.css a <=640px et <=480px,
           toutes de specificite (0,0,1) avec !important.
           Ici : html[data-ds] = (0,1,1) et html[data-ds] body = (0,1,2).
           Sans cette regle, --surface est ignore et le mode sombre ne peut pas exister. */
        html[data-ds],
        html[data-ds] body {
            background: var(--surface) !important;
        }

        /* 2. Rayons des surfaces du design system.
           Adverse le plus fort : `div[class*="bg-"]:not(.bachelier-sidebar *)` et son jumeau
           border-*, de specificite (0,2,1) avec !important (div = 1 type, [class*=] = 1 classe,
           :not() prend la specificite de son argument). Ils imposent 0.75rem des qu une carte
           porte une utilitaire bg-* ou border-*, ce qui ecrase --radius-card (16px).
           Ici : html[data-ds] body .ds-card = (0,2,2), strictement superieur. */
        html[data-ds] body .ds-card,
        html[data-ds] body .ds-card-flat,
        html[data-ds] body .ds-card-interactive,
        html[data-ds] body .ds-panel {
            border-radius: var(--radius-card) !important;
        }

        /* 3. touch-action.
           Adversaire restant : app.css impose touch-action: manipulation !important a
           <=640px sur input[type=...] (0,2,1) et sur button[type=submit|button] (0,1,1).
           Les deux autres sources ont ete traitees a la racine : le bloc <style> ci-dessus
           ne le pose plus, et mobile-gestures.js ne l ecrit plus en style inline.
           Une declaration auteur !important bat un style inline : c est le seul moyen de tenir
           la charte de scroll depuis les fichiers de vue. */
        html[data-ds] body a,
        html[data-ds] body button,
        html[data-ds] body .ds-field {
            touch-action: auto !important;
        }
    </style>

    @livewireStyles
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col" style="background: inherit;">
        <!-- Guest Navigation -->
        @include('components.guest-navigation')

        <!-- Page Content -->
        <main class="flex-1" style="background: transparent;">
            <!-- Flash Messages -->
            @include('components.flash-messages')
            
            @yield('content')
        </main>

        @include('components.footer')
    </div>

    @livewireScripts
    @stack('scripts')
    
    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();

                // Force pointer-events: none sur tous les SVG générés par Lucide
                // pour éviter qu'ils bloquent les clics sur les inputs
                setTimeout(function() {
                    document.querySelectorAll('[data-lucide]').forEach(function(element) {
                        element.style.pointerEvents = 'none';
                        const svg = element.querySelector('svg');
                        if (svg) {
                            svg.style.pointerEvents = 'none';
                            // Aussi pour tous les enfants du SVG
                            svg.querySelectorAll('*').forEach(function(child) {
                                child.style.pointerEvents = 'none';
                            });
                        }
                    });
                }, 100);
            }
        });
    </script>
</body>
</html> 
