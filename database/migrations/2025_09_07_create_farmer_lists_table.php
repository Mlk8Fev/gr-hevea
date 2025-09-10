<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('farmer_lists', function (Blueprint $table) {
            $table->id();
            $table->string('numero_livraison')->index();
            $table->foreignId('connaissement_id')->constrained()->onDelete('cascade');
            $table->foreignId('producteur_id')->constrained()->onDelete('cascade');
            $table->decimal('quantite_livree', 10, 2)->comment('Quantité livrée par ce producteur en kg');
            $table->boolean('geolocalisation_precise')->default(false)->comment('Précision sur la géolocalisation');
            $table->date('date_livraison')->comment('Date de livraison du camion');
            $table->string('contact_producteur')->nullable()->comment('Contact du producteur');
            $table->text('notes')->nullable()->comment('Notes additionnelles');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['connaissement_id', 'producteur_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('farmer_lists');
    }
}; 