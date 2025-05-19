<?php

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

// 📦 Список всех складов
Route::get('/warehouses', [WarehouseController::class, 'index']);

// 🛒 Список всех товаров
Route::get('/products', [ProductController::class, 'index']);

// 📘 Группа маршрутов, связанных с заказами
Route::prefix('/orders')->group(function () {
    Route::put('/{id}', [OrderController::class, 'update']);      // Обновить существующий заказ
    Route::get('/{id}/complete', [OrderController::class, 'complete']); // Завершить заказ
    Route::get('/{id}/cancel', [OrderController::class, 'cancel']);     // Отменить заказ
    Route::get('/{id}/resume', [OrderController::class, 'resume']);     // Возобновить отменённый заказ
    Route::post('/', [OrderController::class, 'create']);         // Создать новый заказ
    Route::get('/', [OrderController::class, 'index']);           // Получить список заказов
});

// 📈 Получить историю изменений остатков на складе
Route::get('/history', [HistoryController::class, 'index']);
