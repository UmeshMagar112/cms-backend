<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\SubscriptionPlanController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// public subscription view
Route::get('/subscriptions', [SubscriptionPlanController::class, 'index']);
Route::get('/subscriptions/{id}', [SubscriptionPlanController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    // Admin
Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Welcome Admin']);
        });

           // Articles
        Route::get('/articles', [ArticleController::class, 'index']);
        Route::get('/articles/{id}', [ArticleController::class, 'show']);
        Route::post('/articles', [ArticleController::class, 'store']);
        Route::put('/articles/{id}', [ArticleController::class, 'update']);
        Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);


        //company management
        Route::get('/companies', [CompanyController::class, 'index']);
        Route::get('/companies/pending', [CompanyController::class, 'pending']);
        Route::post('/companies/{id}/approve', [CompanyController::class, 'approve']);

     
       // Subscriptions Management
        Route::get('/subscriptions', [SubscriptionPlanController::class, 'index']);
        Route::post('/subscriptions', [SubscriptionPlanController::class, 'store']);
        Route::get('/subscriptions/{id}', [SubscriptionPlanController::class, 'show']);
        Route::put('/subscriptions/{id}', [SubscriptionPlanController::class, 'update']);
        Route::delete('/subscriptions/{id}', [SubscriptionPlanController::class, 'destroy']);
        
        // Subscription Payment
        Route::get('/subscriptions/{id}/payment', [SubscriptionPlanController::class, 'paymentPage']);
        Route::post('/subscriptions/payment', [SubscriptionPlanController::class, 'submitPayment']);

    });
});
