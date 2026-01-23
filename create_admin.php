<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\AdminRole;

$email = $argv[1] ?? 'admin@peub.ansut.ci';

echo "Création de l'administrateur avec l'email: {$email}\n";

// Vérifier si l'utilisateur existe déjà
$existingUser = User::where('email', $email)->first();

if ($existingUser) {
    if ($existingUser->role === 'admin') {
        echo "✅ Un administrateur avec l'email {$email} existe déjà (ID: {$existingUser->id}).\n";
        $admin = $existingUser;
    } else {
        echo "❌ Un utilisateur avec l'email {$email} existe déjà mais n'est pas admin.\n";
        exit(1);
    }
} else {
    // Créer le nouvel admin
    $admin = User::create([
        'email' => $email,
        'role' => 'admin',
        'status' => 'active',
        'email_verified_at' => now(),
    ]);

    echo "✅ Administrateur créé avec succès !\n";
    echo "   ID: {$admin->id}\n";
    echo "   Email: {$admin->email}\n";
    echo "   Statut: {$admin->status}\n";
}

// Créer ou récupérer le rôle super_admin
$superAdminRole = AdminRole::firstOrCreate(
    ['name' => 'super_admin'],
    [
        'display_name' => 'Super Administrateur',
        'description' => 'Accès complet à toutes les fonctionnalités',
        'is_active' => true,
    ]
);

if ($superAdminRole->wasRecentlyCreated) {
    echo "✅ Rôle 'super_admin' créé.\n";
}

// Assigner le rôle si pas déjà assigné
if (!$admin->hasAdminRole('super_admin')) {
    $admin->assignAdminRole($superAdminRole);
    echo "✅ Rôle 'super_admin' assigné avec succès !\n";
} else {
    echo "ℹ️  L'administrateur a déjà le rôle 'super_admin'.\n";
}

echo "\n✅ Processus terminé avec succès !\n";
echo "   Vous pouvez maintenant vous connecter avec l'email: {$email}\n";

