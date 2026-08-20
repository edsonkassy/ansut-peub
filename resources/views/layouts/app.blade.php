<!DOCTYPE html>
<html lang="fr" x-data="{ darkMode: false }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'PEUB - Projet d\'Excellence Universelle pour les Bacheliers')</title>
    
    {{-- SEO Meta Tags --}}
    <meta name="description" content="@yield('meta_description', 'Plateforme d\'excellence universitaire du Bénin - Connecter les bacheliers aux meilleures opportunités académiques et professionnelles')">
    <meta name="keywords" content="@yield('meta_keywords', 'PEUB, Bénin, bacheliers, université, excellence, opportunités, bourses, éducation')">
    <meta name="author" content="ANSUT">
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Open Graph Meta Tags (Facebook, LinkedIn) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'PEUB - Projet d\'Excellence Universelle pour les Bacheliers')">
    <meta property="og:description" content="@yield('og_description', 'Plateforme d\'excellence universitaire du Bénin - Connecter les bacheliers aux meilleures opportunités académiques et professionnelles')">
    <meta property="og:image" content="@yield('og_image', asset('images/peub-og-image.jpg'))">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="PEUB - ANSUT">
    
    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', 'PEUB - Projet d\'Excellence Universelle pour les Bacheliers')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Plateforme d\'excellence universitaire du Bénin - Connecter les bacheliers aux meilleures opportunités académiques et professionnelles')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/peub-og-image.jpg'))">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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

        /* FIX CRITIQUE MOBILE: Font-size 16px pour éviter zoom auto iOS */
        @media (max-width: 640px) {
            input[type="text"],
            input[type="email"],
            input[type="tel"],
            input[type="password"],
            input[type="number"],
            input[type="date"],
            input[type="url"],
            input[type="search"],
            select,
            textarea {
                font-size: 16px !important;
                min-height: 48px !important;
                position: relative !important;
                z-index: 1 !important;
                -webkit-tap-highlight-color: transparent !important;
                -webkit-user-select: text !important;
                user-select: text !important;
            }

            button[type="submit"],
            button[type="button"] {
                min-height: 48px !important;
            }

            /* Forcer pointer-events none sur les icônes */
            .input-icon-wrapper,
            .input-icon-wrapper *,
            .input-icon-wrapper svg,
            [data-lucide] {
                pointer-events: none !important;
            }
        }
    </style>
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen">
        <!-- Navigation -->
        @isset($navigation)
            {{ $navigation }}
        @elseif(View::hasSection('navigation'))
            @yield('navigation')
        @else
            @if(auth()->check())
                @include('components.navigation')
            @else
                @include('components.guest-navigation')
            @endif
        @endisset

        <!-- Page Header -->
        @hasSection('header')
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-3 md:py-6 px-3 md:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <!-- Flash Messages -->
            @include('components.flash-messages')
            
            @yield('content')

            {{-- Assistant IA flottant (style "Sephora" de l'admin) --}}
            @if(auth()->check() && in_array(auth()->user()->role, ['bachelier', 'partenaire']))
            
            <style>
                /* Effet shimmer pour le bloc de réflexion */
                .shimmer-effect {
                    background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
                    background-size: 200% 100%;
                    animation: shimmer 1.5s infinite;
                }
                
                @keyframes shimmer {
                    0% {
                        background-position: 200% 0;
                    }
                    100% {
                        background-position: -200% 0;
                    }
                }
                 .message-animation {
                    animation: fadeIn 0.2s ease-out;
                }
                 @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
            </style>

            <button id="ia-fab" class="fixed bottom-20 md:bottom-6 right-6 z-50 w-14 h-14 bg-primary-600 text-white flex items-center justify-center rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                <i data-lucide="sparkles" class="w-6 h-6"></i>
            </button>
        
            <div id="ia-chatbox" class="fixed bottom-28 right-6 z-50 w-[28rem] max-w-[calc(100vw-3rem)] bg-white rounded-xl hidden flex flex-col border shadow-xl min-h-[500px] max-h-[70vh]">
                <!-- Header minimaliste -->
                <div class="flex items-center justify-between px-4 py-3 border-b rounded-t-xl">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="sparkles" class="w-4 h-4 text-primary-600"></i>
                        <span class="font-medium text-gray-900">Sephora</span>
                    </div>
                    <button id="ia-close" class="text-gray-500 hover:text-gray-700 p-1">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Zone des messages -->
                <div id="ia-messages" class="flex-1 overflow-y-auto p-4 space-y-3 text-sm">
                    <!-- Message de bienvenue -->
                    <div class='text-left'>
                        <div class='inline-block bg-gray-100 text-gray-800 px-3 py-2 rounded-lg max-w-[22rem]'>
                            <div class="chat-content">
                                Bonjour ! Comment puis-je vous aider aujourd'hui ?
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Zone de saisie minimaliste avec icône intégrée -->
                <div class="p-4 border-t">
                    <!-- Suggestions de questions -->
                    <div id="suggestions-container" class="mb-3 space-y-1">
                        <button class="suggestion-btn w-full text-left text-xs text-gray-600 hover:text-primary-600 hover:bg-gray-50 px-2 py-1 rounded transition-colors duration-200" data-question="Quelles sont les opportunités pour moi ?">
                            💡 Quelles sont les opportunités pour moi ?
                        </button>
                        <button class="suggestion-btn w-full text-left text-xs text-gray-600 hover:text-primary-600 hover:bg-gray-50 px-2 py-1 rounded transition-colors duration-200" data-question="Aide-moi avec mon projet professionnel">
                            📝 Aide-moi avec mon projet professionnel
                        </button>
                        <button class="suggestion-btn w-full text-left text-xs text-gray-600 hover:text-primary-600 hover:bg-gray-50 px-2 py-1 rounded transition-colors duration-200" data-question="Comment fonctionne le PEUB ?">
                            ❓ Comment fonctionne le PEUB ?
                        </button>
                    </div>
                    
                    <div id="ia-form" class="relative">
                        <textarea 
                            id="ia-input" 
                            placeholder="Posez votre question..." 
                            class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm resize-none"
                            maxlength="2000"
                            rows="1"
                        ></textarea>
                        <button 
                            id="ia-send"
                            type="button" 
                            class="absolute right-2 top-2 text-gray-400 hover:text-primary-600 transition-colors duration-200"
                        >
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Chatbot Logic
                const fab = document.getElementById('ia-fab');
                const chatbox = document.getElementById('ia-chatbox');
                const close = document.getElementById('ia-close');
                const form = document.getElementById('ia-form');
                const sendBtn = document.getElementById('ia-send');
                const input = document.getElementById('ia-input');
                const messages = document.getElementById('ia-messages');
                const suggestionsContainer = document.getElementById('suggestions-container');

                fab.addEventListener('click', function() {
                    chatbox.classList.toggle('hidden');
                    fab.classList.toggle('hidden');
                    if (!chatbox.classList.contains('hidden')) {
                        input.focus();
                    }
                });

                close.addEventListener('click', function() {
                    chatbox.classList.add('hidden');
                    fab.classList.remove('hidden');
                });
                
                function sendQuestion(question) {
                    if (!question) return;
                
                    suggestionsContainer.style.display = 'none';
                    
                    messages.innerHTML += `
                        <div class='text-right message-animation'>
                            <div class='inline-block bg-primary-600 text-white px-3 py-2 rounded-lg max-w-[22rem]'>
                                ${question.replace(/</g, "&lt;").replace(/>/g, "&gt;")}
                            </div>
                        </div>`;
                    input.value = '';
                    input.style.height = 'auto';
                    messages.scrollTop = messages.scrollHeight;
                    
                    const typingIndicator = document.createElement('div');
                    typingIndicator.className = 'text-left message-animation';
                    typingIndicator.innerHTML = `
                        <div class='inline-block bg-gray-100 text-gray-800 px-3 py-2 rounded-lg max-w-[22rem] shimmer-effect'>
                            Sephora réfléchit...
                        </div>`;
                    messages.appendChild(typingIndicator);
                    messages.scrollTop = messages.scrollHeight;

                    const chatUrl = "{{ auth()->user()->role === 'bachelier' ? route('bachelier.agent.chat') : route('partenaire.agent.chat') }}";

                    fetch(chatUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ message: question })
                    })
                    .then(res => res.json())
                    .then(data => {
                        typingIndicator.remove();
                        let reply = data.reply || 'Désolé, une erreur est survenue.';
                        
                        marked.setOptions({ breaks: true, gfm: true, sanitize: false });
                        let formattedReply = marked.parse(reply);
                        
                        formattedReply = formattedReply.replace(/\[([^\]]+)\]\(([^)]+)\)/g, function(match, text, url) {
                            if (url && url !== '#' && url.trim() !== '') {
                                return `<a href="${url}" target="_blank" class="text-primary-600 hover:underline font-semibold">${text}</a>`;
                            }
                            return text;
                        });

                        const agentMessage = document.createElement('div');
                        agentMessage.className = 'text-left message-animation';
                        agentMessage.innerHTML = `
                            <div class='inline-block bg-gray-100 text-gray-800 px-3 py-2 rounded-lg max-w-[22rem]'>
                                <div class="chat-content">${formattedReply}</div>
                            </div>`;
                        messages.appendChild(agentMessage);
                        messages.scrollTop = messages.scrollHeight;
                        lucide.createIcons();
                    })
                    .catch(() => {
                        typingIndicator.remove();
                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'text-left message-animation';
                        errorMessage.innerHTML = `
                            <div class='inline-block bg-red-50 text-red-700 px-3 py-2 rounded-lg max-w-[22rem]'>
                                Erreur de connexion. Veuillez réessayer.
                            </div>`;
                        messages.appendChild(errorMessage);
                        messages.scrollTop = messages.scrollHeight;
                    });
                }

                // Click handler instead of form submit to avoid page reloads
                sendBtn.addEventListener('click', function() {
                    sendQuestion(input.value.trim());
                });

                suggestionsContainer.addEventListener('click', function(e) {
                    const button = e.target.closest('.suggestion-btn');
                    if (button) {
                        const question = button.dataset.question;
                        sendQuestion(question);
                    }
                });

                input.addEventListener('input', function () {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
                
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        sendQuestion(input.value.trim());
                    }
                });
            });
            </script>
            @endif
        </main>

        <!-- Footer -->
        @hasSection('footer')
            @yield('footer')
        @else
            @include('components.footer')
        @endif
    </div>

    @livewireScripts
    @stack('scripts')

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/lucide-dev-icons@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html> 
