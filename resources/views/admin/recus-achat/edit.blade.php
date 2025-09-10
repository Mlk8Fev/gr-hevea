<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Signatures - Reçu {{ $recuAchat->numero_recu }}</title>
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
                <h6 class="fw-semibold mb-0">Modifier Signatures - Reçu {{ $recuAchat->numero_recu }}</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="{{ route('admin.recus-achat.show', $recuAchat) }}" class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:arrow-left-outline" class="icon text-lg"></iconify-icon>
                            Retour au Reçu
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
                                    <p><strong>Nom :</strong> {{ $recuAchat->nom_producteur }}</p>
                                    <p><strong>Prénom :</strong> {{ $recuAchat->prenom_producteur }}</p>
                                    <p><strong>Téléphone :</strong> {{ $recuAchat->telephone_producteur }}</p>
                                    <p><strong>Code FPH-CI :</strong> {{ $recuAchat->code_fphci }}</p>
                                    <p><strong>Secteur :</strong> {{ $recuAchat->secteur_fphci }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Informations de la Livraison</h6>
                                    <p><strong>Centre de Collecte :</strong> {{ $recuAchat->centre_collecte }}</p>
                                    <p><strong>Date de Création :</strong> {{ $recuAchat->date_creation ? $recuAchat->date_creation->format('d/m/Y H:i') : 'N/A' }}</p>
                                    <p><strong>Quantité :</strong> {{ number_format($recuAchat->quantite_livree, 2) }} kg</p>
                                    <p><strong>Prix Unitaire :</strong> {{ number_format($recuAchat->prix_unitaire, 0) }} FCFA</p>
                                    <p><strong>Montant Total :</strong> {{ number_format($recuAchat->montant_total, 0) }} FCFA</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de modification des signatures -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Modifier les Signatures</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.recus-achat.update', $recuAchat) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Signature Acheteur -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">Signature Acheteur</label>
                                            <div class="signature-pad-container">
                                                <canvas id="signatureAcheteur" class="signature-pad" width="400" height="200"></canvas>
                                                <div class="d-flex gap-2 mt-2">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSignature('signatureAcheteur')">
                                                        <iconify-icon icon="solar:refresh-outline"></iconify-icon>
                                                        Effacer
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="reloadSignatures()">
                                                        <iconify-icon icon="solar:refresh-outline"></iconify-icon>
                                                        Recharger
                                                    </button>
                                                </div>
                                                <input type="hidden" name="signature_acheteur" id="signatureAcheteurInput" value="{{ $recuAchat->signature_acheteur }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Signature Producteur -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">Signature Producteur</label>
                                            <div class="signature-pad-container">
                                                <canvas id="signatureProducteur" class="signature-pad" width="400" height="200"></canvas>
                                                <div class="d-flex gap-2 mt-2">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSignature('signatureProducteur')">
                                                        <iconify-icon icon="solar:refresh-outline"></iconify-icon>
                                                        Effacer
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="reloadSignatures()">
                                                        <iconify-icon icon="solar:refresh-outline"></iconify-icon>
                                                        Recharger
                                                    </button>
                                                </div>
                                                <input type="hidden" name="signature_producteur" id="signatureProducteurInput" value="{{ $recuAchat->signature_producteur }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <iconify-icon icon="solar:check-circle-outline"></iconify-icon>
                                        Mettre à jour les Signatures
                                    </button>
                                    <a href="{{ route('admin.recus-achat.show', $recuAchat) }}" class="btn btn-outline-secondary">
                                        <iconify-icon icon="solar:close-circle-outline"></iconify-icon>
                                        Annuler
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
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        // Initialiser les pads de signature
        const signatureAcheteurPad = new SignaturePad(document.getElementById('signatureAcheteur'));
        const signatureProducteurPad = new SignaturePad(document.getElementById('signatureProducteur'));

        // Charger les signatures existantes si elles existent
        @if($recuAchat->signature_acheteur)
            signatureAcheteurPad.fromDataURL('{{ $recuAchat->signature_acheteur }}');
            // Mettre à jour l'input caché avec la signature existante
            document.getElementById('signatureAcheteurInput').value = '{{ $recuAchat->signature_acheteur }}';
        @endif

        @if($recuAchat->signature_producteur)
            signatureProducteurPad.fromDataURL('{{ $recuAchat->signature_producteur }}');
            // Mettre à jour l'input caché avec la signature existante
            document.getElementById('signatureProducteurInput').value = '{{ $recuAchat->signature_producteur }}';
        @endif

        // Mettre à jour les inputs cachés quand les signatures changent
        signatureAcheteurPad.addEventListener('endStroke', () => {
            document.getElementById('signatureAcheteurInput').value = signatureAcheteurPad.toDataURL();
        });

        signatureProducteurPad.addEventListener('endStroke', () => {
            document.getElementById('signatureProducteurInput').value = signatureProducteurPad.toDataURL();
        });

        // Fonction pour effacer les signatures
        function clearSignature(canvasId) {
            if (canvasId === 'signatureAcheteur') {
                signatureAcheteurPad.clear();
                document.getElementById('signatureAcheteurInput').value = '';
            } else if (canvasId === 'signatureProducteur') {
                signatureProducteurPad.clear();
                document.getElementById('signatureProducteurInput').value = '';
            }
        }

        // Fonction pour recharger les signatures existantes
        function reloadSignatures() {
            @if($recuAchat->signature_acheteur)
                signatureAcheteurPad.fromDataURL('{{ $recuAchat->signature_acheteur }}');
                document.getElementById('signatureAcheteurInput').value = '{{ $recuAchat->signature_acheteur }}';
            @endif

            @if($recuAchat->signature_producteur)
                signatureProducteurPad.fromDataURL('{{ $recuAchat->signature_producteur }}');
                document.getElementById('signatureProducteurInput').value = '{{ $recuAchat->signature_producteur }}';
            @endif
        }
    </script>
</body>
</html> 