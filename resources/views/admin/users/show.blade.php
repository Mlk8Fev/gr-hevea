@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        Détails de l'utilisateur : {{ $user->name }}
                    </h4>
                    <div>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Informations de base -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Informations de base</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 150px;">ID :</td>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nom d'utilisateur :</td>
                                    <td>{{ $user->username }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Prénom :</td>
                                    <td>{{ $user->name }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold">Email :</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Rôle :</td>
                                    <td>
                                        @php
                                            $roleColors = [
                                                'superadmin' => 'danger',
                                                'admin' => 'warning', 
                                                'manager' => 'info',
                                                'agc' => 'success',
                                                'cs' => 'primary',
                                                'ac' => 'secondary',
                                                'rt' => 'info',
                                                'rd' => 'success',
                                                'comp' => 'warning',
                                                'ctu' => 'dark',
                                                'rcoop' => 'primary'
                                            ];
                                            $roleLabels = [
                                                'superadmin' => 'Super Admin',
                                                'admin' => 'Admin',
                                                'manager' => 'Manager',
                                                'agc' => 'Agent Gestion Qualité',
                                                'cs' => 'Chef Secteur',
                                                'ac' => 'Assistante Comptable',
                                                'rt' => 'Responsable Traçabilité',
                                                'rd' => 'Responsable Durabilité',
                                                'comp' => 'Comptable Siège',
                                                'ctu' => 'Contrôleur Usine',
                                                'rcoop' => 'Responsable Coopérative'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $roleColors[$user->role] ?? 'secondary' }}-100 text-{{ $roleColors[$user->role] ?? 'secondary' }}-600 px-8 py-2 radius-6">
                                            {{ $roleLabels[$user->role] ?? ucfirst($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Informations professionnelles -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Informations professionnelles</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 150px;">Fonction :</td>
                                    <td>
                                        @if($user->fonction)
                                            <span class="badge bg-primary">{{ $user->fonction->nom }}</span>
                                            <br>
                                            <small class="text-muted">{{ $user->fonction->description }}</small>
                                        @else
                                            <span class="text-muted">Non définie</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Coopérative :</td>
                                    <td>
                                        @if($user->cooperative)
                                            <span class="badge bg-success">{{ $user->cooperative->nom }}</span>
                                            <br>
                                            <small class="text-muted">Code: {{ $user->cooperative->code }}</small>
                                        @else
                                            <span class="text-muted">Non assignée</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Secteur :</td>
                                    <td>
                                        @if($user->secteurRelation)
                                            <span class="badge bg-info">{{ $user->secteurRelation->nom }}</span>
                                            <br>
                                            <small class="text-muted">Code: {{ $user->secteurRelation->code }}</small>
                                        @elseif($user->secteur)
                                            <span class="text-muted">{{ $user->secteur }}</span>
                                        @else
                                            <span class="text-muted">Non défini</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Centre de collecte :</td>
                                    <td>
                                        @if($user->centreCollecte)
                                            <span class="badge bg-warning">{{ $user->centreCollecte->nom }}</span>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <!-- Statut et permissions -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Statut et permissions</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 150px;">Statut :</td>
                                    <td>
                                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                            {{ $user->status === 'active' ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Utilisateur du siège :</td>
                                    <td>
                                        @if($user->siege)
                                            <span class="badge bg-success">Oui</span>
                                        @else
                                            <span class="badge bg-secondary">Non</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Peut gérer coopérative :</td>
                                    <td>
                                        @if($user->peutGererCooperative())
                                            <span class="badge bg-success">Oui</span>
                                        @else
                                            <span class="badge bg-secondary">Non</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Niveau d'accès :</td>
                                    <td>
                                        @if($user->fonction)
                                            <span class="badge bg-info">{{ ucfirst($user->fonction->niveau_acces) }}</span>
                                        @else
                                            <span class="text-muted">Non défini</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Informations système -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Informations système</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" style="width: 150px;">Créé le :</td>
                                    <td>{{ $user->created_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Dernière modification :</td>
                                    <td>{{ $user->updated_at->format('d/m/Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Dernière connexion :</td>
                                    <td>
                                        @if($user->last_login_at)
                                            {{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y à H:i') }}
                                        @else
                                            <span class="text-muted">Jamais connecté</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($user->fonction && $user->fonction->peut_gerer_cooperative && $user->cooperative)
                    <hr class="my-4">
                    
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-handshake me-2"></i>
                                Responsabilités de coopérative
                            </h5>
                            <div class="alert alert-info">
                                <strong>{{ $user->name }} {{ $user->nom }}</strong> est responsable de la coopérative 
                                <strong>{{ $user->cooperative->nom }}</strong> ({{ $user->cooperative->code }}).
                                <br>
                                <small class="text-muted">
                                    Cette fonction permet de gérer les producteurs, les tickets de pesée et les factures 
                                    de cette coopérative spécifique.
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Modifier l'utilisateur
                                </a>
                                <button type="button" class="btn btn-warning" onclick="resetPassword()">
                                    <i class="fas fa-key me-2"></i>Réinitialiser le mot de passe
                                </button>
                                @if($user->status === 'active')
                                    <button type="button" class="btn btn-danger" onclick="deactivateUser()">
                                        <i class="fas fa-user-slash me-2"></i>Désactiver
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success" onclick="activateUser()">
                                        <i class="fas fa-user-check me-2"></i>Activer
                                    </button>
                                @endif
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetPassword() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de cet utilisateur ?')) {
        // Ici on pourrait faire une requête AJAX pour réinitialiser le mot de passe
        alert('Fonctionnalité de réinitialisation de mot de passe à implémenter');
    }
}

function deactivateUser() {
    if (confirm('Êtes-vous sûr de vouloir désactiver cet utilisateur ?')) {
        // Ici on pourrait faire une requête AJAX pour désactiver l'utilisateur
        alert('Fonctionnalité de désactivation à implémenter');
    }
}

function activateUser() {
    if (confirm('Êtes-vous sûr de vouloir activer cet utilisateur ?')) {
        // Ici on pourrait faire une requête AJAX pour activer l'utilisateur
        alert('Fonctionnalité d\'activation à implémenter');
    }
}
</script>
@endsection 