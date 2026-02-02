<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AboutController;

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // ✅ ROUTE KHUSUS HARUS DI ATAS RESOURCE
    Route::get('/expenses/recap', [ExpenseController::class,'monthlyRecap'])
        ->name('expenses.recap');

    Route::get('/expenses/export/pdf', [ExpenseController::class,'exportPdf'])
        ->name('expenses.export.pdf');

    Route::get('/expenses/export/excel', [ExpenseController::class,'exportExcel'])
        ->name('expenses.export.excel');

    // ✅ BARU RESOURCE
    Route::resource('/expenses', ExpenseController::class);

    Route::get('/about', [AboutController::class, 'index'])->name('about.index');
});
