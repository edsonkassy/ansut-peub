<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Table des rôles admin
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // super_admin, content_admin, etc.
            $table->string('display_name'); // Super Administrateur, Gestionnaire de Contenu
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Table des permissions
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // dashboard.view, users.bacheliers.view, etc.
            $table->string('display_name'); // Voir le tableau de bord
            $table->string('module'); // dashboard, users, opportunities, etc.
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Table pivot rôles-permissions
        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_role_id')->constrained('admin_roles')->onDelete('cascade');
            $table->foreignId('admin_permission_id')->constrained('admin_permissions')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['admin_role_id', 'admin_permission_id']);
        });

        // Table pivot utilisateurs-rôles
        Schema::create('admin_user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_role_id')->constrained('admin_roles')->onDelete('cascade');
            $table->timestamp('assigned_at')->useCurrent();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['user_id', 'admin_role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user_roles');
        Schema::dropIfExists('admin_role_permissions');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admin_roles');
    }
}; 