<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générer Reçu d'Achat - {{ $connaissement->numero_livraison }}</title>
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
    @include('partials.sidebar', ['navigation' => $navigation])
    <main class="dashboard-main">
        @include('partials.navbar-header')
        <div class="dashboard-main-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <h6 class="fw-semibold mb-0">Générer Reçu d'Achat - {{ $connaissement->numero_livraison }}</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="{{ route('admin.farmer-lists.show', $connaissement) }}" class="d-flex align-items-center gap-1 hover-text-primary">
                            <i class="ri-home-line icon text-lg"></i>
                            Retour à la Farmer List
                        </a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Informations du Reçu d'Achat</h6>
                        </div>
                        <div class="card-body">
                            <!-- Informations du producteur -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Informations du Producteur</h6>
                                    <p><strong>Nom :</strong> {{ $farmerList->producteur->nom }}</p>
                                    <p><strong>Prénom :</strong> {{ $farmerList->producteur->prenom }}</p>
                                    <p><strong>Téléphone :</strong> {{ $farmerList->producteur->contact }}</p>
                                    <p><strong>Code FPH-CI :</strong> {{ $farmerList->code_producteur }}</p>
                                    <p><strong>Secteur :</strong> {{ $farmerList->producteur->secteur->nom }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Informations de la Livraison</h6>
                                    <p><strong>Centre de Collecte :</strong> {{ $connaissement->centreCollecte->nom }}</p>
                                    <p><strong>Date de Livraison :</strong> {{ $farmerList->date_livraison->format('d/m/Y') }}</p>
                                    <p><strong>Quantité :</strong> {{ number_format($farmerList->quantite_livree, 2) }} kg</p>
                                    <p><strong>Nombre de Sacs :</strong> {{ $farmerList->nombre_sacs }}</p>
                                </div>
                            </div>

                            <!-- Calcul du prix -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Calcul du Prix</h6>
                                    <p><strong>Prix Unitaire :</strong> {{ number_format($prixUnitaire, 2) }} FCFA/kg</p>
                                    <p><strong>Montant à Payer :</strong> {{ number_format($montantAPayer, 2) }} FCFA</p>
                                </div>
                            </div>

                            <!-- Formulaire de signatures -->
                            <form action="{{ route('admin.recus-achat.store', ['connaissement' => $connaissement, 'farmerList' => $farmerList]) }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Signature Acheteur</label>
                                        <div class="signature-pad-container">
                                            <canvas id="signatureAcheteur" width="300" height="150" class="border rounded"></canvas>
                                            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature('signatureAcheteur')">Effacer</button>
                                        </div>
                                        <input type="hidden" name="signature_acheteur" id="signatureAcheteurInput">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Signature Producteur</label>
                                        <div class="signature-pad-container">
                                            <canvas id="signatureProducteur" width="300" height="150" class="border rounded"></canvas>
                                            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature('signatureProducteur')">Effacer</button>
                                        </div>
                                        <input type="hidden" name="signature_producteur" id="signatureProducteurInput">
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-file-text-line"></i> Générer le Reçu
                                    </button>
                                    <a href="{{ route('admin.farmer-lists.show', $connaissement) }}" class="btn btn-secondary">
                                        <i class="ri-arrow-left-line"></i> Annuler
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('partials.wowdash-scripts')
    
    <script>
        // Signature Pad JavaScript
        function initSignaturePad(canvasId, inputId) {
            const canvas = document.getElementById(canvasId);
            const input = document.getElementById(inputId);
            const ctx = canvas.getContext('2d');
            
            let isDrawing = false;
            
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);
            
            function startDrawing(e) {
                isDrawing = true;
                draw(e);
            }
            
            function draw(e) {
                if (!isDrawing) return;
                
                const rect = canvas.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#000';
                
                ctx.lineTo(x, y);
                ctx.stroke();
                ctx.beginPath();
                ctx.moveTo(x, y);
                
                // Mettre à jour l'input avec l'image
                input.value = canvas.toDataURL();
            }
            
            function stopDrawing() {
                isDrawing = false;
                ctx.beginPath();
            }
            
            window.clearSignature = function(canvasId) {
                const canvas = document.getElementById(canvasId);
                const input = document.getElementById(canvasId + 'Input');
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                input.value = '';
            };
        }
        
        // Initialiser les signature pads
        initSignaturePad('signatureAcheteur', 'signatureAcheteurInput');
        initSignaturePad('signatureProducteur', 'signatureProducteurInput');
    </script>
</body>
</html> 