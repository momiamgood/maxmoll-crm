<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Throwable;

class WarehouseController extends Controller
{
    /**
     *
     * Получение списка всех складов
     *
     * @return ResourceCollection
     * @throws Throwable
     */
    public function index(): ResourceCollection
    {
        return Warehouse::all()->toResourceCollection();
    }
}
