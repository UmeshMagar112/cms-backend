<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\SubscriptionPlanController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Admin
Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Welcome Admin']);
        });

        //company management
        Route::get('/companies', [CompanyController::class, 'index']);
        Route::get('/companies/pending', [CompanyController::class, 'pending']);
        Route::post('/companies/{id}/approve', [CompanyController::class, 'approve']);

        // Articles
        Route::get('/articles', [ArticleController::class, 'index']);
        Route::get('/articles/{id}', [ArticleController::class, 'show']);
        Route::post('/articles', [ArticleController::class, 'store']);
        Route::put('/articles/{id}', [ArticleController::class, 'update']);
        Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

        // Subscriptions
        Route::get('/subscriptions', [SubscriptionPlanController::class, 'index']);
        Route::get('/subscriptions/{id}', [SubscriptionPlanController::class, 'show']);
        Route::post('/subscriptions', [SubscriptionPlanController::class, 'store']);
        Route::put('/subscriptions/{id}', [SubscriptionPlanController::class, 'update']);
        Route::delete('/subscriptions/{id}', [SubscriptionPlanController::class, 'destroy']);
    });
});
