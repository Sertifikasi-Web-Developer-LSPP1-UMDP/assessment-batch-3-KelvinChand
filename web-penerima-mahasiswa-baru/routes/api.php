<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengumumanController;

// Routes dengan prefix 'user'
Route::prefix('user')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify', [AuthController::class, 'verifyAccount']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('/', [UserController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{id}', [UserController::class, 'show'])->middleware('auth:sanctum');
    Route::put('/{id}', [UserController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('auth:sanctum');

});

// Routes dengan prefix 'pengumuman'
Route::prefix('pengumuman')->group(function () {

    Route::post('/', [PengumumanController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/', [PengumumanController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{id}', [PengumumanController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/{id}', [PengumumanController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [PengumumanController::class, 'destroy'])->middleware('auth:sanctum');

});

