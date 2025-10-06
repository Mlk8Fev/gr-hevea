<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Producteur;
use App\Models\Cooperative;
use App\Services\AuditService;

class AuditLogTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Génération de logs d\'audit de test...');
        
        // Récupérer des utilisateurs et objets existants
        $users = User::take(3)->get();
        $producteurs = Producteur::take(5)->get();
        $cooperatives = Cooperative::take(3)->get();
        
        if ($users->isEmpty() || $producteurs->isEmpty() || $cooperatives->isEmpty()) {
            $this->command->error('Veuillez d\'abord exécuter les seeders pour les utilisateurs, producteurs et coopératives.');
            return;
        }

        $actions = ['CREATE', 'UPDATE', 'DELETE', 'VALIDATE', 'CANCEL'];
        $modules = ['producteurs', 'cooperatives', 'connaissements', 'farmer-lists', 'tickets-pesee'];
        $objectTypes = ['Producteur', 'Cooperative', 'Connaissement', 'FarmerList', 'TicketPesee'];
        
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];
        $os = ['Windows', 'macOS', 'Linux'];
        $devices = ['Desktop', 'Mobile', 'Tablet'];
        
        $descriptions = [
            'CREATE' => 'Création d\'un nouvel enregistrement',
            'UPDATE' => 'Modification d\'un enregistrement existant',
            'DELETE' => 'Suppression d\'un enregistrement',
            'VALIDATE' => 'Validation d\'un enregistrement',
            'CANCEL' => 'Annulation d\'un enregistrement',
        ];

        $bar = $this->command->getOutput()->createProgressBar(50);
        $bar->start();

        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $action = $actions[array_rand($actions)];
            $module = $modules[array_rand($modules)];
            $objectType = $objectTypes[array_rand($objectTypes)];
            
            // Générer des données d'exemple
            $oldValues = null;
            $newValues = null;
            
            if ($action === 'UPDATE') {
                $oldValues = [
                    'nom' => 'Ancien nom',
                    'updated_at' => now()->subHours(2)->toDateTimeString(),
                ];
                $newValues = [
                    'nom' => 'Nouveau nom',
                    'updated_at' => now()->toDateTimeString(),
                ];
            } elseif ($action === 'CREATE') {
                $newValues = [
                    'nom' => 'Nouvel enregistrement',
                    'created_at' => now()->toDateTimeString(),
                ];
            }

            AuditLog::create([
                'action' => $action,
                'module' => $module,
                'object_type' => $objectType,
                'object_id' => rand(1, 100),
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'description' => $descriptions[$action] . ' dans le module ' . $module,
                'ip_address' => '192.168.1.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'session_id' => 'session_' . rand(100000, 999999),
                'request_method' => ['GET', 'POST', 'PUT', 'DELETE'][array_rand([0, 1, 2, 3])],
                'request_url' => 'http://localhost:8000/admin/' . $module,
                'request_data' => ['test' => 'data'],
                'browser' => $browsers[array_rand($browsers)],
                'os' => $os[array_rand($os)],
                'device' => $devices[array_rand($devices)],
                'is_successful' => rand(0, 10) < 9, // 90% de succès
                'error_message' => rand(0, 10) < 2 ? 'Erreur de validation' : null,
                'execution_time' => rand(50, 500),
                'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
            ]);
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('✅ 50 logs d\'audit de test créés avec succès !');
    }
}