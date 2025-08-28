<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SecteurController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes d'authentification
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes protégées
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes admin
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
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
        
        // Section Finance
        Route::get('finance', [\App\Http\Controllers\FinanceController::class, 'index'])->name('finance.index');
        Route::get('finance/calcul/{id}', [\App\Http\Controllers\FinanceController::class, 'showCalcul'])->name('finance.show-calcul');
        Route::get('finance/matrice-prix', [\App\Http\Controllers\FinanceController::class, 'matricePrix'])->name('finance.matrice-prix');
        Route::post('finance/matrice-prix', [\App\Http\Controllers\FinanceController::class, 'storeMatricePrix'])->name('finance.store-matrice-prix');
        Route::patch('finance/matrice-prix/{id}/activer', [\App\Http\Controllers\FinanceController::class, 'activerMatricePrix'])->name('finance.activer-matrice-prix');
        
        // Validation ENE CI
        Route::get('ene-validation', [\App\Http\Controllers\EneValidationController::class, 'index'])->name('ene-validation.index');
        Route::get('ene-validation/{id}', [\App\Http\Controllers\EneValidationController::class, 'show'])->name('ene-validation.show');
        Route::post('ene-validation/{id}/validate', [\App\Http\Controllers\EneValidationController::class, 'validate'])->name('ene-validation.validate');
        Route::post('ene-validation/{id}/reject', [\App\Http\Controllers\EneValidationController::class, 'reject'])->name('ene-validation.reject');
        Route::patch('ene-validation/{id}/cancel', [\App\Http\Controllers\EneValidationController::class, 'cancel'])->name('ene-validation.cancel');
        
        // Gestion des Factures
        Route::resource('factures', \App\Http\Controllers\FactureController::class);
        Route::post('factures/{facture}/validate', [\App\Http\Controllers\FactureController::class, 'validate'])->name('factures.validate');
        Route::post('factures/{facture}/mark-as-paid', [\App\Http\Controllers\FactureController::class, 'markAsPaid'])->name('factures.mark-as-paid');
        Route::get('factures/{facture}/pdf', [\App\Http\Controllers\FactureController::class, 'generatePdf'])->name('factures.pdf');
        Route::get('factures/{facture}/preview', [\App\Http\Controllers\FactureController::class, 'preview'])->name('factures.preview');
    });
});