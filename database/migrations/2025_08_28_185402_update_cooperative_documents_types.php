<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les types de documents existants avec les nouvelles clés
        DB::table('cooperative_documents')
            ->where('type', 'DFE')
            ->update(['type' => 'dfe']);
            
        DB::table('cooperative_documents')
            ->where('type', 'Statuts')
            ->update(['type' => 'statuts']);
            
        DB::table('cooperative_documents')
            ->where('type', 'Registre de commerce')
            ->update(['type' => 'registre_commerce']);
            
        DB::table('cooperative_documents')
            ->where('type', 'Délégation de pouvoir')
            ->update(['type' => 'delegation_pouvoir']);
            
        DB::table('cooperative_documents')
            ->where('type', 'Journal officiel')
            ->update(['type' => 'journal_officiel']);
            
        DB::table('cooperative_documents')
            ->where('type', 'Contrat de bail')
            ->update(['type' => 'contrat_bail']);
            
        DB::table('cooperative_documents')
            ->where('type', 'Protocole FPH-CI')
            ->update(['type' => 'protocole_fph_ci']);
            
        DB::table('cooperative_documents')
            ->where('type', 'Fiche enquête')
            ->update(['type' => 'fiche_enquete']);
            
        DB::table('cooperative_documents')
            ->where('type', 'Fiche étalonnage')
            ->update(['type' => 'fiche_etalonnage']);
            
        DB::table('cooperative_documents')
            ->where('type', 'Liste formation')
            ->update(['type' => 'liste_formation']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer les anciens types de documents
        DB::table('cooperative_documents')
            ->where('type', 'dfe')
            ->update(['type' => 'DFE']);
            
        DB::table('cooperative_documents')
            ->where('type', 'statuts')
            ->update(['type' => 'Statuts']);
            
        DB::table('cooperative_documents')
            ->where('type', 'registre_commerce')
            ->update(['type' => 'Registre de commerce']);
            
        DB::table('cooperative_documents')
            ->where('type', 'delegation_pouvoir')
            ->update(['type' => 'Délégation de pouvoir']);
            
        DB::table('cooperative_documents')
            ->where('type', 'journal_officiel')
            ->update(['type' => 'Journal officiel']);
            
        DB::table('cooperative_documents')
            ->where('type', 'contrat_bail')
            ->update(['type' => 'Contrat de bail']);
            
        DB::table('cooperative_documents')
            ->where('type', 'protocole_fph_ci')
            ->update(['type' => 'Protocole FPH-CI']);
            
        DB::table('cooperative_documents')
            ->where('type', 'fiche_enquete')
            ->update(['type' => 'Fiche enquête']);
            
        DB::table('cooperative_documents')
            ->where('type', 'fiche_etalonnage')
            ->update(['type' => 'Fiche étalonnage']);
            
        DB::table('cooperative_documents')
            ->where('type', 'liste_formation')
            ->update(['type' => 'Liste formation']);
    }
};
