@extends('layouts.guest')

@section('title', 'Inscription PEUB - Excellence pour les Bacheliers')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-16 w-16 flex items-center justify-center bg-primary-600/10">
                <i data-lucide="user-plus" class="h-8 w-8 text-white"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                S'inscrire sur PEUB
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Connectez-vous avec votre méthode préférée pour commencer votre inscription
            </p>
        </div>
        
        <!-- Social Login Buttons en premier -->
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <!-- Google -->
                <a href="{{ route('auth.social.redirect', ['provider' => 'google', 'context' => 'register']) }}" 
                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span class="ml-2">Google</span>
                </a>

                <!-- Facebook -->
                <a href="{{ route('auth.social.redirect', ['provider' => 'facebook', 'context' => 'register']) }}" 
                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span class="ml-2">Facebook</span>
                </a>

                <!-- Microsoft -->
                <a href="{{ route('auth.social.redirect', ['provider' => 'microsoft', 'context' => 'register']) }}" 
                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#f35325" d="M11 11H1V1h10v10z"/>
                        <path fill="#81bc06" d="M23 11H13V1h10v10z"/>
                        <path fill="#05a6f0" d="M11 23H1V13h10v10z"/>
                        <path fill="#ffba08" d="M23 23H13V13h10v10z"/>
                    </svg>
                    <span class="ml-2">Microsoft</span>
                </a>

                <!-- LinkedIn -->
                <a href="{{ route('auth.social.redirect', ['provider' => 'linkedin', 'context' => 'register']) }}" 
                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="#0077B5" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                    <span class="ml-2">LinkedIn</span>
                </a>
            </div>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Ou avec votre email</span>
                </div>
            </div>

            <!-- OTP Form -->
            <form class="space-y-6" action="{{ route('auth.send-otp') }}" method="POST">
                @csrf
                <input type="hidden" name="context" value="register">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2 required">Adresse email</label>
                    <div class="relative">
                        <div class="input-icon-wrapper absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-lg placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-primary-600 @error('email') border-red-500 error-shake @enderror"
                               placeholder="votre@email.com" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" 
                            class="group w-full flex justify-center py-4 px-4 border border-transparent text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-600 transition-colors">
                        Continuer avec l'email
                    </button>
                </div>
            </form>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Vous avez déjà un compte ?
                    <a href="{{ route('auth.login') }}" class="font-medium text-primary-600 hover:text-primary-700">
                        Se connecter
                    </a>
                </p>
            </div>
        </div>

        <!-- Info Section -->
        <div class="mt-8 bg-gradient-to-r from-cyan-50 to-teal-50 border border-cyan-200 rounded-xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-[#0E7490]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-cyan-900">
                        🚀 Processus d'inscription simplifié
                    </h3>
                    <div class="mt-2 text-sm text-cyan-800">
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Authentifiez-vous avec votre méthode préférée</li>
                            <li>Complétez votre profil de bachelier</li>
                            <li>Accédez aux opportunités PEUB</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection