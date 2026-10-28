<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\FinanceController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\WorkReportController;
use App\Http\Controllers\Api\AccessController;
use App\Http\Controllers\Api\SalaryController;
use App\Http\Controllers\Api\ImageController;

// ==========================================
// ПУБЛИЧНЫЕ МАРШРУТЫ (Доступны без токена)
// ==========================================
// Только вход и тест
Route::post('/login', [AuthController::class, 'login']);
Route::get('/test', function () {
    return ['message' => 'API работает!'];
});

// просмотр товаров должен быть доступен всем
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::get('products/category/{category}', [ProductController::class, 'byCategory']);
Route::get('images', [ImageController::class, 'index']);


// ==========================================
// ЗАЩИЩЕННЫЕ МАРШРУТЫ (Только с токеном)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // Заказы
    Route::apiResource('orders', OrderController::class);

    // Управление товарами (создание, изменение, удаление)
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);

    // Остальные ресурсы
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('finances', FinanceController::class);
    Route::apiResource('materials', MaterialController::class);
    Route::apiResource('work-reports', WorkReportController::class);
    Route::apiResource('access', AccessController::class);
    Route::apiResource('salaries', SalaryController::class);

    // Изображения
    Route::post('images/upload', [ImageController::class, 'upload']);
    Route::delete('images/delete', [ImageController::class, 'delete']);
    Route::put('images/rename', [ImageController::class, 'rename']);
});
