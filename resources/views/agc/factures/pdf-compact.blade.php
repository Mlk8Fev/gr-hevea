<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $facture->numero_facture }} - FPH-CI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            color: #000;
            font-size: 11px;
            line-height: 1.2;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 8px;
        }
        .company-info {
            font-size: 9px;
            margin-bottom: 10px;
        }
        .company-name {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 3px;
        }
        .document-title {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
            text-align: center;
            text-decoration: underline;
        }
        .main-content {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        .left-column {
            flex: 1;
        }
        .right-column {
            flex: 1;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
            text-decoration: underline;
            font-size: 10px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 2px 4px;
            border: 1px solid #000;
            font-size: 10px;
        }
        .info-table .label {
            background-color: #f0f0f0;
            font-weight: bold;
            width: 40%;
        }
        .info-table .value {
            width: 60%;
        }
        .tickets-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9px;
        }
        .tickets-table th,
        .tickets-table td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
        }
        .tickets-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .tickets-table .text-left {
            text-align: left;
        }
        .totals-section {
            margin-top: 10px;
            border-top: 2px solid #000;
            padding-top: 8px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .totals-table td {
            padding: 2px 4px;
            border: 1px solid #000;
        }
        .totals-table .label {
            background-color: #f0f0f0;
            font-weight: bold;
            width: 70%;
        }
        .totals-table .value {
            width: 30%;
            text-align: right;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 15px;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: underline;
            text-align: center;
            font-size: 9px;
        }
        .signature-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }
        .signature-item {
            width: 180px;
            display: flex;
            flex-direction: column;
        }
        .signature-label {
            font-size: 9px;
            text-align: center;
            margin-bottom: 6px;
            font-weight: bold;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            height: 25px;
            margin: 8px 0;
        }
        .footer {
            margin-top: 15px;
            font-size: 9px;
            text-align: center;
        }
        .status-badge {
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-validee {
            background-color: #d4edda;
            color: #155724;
        }
        .status-payee {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status-brouillon {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('wowdash/images/fph-ci.png') }}" class="logo" alt="FPH-CI Logo">
        <div class="company-info">
            <div class="company-name">FPH-CI (Fédération des Producteurs d'Hévéa de Côte d'Ivoire)</div>
            <div>N CC. 2033188 M | Siège: Cocody Angré terminus 81</div>
            <div>TEL: +225 27 22 47 59 62 | Mail: info@fphci.com</div>
        </div>
        
        <div class="document-title">FACTURE n° {{ $facture->numero_facture }}</div>
        
        <div style="font-weight: bold; margin: 10px 0;">
            Type: {{ ucfirst($facture->type) }} | 
            Statut: <span class="status-badge status-{{ $facture->statut }}">{{ ucfirst($facture->statut) }}</span>
        </div>
    </div>

    <div class="main-content">
        <div class="left-column">
            <div class="section-title">FACTURÉ À</div>
            <table class="info-table">
                <tr>
                    <td class="label">Coopérative :</td>
                    <td class="value">{{ $facture->cooperative->nom }}</td>
                </tr>
                <tr>
                    <td class="label">Secteur :</td>
                    <td class="value">{{ $facture->cooperative->secteur->code }} - {{ $facture->cooperative->secteur->nom }}</td>
                </tr>
                <tr>
                    <td class="label">Président :</td>
                    <td class="value">{{ $facture->cooperative->president }}</td>
                </tr>
                <tr>
                    <td class="label">Contact :</td>
                    <td class="value">{{ $facture->cooperative->contact ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="right-column">
            <div class="section-title">INFORMATIONS FACTURE</div>
            <table class="info-table">
                <tr>
                    <td class="label">Date d'émission :</td>
                    <td class="value">{{ $facture->date_emission ? $facture->date_emission->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Date d'échéance :</td>
                    <td class="value">{{ $facture->date_echeance ? $facture->date_echeance->format('d/m/Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Nombre de tickets :</td>
                    <td class="value">{{ $facture->ticketsPesee->count() }}</td>
                </tr>
                <tr>
                    <td class="label">Poids total :</td>
                    <td class="value">{{ number_format($facture->poids_total, 2) }} kg</td>
                </tr>
            </table>
        </div>
    </div>

    @if($facture->ticketsPesee->count() > 0)
    <div class="section-title">DÉTAIL DES TICKETS DE PESÉE</div>
    <table class="tickets-table">
        <thead>
            <tr>
                <th style="width: 20%;">N° Ticket</th>
                <th style="width: 20%;">Date</th>
                <th style="width: 20%;">Poids Net (kg)</th>
                <th style="width: 20%;">Prix/Kg</th>
                <th style="width: 20%;">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ticketsAvecPrix as $item)
            @php
                $ticket = $item['ticket'];
                $prix = $item['prix'];
            @endphp
            <tr>
                <td class="text-left">{{ $ticket->numero_ticket }}</td>
                <td>{{ $ticket->date_entree->format('d/m/Y') }}</td>
                <td>{{ number_format($ticket->poids_net, 2) }}</td>
                <td>
                    @if(isset($prix['details']['prix_final_public']))
                        {{ number_format($prix['details']['prix_final_public'], 0) }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if(isset($prix['details']['prix_final_public']))
                        {{ number_format($ticket->poids_net * $prix['details']['prix_final_public'], 0) }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($facture->ticketsPesee->count() > 0)
    <div class="section-title">DÉTAILS DE CALCUL</div>
    <table class="tickets-table">
        <thead>
            <tr>
                <th style="width: 25%;">Élément de Calcul</th>
                <th style="width: 25%;">Valeur</th>
                <th style="width: 25%;">Unité</th>
                <th style="width: 25%;">Description</th>
            </tr>
        </thead>
        <tbody>
            @php
                $firstTicket = $ticketsAvecPrix[0]['prix'] ?? null;
            @endphp
            @if($firstTicket && isset($firstTicket['details']))
                <tr>
                    <td class="text-left">Prix de base</td>
                    <td>{{ number_format($firstTicket['details']['prix_base'] ?? 0, 2) }}</td>
                    <td>FCFA/kg</td>
                    <td class="text-left">Prix de référence</td>
                </tr>
                <tr>
                    <td class="text-left">Bonus qualité</td>
                    <td>{{ number_format($firstTicket['details']['bonus_qualite'] ?? 0, 2) }}</td>
                    <td>FCFA/kg</td>
                    <td class="text-left">Prime qualité</td>
                </tr>
                <tr>
                    <td class="text-left">Coût transport</td>
                    <td>{{ number_format($firstTicket['details']['cout_transport'] ?? 0, 2) }}</td>
                    <td>FCFA/kg</td>
                    <td class="text-left">Frais de transport</td>
                </tr>
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td class="text-left">Prix final</td>
                    <td>{{ number_format($firstTicket['details']['prix_final_public'] ?? 0, 2) }}</td>
                    <td>FCFA/kg</td>
                    <td class="text-left">Prix total par kg</td>
                </tr>
            @else
                <tr>
                    <td colspan="4" class="text-center">Calcul non disponible</td>
                </tr>
            @endif
        </tbody>
    </table>
    @endif

    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td class="label">Poids Total :</td>
                <td class="value">{{ number_format($facture->poids_total, 2) }} kg</td>
            </tr>
            <tr>
                <td class="label">Montant HT :</td>
                <td class="value">{{ number_format($facture->montant_ht, 0) }} FCFA</td>
            </tr>
            <tr>
                <td class="label">TVA (18%) :</td>
                <td class="value">{{ number_format($facture->montant_tva, 0) }} FCFA</td>
            </tr>
            <tr style="border-top: 2px solid #000; font-size: 12px;">
                <td class="label">MONTANT TTC :</td>
                <td class="value">{{ number_format($facture->montant_ttc, 0) }} FCFA</td>
            </tr>
            @if($facture->montant_paye > 0)
            <tr>
                <td class="label">Montant Payé :</td>
                <td class="value">{{ number_format($facture->montant_paye, 0) }} FCFA</td>
            </tr>
            <tr>
                <td class="label">Reste à Payer :</td>
                <td class="value">{{ number_format($facture->montant_restant, 0) }} FCFA</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="signature-section">
        <div style="margin-bottom: 10px;">
            <strong>Date d'impression :</strong> 
            <span class="signature-line" style="display: inline-block; width: 200px; margin-left: 10px;">{{ now()->format('d/m/Y') }}</span>
        </div>
        
        <div class="signature-title">Signatures</div>
        
        <div class="signature-row">
            <div class="signature-item">
                <div class="signature-label">Signature FPH-CI</div>
                <div class="signature-line"></div>
            </div>
            
            <div class="signature-item">
                <div class="signature-label">Signature Coopérative</div>
                <div class="signature-line"></div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Facture générée le {{ now()->format('d/m/Y H:i') }} par le système FPH-CI</p>
    </div>
</body>
</html>
