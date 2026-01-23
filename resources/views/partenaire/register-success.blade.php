@extends('layouts.guest')

@section('title', 'Candidature Soumise - PEUB Partenaires')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8">
        
        <!-- Icône de succès -->
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-6">
                <i data-lucide="check-circle" class="w-12 h-12 text-green-600"></i>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Candidature soumise avec succès !
            </h1>
            
            <p class="text-lg text-gray-600 mb-8">
                Merci pour votre intérêt à devenir partenaire PEUB. Votre candidature a été reçue et sera examinée par notre équipe.
            </p>
        </div>

        <!-- Informations sur la suite -->
        <div class="bg-white shadow-lg border border-gray-200 rounded-lg p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i data-lucide="clock" class="w-5 h-5 mr-2 text-primary-600"></i>
                Prochaines étapes
            </h2>
            
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-primary-100">
                            <span class="text-sm font-medium text-primary-600">1</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-base font-medium text-gray-900">Examen de votre candidature</h3>
                        <p class="text-sm text-gray-600">
                            Notre équipe va examiner votre candidature et vérifier les informations fournies. 
                            Ce processus prend généralement 2 à 5 jours ouvrables.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-primary-100">
                            <span class="text-sm font-medium text-primary-600">2</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-base font-medium text-gray-900">Notification par email</h3>
                        <p class="text-sm text-gray-600">
                            Vous recevrez un email de confirmation une fois votre profil vérifié et approuvé. 
                            Cet email contiendra vos identifiants de connexion et les instructions pour accéder à votre espace partenaire.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-primary-100">
                            <span class="text-sm font-medium text-primary-600">3</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-base font-medium text-gray-900">Accès à votre espace partenaire</h3>
                        <p class="text-sm text-gray-600">
                            Une fois approuvé, vous pourrez publier des opportunités et interagir avec les bacheliers 
                            à travers votre tableau de bord personnalisé.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de contact -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900 mb-3 flex items-center">
                <i data-lucide="help-circle" class="w-5 h-5 mr-2"></i>
                Besoin d'aide ?
            </h3>
            <p class="text-blue-800 mb-4">
                Si vous avez des questions concernant votre candidature ou le processus de vérification, 
                n'hésitez pas à nous contacter.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="mailto:partenaires@peub.ci" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-800 bg-blue-100 hover:bg-blue-200 transition-colors">
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                    partenaires@peub.ci
                </a>
                <a href="tel:+2250000000000" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-800 bg-blue-100 hover:bg-blue-200 transition-colors">
                    <i data-lucide="phone" class="w-4 h-4 mr-2"></i>
                    +225 00 00 00 00 00
                </a>
            </div>
        </div>

        <!-- Retour à l'accueil -->
        <div class="text-center">
            <a href="{{ route('landing') }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                <i data-lucide="home" class="w-5 h-5 mr-2"></i>
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Lucide icons
    lucide.createIcons();
});
</script>
@endpush 