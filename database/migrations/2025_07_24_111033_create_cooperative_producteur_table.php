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
        Schema::create('cooperative_producteur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producteur_id')->constrained();
            $table->foreignId('cooperative_id')->constrained();
            $table->unique(['producteur_id', 'cooperative_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperative_producteur');
    }
};
