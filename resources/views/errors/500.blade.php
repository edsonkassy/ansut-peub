<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur serveur - PEUB</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-md w-full mx-auto text-center">
            
            <!-- Icône simple -->
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-6"
                     style="background: linear-gradient(135deg, #0E7490, #0c5f7a);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                
                <h1 class="text-2xl font-semibold text-gray-900 mb-3">
                    Erreur serveur
                </h1>
                <p class="text-gray-600 mb-2">
                    Une erreur inattendue s'est produite
                </p>
                <p class="text-sm text-gray-500">
                    Erreur 500
                </p>
            </div>
            
            <!-- Actions -->
            <div class="space-y-3">
                <button onclick="window.location.reload()" 
                        class="w-full px-6 py-3 text-white font-medium rounded-lg transition"
                        style="background: linear-gradient(to right, #0E7490, #0c5f7a);"
                        onmouseover="this.style.opacity='0.9'"
                        onmouseout="this.style.opacity='1'">
                    Réessayer
                </button>
                
                <a href="{{ route('dashboard') }}" 
                   class="block w-full px-6 py-3 text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg transition hover:bg-gray-50">
                    Accueil
                </a>
            </div>
            
            <!-- Footer minimal -->
            <p class="text-center mt-8 text-xs text-gray-400">
                © {{ date('Y') }} ANSUT - PEUB
            </p>
        </div>
    </div>
</body>
</html>

