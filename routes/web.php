<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SecteurController;

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    // Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->name('admin.dashboard')
        ->middleware('role:admin');
    
    // Dashboard Manager
    Route::get('/manager/dashboard', [DashboardController::class, 'managerDashboard'])
        ->name('manager.dashboard')
        ->middleware('role:manager');
    
    // Dashboard User
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])
        ->name('user.dashboard')
        ->middleware('role:user');

    // Routes de gestion des utilisateurs et secteurs (superadmin seulement)
    Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
        Route::get('producteurs/{producteur}/documents/{document}/pdf', [\App\Http\Controllers\ProducteurDocumentController::class, 'generatePdf'])->name('admin.producteurs.documents.pdf');
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        Route::resource('secteurs', SecteurController::class);
        Route::resource('cooperatives', \App\Http\Controllers\CooperativeController::class);
        Route::resource('producteurs', \App\Http\Controllers\ProducteurController::class);
        Route::resource('producteurs.documents', \App\Http\Controllers\ProducteurDocumentController::class);
        
        // Gestion Logistique
        Route::resource('centres-collecte', \App\Http\Controllers\CentreCollecteController::class);
        Route::resource('connaissements', \App\Http\Controllers\ConnaissementController::class);
        Route::get('connaissements/{connaissement}/program', [\App\Http\Controllers\ConnaissementController::class, 'program'])->name('connaissements.program');
        Route::post('connaissements/{connaissement}/program', [\App\Http\Controllers\ConnaissementController::class, 'storeProgram'])->name('connaissements.store-program');
        Route::get('connaissements/{connaissement}/validate', [\App\Http\Controllers\ConnaissementController::class, 'validate'])->name('connaissements.validate');
        Route::post('connaissements/{connaissement}/validate', [\App\Http\Controllers\ConnaissementController::class, 'storeValidation'])->name('connaissements.store-validation');
        
        // Tickets de Pesée
        Route::resource('tickets-pesee', \App\Http\Controllers\TicketPeseeController::class);
        Route::patch('tickets-pesee/{ticketPesee}/validate', [\App\Http\Controllers\TicketPeseeController::class, 'validate'])->name('tickets-pesee.validate');
        Route::patch('tickets-pesee/{ticketPesee}/archive', [\App\Http\Controllers\TicketPeseeController::class, 'archive'])->name('tickets-pesee.archive');
        Route::get('tickets-pesee/{ticketPesee}/pdf', [\App\Http\Controllers\TicketPeseeController::class, 'generatePdf'])->name('tickets-pesee.pdf');
    });
});

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Ancienne route dashboard (temporaire)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');