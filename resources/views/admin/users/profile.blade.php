@extends('layouts.app')

@section('title', 'Mon Profil - FPH-CI')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Mon Profil</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <i class="ri-home-line icon text-lg"></i>
                    Dashboard
                </a>
            </li>
            <li class="fw-medium">
                <span class="text-muted">/</span>
            </li>
            <li class="fw-medium">
                <span class="text-primary">Mon Profil</span>
            </li>
        </ul>
    </div>

    <!-- En-tête du profil -->
    <div class="card border mb-4">
        <div class="card-body p-24">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="bg-gradient-primary rounded-circle p-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="ri-eye-line text-white"></i>
                    </div>
                </div>
                <div class="col">
                    <h3 class="mb-2 fw-bold text-dark">{{ $user->full_name }}</h3>
                    <p class="text-muted mb-1 fs-5">{{ $user->fonction->nom ?? 'Aucune fonction' }}</p>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-primary fs-6 px-3 py-2">{{ ucfirst($user->role) }}</span>
                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }} fs-6 px-3 py-2">
                            {{ $user->status === 'active' ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informations personnelles -->
        <div class="col-lg-6">
            <div class="card border h-100">
                <div class="card-header bg-primary text-white border-0">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-eye-line text-white fs-4"></i>
                        <h5 class="mb-0 fw-semibold">Informations Personnelles</h5>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="space-y-3">
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-user-line text-primary"></i>
                                <span class="text-muted">Nom complet</span>
                            </div>
                            <span class="fw-semibold text-dark">{{ $user->full_name }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-user-line text-primary"></i>
                                <span class="text-muted">Nom d'utilisateur</span>
                            </div>
                            <span class="fw-semibold text-dark">{{ $user->username }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-user-line text-primary"></i>
                                <span class="text-muted">Email</span>
                            </div>
                            <span class="fw-semibold text-dark">{{ $user->email }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-user-line text-primary"></i>
                                <span class="text-muted">Téléphone</span>
                            </div>
                            <span class="fw-semibold text-dark">{{ $user->telephone ?? 'Non renseigné' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="col-lg-6">
            <div class="card border h-100">
                <div class="card-header bg-success text-white border-0">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-eye-line text-white fs-4"></i>
                        <h5 class="mb-0 fw-semibold">Informations Professionnelles</h5>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="space-y-3">
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-eye-line text-success"></i>
                                <span class="text-muted">Fonction</span>
                            </div>
                            <span class="fw-semibold text-dark">{{ $user->fonction->nom ?? 'Aucune fonction' }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-eye-line text-success"></i>
                                <span class="text-muted">Rôle</span>
                            </div>
                            <span class="badge bg-primary fs-6">{{ ucfirst($user->role) }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-eye-line text-success"></i>
                                <span class="text-muted">Statut</span>
                            </div>
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }} fs-6">
                                {{ $user->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-eye-line text-success"></i>
                                <span class="text-muted">Date de création</span>
                            </div>
                            <span class="fw-semibold text-dark">{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affectations -->
        @if($user->cooperative || $user->secteurRelation || $user->centreCollecte)
        <div class="col-12">
            <div class="card border">
                <div class="card-header bg-warning text-dark border-0">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-eye-line text-dark fs-4"></i>
                        <h5 class="mb-0 fw-semibold">Affectations</h5>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="row g-4">
                        @if($user->cooperative)
                        <div class="col-md-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body text-center p-4">
                                    <div class="bg-primary rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                                        <i class="ri-eye-line text-white fs-3"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">Coopérative</h6>
                                    <h5 class="fw-semibold text-dark mb-0">{{ $user->cooperative->nom }}</h5>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($user->secteurRelation)
                        <div class="col-md-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body text-center p-4">
                                    <div class="bg-success rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                                        <i class="ri-eye-line text-white fs-3"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">Secteur</h6>
                                    <h5 class="fw-semibold text-dark mb-0">{{ $user->secteurRelation->nom }}</h5>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($user->centreCollecte)
                        <div class="col-md-4">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body text-center p-4">
                                    <div class="bg-info rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                                        <i class="ri-eye-line text-white fs-3"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">Centre de Collecte</h6>
                                    <h5 class="fw-semibold text-dark mb-0">{{ $user->centreCollecte->nom }}</h5>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
