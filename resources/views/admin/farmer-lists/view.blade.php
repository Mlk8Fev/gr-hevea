<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer List - {{ $connaissement->numero_livraison }}</title>
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 10px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        .company-details {
            font-size: 14px;
            color: #666;
        }
        .document-title {
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .delivery-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
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
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        .actions {
            margin-bottom: 20px;
            text-align: center;
        }
        .btn {
            margin: 0 5px;
        }
        
        /* Styles pour l'impression */
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="actions no-print">
        <a href="{{ route('admin.farmer-lists.show', $connaissement) }}" class="btn btn-secondary">
            <i class="ri-arrow-left-line"></i> Retour
        </a>
        <a href="{{ route('admin.farmer-lists.pdf', $connaissement) }}" class="btn btn-primary">
            <i class="ri-download-line"></i> Télécharger PDF
        </a>
        <button onclick="window.print()" class="btn btn-success">
            <i class="ri-printer-line"></i> Imprimer
        </button>
    </div>

    <div class="header">
        <img src="{{ asset('wowdash/images/fph-ci.png') }}" alt="FPH-CI Logo" class="logo">
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
                <th>Code Producteur</th>
                <th>Géolocalisation</th>
                <th>Date Livraison</th>
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