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
        Schema::create('connaissements', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->enum('statut', ['programme', 'valide'])->default('programme');
            
            // Relations
            $table->foreignId('cooperative_id')->constrained('cooperatives')->onDelete('cascade');
            $table->foreignId('centre_collecte_id')->constrained('centres_collecte')->onDelete('cascade');
            
            // Informations de départ
            $table->string('lieu_depart');
            $table->string('sous_prefecture');
            
            // Informations transporteur
            $table->string('transporteur_nom');
            $table->string('transporteur_immatriculation');
            $table->string('chauffeur_nom');
            
            // Destinataire
            $table->enum('destinataire_type', ['entrepot', 'cooperative', 'acheteur']);
            $table->unsignedBigInteger('destinataire_id')->nullable();
            
            // Marchandise
            $table->integer('nombre_sacs');
            $table->decimal('poids_brut_estime', 10, 2);
            $table->decimal('poids_net', 10, 2)->nullable();
            
            // Validation
            $table->string('signature_cooperative')->nullable();
            $table->string('signature_fphci')->nullable();
            $table->timestamp('date_validation')->nullable();
            
            // Création/Validation
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connaissements');
    }
};
