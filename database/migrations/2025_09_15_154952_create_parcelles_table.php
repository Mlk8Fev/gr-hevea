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
        Schema::create('parcelles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producteur_id')->constrained('producteurs')->onDelete('cascade');
            $table->string('nom_parcelle')->nullable(); // Nom optionnel de la parcelle
            $table->decimal('latitude', 10, 8); // Coordonnées GPS précises
            $table->decimal('longitude', 11, 8);
            $table->decimal('superficie', 8, 2); // Superficie en hectares (max 999999.99)
            $table->integer('ordre')->default(1); // Ordre d'affichage (1-10)
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index('producteur_id');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcelles');
    }
};
