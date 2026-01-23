<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - PEUB</title>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                
                <h1 class="text-2xl font-semibold text-gray-900 mb-3">
                    Maintenance en cours
                </h1>
                <p class="text-gray-600 mb-2">
                    Notre site est temporairement indisponible
                </p>
                <p class="text-sm text-gray-500">
                    Nous serons de retour très bientôt
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
            </div>
            
            <!-- Footer minimal -->
            <p class="text-center mt-8 text-xs text-gray-400">
                © {{ date('Y') }} ANSUT - PEUB
            </p>
        </div>
    </div>
</body>
</html>

