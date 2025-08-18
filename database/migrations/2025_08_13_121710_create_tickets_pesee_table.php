<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets_pesee', function (Blueprint $table) {
            $table->id();
            
            // Référence au connaissement
            $table->foreignId('connaissement_id')->constrained('connaissements')->onDelete('cascade');
            
            // Informations du ticket
            $table->string('numero_ticket')->unique(); // N°Ticket Fourn
            $table->string('campagne'); // 2023-2024
            $table->string('client'); // COTRAF SA
            $table->string('fournisseur'); // FPH-CI / COOPERATIVE
            $table->string('numero_bl'); // N° BL (connaissement)
            $table->string('origine'); // DALOA
            $table->string('destination'); // PK 24
            $table->string('produit')->default('GRAINE DE HEVEA');
            
            // Informations transport
            $table->string('numero_camion'); // N°Camion
            $table->string('transporteur'); // TR0005 - PARTICULIER
            $table->string('chauffeur'); // SORO SOULEYMANE
            $table->string('equipe_chargement')->nullable();
            $table->string('equipe_dechargement')->nullable();
            
            // Poids et mesures
            $table->decimal('poids_entree', 10, 2); // Poids Entrée (Kg)
            $table->decimal('poids_sortie', 10, 2); // Poids Sortie (Kg)
            $table->decimal('poids_net', 10, 2); // Poids Net (Kg) - calculé
            $table->integer('nombre_sacs_bidons_cartons'); // Nbre Saos/Bidons/Cartons
            
            // Analyse qualité
            $table->decimal('poids_100_graines', 10, 2)->nullable(); // Poids de 100 graines
            $table->decimal('gp', 10, 3)->nullable(); // GP: 11,74
            $table->decimal('ga', 10, 3)->nullable(); // GA: 1,421
            $table->decimal('me', 10, 3)->nullable(); // ME: 0,06%
            $table->decimal('taux_humidite', 5, 2)->nullable(); // Humidité 18,65%
            $table->decimal('taux_impuretes', 5, 2)->nullable(); // Impuretés: 13,22%
            
            // Dates et heures
            $table->date('date_entree');
            $table->time('heure_entree');
            $table->date('date_sortie');
            $table->time('heure_sortie');
            
            // Informations du peseur
            $table->string('nom_peseur'); // Nom & Prénom du peseur
            $table->string('signature')->nullable();
            
            // Statut et métadonnées
            $table->enum('statut', ['en_attente', 'valide', 'archive'])->default('en_attente');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_validation')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets_pesee');
    }
};
