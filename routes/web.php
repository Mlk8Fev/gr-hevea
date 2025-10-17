<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SecteurController;
use App\Http\Controllers\StatistiquesController;
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
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle-login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes protégées
Route::middleware(['auth', 'audit', 'email-2fa'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil utilisateur
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');

    // Routes admin
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'audit'])->group(function () {
        Route::get('producteurs/{producteur}/documents/{document}/pdf', [\App\Http\Controllers\ProducteurDocumentController::class, 'generatePdf'])->name('admin.producteurs.documents.pdf');
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
        Route::resource('tickets-pesee', \App\Http\Controllers\TicketPeseeController::class)->parameters([
            'tickets-pesee' => 'ticketPesee'
        ]);
        Route::patch('tickets-pesee/{ticketPesee}/validate', [\App\Http\Controllers\TicketPeseeController::class, 'validate'])->name('tickets-pesee.validate');
        Route::patch('tickets-pesee/{ticketPesee}/cancel-validation', [\App\Http\Controllers\TicketPeseeController::class, 'cancelValidation'])->name('tickets-pesee.cancel-validation');
        Route::patch('tickets-pesee/{ticketPesee}/archive', [\App\Http\Controllers\TicketPeseeController::class, 'archive'])->name('tickets-pesee.archive');
        Route::get('tickets-pesee/{ticketPesee}/pdf', [\App\Http\Controllers\TicketPeseeController::class, 'generatePdf'])->name('tickets-pesee.pdf');
        
        // Section Finance (SÉCURISÉE - Admin + Super-Admin)
        Route::middleware(['admin-or-superadmin'])->group(function () {
            Route::get('finance', [\App\Http\Controllers\FinanceController::class, 'index'])->name('finance.index');
            Route::get('finance/calcul/{id}', [\App\Http\Controllers\FinanceController::class, 'showCalcul'])->name('finance.show-calcul');
            Route::get('finance/matrice-prix', [\App\Http\Controllers\FinanceController::class, 'matricePrix'])->name('finance.matrice-prix');
            Route::post('finance/matrice-prix', [\App\Http\Controllers\FinanceController::class, 'storeMatricePrix'])->name('finance.store-matrice-prix');
            Route::patch('finance/matrice-prix/{id}/activer', [\App\Http\Controllers\FinanceController::class, 'activerMatricePrix'])->name('finance.activer-matrice-prix');
        });
        
        // Validation ENE CI (BLOQUÉE pour les AGC)
        Route::middleware(['role:admin,superadmin'])->group(function () {
            Route::get('ene-validation', [\App\Http\Controllers\EneValidationController::class, 'index'])->name('ene-validation.index');
            Route::get('ene-validation/{id}', [\App\Http\Controllers\EneValidationController::class, 'show'])->name('ene-validation.show');
            Route::post('ene-validation/{id}/validate', [\App\Http\Controllers\EneValidationController::class, 'validate'])->name('ene-validation.validate');
            Route::post('ene-validation/{id}/reject', [\App\Http\Controllers\EneValidationController::class, 'reject'])->name('ene-validation.reject');
            Route::patch('ene-validation/{id}/cancel', [\App\Http\Controllers\EneValidationController::class, 'cancel'])->name('ene-validation.cancel');
        });
        
        // Gestion des Factures (SÉCURISÉE - Admin + Super-Admin + AGC + CS)
        Route::middleware(['role:admin,superadmin,agc,cs'])->group(function () {
            Route::resource('factures', \App\Http\Controllers\FactureController::class);
            Route::post('factures/{facture}/validate', [\App\Http\Controllers\FactureController::class, 'validate'])->name('factures.validate');
            Route::post('factures/{facture}/mark-as-paid', [\App\Http\Controllers\FactureController::class, 'markAsPaid'])->name('factures.mark-as-paid');
            Route::get('factures/{facture}/pdf', [\App\Http\Controllers\FactureController::class, 'generatePdf'])->name('factures.pdf');
            Route::get('factures/{facture}/preview', [\App\Http\Controllers\FactureController::class, 'preview'])->name('factures.preview');
        });
        
        // Coopératives (SÉCURISÉES - Admin + Super-Admin + AGC + CS avec restrictions)
        Route::middleware(['role:admin,superadmin,agc,cs'])->group(function () {
            Route::get('cooperatives', [\App\Http\Controllers\CooperativeController::class, 'index'])->name('cooperatives.index');
            Route::get('cooperatives/create', [\App\Http\Controllers\CooperativeController::class, 'create'])->name('cooperatives.create');
            Route::post('cooperatives', [\App\Http\Controllers\CooperativeController::class, 'store'])->name('cooperatives.store');
            Route::get('cooperatives/{cooperative}', [\App\Http\Controllers\CooperativeController::class, 'show'])->name('cooperatives.show');
            Route::get('cooperatives/{cooperative}/edit', [\App\Http\Controllers\CooperativeController::class, 'edit'])->name('cooperatives.edit');
            Route::put('cooperatives/{cooperative}', [\App\Http\Controllers\CooperativeController::class, 'update'])->name('cooperatives.update');
            Route::delete('cooperatives/{cooperative}', [\App\Http\Controllers\CooperativeController::class, 'destroy'])->name('cooperatives.destroy');
            Route::get('cooperatives/{cooperative}/documents', [\App\Http\Controllers\CooperativeController::class, 'documents'])->name('cooperatives.documents');
            Route::post('cooperatives/{cooperative}/documents', [\App\Http\Controllers\CooperativeController::class, 'storeDocument'])->name('cooperatives.store-document');
            Route::delete('cooperatives/{cooperative}/documents/{document}', [\App\Http\Controllers\CooperativeController::class, 'destroyDocument'])->name('cooperatives.destroy-document');
        });
        
        // Farmer Lists
        Route::get('farmer-lists', [\App\Http\Controllers\FarmerListController::class, 'index'])->name('farmer-lists.index');
        Route::get('farmer-lists/{connaissement}', [\App\Http\Controllers\FarmerListController::class, 'show'])->name('farmer-lists.show');
        Route::get('farmer-lists/{connaissement}/create', [\App\Http\Controllers\FarmerListController::class, 'create'])->name('farmer-lists.create');
        Route::post('farmer-lists/{connaissement}', [\App\Http\Controllers\FarmerListController::class, 'store'])->name('farmer-lists.store');
        Route::get('farmer-lists/{farmerList}/edit', [\App\Http\Controllers\FarmerListController::class, 'edit'])->name('farmer-lists.edit');
        Route::put('farmer-lists/{farmerList}', [\App\Http\Controllers\FarmerListController::class, 'update'])->name('farmer-lists.update');
        Route::delete('farmer-lists/{farmerList}', [\App\Http\Controllers\FarmerListController::class, 'destroy'])->name('farmer-lists.destroy');
        Route::get('farmer-lists/{connaissement}/pdf', [\App\Http\Controllers\FarmerListController::class, 'pdf'])->name('farmer-lists.pdf');
        Route::get('farmer-lists/{connaissement}/view', [\App\Http\Controllers\FarmerListController::class, 'view'])->name('farmer-lists.view');

        // Reçus d'achat
        Route::get('farmer-lists/{connaissement}/recus/{farmerList}/create', [\App\Http\Controllers\RecuAchatController::class, 'create'])->name('recus-achat.create');
        Route::post('farmer-lists/{connaissement}/recus/{farmerList}', [\App\Http\Controllers\RecuAchatController::class, 'store'])->name('recus-achat.store');
        Route::get('recus-achat/{recuAchat}', [\App\Http\Controllers\RecuAchatController::class, 'show'])->name('recus-achat.show');
        Route::get('recus-achat/{recuAchat}/edit', [\App\Http\Controllers\RecuAchatController::class, 'edit'])->name('recus-achat.edit');
        Route::put('recus-achat/{recuAchat}', [\App\Http\Controllers\RecuAchatController::class, 'update'])->name('recus-achat.update');
        Route::get('recus-achat/{recuAchat}/pdf', [\App\Http\Controllers\RecuAchatController::class, 'pdf'])->name('recus-achat.pdf');
        
        // Statistiques générales (ACCESSIBLES À TOUS)
        Route::get('statistiques', [StatistiquesController::class, 'index'])->name('statistiques.index');
        
        // Statistiques avancées (SÉCURISÉES - Admin + Super-Admin)
        Route::middleware(['admin-or-superadmin', 'security'])->group(function () {
            Route::get('statistiques/avancees', [StatistiquesController::class, 'avancees'])->name('statistiques.avancees');
        });
        
        // Logs d'Audit (SÉCURISÉS - Super-Admin uniquement)
        Route::middleware(['superadmin', 'security'])->group(function () {
            Route::get('audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
            Route::get('audit-logs/{auditLog}', [\App\Http\Controllers\AuditLogController::class, 'show'])->name('audit-logs.show');
            Route::get('audit-logs/export/pdf', [\App\Http\Controllers\AuditLogController::class, 'exportPdf'])->name('audit-logs.export-pdf');
            Route::get('audit-logs/export/excel', [\App\Http\Controllers\AuditLogController::class, 'exportExcel'])->name('audit-logs.export-excel');
            Route::get('audit-logs/stats', [\App\Http\Controllers\AuditLogController::class, 'stats'])->name('audit-logs.stats');
            
            // Gestion des Utilisateurs (SÉCURISÉE - Super-Admin uniquement)
            Route::resource('users', UserController::class);
            Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
            
            // Gestion des Secteurs (SÉCURISÉE - Super-Admin uniquement)
            Route::resource('secteurs', SecteurController::class);
        });
    });

    // Routes pour les Responsables de Coopératives (rcoop)
    Route::prefix('cooperative')->name('cooperative.')->middleware(['auth', 'audit', 'role:rcoop'])->group(function () {
        Route::get('producteurs', [\App\Http\Controllers\CooperativeProducteurController::class, 'index'])->name('producteurs.index');
        Route::get('producteurs/{producteur}', [\App\Http\Controllers\CooperativeProducteurController::class, 'show'])->name('producteurs.show');
        
        Route::get('tickets-pesee', [\App\Http\Controllers\CooperativeTicketPeseeController::class, 'index'])->name('tickets-pesee.index');
        Route::get('tickets-pesee/{ticketPesee}', [\App\Http\Controllers\CooperativeTicketPeseeController::class, 'show'])->name('tickets-pesee.show');
        
        Route::get('connaissements', [\App\Http\Controllers\CooperativeConnaissementController::class, 'index'])->name('connaissements.index');
        Route::get('connaissements/{connaissement}', [\App\Http\Controllers\CooperativeConnaissementController::class, 'show'])->name('connaissements.show');
        
        Route::get('finance', [\App\Http\Controllers\CooperativeFinanceController::class, 'index'])->name('finance.index');
        Route::get('finance/{ticket}/calcul', [\App\Http\Controllers\CooperativeFinanceController::class, 'showCalcul'])->name('finance.show-calcul');
        
        Route::get('factures', [\App\Http\Controllers\CooperativeFactureController::class, 'index'])->name('factures.index');
        Route::get('factures/create', [\App\Http\Controllers\CooperativeFactureController::class, 'create'])->name('factures.create');
        Route::get('factures/{facture}', [\App\Http\Controllers\CooperativeFactureController::class, 'show'])->name('factures.show');
        Route::get('factures/{facture}/preview', [\App\Http\Controllers\CooperativeFactureController::class, 'preview'])->name('factures.preview');
        Route::get('factures/{facture}/pdf', [\App\Http\Controllers\CooperativeFactureController::class, 'pdf'])->name('factures.pdf');
    });
});

        // Routes 2FA (Authentification à deux facteurs)
        Route::prefix('2fa')->name('2fa.')->group(function () {
            
            // Vérification 2FA
            Route::get('verify', [\App\Http\Controllers\TwoFactorController::class, 'showVerify'])->name('verify');
            Route::post('verify', [\App\Http\Controllers\TwoFactorController::class, 'verifyCode'])->name('verify');
            Route::post('resend', [\App\Http\Controllers\TwoFactorController::class, 'sendVerifyCode'])->name('resend');
            
            // Désactivation 2FA (Super-Admin uniquement)
            Route::post('email/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('email.disable');
        });

    // Routes CS (Chef Secteur) - Coopératives
    Route::prefix('cs')->name('cs.')->middleware(['role:cs,agc'])->group(function () {
        Route::get('cooperatives', [\App\Http\Controllers\CsCooperativeController::class, 'index'])->name('cooperatives.index');
        Route::get('cooperatives/{cooperative}', [\App\Http\Controllers\CsCooperativeController::class, 'show'])->name('cooperatives.show');
        Route::get('cooperatives/{cooperative}/edit', [\App\Http\Controllers\CsCooperativeController::class, 'edit'])->name('cooperatives.edit');
        Route::put('cooperatives/{cooperative}', [\App\Http\Controllers\CsCooperativeController::class, 'update'])->name('cooperatives.update');
        Route::get('cooperatives/{cooperative}/documents', [\App\Http\Controllers\CsCooperativeController::class, 'documents'])->name('cooperatives.documents');
        Route::post('cooperatives/{cooperative}/documents', [\App\Http\Controllers\CsCooperativeController::class, 'storeDocument'])->name('cooperatives.store-document');
        Route::delete('cooperatives/{cooperative}/documents/{document}', [\App\Http\Controllers\CsCooperativeController::class, 'destroyDocument'])->name('cooperatives.destroy-document');
        
        // Routes CS (Chef Secteur) - Factures
        Route::get('factures', [\App\Http\Controllers\CsFactureController::class, 'index'])->name('factures.index');
        Route::get('factures/create', [\App\Http\Controllers\CsFactureController::class, 'create'])->name('factures.create');
        Route::get('factures/{facture}', [\App\Http\Controllers\CsFactureController::class, 'show'])->name('factures.show');
        Route::get('factures/{facture}/preview', [\App\Http\Controllers\CsFactureController::class, 'preview'])->name('factures.preview');
        Route::get('factures/{facture}/pdf', [\App\Http\Controllers\CsFactureController::class, 'pdf'])->name('factures.pdf');
    });

