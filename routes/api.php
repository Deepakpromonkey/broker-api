<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Company\CompanyController;
use App\Http\Controllers\Api\V1\Invitation\InvitationController;
use App\Http\Controllers\Api\V1\Shipment\ShipmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/invitations/accept', [InvitationController::class, 'accept']);
    Route::post('/verify-login-otp', [AuthController::class, 'verifyLoginOtp']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/company', [CompanyController::class, 'show']);
        Route::put('/company', [CompanyController::class, 'update']);
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/invitations', [InvitationController::class, 'store']);
        Route::post('/shipments', [ShipmentController::class, 'store']);
    });

});
