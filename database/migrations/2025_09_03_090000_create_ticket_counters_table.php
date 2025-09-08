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
        Schema::create('ticket_counters', function (Blueprint $table) {
            $table->id();
            $table->string('numero_livraison')->comment('Numéro de livraison associé');
            $table->integer('dernier_numero')->default(0)->comment('Dernier numéro de ticket généré pour cette livraison');
            $table->timestamps();
            
            // Index et contraintes
            $table->unique('numero_livraison');
            $table->index('numero_livraison');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_counters');
    }
}; 