@extends('layouts.admin')

@section('title', 'Gestion des Administrateurs')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Administrateurs</h1>
            <p class="text-gray-600">Gérez les administrateurs et leurs permissions</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.administrators.roles') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i data-lucide="shield" class="w-4 h-4 mr-2 inline"></i>
                Gérer les Rôles
            </a>
            <a href="{{ route('admin.administrators.create') }}" 
               class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2 inline"></i>
                Nouvel Administrateur
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @include('components.flash-messages')

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Administrateurs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $admins->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i data-lucide="shield-check" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Actifs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $admins->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i data-lucide="shield-alert" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Suspendus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $admins->where('status', 'suspended')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i data-lucide="crown" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Super Admins</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $admins->filter(fn($admin) => $admin->hasAdminRole('super_admin'))->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table des administrateurs -->
    <div class="bg-white shadow-sm border border-gray-200">
        <div class="px-6 py-4 ">
            <h2 class="text-lg font-semibold text-gray-900">Liste des Administrateurs</h2>
        </div>
        
        @if($admins->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Administrateur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rôles
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dernière Connexion
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($admins as $admin)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                            <span class="text-primary-600 font-medium text-sm">
                                                {{ strtoupper(substr($admin->email, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $admin->email }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Créé le {{ $admin->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($admin->adminRoles as $role)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $role->name === 'super_admin' ? 'bg-red-100 text-red-800' : 
                                                   ($role->name === 'user_admin' ? 'bg-blue-100 text-blue-800' : 
                                                   ($role->name === 'content_admin' ? 'bg-green-100 text-green-800' : 
                                                   ($role->name === 'opportunity_admin' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($role->name === 'analytics_admin' ? 'bg-purple-100 text-purple-800' : 
                                                   'bg-gray-100 text-gray-800')))) }}">
                                                {{ $role->display_name }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-gray-500">Aucun rôle</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $admin->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $admin->status === 'active' ? 'Actif' : 'Suspendu' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $admin->last_login_at ? $admin->last_login_at->format('d/m/Y H:i') : 'Jamais' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.administrators.show', $admin) }}" 
                                           class="text-blue-600 hover:text-blue-800 transition-colors">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('admin.administrators.edit', $admin) }}" 
                                           class="text-green-600 hover:text-green-800 transition-colors">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        @if($admin->id !== auth()->id())
                                            <button onclick="confirmDelete({{ $admin->id }})" 
                                                    class="text-red-600 hover:text-red-800 transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $admins->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i data-lucide="users" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                <p class="text-gray-500">Aucun administrateur trouvé</p>
            </div>
        @endif
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
                <form id="deleteForm" method="POST" class="inline">
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
function confirmDelete(adminId) {
    document.getElementById('deleteForm').action = `/admin/administrators/${adminId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endpush 