<?php

namespace Database\Seeders;

use App\Models\AdminRole;
use App\Models\AdminPermission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Créer les permissions
            $permissions = $this->createPermissions();
            
            // Créer les rôles
            $roles = $this->createRoles();
            
            // Assigner les permissions aux rôles
            $this->assignPermissionsToRoles($roles, $permissions);
            
            // Assigner le rôle super_admin aux admins existants
            $this->assignSuperAdminRole($roles['super_admin']);
        });
    }

    /**
     * Créer toutes les permissions basées sur les modules du sidebar
     */
    private function createPermissions(): array
    {
        $permissionsData = [
            // Dashboard
            [
                'name' => 'dashboard.view',
                'display_name' => 'Voir le tableau de bord',
                'module' => 'dashboard',
                'description' => 'Accès au tableau de bord principal'
            ],

            // Gestion des utilisateurs - Bacheliers
            [
                'name' => 'users.bacheliers.view',
                'display_name' => 'Voir les bacheliers',
                'module' => 'users',
                'description' => 'Consulter la liste des bacheliers'
            ],
            [
                'name' => 'users.bacheliers.create',
                'display_name' => 'Créer des bacheliers',
                'module' => 'users',
                'description' => 'Ajouter de nouveaux bacheliers'
            ],
            [
                'name' => 'users.bacheliers.edit',
                'display_name' => 'Modifier les bacheliers',
                'module' => 'users',
                'description' => 'Modifier les informations des bacheliers'
            ],
            [
                'name' => 'users.bacheliers.delete',
                'display_name' => 'Supprimer les bacheliers',
                'module' => 'users',
                'description' => 'Supprimer des bacheliers'
            ],
            [
                'name' => 'users.bacheliers.validate',
                'display_name' => 'Valider les bacheliers',
                'module' => 'users',
                'description' => 'Valider ou suspendre des bacheliers'
            ],

            // Gestion des utilisateurs - Partenaires
            [
                'name' => 'users.partenaires.view',
                'display_name' => 'Voir les partenaires',
                'module' => 'users',
                'description' => 'Consulter la liste des partenaires'
            ],
            [
                'name' => 'users.partenaires.create',
                'display_name' => 'Créer des partenaires',
                'module' => 'users',
                'description' => 'Ajouter de nouveaux partenaires'
            ],
            [
                'name' => 'users.partenaires.edit',
                'display_name' => 'Modifier les partenaires',
                'module' => 'users',
                'description' => 'Modifier les informations des partenaires'
            ],
            [
                'name' => 'users.partenaires.delete',
                'display_name' => 'Supprimer les partenaires',
                'module' => 'users',
                'description' => 'Supprimer des partenaires'
            ],
            [
                'name' => 'users.partenaires.verify',
                'display_name' => 'Vérifier les partenaires',
                'module' => 'users',
                'description' => 'Vérifier ou rejeter des partenaires'
            ],

            // Gestion des administrateurs
            [
                'name' => 'users.administrators.view',
                'display_name' => 'Voir les administrateurs',
                'module' => 'users',
                'description' => 'Consulter la liste des administrateurs'
            ],
            [
                'name' => 'users.administrators.create',
                'display_name' => 'Créer des administrateurs',
                'module' => 'users',
                'description' => 'Ajouter de nouveaux administrateurs'
            ],
            [
                'name' => 'users.administrators.edit',
                'display_name' => 'Modifier les administrateurs',
                'module' => 'users',
                'description' => 'Modifier les informations des administrateurs'
            ],
            [
                'name' => 'users.administrators.delete',
                'display_name' => 'Supprimer les administrateurs',
                'module' => 'users',
                'description' => 'Supprimer des administrateurs'
            ],
            [
                'name' => 'users.administrators.roles',
                'display_name' => 'Gérer les rôles',
                'module' => 'users',
                'description' => 'Gérer les rôles et permissions'
            ],

            // Boursiers
            [
                'name' => 'boursiers.view',
                'display_name' => 'Voir la carte des boursiers',
                'module' => 'boursiers',
                'description' => 'Accès à la visualisation géographique des boursiers'
            ],

            // Opportunités
            [
                'name' => 'opportunities.view',
                'display_name' => 'Voir les opportunités',
                'module' => 'opportunities',
                'description' => 'Consulter la liste des opportunités'
            ],
            [
                'name' => 'opportunities.create',
                'display_name' => 'Créer des opportunités',
                'module' => 'opportunities',
                'description' => 'Ajouter de nouvelles opportunités'
            ],
            [
                'name' => 'opportunities.edit',
                'display_name' => 'Modifier les opportunités',
                'module' => 'opportunities',
                'description' => 'Modifier les opportunités existantes'
            ],
            [
                'name' => 'opportunities.delete',
                'display_name' => 'Supprimer les opportunités',
                'module' => 'opportunities',
                'description' => 'Supprimer des opportunités'
            ],

            // Candidatures
            [
                'name' => 'candidatures.view',
                'display_name' => 'Voir les candidatures',
                'module' => 'opportunities',
                'description' => 'Consulter les candidatures'
            ],
            [
                'name' => 'candidatures.manage',
                'display_name' => 'Gérer les candidatures',
                'module' => 'opportunities',
                'description' => 'Approuver ou rejeter les candidatures'
            ],

            // Dotations
            [
                'name' => 'dotations.view',
                'display_name' => 'Voir les dotations',
                'module' => 'opportunities',
                'description' => 'Consulter les dotations et attributions'
            ],
            [
                'name' => 'dotations.create',
                'display_name' => 'Créer des dotations',
                'module' => 'opportunities',
                'description' => 'Ajouter de nouvelles dotations'
            ],
            [
                'name' => 'dotations.edit',
                'display_name' => 'Modifier les dotations',
                'module' => 'opportunities',
                'description' => 'Modifier les dotations existantes'
            ],
            [
                'name' => 'dotations.delete',
                'display_name' => 'Supprimer les dotations',
                'module' => 'opportunities',
                'description' => 'Supprimer des dotations'
            ],

            // Inventaire
            [
                'name' => 'inventaire.view',
                'display_name' => 'Voir l\'inventaire',
                'module' => 'opportunities',
                'description' => 'Consulter l\'inventaire des dotations'
            ],
            [
                'name' => 'inventaire.manage',
                'display_name' => 'Gérer l\'inventaire',
                'module' => 'opportunities',
                'description' => 'Gérer les stocks et mouvements'
            ],

            // Fournisseurs
            [
                'name' => 'fournisseurs.view',
                'display_name' => 'Voir les fournisseurs',
                'module' => 'opportunities',
                'description' => 'Consulter la liste des fournisseurs'
            ],
            [
                'name' => 'fournisseurs.manage',
                'display_name' => 'Gérer les fournisseurs',
                'module' => 'opportunities',
                'description' => 'Ajouter, modifier ou supprimer des fournisseurs'
            ],

            // Articles
            [
                'name' => 'articles.view',
                'display_name' => 'Voir les articles',
                'module' => 'articles',
                'description' => 'Consulter la liste des articles'
            ],
            [
                'name' => 'articles.create',
                'display_name' => 'Créer des articles',
                'module' => 'articles',
                'description' => 'Ajouter de nouveaux articles'
            ],
            [
                'name' => 'articles.edit',
                'display_name' => 'Modifier les articles',
                'module' => 'articles',
                'description' => 'Modifier les articles existants'
            ],
            [
                'name' => 'articles.delete',
                'display_name' => 'Supprimer les articles',
                'module' => 'articles',
                'description' => 'Supprimer des articles'
            ],
            [
                'name' => 'articles.analytics',
                'display_name' => 'Analytics articles',
                'module' => 'articles',
                'description' => 'Voir les statistiques des articles'
            ],

            // Analytics
            [
                'name' => 'analytics.view',
                'display_name' => 'Voir les analytics',
                'module' => 'analytics',
                'description' => 'Accès aux analytics avancées'
            ],
            [
                'name' => 'reports.view',
                'display_name' => 'Voir les rapports',
                'module' => 'analytics',
                'description' => 'Générer et consulter les rapports'
            ],

            // Messages
            [
                'name' => 'messages.view',
                'display_name' => 'Voir les messages',
                'module' => 'messages',
                'description' => 'Consulter la messagerie'
            ],
            [
                'name' => 'messages.manage',
                'display_name' => 'Gérer les messages',
                'module' => 'messages',
                'description' => 'Répondre et gérer les messages'
            ],

            // Paramètres
            [
                'name' => 'settings.view',
                'display_name' => 'Voir les paramètres',
                'module' => 'settings',
                'description' => 'Accès aux paramètres du système'
            ],
            [
                'name' => 'settings.edit',
                'display_name' => 'Modifier les paramètres',
                'module' => 'settings',
                'description' => 'Modifier les paramètres du système'
            ],
        ];

        $permissions = [];
        foreach ($permissionsData as $permissionData) {
            $permissions[$permissionData['name']] = AdminPermission::firstOrCreate(
                ['name' => $permissionData['name']], 
                $permissionData
            );
        }

        return $permissions;
    }

    /**
     * Créer les rôles de base
     */
    private function createRoles(): array
    {
        $rolesData = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrateur',
                'description' => 'Accès complet à toutes les fonctionnalités'
            ],
            [
                'name' => 'content_admin',
                'display_name' => 'Gestionnaire de Contenu',
                'description' => 'Gestion des articles et du contenu'
            ],
            [
                'name' => 'user_admin',
                'display_name' => 'Gestionnaire d\'Utilisateurs',
                'description' => 'Gestion des bacheliers et partenaires'
            ],
            [
                'name' => 'opportunity_admin',
                'display_name' => 'Gestionnaire d\'Opportunités',
                'description' => 'Gestion des opportunités, candidatures et dotations'
            ],
            [
                'name' => 'analytics_admin',
                'display_name' => 'Gestionnaire d\'Analytics',
                'description' => 'Accès aux rapports et analytics'
            ],
            [
                'name' => 'readonly_admin',
                'display_name' => 'Administrateur Lecture Seule',
                'description' => 'Accès en lecture seule aux données'
            ],
        ];

        $roles = [];
        foreach ($rolesData as $roleData) {
            $roles[$roleData['name']] = AdminRole::firstOrCreate(
                ['name' => $roleData['name']], 
                $roleData
            );
        }

        return $roles;
    }

    /**
     * Assigner les permissions aux rôles
     */
    private function assignPermissionsToRoles(array $roles, array $permissions): void
    {
        // Super Admin n'a pas besoin de permissions explicites (il a tout)
        
        // Content Admin
        $contentPermissions = [
            'dashboard.view',
            'articles.view',
            'articles.create',
            'articles.edit',
            'articles.delete',
            'articles.analytics',
        ];
        foreach ($contentPermissions as $permissionName) {
            $roles['content_admin']->assignPermission($permissions[$permissionName]);
        }

        // User Admin
        $userPermissions = [
            'dashboard.view',
            'users.bacheliers.view',
            'users.bacheliers.create',
            'users.bacheliers.edit',
            'users.bacheliers.delete',
            'users.bacheliers.validate',
            'users.partenaires.view',
            'users.partenaires.create',
            'users.partenaires.edit',
            'users.partenaires.delete',
            'users.partenaires.verify',
            'boursiers.view',
        ];
        foreach ($userPermissions as $permissionName) {
            $roles['user_admin']->assignPermission($permissions[$permissionName]);
        }

        // Opportunity Admin
        $opportunityPermissions = [
            'dashboard.view',
            'opportunities.view',
            'opportunities.create',
            'opportunities.edit',
            'opportunities.delete',
            'candidatures.view',
            'candidatures.manage',
            'dotations.view',
            'dotations.create',
            'dotations.edit',
            'dotations.delete',
            'inventaire.view',
            'inventaire.manage',
            'fournisseurs.view',
            'fournisseurs.manage',
        ];
        foreach ($opportunityPermissions as $permissionName) {
            $roles['opportunity_admin']->assignPermission($permissions[$permissionName]);
        }

        // Analytics Admin
        $analyticsPermissions = [
            'dashboard.view',
            'analytics.view',
            'reports.view',
            'articles.analytics',
            'users.bacheliers.view',
            'users.partenaires.view',
            'opportunities.view',
            'candidatures.view',
            'boursiers.view',
        ];
        foreach ($analyticsPermissions as $permissionName) {
            $roles['analytics_admin']->assignPermission($permissions[$permissionName]);
        }

        // Readonly Admin
        $readonlyPermissions = [
            'dashboard.view',
            'users.bacheliers.view',
            'users.partenaires.view',
            'opportunities.view',
            'candidatures.view',
            'dotations.view',
            'articles.view',
            'analytics.view',
            'reports.view',
            'boursiers.view',
        ];
        foreach ($readonlyPermissions as $permissionName) {
            $roles['readonly_admin']->assignPermission($permissions[$permissionName]);
        }
    }

    /**
     * Assigner le rôle super_admin aux admins existants
     */
    private function assignSuperAdminRole(AdminRole $superAdminRole): void
    {
        $adminUsers = User::where('role', 'admin')->get();
        
        foreach ($adminUsers as $admin) {
            $admin->assignAdminRole($superAdminRole);
        }
    }
} 