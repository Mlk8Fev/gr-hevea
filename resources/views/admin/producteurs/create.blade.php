<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Producteur - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
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
/* Style pour les inputs de coopératives */
.cooperative-input {
    position: relative;
}
.cooperative-input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
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
                        <i class="ri-home-line icon text-lg"></i>
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
                <h5 class="card-title mb-4"><i class="ri-eye-line me-2 text-primary"></i> Informations principales</h5>
                <div class="alert alert-info mb-0">En tant qu'AT/AGQ, vous pouvez modifier toutes les informations du producteur, gérer les coopératives, les parcelles et les documents.</div>
                
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
                        <input type="number" step="0.01" class="form-control" id="superficie_totale" name="superficie_totale" value="0" readonly>
                        <div class="form-text">Calculée automatiquement à partir des parcelles</div>
                    </div>
                    <div class="col-md-6">
                        <label for="agronica_id" class="form-label">ID AGRONICA</label>
                        <input type="text" class="form-control" id="agronica_id" name="agronica_id" value="{{ old('agronica_id') }}">
                        <div class="form-text">Identifiant AGRONICA du producteur</div>
                    </div>
                    <div class="col-md-6">
                        <label for="localite" class="form-label">Localité</label>
                        <input type="text" class="form-control" id="localite" name="localite" value="{{ old('localite') }}">
                        <div class="form-text">Village ou localité du producteur</div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Coopératives (max 5) *</label>
                        <div id="coop-list">
                            <div class="input-group mb-2 coop-select-row">
                                <input class="form-control cooperative-input" name="cooperatives_display[]" list="cooperatives-list" placeholder="Tapez le nom de la coopérative...">
                                <datalist id="cooperatives-list">
                                    @foreach($cooperatives as $cooperative)
                                        <option value="{{ $cooperative->code }} - {{ $cooperative->nom }}" data-id="{{ $cooperative->id }}">
                                    @endforeach
                                </datalist>
                                <input type="hidden" name="cooperatives[]" class="cooperative-id-input">
                                <button type="button" class="btn btn-outline-danger btn-remove-coop d-none"><i class="ri-delete-bin-line icon"></i></button>
                                <button type="button" class="btn btn-outline-primary btn-add-coop ms-2"><i class="ri-add-line icon"></i></button>
                            </div>
                        </div>
                        <small class="text-muted">Vous pouvez ajouter jusqu'à 5 coopératives. Au moins une coopérative est requise.</small>
                    </div>
                </div>
            </div>
            <div class="card p-24 radius-12 mb-24">
                <h5 class="card-title mb-4"><i class="ri-eye-line me-2 text-warning"></i> Documents de traçabilité</h5>
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
            <!-- Section Parcelles -->
            <div class="card p-24 radius-12 mb-24">
                <h5 class="card-title mb-4">
                    <i class="ri-eye-line me-2 text-primary"></i> 
                    Parcelles (max 10)
                </h5>
                <div id="parcelles-container">
                    <div class="parcelle-row mb-3 p-3 border rounded">
                        <div class="row gy-3">
                            <div class="col-md-4">
                                <label class="form-label">Nom de la parcelle</label>
                                <input type="text" class="form-control" name="parcelles[0][nom_parcelle]" value="PARC1" readonly>
                                <div class="form-text">Généré automatiquement</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Latitude *</label>
                                <input type="number" step="0.00000001" class="form-control" name="parcelles[0][latitude]" placeholder="Ex: 5.12345678">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Longitude *</label>
                                <input type="number" step="0.00000001" class="form-control" name="parcelles[0][longitude]" placeholder="Ex: -4.12345678">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Superficie (ha) *</label>
                                <input type="number" step="0.01" class="form-control" name="parcelles[0][superficie]" placeholder="Ex: 2.5">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Actions</label>
                                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeParcelle(this)">
                                    <i class="ri-delete-bin-line menu-icon"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary" onclick="addParcelle()">
                    <i class="ri-add-line icon text-xl line-height-1"></i> Ajouter une parcelle
                </button>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="ri-eye-line"></i>
                        La superficie totale sera calculée automatiquement à partir des parcelles ajoutées.
                    </small>
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
        // Gérer la sélection des coopératives avec datalist (pour tous les inputs, y compris ceux ajoutés dynamiquement)
        $(document).off('input change blur', '.cooperative-input').on('input change blur', '.cooperative-input', function(e) {
            const input = $(this);
            const value = input.val().trim();
            const datalist = $('#cooperatives-list');
            const hiddenInput = input.siblings('.cooperative-id-input');
            
            // Si le champ est vide, vider l'input caché et permettre la saisie libre
            if (!value) {
                hiddenInput.val('');
                input.removeClass('is-invalid');
                return;
            }
            
            // Trouver l'option correspondante exacte
            const option = datalist.find(`option[value="${value}"]`);
            if (option.length > 0) {
                hiddenInput.val(option.data('id'));
                input.removeClass('is-invalid');
            } else {
                // Ne pas forcer la sélection pendant la saisie
                // On attend que l'utilisateur sélectionne depuis le datalist ou termine sa saisie
                hiddenInput.val('');
                
                // Si c'est un événement blur (perte de focus), alors on peut essayer une recherche partielle
                if (e.type === 'blur' && value.length > 0) {
                    let found = false;
                    datalist.find('option').each(function() {
                        const optionValue = $(this).val().toLowerCase();
                        if (optionValue === value.toLowerCase() || optionValue.startsWith(value.toLowerCase())) {
                            hiddenInput.val($(this).data('id'));
                            input.val($(this).val());
                            input.removeClass('is-invalid');
                            found = true;
                            return false; // break
                        }
                    });
                    if (!found) {
                        // Si aucune correspondance, laisser l'utilisateur continuer à taper
                        hiddenInput.val('');
                    }
                }
            }
        });
    }
    $(document).on('click', '.btn-add-coop', function() {
        let count = $('#coop-list .coop-select-row').length;
        if(count >= 5) return;
        let row = $(this).closest('.coop-select-row').clone();
        row.find('.cooperative-input').val('');
        row.find('.cooperative-id-input').val('');
        $('#coop-list').append(row);
        applySelect2();
        updateButtons();
    });
    $(document).on('click', '.btn-remove-coop', function() {
        $(this).closest('.coop-select-row').remove();
        updateButtons();
    });
    updateButtons();
    applySelect2(); // Initialiser au chargement
});

