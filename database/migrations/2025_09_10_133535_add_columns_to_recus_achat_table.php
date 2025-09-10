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
        Schema::table('recus_achat', function (Blueprint $table) {
            $table->string('numero_recu')->unique();
            $table->unsignedBigInteger('connaissement_id');
            $table->unsignedBigInteger('producteur_id');
            $table->unsignedBigInteger('farmer_list_id');
            $table->string('nom_producteur');
            $table->string('prenom_producteur');
            $table->string('telephone_producteur');
            $table->string('code_fphci');
            $table->string('secteur_fphci');
            $table->string('centre_collecte');
            $table->decimal('quantite_livree', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 15, 2);
            $table->text('signature_acheteur')->nullable();
            $table->text('signature_producteur')->nullable();
            $table->datetime('date_creation');
            $table->unsignedBigInteger('created_by');

            // Clés étrangères
            $table->foreign('connaissement_id')->references('id')->on('connaissements');
            $table->foreign('producteur_id')->references('id')->on('producteurs');
            $table->foreign('farmer_list_id')->references('id')->on('farmer_lists');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recus_achat', function (Blueprint $table) {
            $table->dropForeign(['connaissement_id']);
            $table->dropForeign(['producteur_id']);
            $table->dropForeign(['farmer_list_id']);
            $table->dropForeign(['created_by']);
            
            $table->dropColumn([
                'numero_recu',
                'connaissement_id',
                'producteur_id',
                'farmer_list_id',
                'nom_producteur',
                'prenom_producteur',
                'telephone_producteur',
                'code_fphci',
                'secteur_fphci',
                'centre_collecte',
                'quantite_livree',
                'prix_unitaire',
                'montant_total',
                'signature_acheteur',
                'signature_producteur',
                'date_creation',
                'created_by'
            ]);
        });
    }
};
