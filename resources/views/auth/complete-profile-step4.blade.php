@extends('layouts.guest')
@section('title', 'Compléter votre profil - Étape 4/4')
@section('content')
<style>
* { touch-action: pan-y; }
</style>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-[#0E7490]">Bienvenue sur PEUB</h1>
            <p class="text-gray-500 text-sm mt-1">Complétez votre profil - Étape 4 sur 4</p>
        </div>

        <div class="flex items-center justify-center mb-6"><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold shadow">✓</div></div><span class="text-xs text-green-600 hidden sm:block">Infos générales</span></div><div class="h-px w-8 bg-green-400 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold shadow">✓</div></div><span class="text-xs text-green-600 hidden sm:block">Infos scolaires</span></div><div class="h-px w-8 bg-green-400 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold shadow">✓</div></div><span class="text-xs text-green-600 hidden sm:block">Situation sociale</span></div><div class="h-px w-8 bg-green-400 mb-4"></div><div class="flex flex-col items-center gap-1"><div><div class="w-9 h-9 rounded-full bg-[#0E7490] text-white flex items-center justify-center text-sm font-bold shadow-lg ring-4 ring-[#0E7490]/20">4</div></div><span class="text-xs text-[#0E7490] font-semibold hidden sm:block">Motivation</span></div></div>
        </div>

        @if(session('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <form method="POST" action="{{ route('auth.complete-profile.step.save', 4) }}" enctype="multipart/form-data" style="touch-action: pan-y;">
                @csrf
                                <!-- Section 4: Motivation avec icône -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b-2 border-[#0E7490]/20">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-[#0E7490] to-[#0c5f7a] text-white shadow-md">
                            <i data-lucide="message-square" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Lettre de motivation</h3>
                            <p class="text-sm text-gray-500">Exprimez votre motivation à rejoindre le programme PEUB</p>
                        </div>
                    </div>
                    
                    <div>
                        <label for="motivation" class="block text-sm font-medium text-gray-700 required">Lettre de motivation</label>
                        <div class="relative">
                            <textarea name="motivation" id="motivation" rows="8" required maxlength="5000"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                      placeholder="Expliquez pourquoi vous souhaitez rejoindre PEUB et comment ce programme peut contribuer à votre parcours académique et professionnel...">{{ $getValue('motivation') }}</textarea>
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400" id="char-count">
                                <span id="current-chars">0</span> / 5000 caractères
                            </div>
                        </div>
                        <div class="mt-2 flex items-start gap-2 text-xs text-gray-600 bg-blue-50 p-3 rounded-lg border border-blue-200">
                            <i data-lucide="lightbulb" class="w-4 h-4 text-[#0E7490] flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="font-medium text-[#0E7490] mb-1">Conseils pour une bonne lettre :</p>
                                <ul class="space-y-1 list-disc list-inside">
                                    <li>Présentez vos ambitions académiques et professionnelles</li>
                                    <li>Expliquez en quoi PEUB peut vous aider à les atteindre</li>
                                    <li>Mettez en avant vos qualités et votre détermination</li>
                                </ul>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 font-medium">Minimum 100 caractères requis</p>
                        @error('motivation')<p class="error-message">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Conditions avec design amélioré -->
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-6 border-2 border-[#0E7490]/20">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="flex-shrink-0">
                            <i data-lucide="shield-check" class="w-6 h-6 text-[#0E7490]"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Acceptations et engagements</h4>
                            <p class="text-sm text-gray-600">Veuillez lire et accepter les conditions suivantes</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="flex items-start p-4 bg-white rounded-lg border-2 border-transparent hover:border-[#0E7490]/30 transition-all cursor-pointer group">
                            <input type="checkbox" name="acceptation_conditions" required
                                   class="mt-1 h-5 w-5 text-[#0E7490] focus:ring-[#0E7490] border-gray-300 rounded">
                            <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900">
                                <span class="font-semibold">Je certifie l'exactitude des informations.</span> 
                                Je confirme que toutes les informations fournies sont exactes et complètes. *
                            </span>
                        </label>
                        <label class="flex items-start p-4 bg-white rounded-lg border-2 border-transparent hover:border-[#0E7490]/30 transition-all cursor-pointer group">
                            <input type="checkbox" name="acceptation_donnees" required
                                   class="mt-1 h-5 w-5 text-[#0E7490] focus:ring-[#0E7490] border-gray-300 rounded">
                            <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900">
                                <span class="font-semibold">J'accepte la politique de confidentialité.</span>
                                J'accepte le traitement de mes données personnelles conformément à la 
                                <a href="#" class="text-[#0E7490] underline hover:text-[#0c5f7a]">politique de confidentialité</a>. *
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Bouton Retour -->
                
                <div class="pt-6">
                    <button type="submit" 
                            style="background: linear-gradient(to right, #0E7490, #0c5f7a);"
                            class="group relative w-full flex justify-center items-center py-5 px-6 border-2 border-transparent rounded-xl shadow-lg text-base font-bold text-white hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-cyan-700/30 transition-all duration-300 transform hover:scale-[1.02]"
                            onmouseover="this.style.background='linear-gradient(to right, #0c5f7a, #0a4f63)'"
                            onmouseout="this.style.background='linear-gradient(to right, #0E7490, #0c5f7a)'">
                        <span class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="text-lg">Prévisualiser ma candidature</span>
                            <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                        <div class="absolute inset-0 rounded-xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    </button>
                    <p class="mt-4 text-center text-sm text-gray-500">
                        En soumettant ce formulaire, vous rejoignez une communauté de 
                        <span class="font-semibold text-[#0E7490]">plus de 2000 bacheliers d'excellence</span>
                    </p>
                </div>
            </form>
        </div>

                <div class="pt-4 flex gap-3">
                    <a href="{{ route('auth.complete-profile.step', 3) }}" class="flex-1 text-center py-3 border-2 border-[#0E7490] text-[#0E7490] rounded-xl font-medium">← Retour</a>
                    <button type="submit" style="background: linear-gradient(to right, #0E7490, #0c5f7a);" class="flex-1 py-3 text-white rounded-xl font-bold">
                        Prévisualiser →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
