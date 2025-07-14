<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - Gestion des Utilisateurs</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .status-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row bg-primary text-white p-3 mb-4">
            <div class="col">
                <h1><i class="fas fa-users-cog"></i> Dashboard Superadmin</h1>
                <p class="mb-0">Gestion des utilisateurs</p>
            </div>
            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <span class="me-3">Connecté en tant que : {{ auth()->user()->full_name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Bouton Ajouter Utilisateur -->
        <div class="row mb-4">
            <div class="col">
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Ajouter un utilisateur
                </a>
            </div>
        </div>

        <!-- Tableau des utilisateurs -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Liste des utilisateurs</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Secteur</th>
                                        <th>Fonction</th>
                                        <th>Siège</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role === 'superadmin' ? 'dark' : ($user->role === 'admin' ? 'danger' : ($user->role === 'manager' ? 'warning' : 'info')) }}">
                                                {{ $user->role === 'superadmin' ? 'Super Admin' : ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->secteur ?? 'Non défini' }}</td>
                                        <td>{{ $user->fonction ?? 'Non défini' }}</td>
                                        <td>
                                            @if($user->siege)
                                                <span class="badge bg-success">Oui</span>
                                            @else
                                                <span class="badge bg-secondary">Non</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->status === 'active')
                                                <span class="badge bg-success status-badge">Actif</span>
                                            @else
                                                <span class="badge bg-danger status-badge">Inactif</span>
                                            @endif
                                        </td>
                                        <td class="action-buttons">
                                            <!-- Bouton Activer/Désactiver -->
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-warning' : 'btn-success' }}" 
                                                        title="{{ $user->status === 'active' ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas {{ $user->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>

                                            <!-- Bouton Éditer -->
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary" title="Éditer">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Bouton Supprimer -->
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 