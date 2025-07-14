<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Secteur;

class SecteurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $secteurs = [
            ['code' => 'AB01', 'nom' => 'Abengourou'],
            ['code' => 'AB02', 'nom' => 'Aboisso'],
            ['code' => 'AD04', 'nom' => 'Adzope'],
            ['code' => 'AN03', 'nom' => 'Anguededou'],
            ['code' => 'BE04', 'nom' => 'Bettie'],
            ['code' => 'BO05', 'nom' => 'Bondoukou'],
            ['code' => 'BO08', 'nom' => 'Bongouanou'],
            ['code' => 'BO06', 'nom' => 'Bonoua'],
            ['code' => 'DA07', 'nom' => 'Dabou'],
            ['code' => 'DA13', 'nom' => 'Daloa'],
            ['code' => 'DA08', 'nom' => 'Daoukro'],
            ['code' => 'DI09', 'nom' => 'Divo'],
            ['code' => 'GA09', 'nom' => 'Gagnoa'],
            ['code' => 'GB10', 'nom' => 'Grand Bereby'],
            ['code' => 'GL11', 'nom' => 'Grand Lahou'],
            ['code' => 'GU12', 'nom' => 'Guiglo'],
            ['code' => 'IS13', 'nom' => 'Issia'],
            ['code' => 'MA14', 'nom' => 'Man'],
            ['code' => 'ME17', 'nom' => 'Meagui'],
            ['code' => 'SP15', 'nom' => 'San Pedro'],
            ['code' => 'ST16', 'nom' => 'Sikensi-Tiassale'],
            ['code' => 'SO17', 'nom' => 'Soubre'],
            ['code' => 'TA10', 'nom' => 'Tabou'],
            ['code' => 'TO12', 'nom' => 'Toulepleu'],
            ['code' => 'YA18', 'nom' => 'Yamoussoukro'],
            ['code' => 'ADMIN', 'nom' => 'ADMIN'],
            ['code' => 'SS19', 'nom' => 'Sassandra'],
        ];

        foreach ($secteurs as $secteur) {
            Secteur::create($secteur);
        }
    }
}
