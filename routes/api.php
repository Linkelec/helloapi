<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ProfileController;

Route::post('admin/login', [AdminAuthController::class, 'login']);
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('profiles', [ProfileController::class, 'store']);
    Route::put('profiles/{profile}', [ProfileController::class, 'update']);
    Route::delete('profiles/{profile}', [ProfileController::class, 'destroy']);
});

Route::get('profiles/active', [ProfileController::class, 'getActiveProfiles']);