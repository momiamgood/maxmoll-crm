<?php

namespace App\Http\Controllers;

use App\Handlers\Order\ListOrderHandler;
use App\Http\Requests\OrderListRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * Получить список заказов с фильтрацией.
     *
     * Поддерживаемые параметры запроса:
     * - id (int, optional): ID заказа
     * - dateFrom (string, optional): дата создания от
     * - dateTo (string, optional): дата создания до
     * - warehouseId (int, optional): ID склада
     * - status (string, optional): статус (active, canceled, completed)
     * - customer (string, optional): имя клиента
     *
     * @param OrderListRequest $request
     * @param ListOrderHandler $handler
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function index(OrderListRequest $request, ListOrderHandler $handler): JsonResponse
    {
        return response()->json($handler->handle($request));
    }
}
