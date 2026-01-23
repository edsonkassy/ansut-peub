@extends('layouts.admin')

@section('title', 'Modifier un Administrateur')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier l'Administrateur</h1>
            <p class="text-gray-600">Modifier les informations et permissions de l'administrateur</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.administrators.show', $administrator) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2 inline"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @include('components.flash-messages')

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.administrators.update', $administrator) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Formulaire principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations de base -->
                <div class="bg-white shadow-sm border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h2 class="text-lg font-semibold text-gray-900">Informations de Base</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $administrator->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Statut <span class="text-red-500">*</span>
                            </label>
                            <select id="status" 
                                    name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('status') border-red-500 @enderror"
                                    required>
                                <option value="active" {{ old('status', $administrator->status) === 'active' ? 'selected' : '' }}>
                                    Actif
                                </option>
                                <option value="suspended" {{ old('status', $administrator->status) === 'suspended' ? 'selected' : '' }}>
                                    Suspendu
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Rôles -->
                <div class="bg-white shadow-sm border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h2 class="text-lg font-semibold text-gray-900">Rôles et Permissions</h2>
                        <p class="text-sm text-gray-600 mt-1">Sélectionnez les rôles à attribuer à cet administrateur</p>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-4">
                            @foreach($roles as $role)
                                <div class="flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" 
                                           id="role_{{ $role->id }}" 
                                           name="admin_roles[]" 
                                           value="{{ $role->id }}"
                                           class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                           {{ in_array($role->id, old('admin_roles', $assignedRoleIds)) ? 'checked' : '' }}>
                                    <div class="flex-1">
                                        <label for="role_{{ $role->id }}" class="cursor-pointer">
                                            <span class="text-sm font-medium text-gray-900">{{ $role->display_name }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                {{ $role->name === 'super_admin' ? 'bg-red-100 text-red-800' : 
                                                   ($role->name === 'user_admin' ? 'bg-blue-100 text-blue-800' : 
                                                   ($role->name === 'content_admin' ? 'bg-green-100 text-green-800' : 
                                                   ($role->name === 'opportunity_admin' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($role->name === 'analytics_admin' ? 'bg-purple-100 text-purple-800' : 
                                                   'bg-gray-100 text-gray-800')))) }} ml-2">
                                                {{ $role->name }}
                                            </span>
                                        </label>
                                        @if($role->description)
                                            <p class="text-sm text-gray-500 mt-1">{{ $role->description }}</p>
                                        @endif
                                        
                                        <!-- Permissions du rôle -->
                                        <div class="mt-2">
                                            <div class="text-xs text-gray-500">
                                                @if($role->name === 'super_admin')
                                                    <span class="font-medium text-red-600">Toutes les permissions</span>
                                                @elseif($role->permissions->count() > 0)
                                                    <span class="font-medium">{{ $role->permissions->count() }} permissions:</span>
                                                    {{ $role->permissions->pluck('display_name')->join(', ') }}
                                                @else
                                                    <span class="text-gray-400">Aucune permission assignée</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @error('admin_roles')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informations actuelles -->
                <div class="bg-white shadow-sm border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h2 class="text-lg font-semibold text-gray-900">Informations Actuelles</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                                <span class="text-primary-600 font-bold text-sm">
                                    {{ strtoupper(substr($administrator->email, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">{{ $administrator->email }}</h3>
                                <p class="text-xs text-gray-500">ID: #{{ $administrator->id }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Statut actuel</span>
                                <span class="font-medium {{ $administrator->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $administrator->status === 'active' ? 'Actif' : 'Suspendu' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rôles actuels</span>
                                <span class="font-medium">{{ $administrator->adminRoles->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dernière connexion</span>
                                <span class="font-medium">
                                    {{ $administrator->last_login_at ? $administrator->last_login_at->format('d/m/Y') : 'Jamais' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Créé le</span>
                                <span class="font-medium">{{ $administrator->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rôles actuels -->
                <div class="bg-white shadow-sm border border-gray-200">
                    <div class="px-6 py-4 ">
                        <h2 class="text-lg font-semibold text-gray-900">Rôles Actuels</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-2">
                            @forelse($administrator->adminRoles as $role)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">{{ $role->display_name }}</span>
                                        <p class="text-xs text-gray-500">{{ $role->name }}</p>
                                    </div>
                                    <span class="text-xs text-gray-400">
                                        {{ $role->pivot->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">Aucun rôle assigné</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Avertissements -->
                @if($administrator->hasAdminRole('super_admin'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 mr-2"></i>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">Super Administrateur</h3>
                                <p class="text-xs text-red-700 mt-1">
                                    Cet utilisateur a tous les droits sur le système. Soyez prudent lors de la modification.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($administrator->id === auth()->id())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i data-lucide="info" class="w-5 h-5 text-yellow-600 mr-2"></i>
                            <div>
                                <h3 class="text-sm font-medium text-yellow-800">Votre Profil</h3>
                                <p class="text-xs text-yellow-700 mt-1">
                                    Vous modifiez votre propre profil. Assurez-vous de conserver au moins un rôle.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.administrators.show', $administrator) }}" 
               class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                Annuler
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i data-lucide="save" class="w-4 h-4 mr-2 inline"></i>
                Enregistrer les Modifications
            </button>
        </div>
    </form>
</div>
@endsection 