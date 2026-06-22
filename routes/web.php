<?php

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', [RecipeController::class, 'index'])->name('home');

// Auth
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// Resep (butuh login) - harus SEBELUM route {recipe}
Route::middleware('auth')->group(function () {
    Route::get('/recipes/create',         [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes',               [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{recipe}/edit',  [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{recipe}',       [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}',    [RecipeController::class, 'destroy'])->name('recipes.destroy');
    Route::post('/recipes/{recipe}/rate',    [RecipeController::class, 'rate'])->name('recipes.rate');
    Route::post('/recipes/{recipe}/comment', [RecipeController::class, 'comment'])->name('recipes.comment');
});

// Resep (publik) - harus SETELAH /recipes/create
Route::get('/recipes',           [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{recipe}',  [RecipeController::class, 'show'])->name('recipes.show');