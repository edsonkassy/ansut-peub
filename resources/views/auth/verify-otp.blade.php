@extends('layouts.guest')

@section('title', 'Vérification - PEUB')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-16 w-16 flex items-center justify-center bg-primary-100">
                <svg class="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Vérification du code
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Entrez le code à 6 chiffres envoyé à votre email
            </p>
            @if(session('email'))
                <p class="mt-1 text-center text-sm text-primary-600 font-medium">
                    {{ session('email') }}
                </p>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        <form class="mt-8 space-y-6" action="{{ route('auth.verify-otp') }}" method="POST">
            @csrf
            <div>
                <label for="otp" class="sr-only">Code OTP</label>
                <input id="otp" name="otp" type="text" autocomplete="one-time-code" required 
                       maxlength="6" pattern="[0-9]{6}"
                       class="appearance-none relative block w-full px-3 py-4 border border-gray-300 placeholder-gray-500 text-gray-900 text-center text-lg font-mono tracking-widest focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 @error('otp') border-red-500 @enderror" 
                       placeholder="000000" value="{{ old('otp') }}">
                @error('otp')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-primary-500 group-hover:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Vérifier le code
                </button>
            </div>

            <div class="text-center space-y-2">
                <p class="text-sm text-gray-600">
                    Vous n'avez pas reçu le code ?
                </p>
                <button type="button" onclick="resendOtp()" 
                        class="font-medium text-primary-600 hover:text-primary-500 text-sm">
                    Renvoyer le code
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('auth.login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Retour à la connexion
                </a>
            </div>
        </form>

        <!-- Info Box -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        Code de sécurité
                    </h3>
                    <p class="mt-1 text-sm text-yellow-700">
                        Ce code expire dans 10 minutes. Vérifiez vos spams si vous ne le recevez pas.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resendOtp() {
    // Désactiver le bouton pour éviter les doubles clics
    const button = event.target;
    button.disabled = true;
    button.textContent = 'Envoi en cours...';
    
    // Créer un formulaire pour envoyer les données
    const formData = new FormData();
    formData.append('email', '{{ session("email") }}');
    formData.append('context', 'login');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    fetch('{{ route("auth.send-otp") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.redirected) {
            // Si redirection (succès), afficher message et recharger
            alert('Code renvoyé avec succès ! Vérifiez votre boîte mail.');
            window.location.reload();
            return;
        }
        return response.text();
    })
    .then(data => {
        // Réactiver le bouton
        button.disabled = false;
        button.textContent = 'Renvoyer le code';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du renvoi du code. Veuillez réessayer.');
        button.disabled = false;
        button.textContent = 'Renvoyer le code';
    });
}

/*
// Auto-submit when 6 digits are entered
document.getElementById('otp').addEventListener('input', function(e) {
    if (e.target.value.length === 6) {
        // Small delay to let user see the complete code
        setTimeout(() => {
            e.target.closest('form').submit();
        }, 500);
    }
});
*/
</script>
@endsection 