// Protection contre la soumission automatique et validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action*="producteurs"]');
    if (!form) return;
    
    let submittedByButton = false;
    
    // Empêcher TOUTE soumission automatique
    form.addEventListener('submit', function(e) {
        if (!submittedByButton) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
    }, true);
    
    // Validation et soumission via le bouton
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Marquer que la soumission vient du bouton
            submittedByButton = true;
            
            // 1. Valider et remplir les inputs cachés des coopératives
            const coopRows = form.querySelectorAll('.coop-select-row');
            let hasError = false;
            let hasAtLeastOneCoop = false;
            
            coopRows.forEach((row, index) => {
                const displayInput = row.querySelector('.cooperative-input');
                const hiddenInput = row.querySelector('.cooperative-id-input');
                const value = displayInput.value.trim();
                
                // Si la ligne est vide, on la désactive pour qu'elle ne soit pas envoyée
                if (!value) {
                    displayInput.disabled = true;
                    hiddenInput.disabled = true;
                    displayInput.classList.remove('is-invalid');
                    return; // Passer à la ligne suivante
                }
                
                // Réactiver les inputs si ils ont une valeur
                displayInput.disabled = false;
                hiddenInput.disabled = false;
                
                // Valider la coopérative
                const datalist = document.getElementById('cooperatives-list');
                const option = datalist.querySelector(`option[value="${value}"]`);
                
                if (option) {
                    hiddenInput.value = option.getAttribute('data-id');
                    displayInput.classList.remove('is-invalid');
                    hasAtLeastOneCoop = true;
                } else {
                    // Recherche partielle
                    const options = datalist.querySelectorAll('option');
                    let found = false;
                    for (let opt of options) {
                        if (opt.value.toLowerCase().includes(value.toLowerCase())) {
                            hiddenInput.value = opt.getAttribute('data-id');
                            displayInput.value = opt.value; // Corriger la valeur affichée
                            displayInput.classList.remove('is-invalid');
                            found = true;
                            hasAtLeastOneCoop = true;
                            break;
                        }
                    }
                    if (!found) {
                        displayInput.classList.add('is-invalid');
                        hasError = true;
                    }
                }
            });
            
            // Vérifier qu'au moins une coopérative est sélectionnée
            if (!hasAtLeastOneCoop) {
                hasError = true;
                alert('Veuillez sélectionner au moins une coopérative.');
            }
            
            // 2. Filtrer les parcelles vides avant soumission
            const parcelleRows = form.querySelectorAll('.parcelle-row');
            parcelleRows.forEach((row, index) => {
                const lat = row.querySelector('input[name*="[latitude]"]');
                const lng = row.querySelector('input[name*="[longitude]"]');
                const superficie = row.querySelector('input[name*="[superficie]"]');
                
                // Si tous les champs sont vides, désactiver les inputs pour qu'ils ne soient pas envoyés
                if (!lat.value && !lng.value && !superficie.value) {
                    lat.disabled = true;
                    lng.disabled = true;
                    superficie.disabled = true;
                } else {
                    // Valider que tous les champs requis sont remplis
                    if (!lat.value || !lng.value || !superficie.value) {
                        if (!lat.value) lat.classList.add('is-invalid');
                        if (!lng.value) lng.classList.add('is-invalid');
                        if (!superficie.value) superficie.classList.add('is-invalid');
                        hasError = true;
                    }
                }
            });
            
            // 3. Validation des champs requis
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value || !field.value.trim()) {
                    field.classList.add('is-invalid');
                    hasError = true;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (hasError) {
                submittedByButton = false;
                alert('Veuillez remplir tous les champs obligatoires et sélectionner des coopératives valides.');
                return false;
            }
            
            // Si tout est valide, soumettre le formulaire
            form.submit();
        }, true);
    }
});

