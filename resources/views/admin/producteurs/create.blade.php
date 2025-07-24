<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Producteur - WowDash</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <style>
/* Style Select2 pour Wowdash */
.select2-container--default .select2-selection--single {
    height: 40px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: var(--bs-body-bg, #fff);
    padding: 6px 12px;
    font-size: 1rem;
    color: #222;
    box-shadow: none;
    transition: border-color 0.2s;
}
.select2-container--default .select2-selection--single:focus,
.select2-container--default .select2-selection--single.select2-selection--focus {
    border-color: #6366f1;
    outline: none;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 26px;
    color: #222;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
    right: 8px;
}
.select2-dropdown {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    background: var(--bs-body-bg, #fff);
}
.select2-results__option--highlighted {
    background: #f1f5f9 !important;
    color: #222 !important;
}
.select2-results__option--selected {
    background: #6366f1 !important;
    color: #fff !important;
}
</style>
</head>
<body>
@include('partials.sidebar')
<main class="dashboard-main">
    @include('partials.navbar-header')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Ajouter un Producteur</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('admin.producteurs.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Liste des Producteurs
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Ajouter</li>
            </ul>
        </div>
        <form action="{{ route('admin.producteurs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card p-24 radius-12 mb-24">
                <h5 class="card-title mb-4"><iconify-icon icon="solar:info-square-outline" class="me-2 text-primary"></iconify-icon> Informations principales</h5>
                <div class="row gy-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom *</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom *</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="code_fphci" class="form-label">Code producteur FPHCI *</label>
                        <input type="text" class="form-control" id="code_fphci" name="code_fphci" value="{{ old('code_fphci') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="secteur_id" class="form-label">Secteur *</label>
                        <select class="form-select" id="secteur_id" name="secteur_id" required>
                            <option value="">Sélectionner un secteur</option>
                            @foreach($secteurs as $secteur)
                                <option value="{{ $secteur->id }}" {{ old('secteur_id') == $secteur->id ? 'selected' : '' }}>{{ $secteur->code }} - {{ $secteur->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="genre" class="form-label">Genre *</label>
                        <select class="form-select" id="genre" name="genre" required>
                            <option value="">Sélectionner</option>
                            <option value="Homme" {{ old('genre') == 'Homme' ? 'selected' : '' }}>Homme</option>
                            <option value="Femme" {{ old('genre') == 'Femme' ? 'selected' : '' }}>Femme</option>
                            <option value="Autre" {{ old('genre') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="contact" class="form-label">Contact *</label>
                        <input type="text" class="form-control" id="contact" name="contact" value="{{ old('contact') }}" required maxlength="10">
                    </div>
                    <div class="col-md-6">
                        <label for="superficie_totale" class="form-label">Superficie totale (ha)</label>
                        <input type="number" step="0.01" class="form-control" id="superficie_totale" name="superficie_totale" value="{{ old('superficie_totale') }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Coopératives (max 5) *</label>
                        <div id="coop-list">
                            <div class="input-group mb-2 coop-select-row">
                                <select class="form-select" name="cooperatives[]" required>
                                    <option value="">Sélectionner une coopérative</option>
                                    @foreach($cooperatives as $cooperative)
                                        <option value="{{ $cooperative->id }}">{{ $cooperative->code }} - {{ $cooperative->nom }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-danger btn-remove-coop d-none"><iconify-icon icon="ic:round-remove" class="icon"></iconify-icon></button>
                                <button type="button" class="btn btn-outline-primary btn-add-coop ms-2"><iconify-icon icon="ic:round-add" class="icon"></iconify-icon></button>
                            </div>
                        </div>
                        <small class="text-muted">Vous pouvez ajouter jusqu'à 5 coopératives</small>
                    </div>
                </div>
            </div>
            <div class="card p-24 radius-12 mb-24">
                <h5 class="card-title mb-4"><iconify-icon icon="solar:document-text-outline" class="me-2 text-warning"></iconify-icon> Documents de traçabilité</h5>
                <div class="row gy-3">
                    @foreach($documentTypes as $key => $label)
                    <div class="col-md-6">
                        <label class="form-label">{{ $label }}</label>
                        <div class="d-flex gap-2 align-items-center">
                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled>Remplir digitalement</button>
                            <span>ou</span>
                            <input type="file" class="form-control" name="{{ $key }}_fichier" accept=".pdf,image/*">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success px-4">Créer le producteur</button>
                <a href="{{ route('admin.producteurs.index') }}" class="btn btn-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>
</main>
@include('partials.wowdash-scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function() {
    function updateButtons() {
        let rows = $('#coop-list .coop-select-row');
        rows.find('.btn-add-coop').addClass('d-none');
        rows.last().find('.btn-add-coop').removeClass('d-none');
        rows.find('.btn-remove-coop').toggleClass('d-none', rows.length === 1);
    }
    function applySelect2() {
        $('#coop-list select').select2({
            width: '100%',
            placeholder: 'Sélectionner une coopérative',
            allowClear: true,
            dropdownParent: $('#coop-list')
        });
    }
    $(document).on('click', '.btn-add-coop', function() {
        let count = $('#coop-list .coop-select-row').length;
        if(count >= 5) return;
        let row = $(this).closest('.coop-select-row').clone();
        row.find('select').val('');
        row.find('select').next('.select2-container').remove();
        $('#coop-list').append(row);
        applySelect2();
        updateButtons();
    });
    $(document).on('click', '.btn-remove-coop', function() {
        $(this).closest('.coop-select-row').remove();
        updateButtons();
    });
    applySelect2();
    updateButtons();
});
</script>
</body>
</html> 