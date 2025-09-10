<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu d'Achat - {{ $recuAchat->numero_recu }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            color: #000;
            font-size: 12px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        .company-info {
            font-size: 10px;
            margin-bottom: 15px;
        }
        .company-name {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            text-align: center;
            text-decoration: underline;
        }
        .certificate {
            font-size: 9px;
            margin: 15px 0;
            text-align: center;
            border: 2px solid #000;
            padding: 8px;
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .main-content {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .left-column {
            flex: 1;
        }
        .right-column {
            flex: 1;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 3px 5px;
            border: 1px solid #000;
            font-size: 11px;
        }
        .info-table .label {
            background-color: #f0f0f0;
            font-weight: bold;
            width: 40%;
        }
        .info-table .value {
            width: 60%;
        }
        .signature-section {
            margin-top: 20px;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 15px;
            text-decoration: underline;
            text-align: center;
        }
        .signature-row {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-bottom: 10px;
            gap: 60px;
        }
        .signature-item {
            width: 250px;
            display: flex;
            flex-direction: column;
        }
        .signature-label {
            font-size: 10px;
            text-align: center;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .signature-image {
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            padding: 10px;
        }
        .date-line {
            border-bottom: 1px solid #000;
            height: 20px;
            margin: 10px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
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
        
        <div class="document-title">REÇU D'ACHAT n° {{ $recuAchat->numero_recu }}</div>
        
        <div style="font-weight: bold; margin: 10px 0;">Produit : Graines d'hévéa</div>
        <div style="font-weight: bold;">Campagne 2024</div>
        
        <div class="certificate">CERTIFICAT ISCC EU - EU-ISCC-Cert-IT206-2121</div>
    </div>

    <div class="main-content">
        <div class="left-column">
            <div class="section-title">VENDEUR</div>
            <table class="info-table">
                <tr>
                    <td class="label">Nom :</td>
                    <td class="value">{{ $recuAchat->nom_producteur }} {{ $recuAchat->prenom_producteur }}</td>
                </tr>
                <tr>
                    <td class="label">Téléphone :</td>
                    <td class="value">{{ $recuAchat->telephone_producteur }}</td>
                </tr>
                <tr>
                    <td class="label">Code ident. producteur (ID) :</td>
                    <td class="value">{{ $recuAchat->code_fphci }}</td>
                </tr>
                <tr>
                    <td class="label">Centre de collecte :</td>
                    <td class="value">{{ $recuAchat->centre_collecte }}</td>
                </tr>
                <tr>
                    <td class="label">Secteur FPH-CI :</td>
                    <td class="value">{{ $recuAchat->secteur_fphci }}</td>
                </tr>
            </table>
        </div>

        <div class="right-column">
            <div class="section-title">RUBRIQUE</div>
            <table class="info-table">
                <tr>
                    <td class="label">Nombre de sacs</td>
                    <td class="value">{{ $recuAchat->farmerList->nombre_sacs ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Poids net (Kg)</td>
                    <td class="value">{{ number_format($recuAchat->quantite_livree, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Prix unitaire (Kg)</td>
                    <td class="value">{{ number_format($recuAchat->prix_unitaire, 0) }} FCFA</td>
                </tr>
                <tr>
                    <td class="label">Montant à payer</td>
                    <td class="value"><strong>{{ number_format($recuAchat->montant_total, 0) }} FCFA</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="signature-section">
        <div style="margin-bottom: 10px;">
            <strong>Date :</strong> 
            <span class="date-line" style="display: inline-block; width: 200px; margin-left: 10px;">{{ $recuAchat->date_creation ? $recuAchat->date_creation->format('d/m/Y') : now()->format('d/m/Y') }}</span>
        </div>
        
        <div class="signature-title">Signatures</div>
        
        <div class="signature-row">
            <div class="signature-item">
                <div class="signature-label">Signature Acheteur</div>
                <div class="signature-image">
                    @if($recuAchat->signature_acheteur)
                        <img src="{{ $recuAchat->signature_acheteur }}" style="max-height: 100px; max-width: 100%;" alt="Signature Acheteur">
                    @endif
                </div>
            </div>
            
            <div class="signature-item">
                <div class="signature-label">Signature Producteur</div>
                <div class="signature-image">
                    @if($recuAchat->signature_producteur)
                        <img src="{{ $recuAchat->signature_producteur }}" style="max-height: 100px; max-width: 100%;" alt="Signature Producteur">
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Reçu généré le {{ now()->format('d/m/Y H:i') }} par le système FPH-CI</p>
    </div>
</body>
</html> 