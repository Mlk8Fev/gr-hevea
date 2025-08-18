<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ENQUETE DE CERTIFICATION ISCC EU</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #222; margin: 30px 40px; }
        .page-break { page-break-before: always; }
        .cover-page { text-align: center; padding: 100px 0; }
        .logo { width: 200px; height: auto; margin-bottom: 50px; }
        .cover-title { font-size: 24px; font-weight: bold; margin-bottom: 30px; }
        .cover-confidential { font-size: 14px; color: #555; margin-top: 50px; }
        h2 { text-align: center; font-size: 18px; margin-bottom: 8px; }
        h4 { margin-top: 24px; margin-bottom: 10px; font-size: 15px; text-decoration: underline; }
        .section { margin-bottom: 18px; }
        .q { font-weight: bold; }
        .signatures { display: flex; justify-content: space-between; margin-top: 40px; }
        .signature-block { text-align: center; }
        .signature-img { width: 180px; height: 60px; object-fit: contain; border: none; border-radius: 0; }
        .confidential { font-size: 12px; color: #555; text-align: center; margin-bottom: 18px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .table th, .table td { border: 1px solid #bbb; padding: 4px 8px; font-size: 12px; }
        .table th { background: #f5f5f5; }
        .table td:first-child { width: 60%; }
        .table td:last-child { width: 40%; font-weight: bold; }
    </style>
</head>
<body>
    <!-- Page de garde -->
    <div class="cover-page">
        <img src="{{ public_path('wowdash/images/fph-ci.png') }}" alt="Logo FPH-CI" class="logo">
        <div class="cover-title">ENQUETE DE CERTIFICATION ISCC EU</div>
        <div class="cover-confidential">
            Cette enquête est strictement confidentielle. Les informations recueillies sont protégées par le<br>
            secret statistique et la loi sur la protection des données à caractère personnel.
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="page-break">
        <img src="{{ public_path('wowdash/images/fph-ci.png') }}" alt="Logo FPH-CI" style="width: 100px; height: auto; position: absolute; top: 20px; left: 20px;">
        <h2>ENQUETE DE CERTIFICATION ISCC EU</h2>
        <div class="confidential">
            Cette enquête est strictement confidentielle. Les informations recueillies sont protégées par le secret statistique et la loi sur la protection des données à caractère personnel.
        </div>
        
        <h4>1. Données de l'opérateur et de l'agriculteur</h4>
        <table class="table">
            <tr><th>Question</th><th>Réponse</th></tr>
            <tr><td>ID producteur</td><td>{{ $producteur->code_fphci }}</td></tr>
            <tr><td>Culture certifiée</td><td>Hevea brasiliensis</td></tr>
            <tr><td>Nom de l'enquêteur</td><td>{{ $data['enqueteur_nom'] ?? '' }}</td></tr>
            <tr><td>Numéro de téléphone (enquêteur)</td><td>{{ $data['enqueteur_tel'] ?? '' }}</td></tr>
            <tr><td>Nom du producteur</td><td>{{ $producteur->nom }}</td></tr>
            <tr><td>Prénoms du producteur</td><td>{{ $producteur->prenom }}</td></tr>
            <tr><td>Numéro de téléphone (producteur)</td><td>{{ $producteur->contact }}</td></tr>
            <tr><td>Région</td><td>{{ $data['region'] ?? '' }}</td></tr>
            <tr><td>Département</td><td>{{ $data['departement'] ?? '' }}</td></tr>
            <tr><td>Localité</td><td>{{ $data['localite'] ?? '' }}</td></tr>
            <tr><td>Secteur FPH-CI</td><td>{{ $producteur->secteur ? $producteur->secteur->nom : '' }}</td></tr>
            <tr><td>Genre</td><td>{{ $data['genre'] ?? '' }}</td></tr>
            <tr><td>Superficie totale cultivée (ha)</td><td>{{ $data['superficie_totale'] ?? '' }}</td></tr>
            <tr><td>Nombre total de champs (cacao + hevea+…)</td><td>{{ $data['nb_champs_total'] ?? '' }}</td></tr>
            <tr><td>Superficie totale cultivée avec hévéa (ha)</td><td>{{ $data['superficie_hevea'] ?? '' }}</td></tr>
            <tr><td>Nombre de plantations d'hévéas</td><td>{{ $data['nb_plantations_hevea'] ?? '' }}</td></tr>
            <tr><td>Clone principal</td><td>{{ $data['clone_principal'] ?? '' }} @if(!empty($data['clone_autre'])) ({{ $data['clone_autre'] }}) @endif</td></tr>
            <tr><td>Année de création du champ d'hévéa</td><td>{{ $data['annee_creation_champ'] ?? '' }}</td></tr>
            <tr><td>Pour laquelle de ces productions êtes-vous déjà certifiée ?</td><td>
                @if(!empty($data['certif_cacao'])) Cacao @endif
                @if(!empty($data['certif_cafe'])) Café @endif
                @if(!empty($data['certif_palmier'])) Palmier à huile @endif
                @if(!empty($data['certif_autre'])) Autre @endif
                @if(!empty($data['certif_autre_preciser'])) ({{ $data['certif_autre_preciser'] }}) @endif
            </td></tr>
            <tr><td>Le Producteur marque son accord pour la réalisation de cette enquête de durabilité, librement et sans contrainte.</td><td>{{ $data['accord_enquete'] ?? '' }}</td></tr>
            <tr><td>Date</td><td>{{ $data['date_enquete'] ?? '' }}</td></tr>
        </table>
        
        <div class="signatures">
            <div class="signature-block">
                <div>Signature du producteur</div>
                @if(!empty($data['signature_producer']) && file_exists(public_path('storage/' . $data['signature_producer'])))
                    <img src="{{ public_path('storage/' . $data['signature_producer']) }}" class="signature-img" alt="Signature producteur">
                @else
                    <span style="color:#888;">[Signature non disponible]</span>
                @endif
            </div>
            <div class="signature-block">
                <div>Signature de l'agent de traçabilité</div>
                @if(!empty($data['signature_agent']) && file_exists(public_path('storage/' . $data['signature_agent'])))
                    <img src="{{ public_path('storage/' . $data['signature_agent']) }}" class="signature-img" alt="Signature agent">
                @else
                    <span style="color:#888;">[Signature non disponible]</span>
                @endif
            </div>
        </div>

        <div class="page-break">
        <img src="{{ public_path('wowdash/images/fph-ci.png') }}" alt="Logo FPH-CI" style="width: 100px; height: auto; position: absolute; top: 20px; left: 20px;">
        <h4>2. IMPACT ENVIRONNEMENTAL</h4>
        <div class="q">Protection du sol</div>
        <table class="table">
            <tr><th>Question</th><th>Réponse</th></tr>
            <tr><td>1.1. Existe-t-il des problèmes d'érosion du sol dans les champs ?</td><td>{{ $data['erosion_sol'] ?? '' }}</td></tr>
            <tr><td>1.2. Existe-t-il autour des champs des rangées d'arbre en vue de protéger les champs des grands vents ?</td><td>{{ $data['protection_vents'] ?? '' }}</td></tr>
        </table>
        
        <div class="q">Produits phytosanitaires</div>
        <table class="table">
            <tr><th>Question</th><th>Réponse</th></tr>
            <tr><td>1.3. Utilisez-vous des pesticides ou autres produits agrochimiques dans vos champs ?</td><td>{{ $data['pesticides_utilises'] ?? '' }}</td></tr>
            <tr><td>Si oui, listez et dites les quantités de pesticide ou produits agrochimiques utilisés dans le champ :</td><td>{{ $data['liste_pesticides'] ?? '' }}</td></tr>
            <tr><td>1.4. Le producteur a-t-il reçu une formation sur la gestion et l'application des pesticides et produits agrochimiques ?</td><td>{{ $data['formation_pesticides'] ?? '' }}</td></tr>
            <tr><td>1.5. Les pesticides/produits agrochimiques sont-ils gérés et appliqués par des professionnels ?</td><td>{{ $data['pro_pesticides'] ?? '' }}</td></tr>
            <tr><td>1.6. Le producteur possède-t-il son propre équipement de protection personnelle et un équipement de pulvérisation ?</td><td>{{ $data['equipement_protection'] ?? '' }}</td></tr>
            <tr><td>1.7. Quels sont les équipements de Protection Individuelle que vous avez ?</td><td>{{ $data['epi'] ?? '' }}</td></tr>
            <tr><td>1.8. Que faites-vous des emballages vides des produits pesticides ou autres produits agrochimiques quand vous finissez de les utiliser ?</td><td>{{ $data['gestion_emballages'] ?? '' }}</td></tr>
        </table>
    </div>

        <h4>3. SANTÉ ET SÉCURITÉ</h4>
        <table class="table">
            <tr><th>Question</th><th>Réponse</th></tr>
            <tr><td>2.1 Le producteur a-t-il reçu une formation sanitaire et sécuritaire adéquate pour les pratiques agricoles usitées dans sa zone ?</td><td>{{ $data['formation_sante'] ?? '' }}</td></tr>
            <tr><td>2.2 Le producteur utilise-t-il correctement le matériel de protection personnelle requis pour la pratique de l'agriculture ?</td><td>{{ $data['utilisation_epi'] ?? '' }}</td></tr>
            <tr><td>2.3 Avez-vous accès à une boite à pharmacie appropriée à côté du champ où vous travaillez ?</td><td>{{ $data['pharmacie_acces'] ?? '' }}</td></tr>
            <tr><td>2.4 Avez-vous accès à une grande quantité d'eau à côté du champ où vous travaillez ?</td><td>{{ $data['eau_acces'] ?? '' }}</td></tr>
            <tr><td>2.5 S'est-il produit une de ces blessures dans le champ l'année dernière ?</td><td>{{ $data['blessures'] ?? '' }}</td></tr>
            <tr><td>2.6 Connaissez-vous les numéros d'urgence de l'hôpital ou l'infirmière le plus proche ?</td><td>{{ $data['urgence_numero'] ?? '' }}</td></tr>
            <tr><td>2.7 Possédez-vous un magasin pour le stockage des produits phytosanitaires ?</td><td>{{ $data['magasin_phytos'] ?? '' }}</td></tr>
        </table>

        <div class="page-break">
        <img src="{{ public_path('wowdash/images/fph-ci.png') }}" alt="Logo FPH-CI" style="width: 100px; height: auto; position: absolute; top: 20px; left: 20px;">
        <h4>4. IMPACT SOCIAL</h4>
        <div class="q">Restrictions relatives aux enfants et au travail</div>
        <table class="table">
            <tr><th>Question</th><th>Réponse</th></tr>
            <tr><td>3.1 Les enfants du producteur, ou d'autres enfants de moins de 18 ans travaillent-ils dans le champ ?</td><td>{{ $data['travail_enfants'] ?? '' }}</td></tr>
            <tr><td>3.2 Demandez-vous aux enfants de 13 à 16 ans d'effectuer des travaux lourds et fatigants ?</td><td>{{ $data['travaux_lourds_enfants'] ?? '' }}</td></tr>
            <tr><td>3.3 Les travaux dangereux sont-ils effectués par des enfants, des travailleuses enceintes, des travailleurs handicapés, des travailleurs malades ?</td><td>{{ $data['travaux_dangereux'] ?? '' }}</td></tr>
            <tr><td>3.4 Avez-vous connaissance de faits de coercition mentale ou physique, de violence verbale, de harcèlement sexuel, de toute forme d'intimidation ou de traitement dur ou inhumain dans le champ ?</td><td>{{ $data['coercition'] ?? '' }}</td></tr>
            <tr><td>3.5 Est-ce que tous les enfants de 4 à 12 ans de votre localité ont accès à l'école ?</td><td>{{ $data['ecole_enfants'] ?? '' }}</td></tr>
        </table>
        
        <div class="q">Conformité</div>
        <table class="table">
            <tr><th>Question</th><th>Réponse</th></tr>
            <tr><td>3.6 Existe-t-il un conflit de propriété ou une contestation quelconque sur la propriété du/des champ/s que vous exploitez ?</td><td>{{ $data['conflit_propriete'] ?? '' }}</td></tr>
            <tr><td>3.7 Etes-vous en possession d'un titre de propriété des champs que vous cultivez ?</td><td>{{ $data['titre_propriete'] ?? '' }}</td></tr>
        </table>

        <h4>5. CONDITIONS DE TRAVAIL ET D'EMPLOI</h4>
        <table class="table">
            <tr><th>Question</th><th>Réponse</th></tr>
            <tr><td>3.8 Qui le producteur emploie-t-il pour travailler dans son champ ?</td><td>{{ !empty($data['travail_parents']) ? 'Parents ' : '' }}{{ !empty($data['travail_autres']) ? 'Autres travailleurs ' : '' }}{{ !empty($data['travail_personne']) ? 'Personne' : '' }}</td></tr>
            <tr><td>3.9 Existe-t-il un campement pour accueillir les travailleurs dans votre ferme ?</td><td>{{ $data['campement'] ?? '' }}</td></tr>
            <tr><td>3.10 Sur quelle base le producteur emploie-t-il ses travailleurs ?</td><td>{{ !empty($data['base_contrat']) ? 'Contrat écrit ' : '' }}{{ !empty($data['base_accord_verbal']) ? 'Accord verbal ' : '' }}{{ !empty($data['base_accord_agri']) ? 'Accord d\'agriculture ' : '' }}{{ !empty($data['base_autre']) ? 'Autre ' : '' }}{{ !empty($data['base_autre_preciser']) ? $data['base_autre_preciser'] : '' }}</td></tr>
            <tr><td>3.11 Le producteur tient-il un registre de gestion du travail de ses travailleurs ?</td><td>{{ $data['registre_travail'] ?? '' }}</td></tr>
            <tr><td>3.12 Les conditions d'emploi sont-elles conformes aux lois nationales et/ou aux conventions collectives ?</td><td>{{ $data['conditions_emploi'] ?? '' }}</td></tr>
            <tr><td>3.13 Comment sont payés vos travailleurs ?</td><td>{{ !empty($data['paiement_prorata']) ? 'Prorata de la production ' : '' }}{{ !empty($data['paiement_salaire']) ? 'Salaire fixe journalier/mensuel' : '' }}</td></tr>
            <tr><td>3.14 Les travailleurs ont-ils accès aux services de base dans votre localité ?</td><td>{{ $data['services_base'] ?? '' }}</td></tr>
        </table>

        <h4>6. TRAÇABILITÉ ET INFORMATIONS GES</h4>
        <table class="table">
            <tr><th>Question</th><th>Réponse</th></tr>
            <tr><td>4.1 Le producteur tient-il un registre dans lequel il enregistre sa production agricole ?</td><td>{{ $data['registre_production'] ?? '' }}</td></tr>
            <tr><td>4.2 Le producteur conserve-t-il les reçus de la vente de ses produits agricoles ?</td><td>{{ $data['recu_vente'] ?? '' }}</td></tr>
            <tr><td>4.3 Le producteur a-t-il signé une Self Déclaration ISCC-EU et son engagement avec la FPH-CI ?</td><td>{{ $data['selfdeclaration_iscc'] ?? '' }}</td></tr>
            <tr><td>4.4 Le producteur enregistre-t-il un journal de terrain indiquant les dates et les quantités d'engrais, de pesticides et d'autres produits agrochimiques appliqués dans son champ ?</td><td>{{ $data['journal_terrain'] ?? '' }}</td></tr>
            <tr><td>4.5 Le producteur brûle-t-il des résidus de culture sur le champ après la récolte ?</td><td>{{ $data['brule_residus'] ?? '' }}</td></tr>
        </table>
</body>
</html> 