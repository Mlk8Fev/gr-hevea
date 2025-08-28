<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facture;
use App\Models\TicketPesee;
use App\Models\Cooperative;
use App\Models\User;
use Carbon\Carbon;

class FactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer une coopérative existante
        $cooperative = Cooperative::first();
        if (!$cooperative) {
            $this->command->info('Aucune coopérative trouvée. Créez d\'abord des coopératives.');
            return;
        }

        // Récupérer un utilisateur existant
        $user = User::first();
        if (!$user) {
            $this->command->info('Aucun utilisateur trouvé. Créez d\'abord des utilisateurs.');
            return;
        }

        // Récupérer des tickets de pesée validés par ENE CI
        $ticketsEligibles = TicketPesee::where('statut', 'valide')
            ->where('statut_ene', 'valide_par_ene')
            ->whereDoesntHave('factures')
            ->take(5)
            ->get();

        if ($ticketsEligibles->isEmpty()) {
            $this->command->info('Aucun ticket éligible trouvé. Validez d\'abord des tickets par ENE CI.');
            return;
        }

        // Créer une facture individuelle
        $factureIndividuelle = Facture::create([
            'numero_facture' => 'FACT1',
            'type' => 'individuelle',
            'statut' => 'brouillon',
            'cooperative_id' => $cooperative->id,
            'montant_ht' => 50000,
            'montant_tva' => 0,
            'montant_ttc' => 50000,
            'montant_paye' => 0,
            'date_emission' => Carbon::now(),
            'date_echeance' => Carbon::now()->addDays(30),
            'conditions_paiement' => 'Paiement à 30 jours par virement bancaire',
            'notes' => 'Facture de test - Ticket individuel',
            'devise' => 'XOF',
            'created_by' => $user->id
        ]);

        // Lier le premier ticket à la facture individuelle
        if ($ticketsEligibles->count() > 0) {
            $ticket = $ticketsEligibles->first();
            $factureIndividuelle->ticketsPesee()->attach($ticket->id, [
                'montant_ticket' => 50000
            ]);
        }

        // Créer une facture globale
        $factureGlobale = Facture::create([
            'numero_facture' => 'FACT2',
            'type' => 'globale',
            'statut' => 'validee',
            'cooperative_id' => $cooperative->id,
            'montant_ht' => 150000,
            'montant_tva' => 0,
            'montant_ttc' => 150000,
            'montant_paye' => 0,
            'date_emission' => Carbon::now()->subDays(5),
            'date_echeance' => Carbon::now()->addDays(25),
            'conditions_paiement' => 'Paiement à 30 jours par virement bancaire',
            'notes' => 'Facture de test - Tickets groupés',
            'devise' => 'XOF',
            'created_by' => $user->id,
            'validee_par' => $user->id,
            'date_validation' => Carbon::now()->subDays(3)
        ]);

        // Lier plusieurs tickets à la facture globale
        $ticketsGlobaux = $ticketsEligibles->take(3);
        foreach ($ticketsGlobaux as $index => $ticket) {
            $montant = 50000 + ($index * 10000); // 50000, 60000, 70000
            $factureGlobale->ticketsPesee()->attach($ticket->id, [
                'montant_ticket' => $montant
            ]);
        }

        // Créer une facture payée
        $facturePayee = Facture::create([
            'numero_facture' => 'FACT3',
            'type' => 'individuelle',
            'statut' => 'payee',
            'cooperative_id' => $cooperative->id,
            'montant_ht' => 75000,
            'montant_tva' => 0,
            'montant_ttc' => 75000,
            'montant_paye' => 75000,
            'date_emission' => Carbon::now()->subDays(15),
            'date_echeance' => Carbon::now()->subDays(5),
            'conditions_paiement' => 'Paiement à 30 jours par virement bancaire',
            'notes' => 'Facture de test - Payée',
            'devise' => 'XOF',
            'created_by' => $user->id,
            'validee_par' => $user->id,
            'date_validation' => Carbon::now()->subDays(12),
            'date_paiement' => Carbon::now()->subDays(5)
        ]);

        // Lier un ticket à la facture payée
        if ($ticketsEligibles->count() > 3) {
            $ticket = $ticketsEligibles[3];
            $facturePayee->ticketsPesee()->attach($ticket->id, [
                'montant_ticket' => 75000
            ]);
        }

        $this->command->info('Factures de test créées avec succès !');
        $this->command->info('- 1 facture individuelle en brouillon');
        $this->command->info('- 1 facture globale validée');
        $this->command->info('- 1 facture individuelle payée');
    }
}
