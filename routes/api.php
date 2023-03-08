<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('user')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('{id}', [ProductController::class, 'show']);
    Route::middleware(['auth:api', 'admin'])->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::patch('{id}', [ProductController::class, 'update']);
        Route::delete('{id}', [ProductController::class, 'destroy']);
    });
});

Route::prefix('purchase')->middleware(['auth:api', 'admin'])->group(function () {
    Route::get('/', [PurchaseController::class, 'index']);
    Route::get('{id}', [PurchaseController::class, 'show']);
    Route::post('/', [PurchaseController::class, 'store']);
});

Route::prefix('sale')->middleware(['auth:api'])->group(function () {
    Route::get('/', [SaleController::class, 'index']);
    Route::get('{id}', [SaleController::class, 'show']);
    Route::post('/', [SaleController::class, 'store']);
    Route::post('/admin', [SaleController::class, 'storeAdmin']);
    Route::patch('/approve/{id}', [SaleController::class, 'update']);
});