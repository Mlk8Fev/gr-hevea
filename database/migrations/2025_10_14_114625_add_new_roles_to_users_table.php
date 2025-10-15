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
        Schema::table('users', function (Blueprint $table) {
            // Modifier la colonne role pour accepter les nouveaux rôles
            $table->enum('role', [
                'superadmin',
                'admin', 
                'manager',
                'user',
                'agc',
                'cs',      // Chef Secteur
                'ac',      // Assistante Comptable
                'rt',      // Responsable Traçabilité
                'rd',      // Responsable Durabilité
                'comp',    // Comptable Siège
                'ctu',     // Contrôleur Usine
                'rcoop'    // Responsable Coopérative
            ])->default('user')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revenir aux anciens rôles
            $table->enum('role', [
                'superadmin',
                'admin', 
                'manager',
                'user',
                'agc'
            ])->default('user')->change();
        });
    }
};
