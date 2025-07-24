<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Lettre d'engagement producteur</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; line-height: 1.6; margin: 30px 40px 30px 40px; }
        .header { display: flex; align-items: center; margin-bottom: 24px; }
        .logo { width: 210px; height: auto; margin-right: 18px; }
        .title { font-size: 14px; font-weight: bold; text-align: center; margin-bottom: 12px; text-decoration: underline; }
        .section { margin-bottom: 10px; }
        .section + .section { margin-top: 4px; }
        .signature-block { margin-top: 40px; text-align: right; }
        .signature-label { font-size: 13px; font-weight: bold; }
        .signature-img { width: 230px; height: 85px; object-fit: contain; margin-bottom: 6px; border: none; border-radius: 0; }
        .mention { font-style: italic; font-size: 12px; color: #555; margin-top: 8px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $logoPath }}" class="logo" alt="logo FPHCI">
        <div>
            <div class="title">FICHE D’ENGAGEMENT DU PRODUCTEUR POUR LA LIVRAISON DE LA GRAINE<br>D’HEVEA DANS LES CENTRES DE COLLECTE DE LA FPH-CI</div>
        </div>
    </div>
    <div class="section">
        Je soussigné(e) <b>@php
            $civilite = isset($data['genre']) && in_array($data['genre'], ['M', 'M.', 'Mme', 'Mlle']) ? ($data['genre'] === 'M' ? 'M.' : $data['genre']) : 'M.';
        @endphp
        {{ $civilite }} {{ $producteur->nom }} {{ $producteur->prenom }}</b><br>
        Né(e) le <b>@if(isset($data['date_naissance'])){{ \Carbon\Carbon::parse($data['date_naissance'])->format('d/m/Y') }}@endif</b>
        à <b>@if(isset($data['lieu_naissance'])){{ $data['lieu_naissance'] }}@endif</b><br>
        Profession : <b>@if(isset($data['profession'])){{ $data['profession'] }}@endif</b>, membre de la FPH-CI rattaché au Bureau de Secteur de <b>{{ $producteur->secteur ? $producteur->secteur->nom : '' }}</b>.<br>
        Domicile : <b>@if(isset($data['domicile'])){{ $data['domicile'] }}@endif</b><br>
        Titulaire de la plantation d’hévéa d’une surface de près de <b>{{ $producteur->superficie_totale }}</b> ha sise à <b>@if(isset($data['lieu_plantation'])){{ $data['lieu_plantation'] }}@endif</b><br>
        Dans la commune de : <b>@if(isset($data['commune'])){{ $data['commune'] }}@endif</b><br>
        Sous Préfecture : <b>@if(isset($data['sous_prefecture'])){{ $data['sous_prefecture'] }}@endif</b>
    </div>
    <div class="section">
        M’engage, par la présente, dans le cadre de la campagne 2024, pour la commercialisation de la graine d’hévéa,<br>
        À la livraison, auprès des centres de collecte érigés par la Fédération des Organisations Professionnelles Agricoles de Producteurs de la Filière Hévéa Côte d’Ivoire dite « FPH-CI », de l’entière quantité de la production des graines d’hévéa de ma plantation ci-dessus évoquée ;<br>
        Ce, suivant le principe de séchage des graines d’hévéa tel qu’édicté par la FPH-CI en vue de recueillir un taux d’humidité réduit ;<br>
        suivant également le principe du tri des graines d’hévéa afin de les débarrasser de toutes les impuretés que constituent les graines pourries, les graines d’hévéa en germination, les corps étrangers….
    </div>
    <div class="section">
        J’accepte, par là même, le prix communiqué par la FPH-CI conformément à la convention que celle-ci a passé avec l’entreprise ENI-CI dans l’intérêt des planteurs pour la campagne 2024 qui est de soixante-dix (70) francs CFA par kilogramme de graines d’hévéa.
    </div>
    <div class="section">
        En foi de quoi, je délivre le présent engagement pour servir et valoir ce que de droit.
    </div>
    <div class="signature-block">
        <div style="margin-bottom: 12px;">
            @if(isset($data['commune'])){{ $data['commune'] }}@endif, le <b>@if(isset($data['date_signature'])){{ \Carbon\Carbon::parse($data['date_signature'])->format('d/m/Y') }}@endif</b>
        </div>
        <div class="signature-label">M.<br><b>{{ $producteur->nom }} {{ $producteur->prenom }}</b></div>
        <div>
            @if($signaturePath && file_exists($signaturePath))
                <img src="{{ $signaturePath }}" alt="Signature du producteur" class="signature-img">
            @else
                <span style="color:#888;">[Signature non disponible]</span>
            @endif
        </div>
        <div class="mention">bon pour engagement</div>
    </div>
</body>
</html> 