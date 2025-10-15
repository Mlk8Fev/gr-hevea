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
        // Vérifier si les fonctions n'existent pas déjà avant d'insérer
        $existingFonctions = DB::table('fonctions')->pluck('nom')->toArray();
        
        $newFonctions = [
            [
                'nom' => 'Chef Secteur',
                'description' => 'Responsable de la gestion et supervision d\'un secteur',
                'peut_gerer_cooperative' => 0,
                'niveau_acces' => 'manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Assistante Comptable',
                'description' => 'Support comptabilité et gestion administrative',
                'peut_gerer_cooperative' => 0,
                'niveau_acces' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Responsable Traçabilité',
                'description' => 'Gestion de la traçabilité des produits',
                'peut_gerer_cooperative' => 0,
                'niveau_acces' => 'manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Responsable Durabilité',
                'description' => 'Gestion des programmes de durabilité',
                'peut_gerer_cooperative' => 0,
                'niveau_acces' => 'manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Comptable Siège',
                'description' => 'Gestion comptable au niveau du siège',
                'peut_gerer_cooperative' => 0,
                'niveau_acces' => 'manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Contrôleur Usine',
                'description' => 'Contrôle qualité et opérations en usine',
                'peut_gerer_cooperative' => 0,
                'niveau_acces' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Insérer seulement les fonctions qui n'existent pas déjà
        foreach ($newFonctions as $fonction) {
            if (!in_array($fonction['nom'], $existingFonctions)) {
                DB::table('fonctions')->insert($fonction);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('fonctions')->whereIn('nom', [
            'Chef Secteur',
            'Assistante Comptable',
            'Responsable Traçabilité',
            'Responsable Durabilité',
            'Comptable Siège',
            'Contrôleur Usine',
        ])->delete();
    }
};
