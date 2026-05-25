<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ServicePageController;
use App\Http\Controllers\Frontend\CustomerPageController;
use App\Http\Controllers\Frontend\SubscriptionPageController;

// === ROUTE FOR SERVICES ===
Route::get('/services', [ServicePageController::class, 'index']);
Route::post('/services', [ServicePageController::class, 'store']);
Route::get('/services/{id}/edit', [ServicePageController::class, 'edit']);
Route::put('/services/{id}', [ServicePageController::class, 'update']);
Route::delete('/services/{id}', [ServicePageController::class, 'destroy']);
Route::patch('/services/{id}/activate', [ServicePageController::class, 'activate']);
Route::patch('/services/{id}/deactivate', [ServicePageController::class, 'deactivate']);

// === ROUTE FOR CUSTOMERS ===
Route::get('/customers', [CustomerPageController::class, 'index']);
Route::post('/customers', [CustomerPageController::class, 'store']);
Route::get('/customers/{id}/edit', [CustomerPageController::class, 'edit']);
Route::put('/customers/{id}', [CustomerPageController::class, 'update']);
Route::delete('/customers/{id}', [CustomerPageController::class, 'destroy']);
Route::patch('/customers/{id}/activate', [CustomerPageController::class, 'activate']);
Route::patch('/customers/{id}/deactivate', [CustomerPageController::class, 'deactivate']);

// === ROUTE FOR SUBSCRIPTIONS ===
Route::get('/subscriptions', [SubscriptionPageController::class, 'index']);
Route::post('/subscriptions', [SubscriptionPageController::class, 'store']);
Route::patch('/subscriptions/{id}/status', [SubscriptionPageController::class, 'updateStatus']);
Route::delete('/subscriptions/{id}', [SubscriptionPageController::class, 'destroy']);