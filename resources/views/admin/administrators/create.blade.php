@extends('layouts.admin')

@section('title', 'Créer un Administrateur')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Créer un Administrateur</h1>
            <p class="text-gray-600">Ajouter un nouvel administrateur au système</p>
        </div>
        <a href="{{ route('admin.administrators.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2 inline"></i>
            Retour
        </a>
    </div>

    <!-- Flash Messages -->
    @include('components.flash-messages')

    <!-- Formulaire -->
    <div class="bg-white shadow-sm border border-gray-200">
        <div class="px-6 py-4 ">
            <h2 class="text-lg font-semibold text-gray-900">Informations de l'Administrateur</h2>
        </div>
        
        <form method="POST" action="{{ route('admin.administrators.store') }}" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('email') border-red-500 @enderror"
                           placeholder="admin@example.com"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rôles -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Rôles <span class="text-red-500">*</span>
                    </label>
                    <p class="text-sm text-gray-600 mb-3">
                        Sélectionnez les rôles à attribuer à cet administrateur
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($roles as $role)
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" 
                                       id="role_{{ $role->id }}" 
                                       name="admin_roles[]" 
                                       value="{{ $role->id }}"
                                       class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                       {{ collect(old('admin_roles', []))->contains($role->id) ? 'checked' : '' }}>
                                <label for="role_{{ $role->id }}" class="flex-1">
                                    <span class="text-sm font-medium text-gray-900">{{ $role->display_name }}</span>
                                    @if($role->description)
                                        <p class="text-sm text-gray-500">{{ $role->description }}</p>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                    
                    @error('admin_roles')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Information sur l'envoi d'email -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex">
                        <i data-lucide="info" class="w-5 h-5 text-blue-600 mr-3 mt-0.5"></i>
                        <div>
                            <h3 class="text-sm font-medium text-blue-900">Information</h3>
                            <p class="text-sm text-blue-800 mt-1">
                                Un email avec un code OTP sera envoyé à l'administrateur pour qu'il puisse se connecter et configurer son compte.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('admin.administrators.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i data-lucide="plus" class="w-4 h-4 mr-2 inline"></i>
                    Créer l'Administrateur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 