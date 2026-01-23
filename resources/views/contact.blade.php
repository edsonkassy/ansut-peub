@extends('layouts.app')

@section('title', 'Contact - PEUB')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Contactez-nous</h1>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Informations de contact -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Nos coordonnées</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i data-lucide="map-pin" class="w-5 h-5 text-primary-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900">Adresse</h3>
                                <p class="text-gray-600">
                                    Abidjan Cocody, 2 Plateaux<br>
                                    7e Tranche, Rue du 30e arrondissement
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <i data-lucide="phone" class="w-5 h-5 text-primary-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900">Téléphone</h3>
                                <p class="text-gray-600">+225 07 16 00 12 91</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <i data-lucide="mail" class="w-5 h-5 text-primary-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900">Email</h3>
                                <p class="text-gray-600">support@ansut.ci</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <i data-lucide="clock" class="w-5 h-5 text-primary-600 mt-1"></i>
                            <div>
                                <h3 class="font-medium text-gray-900">Horaires</h3>
                                <p class="text-gray-600">
                                    Lundi - Vendredi : 8h00 - 17h00<br>
                                    Samedi : 8h00 - 12h00
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="font-medium text-gray-900 mb-3">Suivez-nous</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                                <i data-lucide="facebook" class="w-6 h-6"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                                <i data-lucide="twitter" class="w-6 h-6"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                                <i data-lucide="linkedin" class="w-6 h-6"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                                <i data-lucide="instagram" class="w-6 h-6"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Formulaire de contact -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Envoyez-nous un message</h2>
                    
                    <form class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                            <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Sujet</label>
                            <select id="subject" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Sélectionnez un sujet</option>
                                <option value="inscription">Question sur l'inscription</option>
                                <option value="candidature">Candidature PEUB</option>
                                <option value="partenaire">Devenir partenaire</option>
                                <option value="technique">Support technique</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea id="message" name="message" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-primary-600 text-white py-2 px-4 rounded-md hover:bg-primary-700 transition-colors">
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection