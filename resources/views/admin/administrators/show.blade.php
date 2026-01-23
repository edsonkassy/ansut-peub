@extends('layouts.admin')

@section('title', 'Détails Administrateur')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Détails de l'Administrateur</h1>
            <p class="text-gray-600">Informations complètes sur l'administrateur</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.administrators.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2 inline"></i>
                Retour
            </a>
            @if(auth()->user()->hasAdminPermission('users.administrators.edit'))
                <a href="{{ route('admin.administrators.edit', $administrator) }}" 
                   class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i data-lucide="edit" class="w-4 h-4 mr-2 inline"></i>
                    Modifier
                </a>
            @endif
        </div>
    </div>

    <!-- Flash Messages -->
    @include('components.flash-messages')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profil -->
            <div class="bg-white shadow-sm border border-gray-200">
                <div class="px-6 py-4 ">
                    <h2 class="text-lg font-semibold text-gray-900">Informations du Profil</h2>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                            <span class="text-primary-600 font-bold text-xl">
                                {{ strtoupper(substr($administrator->email, 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $administrator->email }}</h3>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $administrator->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $administrator->status === 'active' ? 'Actif' : 'Suspendu' }}
                                </span>
                                @if($administrator->hasAdminRole('super_admin'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i data-lucide="crown" class="w-3 h-3 mr-1"></i>
                                        Super Admin
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="font-medium text-gray-700">Email</label>
                            <p class="text-gray-900">{{ $administrator->email }}</p>
                        </div>
                        <div>
                            <label class="font-medium text-gray-700">Rôle</label>
                            <p class="text-gray-900">{{ ucfirst($administrator->role) }}</p>
                        </div>
                        <div>
                            <label class="font-medium text-gray-700">Statut</label>
                            <p class="text-gray-900">{{ $administrator->status === 'active' ? 'Actif' : 'Suspendu' }}</p>
                        </div>
                        <div>
                            <label class="font-medium text-gray-700">Dernière connexion</label>
                            <p class="text-gray-900">
                                {{ $administrator->last_login_at ? $administrator->last_login_at->format('d/m/Y à H:i') : 'Jamais' }}
                            </p>
                        </div>
                        <div>
                            <label class="font-medium text-gray-700">Compte créé le</label>
                            <p class="text-gray-900">{{ $administrator->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <label class="font-medium text-gray-700">Email vérifié</label>
                            <p class="text-gray-900">
                                @if($administrator->email_verified_at)
                                    <span class="text-green-600">✓ Vérifié le {{ $administrator->email_verified_at->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-red-600">✗ Non vérifié</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rôles et Permissions -->
            <div class="bg-white shadow-sm border border-gray-200">
                <div class="px-6 py-4 ">
                    <h2 class="text-lg font-semibold text-gray-900">Rôles et Permissions</h2>
                </div>
                <div class="px-6 py-4">
                    <!-- Rôles -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Rôles Assignés</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($administrator->adminRoles as $role)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $role->name === 'super_admin' ? 'bg-red-100 text-red-800' : 
                                       ($role->name === 'user_admin' ? 'bg-blue-100 text-blue-800' : 
                                       ($role->name === 'content_admin' ? 'bg-green-100 text-green-800' : 
                                       ($role->name === 'opportunity_admin' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($role->name === 'analytics_admin' ? 'bg-purple-100 text-purple-800' : 
                                       'bg-gray-100 text-gray-800')))) }}">
                                    {{ $role->display_name }}
                                </span>
                            @empty
                                <span class="text-gray-500 italic">Aucun rôle assigné</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Permissions Effectives</h3>
                        @if($administrator->hasAdminRole('super_admin'))
                            <div class="bg-red-50 border border-red-200 p-4">
                                <div class="flex items-center">
                                    <i data-lucide="crown" class="w-5 h-5 text-red-600 mr-2"></i>
                                    <span class="text-red-800 font-medium">Toutes les permissions (Super Administrateur)</span>
                                </div>
                            </div>
                        @else
                            @php
                                $permissions = collect();
                                foreach($administrator->adminRoles as $role) {
                                    $permissions = $permissions->merge($role->permissions);
                                }
                                $permissions = $permissions->unique('id')->groupBy('module');
                            @endphp
                            
                            @if($permissions->count() > 0)
                                <div class="space-y-4">
                                    @foreach($permissions as $module => $modulePermissions)
                                        <div class="border border-gray-200 p-4">
                                            <h4 class="font-medium text-gray-900 mb-2 capitalize">{{ ucfirst($module) }}</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                @foreach($modulePermissions as $permission)
                                                    <div class="flex items-center space-x-2">
                                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                                        <span class="text-sm text-gray-700">{{ $permission->display_name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 p-4">
                                    <div class="flex items-center">
                                        <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 mr-2"></i>
                                        <span class="text-yellow-800">Aucune permission assignée</span>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions rapides -->
            <div class="bg-white shadow-sm border border-gray-200">
                <div class="px-6 py-4 ">
                    <h2 class="text-lg font-semibold text-gray-900">Actions</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @if(auth()->user()->hasAdminPermission('users.administrators.edit'))
                        <a href="{{ route('admin.administrators.edit', $administrator) }}" 
                           class="w-full bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                            Modifier
                        </a>
                    @endif
                    
                    @if(auth()->user()->hasAdminPermission('users.administrators.delete') && $administrator->id !== auth()->id())
                        <button onclick="confirmDelete()" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                            Supprimer
                        </button>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white shadow-sm border border-gray-200">
                <div class="px-6 py-4 ">
                    <h2 class="text-lg font-semibold text-gray-900">Informations Système</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">ID Utilisateur</span>
                        <span class="text-sm font-medium text-gray-900">#{{ $administrator->id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Nombre de rôles</span>
                        <span class="text-sm font-medium text-gray-900">{{ $administrator->adminRoles->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Permissions totales</span>
                        <span class="text-sm font-medium text-gray-900">
                            @if($administrator->hasAdminRole('super_admin'))
                                Toutes
                            @else
                                {{ $administrator->adminRoles->flatMap->permissions->unique('id')->count() }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Historique des rôles -->
            <div class="bg-white shadow-sm border border-gray-200">
                <div class="px-6 py-4 ">
                    <h2 class="text-lg font-semibold text-gray-900">Historique des Rôles</h2>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-3">
                        @forelse($administrator->adminRoles as $role)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $role->display_name }}</span>
                                    <p class="text-xs text-gray-500">{{ $role->name }}</p>
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $role->pivot->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 italic">Aucun rôle assigné</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cet administrateur ? Cette action est irréversible.</p>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Annuler
                </button>
                <form method="POST" action="{{ route('admin.administrators.destroy', $administrator) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endpush 