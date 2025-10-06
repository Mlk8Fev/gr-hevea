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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            
            // Informations de base
            $table->string('action'); // CREATE, UPDATE, DELETE, LOGIN, LOGOUT, VALIDATE, CANCEL, EXPORT, IMPORT
            $table->string('module'); // connaissements, farmer-lists, tickets-pesee, factures, etc.
            $table->string('object_type'); // Classe du modèle (Connaissement, FarmerList, etc.)
            $table->unsignedBigInteger('object_id')->nullable(); // ID de l'objet modifié
            
            // Utilisateur
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('user_name')->nullable(); // Nom de l'utilisateur au moment de l'action
            $table->string('user_role')->nullable(); // Rôle de l'utilisateur au moment de l'action
            
            // Données de l'objet
            $table->json('old_values')->nullable(); // Valeurs avant modification
            $table->json('new_values')->nullable(); // Valeurs après modification
            $table->text('description')->nullable(); // Description de l'action
            
            // Informations techniques
            $table->string('ip_address', 45)->nullable(); // IPv4 ou IPv6
            $table->text('user_agent')->nullable(); // User-Agent du navigateur
            $table->string('session_id')->nullable(); // ID de session
            $table->string('request_method', 10)->nullable(); // GET, POST, PUT, DELETE
            $table->text('request_url')->nullable(); // URL de la requête
            $table->json('request_data')->nullable(); // Données de la requête (sans mots de passe)
            
            // Métadonnées
            $table->string('browser')->nullable(); // Nom du navigateur
            $table->string('os')->nullable(); // Système d'exploitation
            $table->string('device')->nullable(); // Type d'appareil
            $table->decimal('latitude', 10, 8)->nullable(); // Géolocalisation
            $table->decimal('longitude', 11, 8)->nullable(); // Géolocalisation
            $table->string('country')->nullable(); // Pays
            $table->string('city')->nullable(); // Ville
            
            // Statut et validation
            $table->boolean('is_successful')->default(true); // Action réussie ou échouée
            $table->text('error_message')->nullable(); // Message d'erreur si échec
            $table->integer('execution_time')->nullable(); // Temps d'exécution en millisecondes
            
            // Index pour les performances
            $table->index(['action', 'module']);
            $table->index(['user_id', 'created_at']);
            $table->index(['object_type', 'object_id']);
            $table->index(['created_at']);
            $table->index(['ip_address']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
