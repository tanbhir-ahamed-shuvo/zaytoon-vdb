<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────────────
Route::get('/login',          [AuthController::class, 'showLogin'])->name('login');
Route::post('/login/officer', [AuthController::class, 'officerLogin'])->name('login.officer');
Route::post('/login/admin',   [AuthController::class, 'adminLogin'])->name('login.admin');
Route::post('/logout',        [AuthController::class, 'logout'])->name('logout');

// Redirect root to login
Route::get('/', fn () => redirect()->route('login'));

// ── Geo cascading dropdown API (public, no auth needed) ───────
Route::get('/geo/districts', [GeoController::class, 'districts'])->name('geo.districts');
Route::get('/geo/thanas',    [GeoController::class, 'thanas'])->name('geo.thanas');
Route::get('/geo/unions',    [GeoController::class, 'unions'])->name('geo.unions');

// ── Field Officer routes ──────────────────────────────────────
Route::middleware('officer.auth')->group(function () {
    Route::get('/dashboard',           [OfficerController::class, 'dashboard'])->name('officer.dashboard');
    Route::get('/form',                [FormController::class,    'index'])->name('form.index');
    Route::post('/form',               [FormController::class,    'store'])->name('form.store');
    Route::get('/submissions/{submission}/edit', [OfficerController::class, 'editForm'])->name('officer.edit');
    Route::put('/submissions/{submission}',      [OfficerController::class, 'update'])->name('officer.update');
});

// ── Admin routes ──────────────────────────────────────────────
Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                  [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/export',            [AdminController::class, 'export'])->name('export');
    Route::get('/submissions/{submission}', [AdminController::class, 'show'])->name('show');
});