let parcelleCount = 1;

function addParcelle() {
    if (parcelleCount >= 10) {
        alert('Vous ne pouvez pas ajouter plus de 10 parcelles.');
        return;
    }
    
    const container = document.getElementById('parcelles-container');
    const newRow = document.createElement('div');
    newRow.className = 'parcelle-row mb-3 p-3 border rounded';
    newRow.innerHTML = `
        <div class="row gy-3">
            <div class="col-md-4">
                <label class="form-label">Nom de la parcelle</label>
                <input type="text" class="form-control" name="parcelles[${parcelleCount}][nom_parcelle]" value="PARC${parcelleCount + 1}" readonly>
                <div class="form-text">Généré automatiquement</div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Latitude *</label>
                <input type="number" step="0.00000001" class="form-control" name="parcelles[${parcelleCount}][latitude]" placeholder="Ex: 5.12345678">
            </div>
            <div class="col-md-2">
                <label class="form-label">Longitude *</label>
                <input type="number" step="0.00000001" class="form-control" name="parcelles[${parcelleCount}][longitude]" placeholder="Ex: -4.12345678">
            </div>
            <div class="col-md-2">
                <label class="form-label">Superficie (ha) *</label>
                <input type="number" step="0.01" class="form-control" name="parcelles[${parcelleCount}][superficie]" placeholder="Ex: 2.5">
            </div>
            <div class="col-md-2">
                <label class="form-label">Actions</label>
                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeParcelle(this)">
                    <i class="ri-delete-bin-line menu-icon"></i> Supprimer
                </button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    parcelleCount++;
}

function removeParcelle(button) {
    const row = button.closest('.parcelle-row');
    row.remove();
    parcelleCount--;
    updateParcelleIndexes();
}

function updateParcelleIndexes() {
    const rows = document.querySelectorAll('.parcelle-row');
    rows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input[name*="parcelles["]');
        inputs.forEach(input => {
            input.name = input.name.replace(/parcelles\[\d+\]/, `parcelles[${index}]`);
        });
    });
}

</script>
</body>
</html> 