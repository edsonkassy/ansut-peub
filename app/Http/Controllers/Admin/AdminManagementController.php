<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminRole;
use App\Models\AdminPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\AdminWelcomeMail;
use Carbon\Carbon;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of administrators.
     */
    public function index()
    {
        $admins = User::admins()
            ->with(['adminRoles.permissions'])
            ->orderBy('email')
            ->paginate(20);

        return view('admin.administrators.index', compact('admins'));
    }

    /**
     * Show the form for creating a new administrator.
     */
    public function create()
    {
        $roles = AdminRole::active()->get();
        return view('admin.administrators.create', compact('roles'));
    }

    /**
     * Store a newly created administrator.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'admin_roles' => 'required|array|min:1',
            'admin_roles.*' => 'exists:admin_roles,id',
        ]);

        // Vérifier que l'utilisateur a la permission de créer des administrateurs
        if (!auth()->user()->hasAdminPermission('users.administrators.create')) {
            abort(403, 'Vous n\'avez pas la permission de créer des administrateurs.');
        }

        // Vérifier que l'utilisateur ne peut pas assigner des rôles qu'il n'a pas
        if (!auth()->user()->hasAdminRole('super_admin')) {
            $userRoleIds = auth()->user()->adminRoles->pluck('id')->toArray();
            $requestedRoleIds = $request->admin_roles;
            
            foreach ($requestedRoleIds as $roleId) {
                if (!in_array($roleId, $userRoleIds)) {
                    $role = AdminRole::find($roleId);
                    return back()->with('error', "Vous ne pouvez pas assigner le rôle '{$role->display_name}' car vous ne l'avez pas vous-même.")
                        ->withInput();
                }
            }
        }

        try {
            DB::transaction(function () use ($request) {
                // Créer l'utilisateur admin
                $admin = User::create([
                    'email' => $request->email,
                    'role' => 'admin',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);

                // Assigner les rôles
                $roleIds = $request->admin_roles;
                $roles = AdminRole::whereIn('id', $roleIds)->get();
                
                foreach ($roles as $role) {
                    $admin->assignAdminRole($role, auth()->user());
                }

                // Générer OTP pour la première connexion
                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $admin->update([
                    'otp_code' => $otp,
                    'otp_expires_at' => Carbon::now()->addMinutes(10)
                ]);

                // Recharger les rôles pour l'email
                $admin->load('adminRoles');

                // Envoyer email de bienvenue avec OTP
                Mail::to($admin->email)->send(new AdminWelcomeMail($admin, $otp, auth()->user()));
            });

            return redirect()->route('admin.administrators.index')
                ->with('success', 'Administrateur créé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de l\'administrateur.')
                ->withInput();
        }
    }

    /**
     * Display the specified administrator.
     */
    public function show(User $administrator)
    {
        $administrator->load(['adminRoles.permissions']);
        return view('admin.administrators.show', compact('administrator'));
    }

    /**
     * Show the form for editing the specified administrator.
     */
    public function edit(User $administrator)
    {
        $administrator->load(['adminRoles']);
        $roles = AdminRole::active()->get();
        $assignedRoleIds = $administrator->adminRoles->pluck('id')->toArray();
        
        return view('admin.administrators.edit', compact('administrator', 'roles', 'assignedRoleIds'));
    }

    /**
     * Update the specified administrator.
     */
    public function update(Request $request, User $administrator)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($administrator->id),
            ],
            'admin_roles' => 'required|array|min:1',
            'admin_roles.*' => 'exists:admin_roles,id',
            'status' => 'required|in:active,suspended',
        ]);

        // Vérifier que l'utilisateur a la permission de modifier des administrateurs
        if (!auth()->user()->hasAdminPermission('users.administrators.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier des administrateurs.');
        }

        // Empêcher l'auto-suppression de ses propres rôles critiques
        if ($administrator->id === auth()->id()) {
            $currentRoles = $administrator->adminRoles->pluck('id')->toArray();
            $newRoles = $request->admin_roles;
            
            // Vérifier que l'utilisateur garde au moins un rôle avec la permission de gérer les administrateurs
            $hasManagementPermission = false;
            foreach ($newRoles as $roleId) {
                $role = AdminRole::find($roleId);
                if ($role && ($role->name === 'super_admin' || $role->hasPermission('users.administrators.edit'))) {
                    $hasManagementPermission = true;
                    break;
                }
            }
            
            if (!$hasManagementPermission) {
                return back()->with('error', 'Vous ne pouvez pas retirer tous vos rôles de gestion des administrateurs.')
                    ->withInput();
            }
        }

        // Vérifier que l'utilisateur ne peut pas assigner des rôles qu'il n'a pas
        if (!auth()->user()->hasAdminRole('super_admin')) {
            $userRoleIds = auth()->user()->adminRoles->pluck('id')->toArray();
            $requestedRoleIds = $request->admin_roles;
            
            foreach ($requestedRoleIds as $roleId) {
                if (!in_array($roleId, $userRoleIds)) {
                    $role = AdminRole::find($roleId);
                    return back()->with('error', "Vous ne pouvez pas assigner le rôle '{$role->display_name}' car vous ne l'avez pas vous-même.")
                        ->withInput();
                }
            }
        }

        try {
            DB::transaction(function () use ($request, $administrator) {
                // Mettre à jour l'utilisateur
                $administrator->update([
                    'email' => $request->email,
                    'status' => $request->status,
                ]);

                // Synchroniser les rôles
                $roleIds = $request->admin_roles;
                $roles = AdminRole::whereIn('id', $roleIds)->get();
                
                // Supprimer tous les rôles actuels
                $administrator->adminRoles()->detach();
                
                // Assigner les nouveaux rôles
                foreach ($roles as $role) {
                    $administrator->assignAdminRole($role, auth()->user());
                }
            });

            return redirect()->route('admin.administrators.index')
                ->with('success', 'Administrateur mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de l\'administrateur.')
                ->withInput();
        }
    }

    /**
     * Remove the specified administrator.
     */
    public function destroy(User $administrator)
    {
        // Vérifier que l'utilisateur a la permission de supprimer des administrateurs
        if (!auth()->user()->hasAdminPermission('users.administrators.delete')) {
            abort(403, 'Vous n\'avez pas la permission de supprimer des administrateurs.');
        }

        // Empêcher l'auto-suppression
        if ($administrator->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        try {
            // Empêcher la suppression du dernier super admin
            if ($administrator->hasAdminRole('super_admin')) {
                $superAdmins = User::whereHas('adminRoles', function($query) {
                    $query->where('name', 'super_admin');
                })->count();
                
                if ($superAdmins <= 1) {
                    return back()->with('error', 'Impossible de supprimer le dernier super administrateur.');
                }
            }

            $administrator->delete();

            return redirect()->route('admin.administrators.index')
                ->with('success', 'Administrateur supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de l\'administrateur.');
        }
    }

    /**
     * Manage roles and permissions.
     */
    public function manageRoles()
    {
        $roles = AdminRole::with('permissions')->get();
        $permissions = AdminPermission::groupedByModule();
        
        return view('admin.administrators.roles', compact('roles', 'permissions'));
    }

    /**
     * Create a new role.
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:admin_roles,name|regex:/^[a-z_]+$/',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:admin_permissions,id',
        ]);

        // Vérifier que l'utilisateur a la permission de gérer les rôles
        if (!auth()->user()->hasAdminPermission('users.administrators.roles')) {
            abort(403, 'Vous n\'avez pas la permission de gérer les rôles.');
        }

        // Vérifier que l'utilisateur ne peut pas créer un rôle avec plus de permissions qu'il n'en a
        if (!auth()->user()->hasAdminRole('super_admin')) {
            $userPermissions = auth()->user()->getAdminPermissions();
            $requestedPermissions = AdminPermission::whereIn('id', $request->permissions)->pluck('name')->toArray();
            
            foreach ($requestedPermissions as $permissionName) {
                if (!in_array($permissionName, $userPermissions)) {
                    $permission = AdminPermission::where('name', $permissionName)->first();
                    return back()->with('error', "Vous ne pouvez pas assigner la permission '{$permission->display_name}' car vous ne l'avez pas vous-même.")
                        ->withInput();
                }
            }
        }

        try {
            DB::transaction(function () use ($request) {
                $role = AdminRole::create([
                    'name' => $request->name,
                    'display_name' => $request->display_name,
                    'description' => $request->description,
                ]);

                // Assigner les permissions
                $permissions = AdminPermission::whereIn('id', $request->permissions)->get();
                foreach ($permissions as $permission) {
                    $role->assignPermission($permission);
                }
            });

            return back()->with('success', 'Rôle créé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du rôle.');
        }
    }

    /**
     * Update a role.
     */
    public function updateRole(Request $request, AdminRole $role)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:admin_permissions,id',
        ]);

        try {
            DB::transaction(function () use ($request, $role) {
                $role->update([
                    'display_name' => $request->display_name,
                    'description' => $request->description,
                ]);

                // Synchroniser les permissions
                $role->permissions()->sync($request->permissions);
            });

            return back()->with('success', 'Rôle mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du rôle.');
        }
    }

    /**
     * Delete a role.
     */
    public function destroyRole(AdminRole $role)
    {
        try {
            // Empêcher la suppression du rôle super_admin
            if ($role->name === 'super_admin') {
                return back()->with('error', 'Impossible de supprimer le rôle super administrateur.');
            }

            $role->delete();

            return back()->with('success', 'Rôle supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du rôle.');
        }
    }
} 