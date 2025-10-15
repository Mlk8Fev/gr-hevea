<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer List - {{ $connaissement->numero_livraison }} - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
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
            <h6 class="fw-semibold mb-0">Farmer List - {{ $connaissement->numero_livraison }}</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">
                    <a href="{{ route('admin.farmer-lists.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        Farmer Lists
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">{{ $connaissement->numero_livraison }}</li>
            </ul>
        </div>

        <!-- Informations de la livraison -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informations de la Livraison</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>N° Livraison:</strong><br>
                                {{ $connaissement->numero_livraison }}
                            </div>
                            <div class="col-md-3">
                                <strong>Coopérative:</strong><br>
                                {{ $connaissement->cooperative->nom }}
                            </div>
                            <div class="col-md-3">
                                <strong>Centre de Collecte:</strong><br>
                                {{ $connaissement->centreCollecte->nom }}
                            </div>
                            <div class="col-md-3">
                                <strong>Poids Net:</strong><br>
                                {{ number_format($poidsNet, 2) }} kg
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progression de la Farmer List -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">Progression de la Farmer List</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Poids</h6>
                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $poidsNet > 0 ? ($poidsTotal / $poidsNet) * 100 : 0 }}%"
                                 aria-valuenow="{{ $poidsNet > 0 ? ($poidsTotal / $poidsNet) * 100 : 0 }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                                {{ $poidsNet > 0 ? number_format(($poidsTotal / $poidsNet) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <p class="mb-0">Poids ajouté: {{ number_format($poidsTotal, 2) }} kg</p>
                        <p class="mb-0">Poids restant: {{ number_format($poidsRestant, 2) }} kg</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Sacs</h6>
                        <div class="progress mb-2" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $sacsNet > 0 ? ($sacsTotal / $sacsNet) * 100 : 0 }}%"
                                 aria-valuenow="{{ $sacsNet > 0 ? ($sacsTotal / $sacsNet) * 100 : 0 }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                                {{ $sacsNet > 0 ? number_format(($sacsTotal / $sacsNet) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <p class="mb-0">Sacs ajoutés: {{ $sacsTotal }} sacs</p>
                        <p class="mb-0">Sacs restants: {{ $sacsRestant }} sacs</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            @if($poidsRestant > 0)
                                <a href="{{ route('admin.farmer-lists.create', $connaissement) }}" 
                                   class="btn btn-success">
                                    <i class="ri-add-line"></i> Ajouter un Producteur
                                </a>
                            @else
                                <button class="btn btn-success" disabled>
                                    <i class="ri-check-line"></i> Farmer List Complète
                                </button>
                            @endif
                            
                            <a href="{{ route('admin.farmer-lists.view', $connaissement) }}" 
                               class="btn btn-info" 
                               target="_blank">
                                <i class="ri-eye-line menu-icon"></i> Voir PDF
                            </a>
                            
                            <a href="{{ route('admin.farmer-lists.pdf', $connaissement) }}" 
                               class="btn btn-primary" 
                               target="_blank">
                                <i class="ri-download-line"></i> Télécharger PDF
                            </a>
                            
                            <a href="{{ route('admin.farmer-lists.index') }}" 
                               class="btn btn-secondary">
                                <i class="ri-arrow-left-line"></i> Retour à la Liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des producteurs -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Producteurs de la Farmer List</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Secteur</th>
                                        <th>Nom & Prénoms</th>
                                        <th>Code Producteur</th>
                                        <th>Géolocalisation</th>
                                        <th>Date Livraison</th>
                                        <th>Quantité (kg)</th>
                                        <th>Nombre de Sacs</th>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($farmerLists as $farmerList)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $farmerList->producteur->secteur->nom ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $farmerList->producteur->nom }} {{ $farmerList->producteur->prenom }}</strong>
                                            </td>
                                            <td>
                                                <code>{{ $farmerList->producteur->code_fphci ?? 'N/A' }}</code>
                                            </td>
                                            <td>
                                                @if($farmerList->geolocalisation_precise)
                                                    <span class="badge bg-success">Oui</span>
                                                @else
                                                    <span class="badge bg-warning">Non</span>
                                                @endif
                                            </td>
                                            <td>{{ $farmerList->date_livraison->format('d/m/Y') }}</td>
                                            <td>
                                                <strong class="text-primary">{{ number_format($farmerList->quantite_livree, 2) }}</strong>
                                            </td>
                                            <td>{{ $farmerList->nombre_sacs ?? 'N/A' }}</td>
                                            <td>
                                                <small class="text-muted">{{ $farmerList->producteur->contact ?? 'N/A' }}</small>
                                            </td>
                                        </tr>
                                        
                                        <!-- Actions en bas de chaque ligne -->
                                        <tr class="bg-light">
                                            <td colspan="9">
                                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                                    <!-- Bouton Modifier Farmer List -->
                                                    <a href="{{ route('admin.farmer-lists.edit', $farmerList) }}" 
                                                       class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                                                        <i class="ri-edit-line"></i>
                                                        <span>Modifier</span>
                                                    </a>
                                                    
                                                    @php
                                                        $recuAchat = \App\Models\RecuAchat::where('farmer_list_id', $farmerList->id)->first();
                                                    @endphp
                                                    
                                                    @if($recuAchat)
                                                        <!-- Boutons si reçu existe -->
                                                        <a href="{{ route('admin.recus-achat.show', $recuAchat) }}" 
                                                           class="btn btn-sm btn-outline-info d-flex align-items-center gap-1">
                                                            <i class="ri-eye-line"></i>
                                                            <span>Voir Reçu</span>
                                                        </a>
                                                        <a href="{{ route('admin.recus-achat.edit', $recuAchat) }}" 
                                                           class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1">
                                                            <i class="ri-edit-line"></i>
                                                            <span>Modifier Signatures</span>
                                                        </a>
                                                        <a href="{{ route('admin.recus-achat.pdf', $recuAchat) }}" 
                                                           class="btn btn-sm btn-outline-success d-flex align-items-center gap-1">
                                                            <i class="ri-eye-line"></i>
                                                            <span>Télécharger PDF</span>
                                                        </a>
                                                    @else
                                                        <!-- Bouton si pas de reçu -->
                                                        <a href="{{ route('admin.recus-achat.create', ['connaissement' => $connaissement, 'farmerList' => $farmerList]) }}" 
                                                           class="btn btn-sm btn-outline-success d-flex align-items-center gap-1">
                                                            <i class="ri-file-text-line"></i>
                                                            <span>Générer Reçu</span>
                                                        </a>
                                                    @endif
                                                    
                                                    <!-- Bouton Supprimer -->
                                                    <form action="{{ route('admin.farmer-lists.destroy', $farmerList) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce producteur de la farmer list ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1">
                                                            <i class="ri-delete-bin-line"></i>
                                                            <span>Supprimer</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Aucun producteur ajouté à cette Farmer List</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')
</body>
</html> 