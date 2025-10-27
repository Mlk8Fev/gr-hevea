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
        .pdf-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
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
        .signature {
            position: absolute;
            right: 60px;
            bottom: 60px;
            width: 220px;
            height: 80px;
        }
        .label {
            font-weight: bold;
            color: #222;
        }
    </style>
</head>
<body>
    @if(file_exists(public_path('wowdash/images/selfd.png')))
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('wowdash/images/selfd.png'))) }}" class="pdf-bg" alt="background">
    @endif
    <div class="pdf-content">
        <h2 style="text-align:center; margin-bottom:30px;"></h2>
        <div class="champ" style="margin-top:35px; margin-bottom:25px; margin-left:80px;"><span class="label">{{ $producteur->nom }} {{ $producteur->prenom }}</span></div>
        <div class="champ" style="margin-top:-12px; margin-left:320px;"><span class="label">{{ $data['adresse_complete'] ?? '' }}</span></div>
        <div class="champ" style="margin-top:30px; margin-left:70px;"><span class="label">{{ $producteur->contact }}</span></div>
        <div style="position:absolute; left:70px; bottom:185px; font-weight:bold;">{{ ($data['lieu'] ?? '') }}, {{ ($data['date'] ?? '') }}</div>
        @if($document->signature)
            <img src="{{ public_path('storage/' . $document->signature) }}" style="position:absolute; right:150px; bottom:170px; width:120px; height:45px; object-fit:contain;" alt="Signature">
        @endif
    </div>
</body>
</html> 