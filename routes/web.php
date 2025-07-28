<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Portal routes
    Route::resource('portals', PortalController::class);
    
    // Nested client routes within portals
    Route::prefix('portals/{portal}')->group(function () {
        Route::get('clients', [ClientController::class, 'index'])->name('portals.clients.index');
        Route::get('clients/create', [ClientController::class, 'create'])->name('portals.clients.create');
        Route::post('clients', [ClientController::class, 'store'])->name('portals.clients.store');
        Route::get('clients/{client}', [ClientController::class, 'show'])->name('portals.clients.show');
        Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('portals.clients.edit');
        Route::put('clients/{client}', [ClientController::class, 'update'])->name('portals.clients.update');
        Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('portals.clients.destroy');
        Route::post('clients/{client}/invite', [ClientController::class, 'sendInvitation'])->name('portals.clients.invite');
        
        // Nested project routes within portals
        Route::get('projects', [ProjectController::class, 'index'])->name('portals.projects.index');
        Route::get('projects/create', [ProjectController::class, 'create'])->name('portals.projects.create');
        Route::post('projects', [ProjectController::class, 'store'])->name('portals.projects.store');
        Route::get('projects/{project}', [ProjectController::class, 'show'])->name('portals.projects.show');
        Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('portals.projects.edit');
        Route::put('projects/{project}', [ProjectController::class, 'update'])->name('portals.projects.update');
        Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('portals.projects.destroy');
        Route::get('projects/{project}/files', [FileController::class, 'projectFiles'])->name('portals.projects.files');
        
        // Nested file routes within portals
        Route::get('files', [FileController::class, 'index'])->name('portals.files.index');
        Route::get('files/create', [FileController::class, 'create'])->name('portals.files.create');
        Route::post('files', [FileController::class, 'store'])->name('portals.files.store');
        Route::get('files/{file}', [FileController::class, 'show'])->name('portals.files.show');
        Route::get('files/{file}/download', [FileController::class, 'download'])->name('portals.files.download');
        Route::get('files/{file}/edit', [FileController::class, 'edit'])->name('portals.files.edit');
        Route::put('files/{file}', [FileController::class, 'update'])->name('portals.files.update');
        Route::delete('files/{file}', [FileController::class, 'destroy'])->name('portals.files.destroy');
        Route::get('clients/{client}/files', [FileController::class, 'clientFiles'])->name('portals.clients.files');
        
        // Invoice routes
        Route::resource('invoices', InvoiceController::class)->except(['index', 'create', 'store']);
        Route::get('invoices', [InvoiceController::class, 'index'])->name('portals.invoices.index');
        Route::get('invoices/create', [InvoiceController::class, 'create'])->name('portals.invoices.create');
        Route::post('invoices', [InvoiceController::class, 'store'])->name('portals.invoices.store');
        Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('portals.invoices.show');
        Route::get('invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('portals.invoices.edit');
        Route::put('invoices/{invoice}', [InvoiceController::class, 'update'])->name('portals.invoices.update');
        Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('portals.invoices.destroy');
        Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('portals.invoices.send');
        Route::post('invoices/{invoice}/payment-intent', [InvoiceController::class, 'createPaymentIntent'])->name('portals.invoices.payment-intent');
    });
});

require __DIR__.'/auth.php';
