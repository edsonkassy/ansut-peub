@extends('layouts.admin')

@section('title', 'Gestion des Rôles et Permissions')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Rôles et Permissions</h1>
            <p class="text-gray-600">Configurez les rôles et leurs permissions</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.administrators.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2 inline"></i>
                Retour aux Administrateurs
            </a>
            <button onclick="openCreateRoleModal()" 
                    class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2 inline"></i>
                Nouveau Rôle
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @include('components.flash-messages')

    <!-- Liste des rôles -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($roles as $role)
            <div class="bg-white shadow-sm border border-gray-200">
                <div class="px-6 py-4 ">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $role->display_name }}</h3>
                            <p class="text-sm text-gray-500">{{ $role->name }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editRole({{ $role->id }})" 
                                    class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            @if($role->name !== 'super_admin')
                                <button onclick="deleteRole({{ $role->id }})" 
                                        class="text-red-600 hover:text-red-800 transition-colors">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    @if($role->description)
                        <p class="text-sm text-gray-600 mt-1">{{ $role->description }}</p>
                    @endif
                </div>
                
                <div class="px-6 py-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Permissions ({{ $role->permissions->count() }})</h4>
                    
                    @if($role->name === 'super_admin')
                        <div class="bg-red-50 p-3 rounded-lg">
                            <p class="text-sm text-red-800 font-medium">
                                <i data-lucide="crown" class="w-4 h-4 mr-1 inline"></i>
                                Toutes les permissions
                            </p>
                        </div>
                    @else
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            @forelse($role->permissions as $permission)
                                <div class="flex items-center justify-between py-1">
                                    <span class="text-sm text-gray-700">{{ $permission->display_name }}</span>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                        {{ $permission->module }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">Aucune permission assignée</p>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Permissions disponibles -->
    <div class="bg-white shadow-sm border border-gray-200">
        <div class="px-6 py-4 ">
            <h2 class="text-lg font-semibold text-gray-900">Permissions Disponibles</h2>
            <p class="text-sm text-gray-600">Liste de toutes les permissions du système</p>
        </div>
        
        <div class="p-6">
            @foreach($permissions as $module => $modulePermissions)
                <div class="mb-6 last:mb-0">
                    <h3 class="text-md font-medium text-gray-900 mb-3 capitalize">
                        {{ ucfirst($module) }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($modulePermissions as $permission)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $permission->name }}</p>
                                    </div>
                                    <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">
                                        {{ $permission->module }}
                                    </span>
                                </div>
                                @if($permission->description)
                                    <p class="text-xs text-gray-600 mt-1">{{ $permission->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal de création de rôle -->
<div id="createRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Créer un Nouveau Rôle</h3>
            
            <form id="createRoleForm" method="POST" action="{{ route('admin.administrators.roles.store') }}">
                @csrf
                
                <div class="space-y-4">
                    <!-- Nom du rôle -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom du rôle <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                                   placeholder="ex: content_manager"
                                   required>
                        </div>
                        
                        <div>
                            <label for="display_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom d'affichage <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="display_name" 
                                   name="display_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                                   placeholder="ex: Gestionnaire de Contenu"
                                   required>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                                  placeholder="Description du rôle..."></textarea>
                    </div>
                    
                    <!-- Permissions -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Permissions <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="space-y-4 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-4">
                            @foreach($permissions as $module => $modulePermissions)
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2 capitalize">{{ ucfirst($module) }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($modulePermissions as $permission)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}"
                                                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                                <span class="text-sm text-gray-700">{{ $permission->display_name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                    <button type="button" 
                            onclick="closeCreateRoleModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        Créer le Rôle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'édition de rôle -->
<div id="editRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Modifier le Rôle</h3>
            
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Nom du rôle (en lecture seule) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom du rôle
                            </label>
                            <input type="text" 
                                   id="edit_name" 
                                   name="name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                   readonly>
                        </div>
                        
                        <div>
                            <label for="edit_display_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nom d'affichage <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="edit_display_name" 
                                   name="display_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                                   required>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea id="edit_description" 
                                  name="description" 
                                  rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                                  placeholder="Description du rôle..."></textarea>
                    </div>
                    
                    <!-- Permissions -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Permissions <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="space-y-4 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-4">
                            @foreach($permissions as $module => $modulePermissions)
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2 capitalize">{{ ucfirst($module) }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($modulePermissions as $permission)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}"
                                                       class="edit-permission h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                                <span class="text-sm text-gray-700">{{ $permission->display_name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                    <button type="button" 
                            onclick="closeEditRoleModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        Modifier le Rôle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de suppression de rôle -->
<div id="deleteRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-6 max-w-sm w-full mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmer la suppression</h3>
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer ce rôle ? Cette action est irréversible et tous les administrateurs ayant ce rôle perdront leurs permissions associées.</p>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteRoleModal()" 
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Annuler
                </button>
                <form id="deleteRoleForm" method="POST" class="inline">
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
// Données des rôles pour JavaScript
const rolesData = @json($roles);

function openCreateRoleModal() {
    document.getElementById('createRoleModal').classList.remove('hidden');
}

function closeCreateRoleModal() {
    document.getElementById('createRoleModal').classList.add('hidden');
}

function editRole(roleId) {
    // Trouver le rôle à éditer
    const role = rolesData.find(r => r.id === roleId);
    if (!role) {
        alert('Rôle non trouvé');
        return;
    }
    
    // Remplir le formulaire d'édition
    document.getElementById('edit_name').value = role.name;
    document.getElementById('edit_display_name').value = role.display_name;
    document.getElementById('edit_description').value = role.description || '';
    
    // Configurer l'action du formulaire
    document.getElementById('editRoleForm').action = `/admin/administrators/roles/${roleId}`;
    
    // Cocher les permissions du rôle
    const permissionCheckboxes = document.querySelectorAll('.edit-permission');
    permissionCheckboxes.forEach(checkbox => {
        checkbox.checked = false; // Décocher toutes les permissions
    });
    
    // Cocher les permissions assignées au rôle
    if (role.permissions) {
        role.permissions.forEach(permission => {
            const checkbox = document.querySelector(`.edit-permission[value="${permission.id}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    }
    
    // Ouvrir le modal
    document.getElementById('editRoleModal').classList.remove('hidden');
}

function closeEditRoleModal() {
    document.getElementById('editRoleModal').classList.add('hidden');
}

function deleteRole(roleId) {
    // Trouver le rôle à supprimer
    const role = rolesData.find(r => r.id === roleId);
    if (!role) {
        alert('Rôle non trouvé');
        return;
    }
    
    // Empêcher la suppression du rôle super_admin
    if (role.name === 'super_admin') {
        alert('Impossible de supprimer le rôle super administrateur.');
        return;
    }
    
    // Configurer l'action du formulaire
    document.getElementById('deleteRoleForm').action = `/admin/administrators/roles/${roleId}`;
    
    // Ouvrir le modal de confirmation
    document.getElementById('deleteRoleModal').classList.remove('hidden');
}

function closeDeleteRoleModal() {
    document.getElementById('deleteRoleModal').classList.add('hidden');
}

// Fermer les modals en cliquant à l'extérieur
document.addEventListener('click', function(event) {
    const modals = ['createRoleModal', 'editRoleModal', 'deleteRoleModal'];
    
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
});

// Gestion des touches Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCreateRoleModal();
        closeEditRoleModal();
        closeDeleteRoleModal();
    }
});
</script>
@endpush 