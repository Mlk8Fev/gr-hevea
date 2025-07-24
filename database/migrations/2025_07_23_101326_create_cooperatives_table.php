<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperatives', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // AB01-COOP1
            $table->string('nom');
            $table->foreignId('secteur_id')->constrained('secteurs')->onDelete('cascade');
            $table->string('president');
            $table->string('contact', 10);
            $table->string('sigle')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->float('kilometrage')->nullable();
            $table->string('compte_bancaire', 12);
            $table->string('code_banque', 5);
            $table->string('code_guichet', 5);
            $table->string('nom_cooperative_banque'); // nom de la coop à la banque
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperatives');
    }
};
