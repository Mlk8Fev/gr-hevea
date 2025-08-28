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
        Schema::create('matrice_prix', function (Blueprint $table) {
            $table->id();
            $table->string('annee', 4); // 2025, 2026, etc.
            $table->decimal('prix_bord_champs', 8, 2); // 72
            $table->decimal('transport_base', 8, 2); // 10
            $table->decimal('cooperatives', 8, 2); // 4
            $table->decimal('stockage', 8, 2); // 5
            $table->decimal('chargeur_dechargeur', 8, 2); // 2
            $table->decimal('soutien_sechoirs', 8, 2); // 1
            $table->decimal('prodev', 8, 2); // 10
            $table->decimal('sac', 8, 2); // 4
            $table->decimal('sechage', 8, 2); // 3
            $table->decimal('certification', 8, 2); // 3
            $table->decimal('fphci', 8, 2); // 2
            $table->decimal('moyenne_transfert', 8, 2); // 22
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->unique('annee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrice_prix');
    }
};
