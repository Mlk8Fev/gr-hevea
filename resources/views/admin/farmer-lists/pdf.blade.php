<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer List - {{ $connaissement->numero_livraison }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            color: #333;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 8px;
        }
        .company-info {
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 3px;
        }
        .company-details {
            font-size: 11px;
            color: #666;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            text-align: center;
        }
        .delivery-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        .info-value {
            color: #212529;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10px;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Logo en base64 pour éviter les problèmes de chemin -->
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('wowdash/images/fph-ci.png'))) }}" alt="FPH-CI Logo" class="logo">
        <div class="company-info">
            <div class="company-name">FPH-CI</div>
            <div class="company-details">
                Fédération des Producteurs d'Hévéa de Côte d'Ivoire<br>
                Abidjan, Côte d'Ivoire
            </div>
        </div>
    </div>

    <div class="document-title">FARMER LIST - {{ $connaissement->numero_livraison }}</div>

    <div class="delivery-info">
        <div class="info-row">
            <span class="info-label">N° Livraison :</span>
            <span class="info-value">{{ $connaissement->numero_livraison }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Coopérative :</span>
            <span class="info-value">{{ $connaissement->cooperative->nom }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Poids Net Total :</span>
            <span class="info-value">{{ number_format($poidsNet, 2) }} kg</span>
        </div>
        <div class="info-row">
            <span class="info-label">Poids Ajouté :</span>
            <span class="info-value">{{ number_format($poidsTotal, 2) }} kg</span>
        </div>
        <div class="info-row">
            <span class="info-label">Poids Restant :</span>
            <span class="info-value">{{ number_format($poidsRestant, 2) }} kg</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Secteur</th>
                <th>Nom & Prénoms</th>
                <th>Code</th>
                <th>Géo</th>
                <th>Date</th>
                <th>Quantité (kg)</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            @foreach($farmerLists as $index => $farmerList)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $farmerList->producteur->secteur->nom ?? 'N/A' }}</td>
                    <td>{{ $farmerList->producteur->nom }} {{ $farmerList->producteur->prenom }}</td>
                    <td>{{ $farmerList->producteur->code_fphci ?? 'N/A' }}</td>
                    <td>{{ $farmerList->geolocalisation_precise ? 'Oui' : 'Non' }}</td>
                    <td>{{ $farmerList->date_livraison->format('d/m/Y') }}</td>
                    <td>{{ number_format($farmerList->quantite_livree, 2) }}</td>
                    <td>{{ $farmerList->producteur->contact ?? 'N/A' }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6"><strong>TOTAL</strong></td>
                <td><strong>{{ number_format($poidsTotal, 2) }} kg</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>FPH-CI - Système de Gestion des Livraisons</p>
    </div>
</body>
</html> 