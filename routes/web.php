<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

//Обрабатывает GET-запросы (переход по ссылке), Главная страница сайта (http://сайт/),
// Вызвать метод home() в контроллере PageController, Дать маршруту имя home для использования в коде
Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/category/{category}', [PageController::class, 'category'])->name('category');

//GET-запрос, URL: /product/категория/ID (два параметра), Вызвать метод product(), Имя маршрута: product
Route::get('/product/{category}/{id}', [PageController::class, 'product'])->name('product');

//Обрабатывает POST-запросы (отправка формы), URL: /order, Вызвать метод store() в контроллере OrderController, 
//Имя маршрута: order.store
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

Route::get('/order/success/{id}', [OrderController::class, 'success'])->name('order.success');

// МАРШРУТ ДЛЯ ПОИСКА
Route::get('/search', [PageController::class, 'search'])->name('search');