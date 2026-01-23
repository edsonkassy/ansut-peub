<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminPermission extends Model
{
    use HasFactory;

    protected $table = 'admin_permissions';

    protected $fillable = [
        'name',
        'display_name',
        'module',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec les rôles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(AdminRole::class, 'admin_role_permissions', 'admin_permission_id', 'admin_role_id')
                    ->withTimestamps();
    }

    /**
     * Scope pour les permissions actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour un module spécifique
     */
    public function scopeForModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Grouper les permissions par module
     */
    public static function groupedByModule()
    {
        return static::active()->orderBy('module')->orderBy('display_name')->get()->groupBy('module');
    }
} 