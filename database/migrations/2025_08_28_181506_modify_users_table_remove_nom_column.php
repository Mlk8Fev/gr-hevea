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
            // Supprimer la colonne nom
            $table->dropColumn('nom');
            
            // Modifier la colonne name pour qu'elle soit plus descriptive
            $table->string('name')->comment('Nom complet (prénom + nom)')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Recréer la colonne nom
            $table->string('nom')->after('name');
            
            // Remettre la colonne name comme avant
            $table->string('name')->comment('')->change();
        });
    }
};
