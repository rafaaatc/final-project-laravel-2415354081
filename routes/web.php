<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ServicePageController;

Route::get('/services', [ServicePageController::class, 'index']);

Route::post('/services', [ServicePageController::class, 'store']);

Route::get('/services/create', [ServicePageController::class, 'create']);

Route::get('/services/{id}/edit', [ServicePageController::class, 'edit']);

Route::put('/services/{id}', [ServicePageController::class, 'update']);

Route::delete('/services/{id}', [ServicePageController::class, 'destroy']);

Route::patch('/services/{id}/activate', [ServicePageController::class, 'activate']);

Route::patch('/services/{id}/deactivate', [ServicePageController::class, 'deactivate']);