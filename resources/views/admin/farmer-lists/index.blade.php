<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Lists - Gestion des Livraisons - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
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
            <h6 class="fw-semibold mb-0">Farmer Lists - Gestion des Livraisons</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Farmer Lists</li>
            </ul>
        </div>

        <!-- Filtres -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.farmer-lists.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Rechercher</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Numéro de livraison...">
                            </div>
                            <div class="col-md-3">
                                <label for="cooperative_id" class="form-label">Coopérative</label>
                                <select class="form-select" id="cooperative_id" name="cooperative_id">
                                    <option value="">Toutes les coopératives</option>
                                    @foreach($cooperatives as $cooperative)
                                        <option value="{{ $cooperative->id }}" 
                                                {{ request('cooperative_id') == $cooperative->id ? 'selected' : '' }}>
                                            {{ $cooperative->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="centre_collecte_id" class="form-label">Centre de Collecte</label>
                                <select class="form-select" id="centre_collecte_id" name="centre_collecte_id">
                                    <option value="">Tous les centres</option>
                                    @foreach($centresCollecte as $centre)
                                        <option value="{{ $centre->id }}" 
                                                {{ request('centre_collecte_id') == $centre->id ? 'selected' : '' }}>
                                            {{ $centre->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Filtrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des livraisons -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Livraisons avec Farmer Lists</h5>
                    </div>
                    <div class="card-body">
                        @if($livraisons->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>N° Livraison</th>
                                            <th>Coopérative</th>
                                            <th>Centre de Collecte</th>
                                            <th>Poids Net (kg)</th>
                                            <th>Poids Farmer List (kg)</th>
                                            <th>État</th>
                                            <th>Date Livraison</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($livraisons as $livraison)
                                            <tr>
                                                <td>
                                                    <strong>{{ $livraison->numero_livraison }}</strong>
                                                </td>
                                                <td>{{ $livraison->cooperative->nom }}</td>
                                                <td>{{ $livraison->centreCollecte->nom }}</td>
                                                <td>{{ number_format($livraison->poids_net, 2) }}</td>
                                                <td>{{ number_format($livraison->poids_total_farmer_list, 2) }}</td>
                                                <td>
                                                    @if($livraison->farmer_list_complete)
                                                        <span class="badge bg-success">Complète</span>
                                                    @else
                                                        <span class="badge bg-warning">Incomplète</span>
                                                    @endif
                                                </td>
                                                <td>{{ $livraison->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.farmer-lists.show', $livraison) }}" 
                                                           class="btn btn-sm btn-outline-primary">
                                                            <iconify-icon icon="ri-eye-line"></iconify-icon> Voir
                                                        </a>
                                                        @if(!$livraison->farmer_list_complete)
                                                            <a href="{{ route('admin.farmer-lists.create', $livraison) }}" 
                                                               class="btn btn-sm btn-outline-success">
                                                                <iconify-icon icon="ri-add-line"></iconify-icon> Ajouter
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('admin.farmer-lists.pdf', $livraison) }}" 
                                                           class="btn btn-sm btn-outline-info" target="_blank">
                                                            <iconify-icon icon="ri-file-pdf-line"></iconify-icon> PDF
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    Affichage de {{ $livraisons->firstItem() }} à {{ $livraisons->lastItem() }} 
                                    sur {{ $livraisons->total() }} résultats
                                </div>
                                <div>
                                    {{ $livraisons->links() }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <iconify-icon icon="ri-inbox-line" class="display-1 text-muted"></iconify-icon>
                                <h5 class="mt-3">Aucune livraison trouvée</h5>
                                <p class="text-muted">Aucune livraison validée ne correspond à vos critères de recherche.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')
</body>
</html> 