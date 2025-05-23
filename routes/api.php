<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PembeliController;
use App\Http\Controllers\API\PenjualController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Models\Wilayah;
use Illuminate\Http\Request;

Route::prefix('auth')->group(function () {
    Route::post('/daftar', [AuthController::class, 'daftar']);
    Route::post('/keluar', [AuthController::class, 'keluar'])->middleware('auth:sanctum');

    // Login sesuai peran
    Route::post('/masuk/pembeli', [PembeliController::class, 'masuk']);
    Route::post('/masuk/penjual', [PenjualController::class, 'masuk']);
    Route::post('/masuk/admin', [AdminController::class, 'masuk']);
});

Route::middleware('auth:sanctum')->get('/profil', function (Request $request) {
    return response()->json([
        'user' => $request->user()
    ]);
});

Route::get('/wilayah', function () {
    return Wilayah::all();
});

Route::middleware(['auth:sanctum'])->prefix('penjual')->group(function () {
    Route::get('products', [\App\Http\Controllers\API\Penjual\ProductController::class, 'index']);
    Route::post('products', [\App\Http\Controllers\API\Penjual\ProductController::class, 'store']);
    Route::get('products/{id}', [\App\Http\Controllers\API\Penjual\ProductController::class, 'show']);
    Route::put('products/{id}', [\App\Http\Controllers\API\Penjual\ProductController::class, 'update']);
    Route::delete('products/{id}', [\App\Http\Controllers\API\Penjual\ProductController::class, 'destroy']);
});
