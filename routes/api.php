<?php

use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

// 📦 Список всех складов
Route::get('/warehouses', [WarehouseController::class, 'index']);
