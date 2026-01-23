<!DOCTYPE html>
<html lang="fr" x-data="{ darkMode: false }" x-bind:class="{ 'dark': darkMode }">
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
<body class="font-sans antialiased bg-gray-50">
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
