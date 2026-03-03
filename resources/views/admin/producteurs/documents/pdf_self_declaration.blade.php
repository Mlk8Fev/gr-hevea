<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déclaration sur l'honneur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 15px;
            margin: 0;
            padding: 0;
        }
        .page {
            position: relative;
            min-height: 100vh;
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: auto;
        }
        .pdf-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        .pdf-content {
            position: relative;
            z-index: 1;
            padding: 60px 50px 120px 50px;
        }
        .champ {
            margin-bottom: 18px;
        }
        .label {
            font-weight: bold;
            color: #222;
        }
        /* Page 2 - Footer avec signature - Éléments indépendants */
        .signature-left {
            position: absolute;
            bottom: 470px;
            left: 85px;
            text-align: left;
            z-index: 2;
        }
        .signature-center {
            position: absolute;
            bottom: 475px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 2;
        }
        .signature-right {
            position: absolute;
            bottom: 480px;
            right: 80px;
            text-align: right;
            z-index: 2;
        }
        .signature-img {
            width: 150px;
            height: 60px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <!-- PAGE 1 : Contenu principal -->
    <div class="page page-1">
        @if(file_exists(public_path('wowdash/images/selfd.png')))
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('wowdash/images/selfd.png'))) }}" class="pdf-bg" alt="background page 1">
        @endif
        <div class="pdf-content">
            <h2 style="text-align:center; margin-bottom:30px;"></h2>
            <!-- Nom et Prénom - Position indépendante -->
            <div style="position: absolute; top: 85px; right: 400px;">
                <span class="label">{{ $producteur->nom }} {{ $producteur->prenom }}</span>
            </div>
            <!-- Adresse complète - Position indépendante -->
            <div style="position: absolute; top: 110px; right: 350px;">
                <span class="label">{{ $data['adresse_complete'] ?? '' }}</span>
            </div>
            <!-- Position/Code postal - Position indépendante -->
            @if(isset($data['position_code_postal']))
                <div style="position: absolute; top: 130px; right: 400px;">
                    <span class="label">{{ $data['position_code_postal'] }}</span>
                </div>
            @endif
            <!-- Contact - Position indépendante -->
            <div style="position: absolute; top: 175px; right: 400px;">
                <span class="label">{{ $producteur->contact }}</span>
            </div>
        </div>
    </div>

    <!-- PAGE 2 : Signature et informations -->
    <div class="page page-2">
        @if(file_exists(public_path('wowdash/images/selfd2.png')))
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('wowdash/images/selfd2.png'))) }}" class="pdf-bg" alt="background page 2">
        @endif
        <div class="pdf-content">
            <!-- À gauche : Lieu et Date - Position indépendante -->
            <div class="signature-left">
                <div style="font-weight: bold; margin-bottom: 5px;">
                    {{ ($data['lieu'] ?? '') }}, {{ ($data['date'] ?? '') }}
                </div>
            </div>
            
            <!-- Au centre : Nom et Fonction - Position indépendante -->
            <div class="signature-center">
                <div style="font-weight: bold;">
                    <strong>{{ $producteur->nom }} {{ $producteur->prenom }}</strong>@if(isset($data['fonction'])), <span style="margin-left: 10px;">{{ $data['fonction'] }}</span>@endif
                </div>
            </div>
            
            <!-- À droite : Signature - Position indépendante -->
            <div class="signature-right">
                @if($document->signature && file_exists(public_path('storage/' . $document->signature)))
                    <img src="{{ public_path('storage/' . $document->signature) }}" class="signature-img" alt="Signature">
                @else
                    <div style="color: #888; font-size: 12px;">[Signature]</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html> 