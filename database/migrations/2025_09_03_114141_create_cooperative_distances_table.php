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
        Schema::create('cooperative_distances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperative_id')->constrained('cooperatives')->onDelete('cascade');
            $table->foreignId('centre_collecte_id')->constrained('centres_collecte')->onDelete('cascade');
            $table->decimal('distance_km', 8, 2)->comment('Distance en kilomètres');
            $table->timestamps();
            
            // Index et contraintes
            $table->unique(['cooperative_id', 'centre_collecte_id'], 'unique_coop_centre');
            $table->index(['cooperative_id', 'centre_collecte_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperative_distances');
    }
};
