@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card radius-12 p-24">
                @if(isset($type) && $type === 'fiche_enquete')
                    <h4 class="mb-4 fw-bold text-primary">Fiche d'enquête du producteur</h4>
                    <form action="{{ route('admin.producteurs.documents.store', ['producteur' => $producteur->id, 'type' => 'fiche_enquete']) }}" method="POST" enctype="multipart/form-data" id="fiche-enquete-form">
                        @csrf
                        <div id="wizard-steps">
                            <!-- Étape 1 -->
                            <div class="wizard-step" id="step-1">
                                <h5 class="fw-bold mb-3">1. Données de l'opérateur et de l'agriculteur</h5>
                                <div class="row gy-3">
                                    <div class="col-md-6">
                                        <label class="form-label">ID producteur</label>
                                        <input type="text" class="form-control" value="{{ $producteur->code_fphci }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Culture certifiée</label>
                                        <input type="text" class="form-control" value="Hevea brasiliensis" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nom de l'enquêteur</label>
                                        <input type="text" class="form-control" name="enqueteur_nom" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Numéro de téléphone (enquêteur)</label>
                                        <input type="text" class="form-control" name="enqueteur_tel" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nom du producteur</label>
                                        <input type="text" class="form-control" value="{{ $producteur->nom }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Prénoms du producteur</label>
                                        <input type="text" class="form-control" value="{{ $producteur->prenom }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Numéro de téléphone (producteur)</label>
                                        <input type="text" class="form-control" value="{{ $producteur->contact }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Région</label>
                                        <input type="text" class="form-control" name="region" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Département</label>
                                        <input type="text" class="form-control" name="departement" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Localité</label>
                                        <input type="text" class="form-control" name="localite" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Secteur FPH-CI</label>
                                        <input type="text" class="form-control" value="{{ $producteur->secteur ? $producteur->secteur->nom : '' }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Genre</label>
                                        <select class="form-select" name="genre" required>
                                            <option value="">Sélectionner</option>
                                            <option value="Homme">Homme</option>
                                            <option value="Femme">Femme</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Superficie totale cultivée (ha)</label>
                                        <input type="number" step="0.01" class="form-control" name="superficie_totale" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre total de champs (cacao + hevea+…)</label>
                                        <input type="number" class="form-control" name="nb_champs_total" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Superficie totale cultivée avec hévéa (ha)</label>
                                        <input type="number" step="0.01" class="form-control" name="superficie_hevea" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre de plantations d’hévéas</label>
                                        <input type="number" class="form-control" name="nb_plantations_hevea" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Clone principal</label>
                                        <select class="form-select" name="clone_principal" required>
                                            <option value="">Sélectionner</option>
                                            <option value="GT1">GT1</option>
                                            <option value="PB217">PB217</option>
                                            <option value="PB235">PB 235</option>
                                            <option value="PR107">PR 107</option>
                                            <option value="RRIM600">RRIM 600</option>
                                            <option value="Autres">Autres</option>
                                        </select>
                                        <input type="text" class="form-control mt-2 d-none" name="clone_autre" placeholder="Préciser si autre">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Année de création du champ d’hévéa</label>
                                        <input type="number" class="form-control" name="annee_creation_champ" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Pour laquelle de ces productions êtes-vous déjà certifiée ?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="certif_cacao" value="1" id="certif_cacao">
                                            <label class="form-check-label" for="certif_cacao">Cacao</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="certif_cafe" value="1" id="certif_cafe">
                                            <label class="form-check-label" for="certif_cafe">Café</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="certif_palmier" value="1" id="certif_palmier">
                                            <label class="form-check-label" for="certif_palmier">Palmier à huile</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="certif_autre" value="1" id="certif_autre">
                                            <label class="form-check-label" for="certif_autre">Autre (préciser)</label>
                                        </div>
                                        <input type="text" class="form-control mt-2 d-none" name="certif_autre_preciser" placeholder="Préciser si autre">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Le Producteur marque son accord pour la réalisation de cette enquête de durabilité, librement et sans contrainte.</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="accord_enquete" value="oui" id="accord_oui" required>
                                            <label class="form-check-label" for="accord_oui">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="accord_enquete" value="non" id="accord_non">
                                            <label class="form-check-label" for="accord_non">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date_enquete" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Signature du producteur</label>
                                        <div class="border p-2 radius-8 bg-light mb-2" style="position:relative;">
                                            <canvas id="signature-producer" width="400" height="120" style="border-radius:8px; background:#fff; border:1px solid #e5e7eb;"></canvas>
                                            <input type="hidden" name="signature_producer" id="signature-producer-input">
                                            <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" id="clear-signature-producer">Effacer</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Signature de l’agent de traçabilité</label>
                                        <div class="border p-2 radius-8 bg-light mb-2" style="position:relative;">
                                            <canvas id="signature-agent" width="400" height="120" style="border-radius:8px; background:#fff; border:1px solid #e5e7eb;"></canvas>
                                            <input type="hidden" name="signature_agent" id="signature-agent-input">
                                            <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" id="clear-signature-agent">Effacer</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-3 mt-4 justify-content-end">
                                    <button type="button" class="btn btn-primary px-4" id="next-step-1">Suivant</button>
                                </div>
                            </div>
                            <!-- Étape 2 -->
                            <div class="wizard-step d-none" id="step-2">
                                <h5 class="fw-bold mb-3">2. Enquête de certification ISCC-EU</h5>
                                <div class="row gy-3">
                                    {{-- IMPACT ENVIRONNEMENTAL --}}
                                    <div class="col-12"><strong>1. IMPACT ENVIRONNEMENTAL</strong></div>
                                    <div class="col-md-12"><strong>Protection du sol</strong></div>
                                    <div class="col-md-12">
                                        <label class="form-label">1.1. Existe-t-il des problèmes d’érosion du sol dans les champs ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="erosion_sol" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="erosion_sol" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">1.2. Existe-t-il autour des champs des rangées d’arbre en vue de protéger les champs des grands vents ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="protection_vents" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="protection_vents" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12"><strong>Produits phytosanitaires</strong></div>
                                    <div class="col-md-12">
                                        <label class="form-label">1.3. Utilisez-vous des pesticides ou autres produits agrochimiques dans vos champs ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pesticides_utilises" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pesticides_utilises" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Si oui, listez et dites les quantités de pesticide ou produits agrochimiques utilisés dans le champ :</label>
                                        <textarea class="form-control" name="liste_pesticides" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">1.4. Le producteur a-t-il reçu une formation sur la gestion et l’application des pesticides et produits agrochimiques ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="formation_pesticides" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="formation_pesticides" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">1.5. Les pesticides/produits agrochimiques sont-ils gérés et appliqués par des professionnels ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pro_pesticides" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pro_pesticides" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">1.6. Le producteur possède-t-il son propre équipement de protection personnelle et un équipement de pulvérisation ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="equipement_protection" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="equipement_protection" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">1.7. Quels sont les équipements de Protection Individuelle que vous avez ?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="epi_bottes" value="1" id="epi_bottes">
                                            <label class="form-check-label" for="epi_bottes">Bottes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="epi_gants" value="1" id="epi_gants">
                                            <label class="form-check-label" for="epi_gants">Gants</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="epi_masques" value="1" id="epi_masques">
                                            <label class="form-check-label" for="epi_masques">Masques</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="epi_autres" value="1" id="epi_autres">
                                            <label class="form-check-label" for="epi_autres">Autres (préciser)</label>
                                        </div>
                                        <input type="text" class="form-control mt-2 d-none" name="epi_autres_preciser" placeholder="Préciser si autre">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">1.8. Que faites-vous des emballages vides des produits pesticides ou autres produits agrochimiques quand vous finissez de les utiliser ?</label>
                                        <select class="form-select" name="gestion_emballages" required>
                                            <option value="">Sélectionner</option>
                                            <option value="Je les jette dans la nature">Je les jette dans la nature</option>
                                            <option value="Je les brule">Je les brule</option>
                                            <option value="Je les enterre">Je les enterre</option>
                                            <option value="Je les stocke dans une zone spécialement désignée">Je les stocke dans une zone spécialement désignée</option>
                                            <option value="Autre">Autre (préciser)</option>
                                        </select>
                                        <input type="text" class="form-control mt-2 d-none" name="gestion_emballages_autre" placeholder="Préciser si autre">
                                    </div>
                                    {{-- SANTÉ ET SÉCURITÉ --}}
                                    <div class="col-12 mt-4"><strong>2. SANTÉ ET SÉCURITÉ</strong></div>
                                    <div class="col-md-12">
                                        <label class="form-label">2.1 Le producteur a-t-il reçu une formation sanitaire et sécuritaire adéquate pour les pratiques agricoles usitées dans sa zone ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="formation_sante" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="formation_sante" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">2.2 Le producteur utilise-t-il correctement le matériel de protection personnelle requis pour la pratique de l’agriculture ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="utilisation_epi" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="utilisation_epi" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                        <input type="text" class="form-control mt-2 d-none" name="utilisation_epi_non_raison" placeholder="Si non, pourquoi ?">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">2.3 Avez-vous accès à une boite à pharmacie appropriée à côté du champ où vous travaillez ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pharmacie_acces" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pharmacie_acces" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">2.4 Avez-vous accès à une grande quantité d’eau à côté du champ où vous travaillez ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="eau_acces" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="eau_acces" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">2.5 S’est-il produit une de ces blessures dans le champ l’année dernière ?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="blessure_materiel" value="1" id="blessure_materiel">
                                            <label class="form-check-label" for="blessure_materiel">Blessure provoquée par l’utilisation de matériels agricoles</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="blessure_epi" value="1" id="blessure_epi">
                                            <label class="form-check-label" for="blessure_epi">Blessure due à la mauvaise utilisation des équipements de protection personnelle</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="blessure_intox" value="1" id="blessure_intox">
                                            <label class="form-check-label" for="blessure_intox">Intoxication chimique</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="blessure_autre" value="1" id="blessure_autre">
                                            <label class="form-check-label" for="blessure_autre">Autre (Préciser)</label>
                                        </div>
                                        <input type="text" class="form-control mt-2 d-none" name="blessure_autre_preciser" placeholder="Préciser si autre">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">2.6 Connaissez-vous les numéros d’urgence de l’hôpital ou l’infirmière le plus proche ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="urgence_numero" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="urgence_numero" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">2.7 Possédez-vous un magasin pour le stockage des produits phytosanitaires ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="magasin_phytos" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="magasin_phytos" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    {{-- IMPACT SOCIAL --}}
                                    <div class="col-12 mt-4"><strong>3. IMPACT SOCIAL</strong></div>
                                    <div class="col-md-12"><strong>Restrictions relatives aux enfants et au travail</strong></div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.1 Les enfants du producteur, ou d’autres enfants de moins de 18 ans travaillent-ils dans le champ ?</label>
                                        <textarea class="form-control" name="travail_enfants" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.2 Demandez-vous aux enfants de 13 à 16 ans d’effectuer des travaux lourds et fatigants ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="travaux_lourds_enfants" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="travaux_lourds_enfants" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.3 Les travaux dangereux sont-ils effectués par des enfants, des travailleuses enceintes, des travailleurs handicapés, des travailleurs malades ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="travaux_dangereux" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="travaux_dangereux" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.4 Avez-vous connaissance de faits de coercition mentale ou physique, de violence verbale, de harcèlement sexuel, de toute forme d’intimidation ou de traitement dur ou inhumain dans le champ ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="coercition" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="coercition" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.5 Est-ce que tous les enfants de 4 à 12 ans de votre localité ont accès à l’école ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="ecole_enfants" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="ecole_enfants" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12"><strong>Conformité</strong></div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.6 Existe-t-il un conflit de propriété ou une contestation quelconque sur la propriété du/des champ/s que vous exploitez ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="conflit_propriete" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="conflit_propriete" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.7 Etes-vous en possession d’un titre de propriété des champs que vous cultivez ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="titre_propriete" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="titre_propriete" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    {{-- CONDITIONS DE TRAVAIL ET D'EMPLOI --}}
                                    <div class="col-12 mt-4"><strong>Conditions de travail et d'emploi</strong></div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.8 Qui le producteur emploie-t-il pour travailler dans son champ ?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="travail_parents" value="1" id="travail_parents">
                                            <label class="form-check-label" for="travail_parents">Parents</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="travail_autres" value="1" id="travail_autres">
                                            <label class="form-check-label" for="travail_autres">Autres travailleurs</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="travail_personne" value="1" id="travail_personne">
                                            <label class="form-check-label" for="travail_personne">Personne</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.9 Existe-t-il un campement pour accueillir les travailleurs dans votre ferme ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="campement" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="campement" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.10 Sur quelle base le producteur emploie-t-il ses travailleurs ?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="base_contrat" value="1" id="base_contrat">
                                            <label class="form-check-label" for="base_contrat">Contrat écrit</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="base_accord_verbal" value="1" id="base_accord_verbal">
                                            <label class="form-check-label" for="base_accord_verbal">Accord verbal</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="base_accord_agri" value="1" id="base_accord_agri">
                                            <label class="form-check-label" for="base_accord_agri">Accord d’agriculture</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="base_autre" value="1" id="base_autre">
                                            <label class="form-check-label" for="base_autre">Autre (préciser)</label>
                                        </div>
                                        <input type="text" class="form-control mt-2 d-none" name="base_autre_preciser" placeholder="Préciser si autre">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.11 Le producteur tient-il un registre de gestion du travail de ses travailleurs dans lequel il enregistre leurs performances, heures de travail, salaire et autres ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="registre_travail" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="registre_travail" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.12 Les conditions d'emploi sont-elles conformes aux lois nationales et/ou aux conventions collectives ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="conditions_emploi" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="conditions_emploi" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.13 Comment sont payés vos travailleurs ?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="paiement_prorata" value="1" id="paiement_prorata">
                                            <label class="form-check-label" for="paiement_prorata">Prorata de la production</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="paiement_salaire" value="1" id="paiement_salaire">
                                            <label class="form-check-label" for="paiement_salaire">Salaire fixe journalier/mensuel</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">3.14 Les travailleurs ont-ils accès aux services de base dans votre localité ? (Ecoles, services de santé, etc.)</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="services_base" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="services_base" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    {{-- TRAÇABILITÉ ET INFORMATIONS GES --}}
                                    <div class="col-12 mt-4"><strong>4. TRAÇABILITÉ ET INFORMATIONS GES</strong></div>
                                    <div class="col-md-12">
                                        <label class="form-label">4.1 Le producteur tient-il un registre dans lequel il enregistre sa production agricole ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="registre_production" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="registre_production" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">4.2 Le producteur conserve-t-il les reçus de la vente de ses produits agricoles ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="recu_vente" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="recu_vente" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">4.3 Le producteur a-t-il signé une Self Déclaration ISCC-EU et son engagement avec la FPH-CI ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="selfdeclaration_iscc" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="selfdeclaration_iscc" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">4.4 Le producteur enregistre-t-il un journal de terrain indiquant les dates et les quantités d'engrais, de pesticides et d'autres produits agrochimiques appliqués dans son champ ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="journal_terrain" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="journal_terrain" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">4.5 Le producteur brûle-t-il des résidus de culture sur le champ après la récolte ?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="brule_residus" value="oui" required> <label class="form-check-label">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="brule_residus" value="non"> <label class="form-check-label">Non</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-3 mt-4 justify-content-between">
                                    <button type="button" class="btn btn-secondary px-4" id="prev-step-2">Précédent</button>
                                    <button type="submit" class="btn btn-success px-4">Enregistrer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
                    <script>
                    // Wizard navigation
                    document.getElementById('next-step-1').onclick = function() {
                        document.getElementById('step-1').classList.add('d-none');
                        document.getElementById('step-2').classList.remove('d-none');
                    };
                    document.getElementById('prev-step-2').onclick = function() {
                        document.getElementById('step-2').classList.add('d-none');
                        document.getElementById('step-1').classList.remove('d-none');
                    };
                    // Signature pads
                    const signaturePadProducer = new SignaturePad(document.getElementById('signature-producer'), { backgroundColor: '#fff' });
                    document.getElementById('clear-signature-producer').onclick = function() { signaturePadProducer.clear(); };
                    const signaturePadAgent = new SignaturePad(document.getElementById('signature-agent'), { backgroundColor: '#fff' });
                    document.getElementById('clear-signature-agent').onclick = function() { signaturePadAgent.clear(); };
                    // Validation à la soumission
                    document.getElementById('fiche-enquete-form').onsubmit = function(e) {
                        if (signaturePadProducer.isEmpty() || signaturePadAgent.isEmpty()) {
                            alert('Veuillez signer les deux signatures avant de soumettre.');
                            e.preventDefault();
                            return false;
                        }
                        document.getElementById('signature-producer-input').value = signaturePadProducer.toDataURL();
                        document.getElementById('signature-agent-input').value = signaturePadAgent.toDataURL();
                    };
                    // Affichage des champs "autre" si besoin
                    document.querySelector('select[name="clone_principal"]').onchange = function() {
                        document.querySelector('input[name="clone_autre"]').classList.toggle('d-none', this.value !== 'Autres');
                    };
                    document.getElementById('certif_autre').onchange = function() {
                        document.querySelector('input[name="certif_autre_preciser"]').classList.toggle('d-none', !this.checked);
                    };
                    document.getElementById('base_autre').onchange = function() {
                        document.querySelector('input[name="base_autre_preciser"]').classList.toggle('d-none', !this.checked);
                    };
                    </script>
                @elseif(isset($type) && $type === 'self_declaration')
                    <h4 class="mb-4 fw-bold text-primary">Déclaration sur l'honneur du producteur</h4>
                    <form action="{{ route('admin.producteurs.documents.store', ['producteur' => $producteur->id, 'type' => 'self_declaration']) }}" method="POST" enctype="multipart/form-data" id="selfdeclaration-form">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control" value="{{ $producteur->nom }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prénoms</label>
                                <input type="text" class="form-control" value="{{ $producteur->prenom }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone</label>
                                <input type="text" class="form-control" value="{{ $producteur->contact }}" readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Adresse complète</label>
                                <textarea class="form-control" name="adresse_complete" rows="2" required placeholder="Adresse, Code postal, Ville/Région, Département, Sous-préfecture"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lieu</label>
                                <input type="text" class="form-control" name="lieu" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="text" class="form-control" value="{{ date('d/m/Y') }}" readonly>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Signature du producteur <span class="text-danger">*</span></label>
                            <div class="border p-2 radius-8 bg-light mb-2" style="position:relative;">
                                <canvas id="signature-pad" width="400" height="120" style="border-radius:8px; background:#fff; border:1px solid #e5e7eb;"></canvas>
                                <input type="hidden" name="signature" id="signature-input">
                                <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" id="clear-signature">Effacer</button>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-success px-4">Enregistrer</button>
                            <a href="{{ route('admin.producteurs.show', $producteur) }}" class="btn btn-secondary px-4">Annuler</a>
                        </div>
                    </form>
                    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
                    <script>
                    const canvas = document.getElementById('signature-pad');
                    const signaturePad = new SignaturePad(canvas, { backgroundColor: '#fff' });
                    document.getElementById('clear-signature').onclick = function() {
                        signaturePad.clear();
                    };
                    document.getElementById('selfdeclaration-form').onsubmit = function(e) {
                        if (signaturePad.isEmpty()) {
                            alert('Veuillez signer avant de soumettre.');
                            e.preventDefault();
                            return false;
                        }
                        document.getElementById('signature-input').value = signaturePad.toDataURL();
                    };
                    </script>
                @else
                    <h4 class="mb-4 fw-bold text-primary">Lettre d'engagement du producteur</h4>
                    <form action="{{ route('admin.producteurs.documents.store', ['producteur' => $producteur->id, 'type' => 'lettre_engagement']) }}" method="POST" enctype="multipart/form-data" id="engagement-form">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control" value="{{ $producteur->nom }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prénom</label>
                                <input type="text" class="form-control" value="{{ $producteur->prenom }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Genre</label>
                                <select class="form-select" name="genre" required>
                                    <option value="">Sélectionner</option>
                                    <option value="Mme">Mme</option>
                                    <option value="Mlle">Mlle</option>
                                    <option value="M">M.</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" name="date_naissance" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lieu de naissance</label>
                                <input type="text" class="form-control" name="lieu_naissance" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profession</label>
                                <input type="text" class="form-control" name="profession" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nom du Bureau de Secteur</label>
                                <input type="text" class="form-control" value="{{ $producteur->secteur ? $producteur->secteur->nom : '' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Domicile</label>
                                <input type="text" class="form-control" name="domicile" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Superficie (ha)</label>
                                <input type="text" class="form-control" value="{{ $producteur->superficie_totale }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lieu de la plantation</label>
                                <input type="text" class="form-control" name="lieu_plantation" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Commune</label>
                                <input type="text" class="form-control" name="commune" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sous-préfecture</label>
                                <input type="text" class="form-control" name="sous_prefecture" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date de signature</label>
                                <input type="date" class="form-control" name="date_signature" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Signature du producteur <span class="text-danger">*</span></label>
                            <div class="border p-2 radius-8 bg-light mb-2" style="position:relative;">
                                <canvas id="signature-pad" width="400" height="120" style="border-radius:8px; background:#fff; border:1px solid #e5e7eb;"></canvas>
                                <input type="hidden" name="signature" id="signature-input">
                                <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" id="clear-signature">Effacer</button>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-success px-4">Enregistrer</button>
                            <a href="{{ route('admin.producteurs.show', $producteur) }}" class="btn btn-secondary px-4">Annuler</a>
                        </div>
                    </form>
                    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
                    <script>
                    const canvas = document.getElementById('signature-pad');
                    const signaturePad = new SignaturePad(canvas, { backgroundColor: '#fff' });
                    document.getElementById('clear-signature').onclick = function() {
                        signaturePad.clear();
                    };
                    document.getElementById('engagement-form').onsubmit = function(e) {
                        if (signaturePad.isEmpty()) {
                            alert('Veuillez signer avant de soumettre.');
                            e.preventDefault();
                            return false;
                        }
                        document.getElementById('signature-input').value = signaturePad.toDataURL();
                    };
                    </script>
                @endif
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
@endsection 