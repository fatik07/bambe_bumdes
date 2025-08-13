<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\KatalogController;
use App\Http\Controllers\Api\LegalitasController;
use App\Http\Controllers\Api\SubKatalogController;
use App\Http\Controllers\Api\TestimonialController;
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
    Route::get('/{slug}', [KatalogController::class, 'show']);
    Route::put('/{slug}', [KatalogController::class, 'update']);
    Route::delete('/{slug}', [KatalogController::class, 'destroy']);
});

// Sub Katalog API Routes
Route::prefix('katalogs/{katalogSlug}/sub-katalogs')->group(function () {
    Route::get('/', [SubKatalogController::class, 'getByKatalog']);
    Route::post('/', [SubKatalogController::class, 'store']);
    Route::get('/{subKatalogSlug}', [SubKatalogController::class, 'show']);
    Route::put('/{subKatalogSlug}', [SubKatalogController::class, 'update']);
    Route::delete('/{subKatalogSlug}', [SubKatalogController::class, 'destroy']);
});

// Testimonial API Routes
Route::prefix('katalogs/{katalogSlug}/sub-katalogs/{subKatalogSlug}/testimonials')->group(function () {
    Route::get('/', [TestimonialController::class, 'getBySubKatalog']);
    Route::post('/', [TestimonialController::class, 'store']);
    Route::get('/{id}', [TestimonialController::class, 'show']);
    Route::put('/{id}', [TestimonialController::class, 'update']);
    Route::delete('/{id}', [TestimonialController::class, 'destroy']);
});

// Legalitas API Routes
Route::apiResource('legalitas', LegalitasController::class);
