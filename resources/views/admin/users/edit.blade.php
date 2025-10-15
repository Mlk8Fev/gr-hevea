@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Modifier l'utilisateur : {{ $user->name }} {{ $user->nom }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Nom d'utilisateur *</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username', $user->username) }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom complet *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Prénom + Nom" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom complet *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Prénom + Nom" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Rôle *</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="">Sélectionner un rôle</option>
                                    <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Super Administrateur</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="agc" {{ old('role', $user->role) == 'agc' ? 'selected' : '' }}>Agent Gestion Qualité</option>
                                    <option value="cs" {{ old('role', $user->role) == 'cs' ? 'selected' : '' }}>Chef Secteur</option>
                                    <option value="ac" {{ old('role', $user->role) == 'ac' ? 'selected' : '' }}>Assistante Comptable</option>
                                    <option value="rt" {{ old('role', $user->role) == 'rt' ? 'selected' : '' }}>Responsable Traçabilité</option>
                                    <option value="rd" {{ old('role', $user->role) == 'rd' ? 'selected' : '' }}>Responsable Durabilité</option>
                                    <option value="comp" {{ old('role', $user->role) == 'comp' ? 'selected' : '' }}>Comptable Siège</option>
                                    <option value="ctu" {{ old('role', $user->role) == 'ctu' ? 'selected' : '' }}>Contrôleur Usine</option>
                                    <option value="rcoop" {{ old('role', $user->role) == 'rcoop' ? 'selected' : '' }}>Responsable Coopérative</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Utilisateur</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fonction_id" class="form-label">Fonction *</label>
                                <select class="form-select @error('fonction_id') is-invalid @enderror" id="fonction_id" name="fonction_id" required>
                                    <option value="">Sélectionner une fonction</option>
                                    @foreach($fonctions as $fonction)
                                        <option value="{{ $fonction->id }}" {{ old('fonction_id', $user->fonction_id) == $fonction->id ? 'selected' : '' }}>
                                            {{ $fonction->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('fonction_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cooperative_id" class="form-label">Coopérative</label>
                                <input type="text" class="form-control @error('cooperative_id') is-invalid @enderror" 
                                       id="cooperative_display" name="cooperative_display" 
                                       placeholder="Tapez le nom de la coopérative..." 
                                       list="cooperatives-list" 
                                       value="{{ old('cooperative_id', $user->cooperative_id) ? ($cooperatives->find(old('cooperative_id', $user->cooperative_id))->nom ?? '') . ' (' . ($cooperatives->find(old('cooperative_id', $user->cooperative_id))->code ?? '') . ')' : '' }}">
                                <datalist id="cooperatives-list">
                                    @foreach($cooperatives as $cooperative)
                                        <option value="{{ $cooperative->nom }} ({{ $cooperative->code }})" data-id="{{ $cooperative->id }}">
                                    @endforeach
                                </datalist>
                                <input type="hidden" id="cooperative_id" name="cooperative_id" value="{{ old('cooperative_id', $user->cooperative_id) }}">
                                @error('cooperative_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Obligatoire si la fonction nécessite une coopérative</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="secteur" class="form-label">Secteur</label>
                                <select class="form-select @error('secteur') is-invalid @enderror" id="secteur" name="secteur">
                                    <option value="">Sélectionner un secteur</option>
                                    @foreach($secteurs as $secteur)
                                        <option value="{{ $secteur->code }}" {{ old('secteur', $user->secteur) == $secteur->code ? 'selected' : '' }}>
                                            {{ $secteur->nom }} ({{ $secteur->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('secteur')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="centre_collecte_id" class="form-label">Centre de collecte</label>
                                <select class="form-select @error('centre_collecte_id') is-invalid @enderror" id="centre_collecte_id" name="centre_collecte_id">
                                    <option value="">Sélectionner un centre de collecte</option>
                                    @foreach($centresCollecte as $centre)
                                        <option value="{{ $centre->id }}" {{ old('centre_collecte_id', $user->centre_collecte_id) == $centre->id ? 'selected' : '' }}>
                                            {{ $centre->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('centre_collecte_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Statut *</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Sélectionner un statut</option>
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="siege" name="siege" value="1" 
                                           {{ old('siege', $user->siege) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="siege">
                                        Utilisateur du siège
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fonctionSelect = document.getElementById('fonction_id');
    const cooperativeDisplay = document.getElementById('cooperative_display');
    const cooperativeHidden = document.getElementById('cooperative_id');
    
    // Gérer la sélection de coopérative avec datalist
    cooperativeDisplay.addEventListener('input', function() {
        const input = this;
        const value = input.value;
        const datalist = document.getElementById('cooperatives-list');
        
        // Trouver l'option correspondante
        const option = datalist.querySelector(`option[value="${value}"]`);
        if (option) {
            cooperativeHidden.value = option.getAttribute('data-id');
        } else {
            cooperativeHidden.value = '';
        }
    });
    
    // Fonction pour vérifier si la fonction nécessite une coopérative
    function checkCooperativeRequired() {
        const selectedFonction = fonctionSelect.value;
        if (selectedFonction) {
            // Ici on pourrait faire une requête AJAX pour vérifier si la fonction nécessite une coopérative
            // Pour l'instant, on suppose que certaines fonctions nécessitent une coopérative
            const cooperativeRequired = ['Responsable Coopérative', 'Chef de Coopérative'].includes(
                fonctionSelect.options[fonctionSelect.selectedIndex].text
            );
            
            if (cooperativeRequired) {
                cooperativeDisplay.setAttribute('required', 'required');
                cooperativeDisplay.classList.add('is-invalid');
            } else {
                cooperativeDisplay.removeAttribute('required');
                cooperativeDisplay.classList.remove('is-invalid');
            }
        }
    }
    
    fonctionSelect.addEventListener('change', checkCooperativeRequired);
    
    // Vérifier au chargement de la page
    checkCooperativeRequired();
});
</script>
@endsection 