<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture Globale {{ $facture->numero_facture }}</title>
    <style>
        @page {
            margin: 20mm;
            size: A4;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
            filter: brightness(0) invert(1);
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .card-body {
            padding: 40px 20px;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        .invoice-info h3 {
            font-size: 24px;
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        
        .invoice-info p {
            margin: 5px 0;
            color: #7f8c8d;
        }
        
        .client-info h6 {
            font-size: 16px;
            color: #2c3e50;
            margin: 0 0 15px 0;
        }
        
        .info-table {
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .info-table td {
            padding: 3px 0;
        }
        
        .info-table td:first-child {
            padding-right: 20px;
        }
        
        .summary-box {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin: 24px 0;
            text-align: center;
        }
        
        .summary-box h4 {
            margin: 0 0 20px 0;
            font-size: 18px;
            opacity: 0.9;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }
        
        .summary-item .label {
            font-size: 11px;
            opacity: 0.8;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0;
            font-size: 12px;
        }
        
        .items-table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .items-table td {
            border: 1px solid #dee2e6;
            padding: 12px 8px;
            vertical-align: top;
        }
        
        .items-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .totals-section {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 24px;
        }
        
        .sales-info {
            flex: 1;
        }
        
        .sales-info p {
            margin: 5px 0;
            color: #7f8c8d;
        }
        
        .totals-table {
            width: 300px;
        }
        
        .totals-table td {
            padding: 8px 0;
            font-size: 12px;
        }
        
        .totals-table td:first-child {
            padding-right: 64px;
            color: #7f8c8d;
        }
        
        .totals-table td:last-child {
            padding-right: 16px;
            text-align: right;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .totals-table .border-bottom {
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 16px;
        }
        
        .totals-table .pt-4 {
            padding-top: 16px;
        }
        
        .thank-you {
            text-align: center;
            margin: 64px 0;
            color: #7f8c8d;
            font-weight: bold;
        }
        
        .signatures {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 64px;
        }
        
        .signature-box {
            font-size: 12px;
            border-top: 1px solid #dee2e6;
            padding: 12px;
            display: inline-block;
            color: #7f8c8d;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-brouillon {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-validee {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-payee {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .centres-summary {
            margin-top: 24px;
        }
        
        .centre-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 16px 0;
        }
        
        .centre-box h5 {
            margin: 0 0 15px 0;
            color: #2c3e50;
            text-align: center;
            font-size: 16px;
        }
        
        .centre-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        
        .centre-item {
            text-align: center;
            padding: 12px;
            background-color: white;
            border-radius: 6px;
            border: 1px solid #ecf0f1;
        }
        
        .centre-item .label {
            font-size: 10px;
            color: #7f8c8d;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .centre-item .value {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <!-- PAGE 1 : RÉSUMÉ DE LA FACTURE GLOBALE -->
    
    <div class="card">
        <div class="card-header">
            <img src="{{ asset('wowdash/images/fph-ci.png') }}" alt="FPH-CI" class="img-fluid" style="max-height: 80px;">
            <div class="company-name">FPH-CI</div>
            <div class="company-subtitle">Fédération des Producteurs de Caoutchouc Côte d'Ivoire</div>
            <div class="company-subtitle">Système de Gestion des Livraisons de Graines</div>
        </div>
        
        <div class="card-body">
            <div class="invoice-header">
                <div class="invoice-info">
                    <h3>Facture Globale #{{ $facture->numero_facture }}</h3>
                    <p>Date d'émission: {{ $facture->date_emission->format('d/m/Y') }}</p>
                    <p>Date d'échéance: {{ $facture->date_echeance->format('d/m/Y') }}</p>
                    <p>Type: Facture Globale</p>
                    <p>Statut: 
                        @if($facture->statut == 'brouillon')
                            <span class="status-badge status-brouillon">Brouillon</span>
                        @elseif($facture->statut == 'validee')
                            <span class="status-badge status-validee">Validée</span>
                        @elseif($facture->statut == 'payee')
                            <span class="status-badge status-payee">Payée</span>
                        @endif
                    </p>
                </div>
                
                <div class="client-info">
                    <h6>Destinataire (ENE CI):</h6>
                    <table class="info-table">
                        <tbody>
                            <tr><td>Société</td><td>: ENE CI</td></tr>
                            <tr><td>Adresse</td><td>: Abidjan, Côte d'Ivoire</td></tr>
                            <tr><td>Type</td><td>: Acheteur de Graines</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Résumé de la Facture Globale -->
            <div class="summary-box">
                <h4>RÉSUMÉ DE LA FACTURE GLOBALE</h4>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="label">Livraisons</div>
                        <div class="value">{{ $facture->factureTicketsPesee->count() }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Poids Total</div>
                        <div class="value">{{ number_format($facture->factureTicketsPesee->sum(function($ft) { return $ft->ticketPesee->poids_net; }), 0) }} kg</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Montant HT</div>
                        <div class="value">{{ number_format($facture->montant_ht, 0) }} FCFA</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Montant TTC</div>
                        <div class="value">{{ number_format($facture->montant_ttc, 0) }} FCFA</div>
                    </div>
                </div>
            </div>
            
            <!-- Résumé Financier -->
            <div class="totals-section">
                <div class="sales-info">
                    <p><span style="color: #3498db; font-weight: bold;">Généré par:</span> {{ $facture->createdBy->name ?? 'Système' }}</p>
                    <p>Merci pour votre confiance</p>
                    @if($facture->notes)
                        <p><strong>Notes:</strong> {{ $facture->notes }}</p>
                    @endif
                </div>
                
                <div class="totals-table">
                    <table>
                        <tbody>
                            <tr>
                                <td>Montant HT:</td>
                                <td>{{ number_format($facture->montant_ht, 0) }} FCFA</td>
                            </tr>
                            <tr>
                                <td>Réduction:</td>
                                <td>0.00 FCFA</td>
                            </tr>
                            <tr>
                                <td class="border-bottom">TVA (0%):</td>
                                <td class="border-bottom">0.00 FCFA</td>
                            </tr>
                            <tr>
                                <td class="pt-4">
                                    <span style="color: #3498db; font-weight: bold;">Total:</span>
                                </td>
                                <td class="pt-4">{{ number_format($facture->montant_ttc, 0) }} FCFA</td>
                            </tr>
                            @if($facture->montant_paye > 0)
                                <tr>
                                    <td>Montant Payé:</td>
                                    <td style="color: #27ae60;">{{ number_format($facture->montant_paye, 0) }} FCFA</td>
                                </tr>
                                <tr>
                                    <td>Reste à Payer:</td>
                                    <td style="color: #e74c3c;">{{ number_format($facture->montant_ttc - $facture->montant_paye, 0) }} FCFA</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="thank-you">
                <p>Merci pour votre collaboration !</p>
            </div>

            <div class="signatures">
                <div class="signature-box">Signature de la Coopérative</div>
                <div class="signature-box">Signature FPH-CI</div>
            </div>
        </div>
    </div>
    
    <!-- PAGE 2 : DÉTAILS DES LIVRAISONS -->
    <div class="page-break">
        <div class="card">
            <div class="card-header">
                <img src="{{ asset('wowdash/images/fph-ci.png') }}" alt="FPH-CI" class="img-fluid" style="max-height: 80px;">
                <div class="company-name">FPH-CI</div>
                <div class="company-subtitle">Fédération des Producteurs de Caoutchouc Côte d'Ivoire</div>
                <div class="company-subtitle">Détails des Livraisons - {{ $facture->numero_facture }}</div>
            </div>
            
            <div class="card-body">
                <h4 style="color: #2c3e50; margin-bottom: 24px; text-align: center;">DÉTAILS DES LIVRAISONS INCLUSES</h4>
                
                <div class="table-responsive">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Ticket de Pesée</th>
                                <th>Date</th>
                                <th>Centre de Collecte</th>
                                <th>Poids Net (kg)</th>
                                <th>Prix Unitaire (FCFA)</th>
                                <th>Montant (FCFA)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($facture->factureTicketsPesee as $index => $factureTicket)
                                @php
                                    $ticket = $factureTicket->ticketPesee;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $ticket->numero_ticket }}</td>
                                    <td>{{ $ticket->date_entree->format('d/m/Y') }}</td>
                                    <td>{{ $ticket->connaissement->centreCollecte->nom ?? 'N/A' }}</td>
                                    <td style="text-align: right;">{{ number_format($ticket->poids_net, 2) }}</td>
                                    <td style="text-align: right;">{{ number_format($factureTicket->montant_ticket / $ticket->poids_net, 2) }}</td>
                                    <td style="text-align: right;">{{ number_format($factureTicket->montant_ticket, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Résumé par Centre de Collecte -->
                <div class="centres-summary">
                    <h4 style="color: #2c3e50; margin: 32px 0 24px 0; text-align: center;">RÉSUMÉ PAR CENTRE DE COLLECTE</h4>
                    
                    @php
                        $centresCollecte = $facture->factureTicketsPesee->groupBy(function($ft) {
                            return $ft->ticketPesee->connaissement->centreCollecte->nom ?? 'N/A';
                        });
                    @endphp
                    
                    @foreach($centresCollecte as $centreNom => $tickets)
                        <div class="centre-box">
                            <h5>{{ $centreNom }}</h5>
                            <div class="centre-grid">
                                <div class="centre-item">
                                    <div class="label">Livraisons</div>
                                    <div class="value">{{ $tickets->count() }}</div>
                                </div>
                                <div class="centre-item">
                                    <div class="label">Poids Total</div>
                                    <div class="value">{{ number_format($tickets->sum(function($ft) { return $ft->ticketPesee->poids_net; }), 0) }} kg</div>
                                </div>
                                <div class="centre-item">
                                    <div class="label">Montant Total</div>
                                    <div class="value">{{ number_format($tickets->sum('montant_ticket'), 0) }} FCFA</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="thank-you">
                    <p>Page 2 de 2 - Détails des livraisons</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 