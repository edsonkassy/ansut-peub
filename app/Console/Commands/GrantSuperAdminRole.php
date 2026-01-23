<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AdminRole;

class GrantSuperAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:grant-super-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign the super_admin role to all administrators';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting the process to grant super_admin role to all admins...');

        // 1. Find or create the super_admin role
        $superAdminRole = AdminRole::firstOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Administrateur',
                'description' => 'Accès complet à toutes les fonctionnalités.',
                'is_active' => true
            ]
        );

        if ($superAdminRole->wasRecentlyCreated) {
            $this->info('The "super_admin" role was created.');
        } else {
            $this->info('The "super_admin" role already exists.');
        }

        // Find the first admin user to act as the assigner
        $assigner = User::where('role', 'admin')->orderBy('id', 'asc')->first();
        $assignerId = $assigner ? $assigner->id : null;

        // 2. Find all admin users
        $adminUsers = User::where('role', 'admin')->get();

        if ($adminUsers->isEmpty()) {
            $this->warn('No admin users found.');
            return 0;
        }

        $this->info("Found {$adminUsers->count()} admin user(s).");

        // 3. Assign the role to each admin user
        $progressBar = $this->output->createProgressBar($adminUsers->count());
        $progressBar->start();

        foreach ($adminUsers as $admin) {
            if (!$admin->hasAdminRole('super_admin')) {
                $admin->adminRoles()->attach($superAdminRole->id, [
                    'assigned_by' => $assignerId
                ]);
                $this->line(" Assigned super_admin to {$admin->email}.");
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->info("\nProcess completed successfully. All admins now have the super_admin role.");

        return 0;
    }
} 