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

// Публичные маршруты
Route::post('/login', [AuthController::class, 'login']);
Route::get('/test', function () {
    return ['message' => 'API работает!'];
});

// ВСЕ РОУТЫ БЕЗ ЗАЩИТЫ - проверка токена будет в коде приложения
Route::get('orders', [OrderController::class, 'index']);
Route::get('orders/{id}', [OrderController::class, 'show']);
Route::post('orders', [OrderController::class, 'store']);
Route::put('orders/{id}', [OrderController::class, 'update']);
Route::delete('orders/{id}', [OrderController::class, 'destroy']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::get('products/category/{category}', [ProductController::class, 'byCategory']);
Route::post('products', [ProductController::class, 'store']);
Route::put('products/{id}', [ProductController::class, 'update']);
Route::delete('products/{id}', [ProductController::class, 'destroy']);

Route::apiResource('employees', EmployeeController::class);
Route::apiResource('finances', FinanceController::class);
Route::apiResource('materials', MaterialController::class);
Route::apiResource('work-reports', WorkReportController::class);
Route::apiResource('access', AccessController::class);
Route::apiResource('salaries', SalaryController::class);

Route::get('images', [ImageController::class, 'index']);
Route::post('images/upload', [ImageController::class, 'upload']);
Route::delete('images/delete', [ImageController::class, 'delete']);
Route::put('images/rename', [ImageController::class, 'rename']);