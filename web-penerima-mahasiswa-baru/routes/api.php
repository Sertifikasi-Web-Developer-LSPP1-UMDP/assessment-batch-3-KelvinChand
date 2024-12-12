<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\PembayaranController;

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

// Routes dengan prefix 'jurusan'
Route::prefix('jurusan')->group(function () {

    Route::post('/', [JurusanController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/', [JurusanController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{id}', [JurusanController::class, 'show'])->middleware('auth:sanctum');
    Route::put('/{id}', [JurusanController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [JurusanController::class, 'destroy'])->middleware('auth:sanctum');

});

// Routes dengan prefix 'mahasiswa'
Route::prefix('mahasiswa')->group(function () {

    Route::post('/', [MahasiswaController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/', [MahasiswaController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{id}', [MahasiswaController::class, 'show'])->middleware('auth:sanctum');
    Route::put('/{id}', [MahasiswaController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [MahasiswaController::class, 'destroy'])->middleware('auth:sanctum');

});

// Routes dengan prefix 'dokumen'
Route::prefix('dokumen')->group(function () {

    Route::post('/', [DokumenController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/', [DokumenController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{id}', [DokumenController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/{id}', [DokumenController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [DokumenController::class, 'destroy'])->middleware('auth:sanctum');

});

// Routes dengan prefix 'dokumen'
Route::prefix('pembayaran')->group(function () {

    Route::post('/', [PembayaranController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/', [PembayaranController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{id}', [PembayaranController::class, 'show'])->middleware('auth:sanctum');
    Route::put('/{id}', [PembayaranController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [PembayaranController::class, 'destroy'])->middleware('auth:sanctum');

});



