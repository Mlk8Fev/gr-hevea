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
        Schema::create('livraison_counters', function (Blueprint $table) {
            $table->id();
            $table->string('secteur_code')->unique()->comment('Code du secteur (ex: GUI12, AB01)');
            $table->integer('dernier_numero')->default(0)->comment('Dernier numéro de livraison utilisé pour ce secteur');
            $table->timestamps();
            
            $table->index('secteur_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livraison_counters');
    }
};
