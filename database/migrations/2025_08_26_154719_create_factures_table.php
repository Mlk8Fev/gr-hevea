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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            
            // Informations de base
            $table->string('numero_facture')->unique(); // FACT-2025-001
            $table->enum('type', ['individuelle', 'globale']);
            $table->enum('statut', ['brouillon', 'validee', 'payee', 'annulee'])->default('brouillon');
            
            // Informations coopérative
            $table->foreignId('cooperative_id')->constrained('cooperatives')->onDelete('cascade');
            
            // Informations financières
            $table->decimal('montant_ht', 15, 2); // Montant hors taxes
            $table->decimal('montant_tva', 15, 2)->default(0); // TVA si applicable
            $table->decimal('montant_ttc', 15, 2); // Montant toutes taxes comprises
            $table->decimal('montant_paye', 15, 2)->default(0); // Montant déjà payé
            
            // Dates importantes
            $table->date('date_emission');
            $table->date('date_echeance');
            $table->date('date_paiement')->nullable();
            
            // Informations de facturation
            $table->text('conditions_paiement')->nullable();
            $table->text('notes')->nullable();
            $table->string('devise', 3)->default('XOF'); // FCFA
            
            // Métadonnées
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('validee_par')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_validation')->nullable();
            $table->timestamps();
            
            // Index pour performance
            $table->index(['cooperative_id', 'statut']);
            $table->index(['date_emission', 'date_echeance']);
            $table->index('numero_facture');
        });
        
        // Table pivot pour lier factures et tickets de pesée
        Schema::create('facture_ticket_pesee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facture_id')->constrained('factures')->onDelete('cascade');
            $table->foreignId('ticket_pesee_id')->constrained('tickets_pesee')->onDelete('cascade');
            $table->decimal('montant_ticket', 15, 2); // Montant de ce ticket dans la facture
            $table->timestamps();
            
            // Index et contraintes
            $table->unique(['facture_id', 'ticket_pesee_id']);
            $table->index(['facture_id', 'ticket_pesee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facture_ticket_pesee');
        Schema::dropIfExists('factures');
    }
};
