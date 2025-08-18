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
        Schema::create('centres_collecte', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // CC1, CC2, etc.
            $table->string('nom')->unique();
            $table->text('adresse');
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centres_collecte');
    }
};
