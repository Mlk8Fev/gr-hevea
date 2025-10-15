<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Log d'Audit - FPH-CI</title>
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
            <h6 class="fw-semibold mb-0">Détails du Log d'Audit #{{ $auditLog->id }}</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <i class="ri-home-line icon text-lg"></i>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">
                    <a href="{{ route('admin.audit-logs.index') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        Logs d'Audit
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Détails #{{ $auditLog->id }}</li>
            </ul>
        </div>

        <div class="row g-3">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <h5 class="card-title mb-4">
                        <i class="ri-eye-line me-2 text-primary"></i>
                        Informations du Log
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">ID du Log</label>
                            <p class="form-control-plaintext">{{ $auditLog->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Date et Heure</label>
                            <p class="form-control-plaintext">
                                {{ $auditLog->created_at->format('d/m/Y H:i:s') }}
                                <small class="text-muted">({{ $auditLog->created_at->diffForHumans() }})</small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Action</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-{{ $auditLog->action == 'CREATE' ? 'success' : ($auditLog->action == 'UPDATE' ? 'primary' : ($auditLog->action == 'DELETE' ? 'danger' : 'warning')) }}">
                                    {{ $auditLog->action_name }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Module</label>
                            <p class="form-control-plaintext">{{ $auditLog->module_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Type d'Objet</label>
                            <p class="form-control-plaintext">{{ $auditLog->object_type }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">ID de l'Objet</label>
                            <p class="form-control-plaintext">{{ $auditLog->object_id ?? 'N/A' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-secondary">Description</label>
                            <p class="form-control-plaintext">{{ $auditLog->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Utilisateur -->
                <div class="card p-24 radius-12 border-0 shadow-sm mt-3">
                    <h5 class="card-title mb-4">
                        <i class="ri-eye-line me-2 text-primary"></i>
                        Informations Utilisateur
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Nom d'utilisateur</label>
                            <p class="form-control-plaintext">{{ $auditLog->user_name ?? 'Système' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Rôle</label>
                            <p class="form-control-plaintext">{{ $auditLog->user_role ? ucfirst($auditLog->user_role) : 'N/A' }}</p>
                        </div>
                        @if($auditLog->user)
                        <div class="col-12">
                            <label class="form-label fw-bold text-secondary">Utilisateur complet</label>
                            <p class="form-control-plaintext">
                                <a href="{{ route('admin.users.show', $auditLog->user) }}" class="text-primary">
                                    {{ $auditLog->user->name }} ({{ $auditLog->user->email }})
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Données de l'objet -->
                @if($auditLog->old_values || $auditLog->new_values)
                <div class="card p-24 radius-12 border-0 shadow-sm mt-3">
                    <h5 class="card-title mb-4">
                        <i class="ri-eye-line me-2 text-primary"></i>
                        Données de l'Objet
                    </h5>
                    
                    @if($auditLog->old_values && $auditLog->new_values)
                        <!-- Comparaison des valeurs -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="fw-semibold text-danger mb-3">Valeurs Avant</h6>
                                <div class="bg-light p-3 radius-8">
                                    <pre class="mb-0 text-sm">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-semibold text-success mb-3">Valeurs Après</h6>
                                <div class="bg-light p-3 radius-8">
                                    <pre class="mb-0 text-sm">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Différences -->
                        @if($auditLog->diff)
                        <div class="mt-4">
                            <h6 class="fw-semibold text-primary mb-3">Différences Détectées</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Champ</th>
                                            <th>Ancienne Valeur</th>
                                            <th>Nouvelle Valeur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($auditLog->diff as $field => $values)
                                        <tr>
                                            <td><code>{{ $field }}</code></td>
                                            <td>
                                                <span class="text-danger">
                                                    {{ is_array($values['old']) ? json_encode($values['old']) : $values['old'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-success">
                                                    {{ is_array($values['new']) ? json_encode($values['new']) : $values['new'] }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    @elseif($auditLog->new_values)
                        <!-- Nouvelles valeurs seulement -->
                        <div class="bg-light p-3 radius-8">
                            <pre class="mb-0 text-sm">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @elseif($auditLog->old_values)
                        <!-- Anciennes valeurs seulement -->
                        <div class="bg-light p-3 radius-8">
                            <pre class="mb-0 text-sm">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Informations techniques -->
            <div class="col-lg-4">
                <div class="card p-24 radius-12 border-0 shadow-sm">
                    <h5 class="card-title mb-4">
                        <i class="ri-eye-line me-2 text-primary"></i>
                        Informations Techniques
                    </h5>
                    
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <label class="form-label fw-bold text-secondary text-sm">Statut</label>
                            <p class="mb-0">
                                @if($auditLog->is_successful)
                                    <span class="badge bg-success">
                                        <i class="ri-search-line me-1"></i>
                                        Succès
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="ri-search-line me-1"></i>
                                        Échec
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="form-label fw-bold text-secondary text-sm">Adresse IP</label>
                            <p class="mb-0 text-muted">{{ $auditLog->ip_address ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="form-label fw-bold text-secondary text-sm">Navigateur</label>
                            <p class="mb-0 text-muted">{{ $auditLog->browser ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="form-label fw-bold text-secondary text-sm">Système d'exploitation</label>
                            <p class="mb-0 text-muted">{{ $auditLog->os ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="form-label fw-bold text-secondary text-sm">Appareil</label>
                            <p class="mb-0 text-muted">{{ $auditLog->device ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="form-label fw-bold text-secondary text-sm">Méthode HTTP</label>
                            <p class="mb-0 text-muted">{{ $auditLog->request_method ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="form-label fw-bold text-secondary text-sm">URL de la requête</label>
                            <p class="mb-0 text-muted">
                                @if($auditLog->request_url)
                                    <a href="{{ $auditLog->request_url }}" target="_blank" class="text-primary">
                                        {{ Str::limit($auditLog->request_url, 50) }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        
                        @if($auditLog->execution_time)
                        <div>
                            <label class="form-label fw-bold text-secondary text-sm">Temps d'exécution</label>
                            <p class="mb-0 text-muted">{{ $auditLog->execution_time }}ms</p>
                        </div>
                        @endif
                        
                        @if($auditLog->error_message)
                        <div>
                            <label class="form-label fw-bold text-secondary text-sm">Message d'erreur</label>
                            <div class="bg-danger-100 p-3 radius-8">
                                <p class="mb-0 text-danger text-sm">{{ $auditLog->error_message }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Données de la requête -->
                @if($auditLog->request_data)
                <div class="card p-24 radius-12 border-0 shadow-sm mt-3">
                    <h5 class="card-title mb-4">
                        <i class="ri-eye-line me-2 text-primary"></i>
                        Données de la Requête
                    </h5>
                    
                    <div class="bg-light p-3 radius-8">
                        <pre class="mb-0 text-sm">{{ json_encode($auditLog->request_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
                @endif

                <!-- User Agent -->
                @if($auditLog->user_agent)
                <div class="card p-24 radius-12 border-0 shadow-sm mt-3">
                    <h5 class="card-title mb-4">
                        <i class="ri-eye-line me-2 text-primary"></i>
                        User Agent
                    </h5>
                    
                    <div class="bg-light p-3 radius-8">
                        <p class="mb-0 text-sm text-muted">{{ $auditLog->user_agent }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex gap-3 mt-24">
            <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary">
                <i class="ri-search-line me-1"></i>
                Retour à la liste
            </a>
            @if($auditLog->object_type && $auditLog->object_id)
                @php
                    $objectRoute = 'admin.' . strtolower($auditLog->object_type) . 's.show';
                @endphp
                @if(Route::has($objectRoute))
                    <a href="{{ route($objectRoute, $auditLog->object_id) }}" class="btn btn-outline-primary">
                        <i class="ri-search-line me-1"></i>
                        Voir l'objet
                    </a>
                @endif
            @endif
        </div>
    </div>
</main>

@include('partials.wowdash-scripts')
</body>
</html>
