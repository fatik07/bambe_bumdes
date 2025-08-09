<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\KatalogController;
use App\Http\Controllers\Api\LegalitasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Article API Routes
Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);
    Route::post('/', [ArticleController::class, 'store']);
    Route::get('/tags', [ArticleController::class, 'getTags']);
    Route::get('/tag/{tagId}', [ArticleController::class, 'getByTag']);
    Route::get('/{id}', [ArticleController::class, 'show']);
    Route::put('/{id}', [ArticleController::class, 'update']);
    Route::delete('/{id}', [ArticleController::class, 'destroy']);
});

// Katalog API Routes
Route::prefix('katalogs')->group(function () {
    Route::get('/', [KatalogController::class, 'index']);
    Route::get('/home', [KatalogController::class, 'getForHome']);
    Route::post('/', [KatalogController::class, 'store']);
    Route::get('/{id}', [KatalogController::class, 'show']);
    Route::put('/{id}', [KatalogController::class, 'update']);
    Route::delete('/{id}', [KatalogController::class, 'destroy']);
});

// Legalitas API Routes
Route::apiResource('legalitas', LegalitasController::class);
