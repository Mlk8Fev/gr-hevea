<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $facture->numero_facture }}</title>
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
            max-height: 80px;
            max-width: 200px;
            filter: brightness(1) contrast(1.2);
            -webkit-filter: brightness(1) contrast(1.2);
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
    </style>
</head>
<body>
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
                    <h3>Facture #{{ $facture->numero_facture }}</h3>
                    <p>Date d'émission: {{ $facture->date_emission->format('d/m/Y') }}</p>
                    <p>Date d'échéance: {{ $facture->date_echeance->format('d/m/Y') }}</p>
                    <p>Type: Facture Individuelle</p>
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
                <div class="from-info">
                    <h6>De:</h6>
                    <table class="info-table">
                        <tbody>
                            <tr><td>Coopérative</td><td>: {{ $facture->cooperative->nom }}</td></tr>
                            <tr><td>Sigle</td><td>: {{ $facture->cooperative->sigle }}</td></tr>
                            <tr><td>Président</td><td>: {{ $facture->cooperative->president }}</td></tr>
                            <tr><td>Contact</td><td>: {{ $facture->cooperative->contact }}</td></tr>
                            <tr><td>Localisation</td><td>: {{ $facture->cooperative->localisation }}</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="client-info">
                    <h6>Destinataire:</h6>
                    <table class="info-table">
                        <tbody>
                            <tr><td>Entreprise</td><td>: ENE CI</td></tr>
                            <tr><td>Adresse</td><td>: Abidjan, Côte d'Ivoire</td></tr>
                            <tr><td>Type</td><td>: Facturation</td></tr>
                            <tr><td>Référence</td><td>: {{ $facture->numero_facture }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="items-section">
                <div class="table-responsive">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Ticket de Pesée</th>
                                <th>Date</th>
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
                                    <td style="text-align: right;">{{ number_format($ticket->poids_net, 2) }}</td>
                                    <td style="text-align: right;">{{ number_format($factureTicket->montant_ticket / $ticket->poids_net, 2) }}</td>
                                    <td style="text-align: right;">{{ number_format($factureTicket->montant_ticket, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
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
    </div>
</body>
</html> 