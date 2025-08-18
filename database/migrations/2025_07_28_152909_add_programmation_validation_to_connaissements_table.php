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
        Schema::table('connaissements', function (Blueprint $table) {
            // Programmation
            $table->date('date_reception')->nullable();
            $table->time('heure_arrivee')->nullable();
            $table->foreignId('programmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_programmation')->nullable();
            
            // Validation
            $table->decimal('poids_net_reel', 10, 2)->nullable();
            $table->timestamp('date_validation_reelle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('connaissements', function (Blueprint $table) {
            $table->dropForeign(['programmed_by']);
            $table->dropColumn([
                'date_reception',
                'heure_arrivee', 
                'programmed_by',
                'date_programmation',
                'poids_net_reel',
                'date_validation_reelle'
            ]);
        });
    }
};
