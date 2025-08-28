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
        Schema::table('tickets_pesee', function (Blueprint $table) {
            // Statut de validation ENE CI
            $table->enum('statut_ene', ['en_attente', 'valide_par_ene', 'rejete_par_ene'])->default('en_attente')->after('statut');
            
            // Informations de validation ENE CI
            $table->foreignId('valide_par_ene')->nullable()->constrained('users')->onDelete('set null')->after('validated_by');
            $table->timestamp('date_validation_ene')->nullable()->after('date_validation');
            
            // Commentaires de validation
            $table->text('commentaire_ene')->nullable()->after('date_validation_ene');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets_pesee', function (Blueprint $table) {
            $table->dropForeign(['valide_par_ene']);
            $table->dropColumn(['statut_ene', 'valide_par_ene', 'date_validation_ene', 'commentaire_ene']);
        });
    }
};
