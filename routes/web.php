<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::get('/logout', [LoginController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('/user', UserController::class)->except(['show', 'create']);
    Route::resource('/lead', LeadController::class)->except(['show', 'create']);
    Route::resource('/product', ProductController::class)->except(['show', 'create']);
    Route::resource('/project', ProjectController::class)->except(['create']);
});
