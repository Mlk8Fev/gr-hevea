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
    });
});

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Ancienne route dashboard (temporaire)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');