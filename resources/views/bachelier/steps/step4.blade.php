<!-- Step 4: Motivations et Aspirations -->
<div class="step hidden" id="step4">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Motivations</h2>

        <div class="space-y-6">
            <!-- Motivation -->
            <div>
                <label for="motivation" class="block text-sm font-medium text-gray-700 mb-2">Lettre de motivation *</label>
                <textarea name="motivation" id="motivation" required rows="8"
                          class="block w-full py-3 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('motivation') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                          placeholder="Présentez-vous et expliquez pourquoi vous méritez cette bourse. Décrivez vos aspirations et comment cette opportunité vous aidera à réaliser vos objectifs...">{{ old('motivation') }}</textarea>
                @error('motivation')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Acceptation des conditions -->
            <div class="space-y-4">
                <div class="bg-gray-50 p-4">
                    <h3 class="text-md font-semibold text-gray-900 mb-2">Conditions d'acceptation</h3>
                    <div class="space-y-2 text-sm text-gray-700">
                        <label class="flex items-start">
                            <input type="checkbox" name="accepte_conditions" value="1" required
                                   {{ old('accepte_conditions') ? 'checked' : '' }}
                                   class="mt-1 text-primary-600 focus:ring-primary-500 border-gray-300">
                            <span class="ml-3">
                                Je certifie que toutes les informations fournies sont exactes et complètes. 
                                Je comprends que toute fausse déclaration peut entraîner l'annulation de ma candidature.
                            </span>
                        </label>
                        <label class="flex items-start">
                            <input type="checkbox" name="accepte_traitement_donnees" value="1" required
                                   {{ old('accepte_traitement_donnees') ? 'checked' : '' }}
                                   class="mt-1 text-primary-600 focus:ring-primary-500 border-gray-300">
                            <span class="ml-3">
                                J'accepte le traitement de mes données personnelles dans le cadre de cette candidature 
                                conformément à la politique de confidentialité de PEUB.
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 