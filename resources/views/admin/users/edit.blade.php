<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer un utilisateur - Dashboard Superadmin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row bg-primary text-white p-3 mb-4">
            <div class="col">
                <h1><i class="fas fa-user-edit"></i> Éditer un utilisateur</h1>
                <p class="mb-0">Modifier les informations de l'utilisateur</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>

        <!-- Messages d'erreur -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-edit"></i> Informations de l'utilisateur</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <!-- Prénom -->
                                <div class="col-md-6 mb-3">
                                    <label for="prenom" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" 
                                           value="{{ old('prenom', $user->prenom) }}" required>
                                </div>

                                <!-- Nom -->
                                <div class="col-md-6 mb-3">
                                    <label for="nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="nom" name="nom" 
                                           value="{{ old('nom', $user->nom) }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email', $user->email) }}" required>
                                </div>

                                <!-- Mot de passe (optionnel) -->
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe (optionnel)</label>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           minlength="8" placeholder="Laissez vide pour ne pas changer">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Rôle -->
                                <div class="col-md-4 mb-3">
                                    <label for="role" class="form-label">Rôle *</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Sélectionner un rôle</option>
                                        <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Utilisateur</option>
                                    </select>
                                </div>

                                <!-- Secteur -->
                                <div class="col-md-4 mb-3">
                                    <label for="secteur" class="form-label">Secteur</label>
                                    <input type="text" class="form-control" id="secteur" name="secteur" 
                                           value="{{ old('secteur', $user->secteur) }}" placeholder="Ex: Ventes, Marketing">
                                </div>

                                <!-- Fonction -->
                                <div class="col-md-4 mb-3">
                                    <label for="fonction" class="form-label">Fonction</label>
                                    <input type="text" class="form-control" id="fonction" name="fonction" 
                                           value="{{ old('fonction', $user->fonction) }}" placeholder="Ex: Chef de service">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Siège -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="siege" name="siege" value="1" 
                                               {{ old('siege', $user->siege) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="siege">
                                            Accès siège (accès global)
                                        </label>
                                    </div>
                                </div>

                                <!-- Statut -->
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Statut *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Informations supplémentaires -->
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="alert alert-info">
                                        <strong>Informations :</strong>
                                        <ul class="mb-0 mt-2">
                                            <li>ID de l'utilisateur : {{ $user->id }}</li>
                                            <li>Créé le : {{ $user->created_at->format('d/m/Y H:i') }}</li>
                                            <li>Dernière modification : {{ $user->updated_at->format('d/m/Y H:i') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="row">
                                <div class="col">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Mettre à jour
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Annuler
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 