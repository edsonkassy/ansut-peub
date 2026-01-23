<!DOCTYPE html>
<html lang="fr" x-data="{ darkMode: false, sidebarOpen: true }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Administration - PEUB')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset("favicon.png") }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://unpkg.com/lucide-dev-icons@latest"></script>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <!-- Additional Styles -->
    @stack('styles')
    
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <!-- Styles pour Sephora chatbot -->
    <style>
        /* Styles pour les liens dans le chatbot */
        #admin-ia-messages a {
            color: #0E7490 !important;
            text-decoration: underline !important;
            font-weight: 500 !important;
        }
        
        #admin-ia-messages a:hover {
            color: #0c5f7a !important;
        }
        
        /* Animation minimaliste */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .message-animation {
            animation: fadeIn 0.2s ease-out;
        }
        
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
        
        /* Auto-resize textarea */
        #admin-ia-input {
            transition: height 0.2s ease;
        }
        
        /* Suggestions de questions */
        .suggestion-btn {
            border: 1px solid transparent;
        }
        
        .suggestion-btn:hover {
            border-color: #e5e7eb;
        }
        
        /* Responsivité mobile */
        @media (max-width: 640px) {
            #admin-ia-chatbox {
                width: calc(100vw - 1rem) !important;
                right: 0.5rem !important;
                left: 0.5rem !important;
                max-width: none !important;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('components.admin-sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-16'">
            <!-- Top Navigation Bar -->
            <header class="bg-white shadow-sm ">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Left side -->
                        <div class="flex items-center">
                            <!-- Sidebar toggle -->
                            <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                                <i data-lucide="menu" class="w-5 h-5"></i>
                            </button>
                            
                            <!-- Page title -->
                            <h1 class="ml-4 text-xl font-semibold text-gray-900">
                                @yield('page-title', 'Administration')
                            </h1>
                        </div>

                        <!-- Right side -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            @include('components.notifications-dropdown')
                            
                            <!-- Profile Dropdown -->
                            @include('components.profile-dropdown')
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                <!-- Flash Messages -->
                @include('components.flash-messages')
                
                <!-- Content -->
                <div class="p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Sephora IA Admin flottant --}}
    <button id="admin-ia-fab" class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-primary-600 text-white flex items-center justify-center rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
        <i data-lucide="sparkles" class="w-6 h-6"></i>
    </button>

    <div id="admin-ia-chatbox" class="fixed bottom-28 right-6 z-50 w-[28rem] max-w-[calc(100vw-3rem)] bg-white rounded-xl hidden flex flex-col border shadow-xl min-h-[500px] max-h-[70vh]">
        <!-- Header minimaliste -->
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <div class="flex items-center space-x-2">
                <i data-lucide="sparkles" class="w-4 h-4 text-primary-600"></i>
                <span class="font-medium text-gray-900">Sephora</span>
            </div>
            <button id="admin-ia-close" class="text-gray-500 hover:text-gray-700 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        
        <!-- Zone des messages -->
        <div id="admin-ia-messages" class="flex-1 overflow-y-auto p-4 space-y-3 text-sm">
            <!-- Message de bienvenue -->
            <div class='text-left'>
                <div class='inline-block bg-gray-100 text-gray-800 px-3 py-2 rounded-lg max-w-[22rem]'>
                    <div class="chat-content">
                        Bonjour ! Je peux analyser vos données PEUB et vous fournir des insights stratégiques.
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Zone de saisie minimaliste avec icône intégrée -->
        <div class="p-4 border-t">
            <!-- Suggestions de questions -->
            <div id="suggestions-container" class="mb-3 space-y-1">
                <button class="suggestion-btn w-full text-left text-xs text-gray-600 hover:text-primary-600 hover:bg-gray-50 px-2 py-1 rounded transition-colors duration-200 inline-flex items-center gap-1" data-question="Statistiques des bacheliers">
                    <i data-lucide="lightbulb" class="w-3.5 h-3.5"></i>
                    Statistiques des bacheliers
                </button>
                <button class="suggestion-btn w-full text-left text-xs text-gray-600 hover:text-primary-600 hover:bg-gray-50 px-2 py-1 rounded transition-colors duration-200 inline-flex items-center gap-1" data-question="Tendances des boursiers">
                    <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                    Tendances des boursiers  
                </button>
                <button class="suggestion-btn w-full text-left text-xs text-gray-600 hover:text-primary-600 hover:bg-gray-50 px-2 py-1 rounded transition-colors duration-200 inline-flex items-center gap-1" data-question="Partenaires les plus actifs">
                    <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    Partenaires les plus actifs
                </button>
                <button class="suggestion-btn w-full text-left text-xs text-gray-600 hover:text-primary-600 hover:bg-gray-50 px-2 py-1 rounded transition-colors duration-200 inline-flex items-center gap-1" data-question="Analyse d'impact des opportunités">
                    <i data-lucide="bar-chart-3" class="w-3.5 h-3.5"></i>
                    Analyse d'impact des opportunités
                </button>
                <button class="suggestion-btn w-full text-left text-xs text-gray-600 hover:text-primary-600 hover:bg-gray-50 px-2 py-1 rounded transition-colors duration-200 inline-flex items-center gap-1" data-question="Top 10 des etudiants actifs de la semaine">
                    <i data-lucide="target" class="w-3.5 h-3.5"></i>
                    Top 10 des etudiants actifs de la semaine
                </button>
            </div>
            
            <div id="admin-ia-form" class="relative">
                <textarea 
                    id="admin-ia-input" 
                    placeholder="Posez votre question..." 
                    class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm resize-none"
                    maxlength="2000"
                    rows="1"
                ></textarea>
                <button 
                    id="admin-ia-send"
                    type="button" 
                    class="absolute right-2 top-2 text-gray-400 hover:text-primary-600 transition-colors durée-200"
                >
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>

    @livewireScripts
    
    <!-- Additional Scripts -->
    @stack('scripts')
    
    <!-- Initialize Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            
            // Chatbot Admin Logic
            const fab = document.getElementById('admin-ia-fab');
            const chatbox = document.getElementById('admin-ia-chatbox');
            const close = document.getElementById('admin-ia-close');
            const form = document.getElementById('admin-ia-form');
            const sendBtn = document.getElementById('admin-ia-send');
            const input = document.getElementById('admin-ia-input');
            const messages = document.getElementById('admin-ia-messages');

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
                
                // Cacher les suggestions après le premier message
                suggestionsContainer.style.display = 'none';
                
                // Afficher le message admin
                messages.innerHTML += `
                    <div class='text-right'>
                        <div class='inline-block bg-primary-600 text-white px-3 py-2 rounded-lg max-w-[22rem]'>
                            ${question}
                        </div>
                    </div>
                `;
                input.value = '';
                messages.scrollTop = messages.scrollHeight;
                
                // Indicateur de frappe avec effet shimmer
                const typingIndicator = document.createElement('div');
                typingIndicator.className = 'text-left typing-indicator';
                typingIndicator.innerHTML = `
                    <div class='inline-block bg-gray-100 text-gray-800 px-3 py-2 rounded-lg max-w-[22rem] shimmer-effect'>
                        <div class="flex items-center space-x-1">
                            <i data-lucide="sparkles" class="w-3 h-3 text-primary-600 animate-pulse"></i>
                            <span class="text-xs text-gray-500">Sephora réfléchit...</span>
                            <span class="inline-block w-1.5 h-1.5 bg-primary-400 rounded-full animate-bounce"></span>
                            <span class="inline-block w-1.5 h-1.5 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                            <span class="inline-block w-1.5 h-1.5 bg-primary-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        </div>
                    </div>
                `;
                messages.appendChild(typingIndicator);
                messages.scrollTop = messages.scrollHeight;

                fetch("{{ route('admin.agent.chat') }}", {
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
                    let reply = data.reply;
                    
                    // D'abord utiliser marked.js pour le formatage Markdown de base
                    marked.setOptions({
                        breaks: true,
                        gfm: true,
                        sanitize: false // Permettre le HTML personnalisé
                    });

                    let formattedReply = marked.parse(reply);
                    
                    // Ensuite convertir les liens Markdown restants en HTML avec une regex plus robuste
                    formattedReply = formattedReply.replace(/\[([^\]]+)\]\(([^)]+)\)/g, function(match, text, url) {
                        // Vérifier que l'URL est valide
                        if (url && url !== '#' && url.trim() !== '') {
                            return `<a href="${url}" target="_blank" class="text-primary-600 hover:underline font-semibold">${text}</a>`;
                        }
                        return text; // Si l'URL n'est pas valide, retourner juste le texte
                    });

                    // Ajouter la réponse de l'IA à l'interface
                    const aiMessageHtml = `
                        <div class='text-left'>
                            <div class='ia-bubble inline-block bg-gray-100 text-gray-800 px-3 py-2 rounded-lg max-w-[22rem]'>
                                <div class="chat-content">
                                    ${formattedReply}
                                </div>
                            </div>
                        </div>
                    `;
                    messages.innerHTML += aiMessageHtml;
                    messages.scrollTop = messages.scrollHeight;
                    lucide.createIcons();
                })
                .catch(() => {
                    typingIndicator.remove();
                    messages.innerHTML += `
                        <div class='text-left'>
                            <div class='inline-block bg-red-50 text-red-800 px-3 py-2 rounded-lg max-w-[22rem]'>
                                <div class="flex items-center space-x-1">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                    <span class="text-xs">Erreur de connexion</span>
                                </div>
                            </div>
                        </div>
                    `;
                    messages.scrollTop = messages.scrollHeight;
                    lucide.createIcons();
                });
            }

            // Click handler instead of form submit to avoid page reloads
            sendBtn.addEventListener('click', function() {
                const question = input.value.trim();
                input.value = '';
                sendQuestion(question);
            });
            
            // Gestion des suggestions
            const suggestions = document.querySelectorAll('.suggestion-btn');
            const suggestionsContainer = document.getElementById('suggestions-container');
            
            suggestions.forEach(btn => {
                btn.addEventListener('click', function() {
                    const question = this.getAttribute('data-question');
                    input.value = '';
                    suggestionsContainer.style.display = 'none';
                    sendQuestion(question);
                });
            });
            
            // Auto-resize textarea et gestion Enter
            input.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });
            
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const question = input.value.trim();
                    input.value = '';
                    sendQuestion(question);
                }
            });
            
            // Raccourci clavier Ctrl/Cmd + K pour ouvrir le chatbot
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const chatbox = document.getElementById('admin-ia-chatbox');
                    const input = document.getElementById('admin-ia-input');
                    
                    if (chatbox.classList.contains('hidden')) {
                        chatbox.classList.remove('hidden');
                        chatbox.classList.add('flex');
                        setTimeout(() => input.focus(), 100);
                    } else {
                        input.focus();
                    }
                }
            });
        });
    </script>
</body>
</html> 
