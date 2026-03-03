<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Lettre d'engagement producteur</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; line-height: 1.4; margin: 25px 40px 25px 40px; text-align: justify; }
        .header { display: flex; align-items: flex-start; margin-bottom: 15px; }
        .logo { width: 120px; height: auto; margin-right: 15px; margin-top: 0; }
        .title { font-size: 14px; font-weight: bold; text-align: center; margin-bottom: 8px; text-decoration: underline; }
        .section { margin-bottom: 4px; text-align: justify; }
        .section + .section { margin-top: 1px; }
        .signature-block { margin-top: 25px; text-align: right; }
        .signature-label { font-size: 13px; font-weight: bold; }
        .signature-img { width: 230px; height: 85px; object-fit: contain; margin-bottom: 6px; border: none; border-radius: 0; }
        .mention { font-style: italic; font-size: 12px; color: #555; margin-top: 6px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $logoPath }}" class="logo" alt="logo FPHCI">
        <div>
            <div class="title">FICHE D'ENGAGEMENT DU PRODUCTEUR POUR LA LIVRAISON DE LA GRAINE D'HEVEA</div>
        </div>
    </div>
    <div class="section">
        Je soussigné, <b>@php
            $civilite = isset($data['genre']) && in_array($data['genre'], ['M', 'M.', 'Mme', 'Mlle']) ? ($data['genre'] === 'M' ? 'M.' : $data['genre']) : 'M.';
        @endphp
        {{ $civilite }} {{ $producteur->nom }} {{ $producteur->prenom }}</b><br>
        Né(e) le <b>@if(isset($data['date_naissance'])){{ \Carbon\Carbon::parse($data['date_naissance'])->format('d/m/Y') }}@endif</b> à <b>@if(isset($data['lieu_naissance'])){{ $data['lieu_naissance'] }}@endif</b><br>
        Planteur, membre de la Fédération des Organisations Professionnelles Agricoles de Producteurs de la Filière Hévéa de Côte d'Ivoire (FPH-CI), rattaché au Bureau de Secteur de <b>{{ $producteur->secteur ? $producteur->secteur->nom : '' }}</b>.<br>
        Domicile : <b>@if(isset($data['domicile'])){{ $data['domicile'] }}@endif</b><br>
        Déclare être le propriétaire d'une plantation d'hévéa d'une superficie de <b>{{ $producteur->superficie_totale }}</b> ha<br>
        Située dans la commune de <b>@if(isset($data['commune'])){{ $data['commune'] }}@endif</b>, sous-préfecture de <b>@if(isset($data['sous_prefecture'])){{ $data['sous_prefecture'] }}@endif</b>
    </div>
    <div class="section">
        Dans le cadre de la campagne de commercialisation de la graine d'hévéa 2025, je m'engage à livrer l'intégralité de la production issue de ma plantation aux coopératives reconnus et autorisés par la FPH-CI.
    </div>
    <div class="section">
        Cette livraison se fera selon les exigences techniques définies par la FPH-CI, notamment en ce qui concerne le séchage adéquat des graines afin de réduire le taux d'humidité, ainsi que le tri rigoureux permettant d'éliminer les graines pourries, en germination ou tout autre corps étranger.
    </div>
    <div class="section">
        J'accepte le prix d'achat de soixante-douze (72) FCFA/Kg bord champ et quatre vingt deux (82) FCFA/Kg livraison aux coopératives avec la qualité requise, tel que fixé pour la campagne 2025, conformément à l'accord conclu entre la FPH-CI et l'entreprise ENE CI, partenaire officiel de la FPH-CI.
    </div>
    <div class="section">
        Je reconnais que le paiement des graines livrées se fera sur la base du poids net, constaté lors de la réception, et que chaque transaction donnera lieu à l'émission d'un reçu signé par les deux parties. Une copie me sera remise pour mes propres archives.
    </div>
    <div class="section">
        Je suis informé que la présente fiche d'engagement constitue un document contractuel écrit, et qu'elle m'engage dans le cadre d'un partenariat équitable avec la FPH-CI.
    </div>
    <div class="section">
        En cas de situation exceptionnelle ou de difficulté majeure, une solution amiable sera d'abord recherchée, mais si aucun accord n'est trouvé, chaque partie peut demander la résiliation du présent contrat après en avoir informé l'autre partie par écrit.
    </div>
    <div class="section">
        Je reconnais également avoir la possibilité, en tant que producteur membre de la FPH-CI, de participer aux réunions d'information et de concertation organisées par la Fédération ou par le Bureau de Secteur, afin de contribuer aux discussions relatives aux prix, à la qualité ou à toute évolution des conditions de commercialisation.
    </div>
    <div class="signature-block">
        <div style="margin-bottom: 12px;">
            Fait à <b>@if(isset($data['commune'])){{ $data['commune'] }}@endif</b>, le <b>@if(isset($data['date_signature'])){{ \Carbon\Carbon::parse($data['date_signature'])->format('d/m/Y') }}@endif</b>
        </div>
        <div class="signature-label">{{ $civilite }}<br><b>{{ $producteur->nom }} {{ $producteur->prenom }}</b></div>
        <div>
            @if($signaturePath && file_exists($signaturePath))
                <img src="{{ $signaturePath }}" alt="Signature du producteur" class="signature-img">
            @else
                <span style="color:#888;">[Signature non disponible]</span>
            @endif
        </div>
        <div class="mention">Signature « Précédée de la mention "bon pour engagement" »</div>
    </div>
</body>
</html>