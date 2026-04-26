<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReceitaController;

// -------------------------------------------------------
// Autenticação
// -------------------------------------------------------
Route::get('/', fn () => view('login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// -------------------------------------------------------
// Receitas (protegido por sessão via middleware)
// -------------------------------------------------------
Route::middleware('auth.session')->group(function () {
    Route::resource('receitas', ReceitaController::class);
    Route::get('receitas-pdf', [ReceitaController::class, 'exportarPdf'])->name('receitas.pdf');
});
