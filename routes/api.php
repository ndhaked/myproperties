<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ConfigController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\PropertyController;
use App\Http\Controllers\Api\V1\SellerController;
use App\Http\Controllers\Api\V1\UploadController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\VisitController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth (public)
    Route::post('auth/otp/send', [AuthController::class, 'sendOtp']);
    Route::post('auth/otp/verify', [AuthController::class, 'verifyOtp']);
    Route::post('auth/otp/resend', [AuthController::class, 'resendOtp']);
    Route::post('auth/social-login', [AuthController::class, 'socialLogin']);
    Route::post('auth/refresh-token', [AuthController::class, 'refreshToken']);

    // Home & properties (public)
    Route::get('home', [HomeController::class, 'index']);
    Route::get('properties/search', [HomeController::class, 'search']);
    Route::get('properties/filter-options', [PropertyController::class, 'filterOptions']);
    Route::get('properties', [PropertyController::class, 'index']);
    Route::get('properties/{property}', [PropertyController::class, 'show']);

    // Config (public)
    Route::get('config/property-form-options', [ConfigController::class, 'propertyFormOptions']);
    Route::get('config/countries', [ConfigController::class, 'countries']);
    Route::get('config/app', [ConfigController::class, 'app']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::post('properties/{property}/save', [PropertyController::class, 'save']);
        Route::delete('properties/{property}/save', [PropertyController::class, 'unsave']);
        Route::post('properties/{property}/contact', [PropertyController::class, 'contact']);
        Route::post('properties/{property}/visits', [VisitController::class, 'store']);

        Route::get('users/me', [UserController::class, 'me']);
        Route::patch('users/me', [UserController::class, 'update']);
        Route::post('users/me/avatar', [UserController::class, 'uploadAvatar']);
        Route::get('users/me/saved-properties', [UserController::class, 'savedProperties']);

        Route::get('visits', [VisitController::class, 'index']);
        Route::patch('visits/{visit}', [VisitController::class, 'update']);

        Route::post('uploads/images', [UploadController::class, 'images']);
        Route::post('uploads/documents', [UploadController::class, 'documents']);

        Route::get('notifications', [NotificationController::class, 'index']);
        Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::patch('notifications/{notification}/read', [NotificationController::class, 'markRead']);

        Route::middleware('role.mobile:Seller')->group(function () {
            Route::get('seller/dashboard', [SellerController::class, 'dashboard']);
            Route::get('seller/leads', [SellerController::class, 'leads']);
            Route::get('seller/listings', [SellerController::class, 'listings']);
            Route::post('seller/listings', [SellerController::class, 'storeListing']);
            Route::get('seller/listings/{property}', [SellerController::class, 'showListing']);
            Route::patch('seller/listings/{property}', [SellerController::class, 'updateListing']);
            Route::delete('seller/listings/{property}', [SellerController::class, 'destroyListing']);
            Route::get('seller/listings/{property}/performance', [SellerController::class, 'performance']);

            Route::get('seller/business-profile', [SellerController::class, 'businessProfile']);
            Route::patch('seller/business-profile', [SellerController::class, 'updateBusinessProfile']);
            Route::get('seller/payout-settings', [SellerController::class, 'payoutSettings']);
            Route::patch('seller/payout-settings', [SellerController::class, 'updatePayoutSettings']);
        });
    });
});
