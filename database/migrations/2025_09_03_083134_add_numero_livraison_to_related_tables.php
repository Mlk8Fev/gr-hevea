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
        // Modifier la table tickets_pesee
        Schema::table('tickets_pesee', function (Blueprint $table) {
            $table->string('numero_livraison')->after('id')->comment('Numéro de livraison lié au connaissement');
            $table->index('numero_livraison');
        });

        // Modifier la table factures
        Schema::table('factures', function (Blueprint $table) {
            $table->string('numero_livraison')->after('id')->comment('Numéro de livraison lié au connaissement');
            $table->index('numero_livraison');
        });

        // Modifier la table paiements (si elle existe)
        if (Schema::hasTable('paiements')) {
            Schema::table('paiements', function (Blueprint $table) {
                $table->string('numero_livraison')->after('id')->comment('Numéro de livraison lié au connaissement');
                $table->index('numero_livraison');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets_pesee', function (Blueprint $table) {
            $table->dropColumn('numero_livraison');
        });

        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn('numero_livraison');
        });

        if (Schema::hasTable('paiements')) {
            Schema::table('paiements', function (Blueprint $table) {
                $table->dropColumn('numero_livraison');
            });
        }
    }
};
