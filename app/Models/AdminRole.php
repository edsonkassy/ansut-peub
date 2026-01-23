<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminRole extends Model
{
    use HasFactory;

    protected $table = 'admin_roles';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec les permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_role_permissions', 'admin_role_id', 'admin_permission_id')
                    ->withTimestamps();
    }

    /**
     * Relation avec les utilisateurs
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'admin_user_roles', 'admin_role_id', 'user_id')
                    ->withPivot('assigned_at', 'assigned_by')
                    ->withTimestamps();
    }

    /**
     * Vérifier si le rôle a une permission spécifique
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * Assigner une permission au rôle
     */
    public function assignPermission(AdminPermission $permission): void
    {
        $this->permissions()->syncWithoutDetaching($permission->id);
    }

    /**
     * Retirer une permission du rôle
     */
    public function removePermission(AdminPermission $permission): void
    {
        $this->permissions()->detach($permission->id);
    }

    /**
     * Scope pour les rôles actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 