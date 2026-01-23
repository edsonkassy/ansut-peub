<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // La table notifications existe déjà, on passe

        // Table pour les notifications système spécifiques
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'otp', 'candidature_status', 'new_resource', 'new_message', 'forum_reply', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Données additionnelles (IDs, liens, etc.)
            $table->boolean('read')->default(false);
            $table->boolean('email_sent')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read']);
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
        Schema::dropIfExists('notifications');
    }
};