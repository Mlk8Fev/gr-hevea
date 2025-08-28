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
            // Ajouter le champ username
            $table->string('username')->unique()->after('id');
            
            // Supprimer le champ prenom
            $table->dropColumn('prenom');
            
            // Ajouter les nouveaux champs
            $table->foreignId('fonction_id')->after('nom')->constrained('fonctions');
            $table->foreignId('cooperative_id')->nullable()->after('fonction_id')->constrained('cooperatives');
            $table->foreignId('centre_collecte_id')->nullable()->after('cooperative_id')->constrained('centres_collecte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer les nouveaux champs
            $table->dropForeign(['fonction_id', 'cooperative_id', 'centre_collecte_id']);
            $table->dropColumn(['username', 'fonction_id', 'cooperative_id', 'centre_collecte_id']);
            
            // Remettre le champ prenom
            $table->string('prenom')->after('nom');
        });
    }
};
