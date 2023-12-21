<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('categories', [CategorieController::class, 'index']);
Route::get('categories/{id}', [CategorieController::class, 'show']);
Route::post('categories', [CategorieController::class, 'store']);
Route::delete('categories/{id}', [CategorieController::class, 'destroy']);


Route::get('produits', [ProduitController::class, 'index']);
Route::get('produits/{id}', [ProduitController::class, 'show']);
Route::post('produits', [ProduitController::class, 'store']);
Route::delete('produits/{id}', [ProduitController::class, 'destroy']);


Route::get('panier', [PanierController::class, 'index']);
Route::get('panier/{id}', [PanierController::class, 'show']);
Route::post('panier', [PanierController::class, 'store']);
Route::delete('panier/{id}', [PanierController::class, 'destroy']);

Route::post('register', [UserController::class, 'register']);
Route::delete('login', [UserController::class, 'login']);
