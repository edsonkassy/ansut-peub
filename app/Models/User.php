<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AdminRole;
use App\Models\AdminPermission;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'email_verified_at',
        'role',
        'status',
        'last_login_at',
        'otp_code',
        'otp_expires_at',
        'provider',
        'provider_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'otp_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'otp_expires_at' => 'datetime',
        ];
    }

    /**
     * Relations
     */
    public function bachelier(): HasOne
    {
        return $this->hasOne(Bachelier::class);
    }

    public function partenaire(): HasOne
    {
        return $this->hasOne(Partenaire::class);
    }

    public function interactionsIa(): HasMany
    {
        return $this->hasMany(InteractionIa::class);
    }

    public function statistiquesEngagement(): HasMany
    {
        return $this->hasMany(StatistiqueEngagement::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'auteur_id');
    }

    public function favoriteThreads(): HasMany
    {
        return $this->hasMany(ForumFavorite::class);
    }

    /**
     * Scopes
     */
    public function scopeBacheliers($query)
    {
        return $query->where('role', 'bachelier');
    }

    public function scopePartenaires($query)
    {
        return $query->where('role', 'partenaire');
    }

    public function scopeActifs($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Méthodes pour gérer les statuts
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }

    public function setPending(): void
    {
        $this->update(['status' => 'pending']);
    }

    /**
     * Accesseur pour obtenir le nom complet de l'utilisateur
     */
    public function getNameAttribute(): string
    {
        if ($this->role === 'bachelier' && $this->bachelier) {
            return $this->bachelier->prenoms . ' ' . $this->bachelier->nom;
        } elseif ($this->role === 'partenaire' && $this->partenaire) {
            return $this->partenaire->personne_contact_nom;
        }
        
        return $this->email; // Fallback vers l'email
    }

    /**
     * Méthode pour obtenir le nom complet avec les relations chargées
     */
    public function getFullName(): string
    {
        if ($this->role === 'bachelier') {
            $bachelier = $this->bachelier ?? $this->load('bachelier')->bachelier;
            return $bachelier ? $bachelier->prenoms . ' ' . $bachelier->nom : $this->email;
        } elseif ($this->role === 'partenaire') {
            $partenaire = $this->partenaire ?? $this->load('partenaire')->partenaire;
            return $partenaire ? $partenaire->personne_contact_nom : $this->email;
        }
        
        return $this->email;
    }

    /**
     * Relations Admin
     */
    public function adminRoles(): BelongsToMany
    {
        return $this->belongsToMany(AdminRole::class, 'admin_user_roles', 'user_id', 'admin_role_id')
                    ->withPivot('assigned_at', 'assigned_by')
                    ->withTimestamps();
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur a un rôle admin spécifique
     */
    public function hasAdminRole(string $roleName): bool
    {
        if (!$this->isAdmin()) {
            return false;
        }
        
        return $this->adminRoles()->where('name', $roleName)->exists();
    }

    /**
     * Vérifier si l'utilisateur a une permission spécifique
     */
    public function hasAdminPermission(string $permissionName): bool
    {
        if (!$this->isAdmin()) {
            return false;
        }

        // Super admin a toutes les permissions
        if ($this->hasAdminRole('super_admin')) {
            return true;
        }

        // Vérifier dans les rôles de l'utilisateur
        foreach ($this->adminRoles as $role) {
            if ($role->hasPermission($permissionName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtenir toutes les permissions de l'utilisateur
     */
    public function getAdminPermissions(): array
    {
        if (!$this->isAdmin()) {
            return [];
        }

        // Super admin a toutes les permissions
        if ($this->hasAdminRole('super_admin')) {
            return AdminPermission::active()->pluck('name')->toArray();
        }

        $permissions = [];
        foreach ($this->adminRoles as $role) {
            $permissions = array_merge($permissions, $role->permissions->pluck('name')->toArray());
        }

        return array_unique($permissions);
    }

    /**
     * Assigner un rôle admin à l'utilisateur
     */
    public function assignAdminRole(AdminRole $role, ?User $assignedBy = null): void
    {
        if (!$this->isAdmin()) {
            return;
        }

        $this->adminRoles()->syncWithoutDetaching([
            $role->id => [
                'assigned_at' => now(),
                'assigned_by' => $assignedBy?->id,
            ]
        ]);
    }

    /**
     * Retirer un rôle admin de l'utilisateur
     */
    public function removeAdminRole(AdminRole $role): void
    {
        $this->adminRoles()->detach($role->id);
    }

    /**
     * Scope pour les admins seulement
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Vérifier si l'utilisateur utilise un login social
     */
    public function isSocialLogin(): bool
    {
        return !is_null($this->provider);
    }

    /**
     * Obtenir l'avatar de l'utilisateur
     */
    public function getAvatarUrl(): ?string
    {
        return $this->avatar;
    }

    /**
     * Obtenir le nom du provider (formaté)
     */
    public function getProviderName(): ?string
    {
        if (!$this->provider) {
            return null;
        }

        return ucfirst($this->provider);
    }
}
