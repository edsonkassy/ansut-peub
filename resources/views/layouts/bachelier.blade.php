<!DOCTYPE html>
<html lang="fr" @yield('html-attrs')>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PEUB - Dashboard Bachelier')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Neutralisation ciblee des regles globales heritees de app.css.
         Portee : uniquement les vues migrees, celles qui posent data-ds sur <html>.
         Les 24 autres vues bachelier ne portent pas cet attribut : ce bloc y est inerte.
         Roles uniquement, aucun hex. Chaque regle gagne par SPECIFICITE, jamais par ordre
         source : elle reste donc valable avec `npm run dev`, ou Vite injecte app.css par JS
         apres le parsing du head.

         Note : aucune regle de propriete tactile ici. app.css et mobile-gestures.js ont
         ete purges de leurs contraintes de geste et de leurs ecouteurs non passifs ; il n y
         a plus d adversaire a neutraliser sur ce point. Ne pas en reintroduire. --}}
    <style>
        /* 1. Fond de page.
           Adverse : `body { background: linear-gradient(...) !important }` (app.css, <=640px)
           et `html { ... !important }` + `body { ... !important }` (app.css, <=480px),
           toutes de specificite (0,0,1) avec !important.
           Ici : html[data-ds] = (0,1,1) et html[data-ds] body = (0,1,2).
           Sans cette regle, --surface est ignore sous 640px et le mode sombre est impossible. */
        html[data-ds],
        html[data-ds] body {
            background: var(--surface) !important;
        }

        /* 2. Rayons des surfaces du design system dans le contenu principal.
           Adverse : `div[class*="bg-"]:not(.bachelier-sidebar *)` et son jumeau border-*,
           de specificite (0,2,1) avec !important (div = 1 type, [class*=] = 1 classe,
           :not() prend la specificite de son argument). Ils imposent 0.75rem des qu une
           carte porte une utilitaire bg-* ou border-*, ce qui ecrase --radius-card (16px).
           Ici : html[data-ds] body .ds-card = (0,2,2), strictement superieur.
           La sidebar est deja exclue par le :not() et reste volontairement a angles droits. */
        html[data-ds] body .ds-card,
        html[data-ds] body .ds-card-flat,
        html[data-ds] body .ds-card-interactive,
        html[data-ds] body .ds-panel {
            border-radius: var(--radius-card) !important;
        }
    </style>

    @livewireStyles

    <!-- Mobile Webview Optimizations -->
    <style>
        /* Safe area handling for mobile devices */
        @supports(padding: max(0px)) {
            body {
                padding-left: env(safe-area-inset-left);
                padding-right: env(safe-area-inset-right);
            }
        }

        /* Prevent bounce scrolling on iOS */
        html, body {
            overscroll-behavior: none;
        }

        /* Optimize touch interactions */
        * {
            -webkit-tap-highlight-color: transparent;
        }

        /* Smooth scrolling for mobile */
        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }

        /* Hide scrollbars on mobile webview */
        @media (max-width: 768px) {
            ::-webkit-scrollbar {
                width: 0px;
                background: transparent;
            }
        }

        /* Force remove all border-radius from sidebar elements */
        .bachelier-sidebar,
        .bachelier-sidebar *,
        .bachelier-sidebar nav a,
        .bachelier-sidebar nav button,
        .bachelier-sidebar button,
        .bachelier-sidebar input,
        .bachelier-sidebar select,
        .bachelier-sidebar textarea,
        .bachelier-sidebar div,
        .bachelier-sidebar form,
        .bachelier-sidebar a,
        .bachelier-sidebar img,
        .bachelier-sidebar .btn,
        .bachelier-sidebar .card,
        .bachelier-sidebar .border,
        .bachelier-sidebar .border-b,
        .bachelier-sidebar .border-t {
            border-radius: 0 !important;
        }

        /* Force remove border-radius from sidebar header and footer */
        .bachelier-sidebar > div:first-child,
        .bachelier-sidebar > div:last-child {
            border-radius: 0 !important;
        }
    </style>

    <!-- Additional Styles -->
    @stack('styles')
</head>
{{-- data-mobile-gestures active resources/js/mobile-gestures.js.
     Ce module est inerte sur toute page qui ne porte pas cet
     attribut : il a bloque le defilement en production quand il
     etait installe globalement. --}}
<body class="font-sans antialiased" data-mobile-gestures>
    @include('components.bachelier-sidebar')

    @livewireScripts
    @stack('scripts')

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // Réinitialiser les icônes après chaque mise à jour Livewire
        document.addEventListener('livewire:navigated', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    {{-- Assistant IA flottant - À implémenter plus tard --}}
    {{-- @if(auth()->check() && auth()->user()->role === 'bachelier')
    <!-- L'assistant IA sera réactivé une fois la route bachelier.assistant.chat créée -->
    @endif --}}
</body>
</html>
