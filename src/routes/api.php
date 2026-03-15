<?php

use App\Http\Controllers\Api\TransactionDataController;
use App\Http\Controllers\Api\CategoryDataController;
use App\Http\Controllers\Api\BudgetDataController;
use Illuminate\Support\Facades\Route;

// Rutas API de SmartBudget.
Route::middleware('web')->group(function () {

    // Transacciones — endpoint para DataTables serverSide
    Route::get('/transactions', [TransactionDataController::class, 'index'])
        ->name('api.transactions.index');

    // Categorías — endpoint para DataTables serverSide
    Route::get('/categories', [CategoryDataController::class, 'index'])
        ->name('api.categories.index');

    // Presupuestos — endpoint para DataTables serverSide
    Route::get('/budgets', [BudgetDataController::class, 'index'])
        ->name('api.budgets.index');
});