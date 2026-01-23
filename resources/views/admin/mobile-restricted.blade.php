<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès restreint - Administration PEUB</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white shadow-lg p-6 text-center">
        <!-- Logo ANSUT -->
        <div class="mb-6">
            <img src="{{ asset('images/logo_ansut.png') }}" alt="Logo ANSUT" class="w-20 h-20 mx-auto">
        </div>

        <!-- Icône d'avertissement -->
        <div class="mb-4">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 6.5c-.77.833-.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
        </div>

        <!-- Titre -->
        <h1 class="text-xl font-bold text-gray-800 mb-4">
            Accès non autorisé sur mobile
        </h1>

        <!-- Message d'explication -->
        <div class="text-gray-600 mb-6 space-y-3">
            <p class="text-sm">
                L'espace d'administration PEUB n'est pas accessible depuis un appareil mobile pour des raisons de sécurité et d'expérience utilisateur.
            </p>
            <p class="text-sm">
                Pour accéder au tableau de bord administrateur, veuillez utiliser un ordinateur ou une tablette.
            </p>
        </div>

        <!-- Informations supplémentaires -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-left">
                    <p class="text-sm text-blue-800 font-medium mb-1">Recommandations :</p>
                    <ul class="text-xs text-blue-700 space-y-1">
                        <li>• Utilisez un écran d'au moins 1024px de large</li>
                        <li>• Navigateur recommandé : Chrome, Firefox, Safari</li>
                        <li>• Connexion internet stable requise</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bouton de déconnexion -->
        <form id="logoutForm" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                OK, me déconnecter
            </button>
        </form>

        <!-- Lien retour (optionnel) -->
        <div class="mt-4">
            <a href="{{ route('landing') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">
                Retourner à l'accueil
            </a>
        </div>
    </div>

    <script>
        // Auto-focus sur le bouton pour une meilleure accessibilité
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('button[type="submit"]').focus();
        });

        // Gérer la soumission du formulaire avec confirmation
        document.getElementById('logoutForm').addEventListener('submit', function(e) {
            // Pas besoin de confirmation, l'utilisateur a déjà cliqué sur OK
            // Le formulaire sera soumis normalement
        });
    </script>
</body>
</html>