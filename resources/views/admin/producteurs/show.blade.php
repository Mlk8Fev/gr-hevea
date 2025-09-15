<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Producteur - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>
@include('partials.sidebar')
<main class="dashboard-main">
    @include('partials.navbar-header')
    <div class="dashboard-main-body">
        <div class="row gy-4">
            <div class="col-lg-4">
                <div class="card border radius-16 overflow-hidden bg-base h-100 p-0">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <div class="text-center mb-16">
                            <h3 class="fw-bold text-primary mb-2">{{ $producteur->nom }} {{ $producteur->prenom }}</h3>
                            <span class="badge bg-secondary fs-6">{{ $producteur->code_fphci }}</span>
                        </div>
                        <h6 class="text-lg fw-semibold mb-0">Informations principales</h6>
                    </div>
                    <div class="card-body p-24">
                        <ul class="list-group radius-8">
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:building-bold" class="text-xl text-info"></iconify-icon></span>
                                    <span class="fw-semibold">Secteur :</span> {{ $producteur->secteur ? $producteur->secteur->code . ' - ' . $producteur->secteur->nom : '-' }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:phone-bold" class="text-xl text-warning"></iconify-icon></span>
                                    <span class="fw-semibold">Contact :</span> {{ $producteur->contact }}
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:road-bold" class="text-xl text-secondary"></iconify-icon></span>
                                    <span class="fw-semibold">Superficie totale :</span> {{ $producteur->superficie_totale }} ha
                                </div>
                            </li>
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:user-bold" class="text-xl text-success"></iconify-icon></span>
                                    <span class="fw-semibold">Genre :</span> {{ $producteur->genre }}
                                </div>
                            </li>
                            @if($producteur->agronica_id)
                            <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:tag-bold" class="text-xl text-info"></iconify-icon></span>
                                    <span class="fw-semibold">ID AGRONICA :</span> {{ $producteur->agronica_id }}
                                </div>
                            </li>
                            @endif
                            @if($producteur->localite)
                            <li class="list-group-item border text-secondary-light p-16 bg-base">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:location-bold" class="text-xl text-warning"></iconify-icon></span>
                                    <span class="fw-semibold">Localité :</span> {{ $producteur->localite }}
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-24 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">Coopératives</h6>
                    </div>
                    <div class="card-body p-24">
                        <ul class="list-group radius-8">
                            @foreach($producteur->cooperatives as $coop)
                            <li class="list-group-item border text-secondary-light p-16 bg-base {{ !$loop->last ? 'border-bottom-0' : '' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-flex"><iconify-icon icon="solar:users-group-rounded-bold" class="text-xl text-primary"></iconify-icon></span>
                                    <span class="fw-semibold">{{ $coop->code }} - {{ $coop->nom }}</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card mb-24 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">Documents de traçabilité</h6>
                    </div>
                    <div class="card-body p-24">
                        <ul class="list-group radius-8">
                            @foreach($documentTypes as $key => $label)
                                @php $doc = $producteur->documents->where('type', $key)->first(); @endphp
                                <li class="list-group-item border text-secondary-light p-16 bg-base {{ !$loop->last ? 'border-bottom-0' : '' }}">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($doc && ($doc->fichier || $doc->data))
                                            <iconify-icon icon="solar:check-circle-bold" class="text-success text-xl"></iconify-icon>
                                        @else
                                            <iconify-icon icon="solar:close-circle-bold" class="text-danger text-xl"></iconify-icon>
                                        @endif
                                        <span class="fw-semibold">{{ $label }}</span>
                                        @if($doc && $key === 'lettre_engagement')
                                            <a href="{{ route('admin.admin.producteurs.documents.pdf', ['producteur' => $producteur->id, 'document' => $doc->id]) }}" class="btn btn-outline-primary btn-sm ms-2" target="_blank">Voir</a>
                                            <a href="{{ route('admin.admin.producteurs.documents.pdf', ['producteur' => $producteur->id, 'document' => $doc->id]) }}?download=1" class="btn btn-outline-danger btn-sm ms-2">Télécharger PDF</a>
                                            <a href="{{ route('admin.producteurs.documents.edit', ['producteur' => $producteur->id, 'document' => $doc->id]) }}" class="btn btn-outline-warning btn-sm ms-2">Modifier</a>
                                            <form action="{{ route('admin.producteurs.documents.destroy', ['producteur' => $producteur->id, 'document' => $doc->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette lettre d\'engagement ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm ms-2">Supprimer</button>
                                            </form>
                                        @elseif($doc && $key === 'self_declaration')
                                            <a href="{{ route('admin.admin.producteurs.documents.pdf', ['producteur' => $producteur->id, 'document' => $doc->id]) }}" class="btn btn-outline-primary btn-sm ms-2" target="_blank">Voir</a>
                                            <a href="{{ route('admin.admin.producteurs.documents.pdf', ['producteur' => $producteur->id, 'document' => $doc->id]) }}?download=1" class="btn btn-outline-danger btn-sm ms-2">Télécharger PDF</a>
                                            <a href="{{ route('admin.producteurs.documents.edit', ['producteur' => $producteur->id, 'document' => $doc->id]) }}" class="btn btn-outline-warning btn-sm ms-2">Modifier</a>
                                            <form action="{{ route('admin.producteurs.documents.destroy', ['producteur' => $producteur->id, 'document' => $doc->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette déclaration sur l\'honneur ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm ms-2">Supprimer</button>
                                            </form>
                                        @elseif($doc && $key === 'fiche_enquete')
                                            <a href="{{ route('admin.admin.producteurs.documents.pdf', ['producteur' => $producteur->id, 'document' => $doc->id]) }}" class="btn btn-outline-primary btn-sm ms-2" target="_blank">Voir</a>
                                            <a href="{{ route('admin.admin.producteurs.documents.pdf', ['producteur' => $producteur->id, 'document' => $doc->id]) }}?download=1" class="btn btn-outline-danger btn-sm ms-2">Télécharger PDF</a>
                                            <a href="{{ route('admin.producteurs.documents.edit', ['producteur' => $producteur->id, 'document' => $doc->id]) }}" class="btn btn-outline-warning btn-sm ms-2">Modifier</a>
                                            <form action="{{ route('admin.producteurs.documents.destroy', ['producteur' => $producteur->id, 'document' => $doc->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette fiche d\'enquête ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm ms-2">Supprimer</button>
                                            </form>
                                        @elseif($doc && $doc->fichier)
                                            <a href="{{ asset('storage/' . $doc->fichier) }}" target="_blank" class="btn btn-outline-primary btn-sm ms-2">Voir</a>
                                        @elseif(!$doc && $key === 'lettre_engagement')
                                            <a href="{{ route('admin.producteurs.documents.create', ['producteur' => $producteur->id, 'type' => $key]) }}" class="btn btn-outline-success btn-sm ms-2">Remplir digitalement</a>
                                        @elseif(!$doc && $key === 'self_declaration')
                                            <a href="{{ route('admin.producteurs.documents.create', ['producteur' => $producteur->id, 'type' => $key]) }}" class="btn btn-outline-success btn-sm ms-2">Remplir digitalement</a>
                                        @elseif(!$doc && $key === 'fiche_enquete')
                                            <a href="{{ route('admin.producteurs.documents.create', ['producteur' => $producteur->id, 'type' => $key]) }}" class="btn btn-outline-success btn-sm ms-2">Remplir digitalement</a>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Section Parcelles -->
                <div class="card mb-24 radius-12">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0">
                            <iconify-icon icon="solar:map-outline" class="me-2 text-primary"></iconify-icon>
                            Parcelles ({{ $producteur->parcelles->count() }})
                        </h6>
                    </div>
                    <div class="card-body p-24">
                        @if($producteur->parcelles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Superficie (ha)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($producteur->parcelles as $parcelle)
                                    <tr>
                                        <td>{{ $parcelle->nom_parcelle ?? 'Parcelle ' . $loop->iteration }}</td>
                                        <td>{{ number_format($parcelle->latitude, 8) }}</td>
                                        <td>{{ number_format($parcelle->longitude, 8) }}</td>
                                        <td>{{ number_format($parcelle->superficie, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <th colspan="3">Total</th>
                                        <th>{{ number_format($producteur->parcelles->sum('superficie'), 2) }} ha</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <iconify-icon icon="solar:map-outline" class="text-muted" style="font-size: 3rem;"></iconify-icon>
                            <p class="text-muted mt-2">Aucune parcelle enregistrée</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('admin.producteurs.edit', $producteur) }}" class="btn btn-warning px-4">Modifier</a>
                    <a href="{{ route('admin.producteurs.index') }}" class="btn btn-secondary px-4">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</main>
@include('partials.wowdash-scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
@if($producteur->parcelles->count() > 0)
// Données des parcelles générées côté serveur
var parcelles = {!! json_encode($producteur->parcelles->map(function($p) {
    return [
        'nom' => $p->nom_parcelle ?: 'Parcelle',
        'lat' => $p->latitude,
        'lng' => $p->longitude,
        'superficie' => $p->superficie
    ];
})) !!};

// Initialiser la carte
var map = L.map('map').setView([5.3608, -4.0083], 10); // Centre sur la Côte d'Ivoire

// Ajouter la couche de tuiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Ajouter les marqueurs pour chaque parcelle
parcelles.forEach(function(parcelle, index) {
    var marker = L.marker([parcelle.lat, parcelle.lng]).addTo(map);
    marker.bindPopup('<strong>' + parcelle.nom + '</strong><br><small>Superficie: ' + parcelle.superficie + ' ha</small>');
});

// Ajuster la vue pour inclure tous les marqueurs
if (parcelles.length > 0) {
    var group = new L.featureGroup(map._layers);
    map.fitBounds(group.getBounds().pad(0.1));
}
@endif
</script>
</body>
</html> 