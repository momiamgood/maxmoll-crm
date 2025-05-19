<?php

namespace App\Http\Controllers;

use App\Handlers\Order\CancelOrderHandler;
use App\Handlers\Order\CompleteOrderHandler;
use App\Handlers\Order\ListOrderHandler;
use App\Handlers\Order\UpdateOrderHandler;
use App\Http\Requests\OrderListRequest;
use App\Http\Requests\OrderRequest;
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

    /**
     * Создать новый заказ.
     *
     * Тело запроса:
     *  - customer (string, required): имя заказчика
     *  - warehouseId (int, required): ID склада
     *  - items (array of objects, required): массив товаров
     *      - productId (int, required): ID товара
     *      - count (int, required): количество товара
     *
     * @param OrderRequest $request
     * @param CreateOrderHandler $handler
     * @return JsonResponse
     */
    public function create(OrderRequest $request, CreateOrderHandler $handler): JsonResponse
    {
        return $this->handleWithExceptions(
            callback: fn () => $handler->handle($request),
            successCode: 201
        );
    }

    /**
     * Обновить существующий заказ.
     *
     * Тело запроса:
     *   - customer (string, required): имя заказчика
     *   - warehouseId (int, required): ID склада
     *   - items (array of objects, required): массив товаров
     *       - productId (int, required): ID товара
     *       - count (int, required): количество товара
     *
     *
     * @param OrderRequest $request
     * @param int $id
     * @param UpdateOrderHandler $handler
     * @return JsonResponse
     */
    public function update(OrderRequest $request, int $id, UpdateOrderHandler $handler): JsonResponse
    {
        return $this->handleWithExceptions(
            callback: fn () => $handler->handle(
                requestData: $request->all(),
                id: $id
            )
        );
    }

    /**
     * Завершить заказ.
     *
     * @param int $id
     * @param CompleteOrderHandler $handler
     * @return JsonResponse
     */
    public function complete(int $id, CompleteOrderHandler $handler): JsonResponse
    {
        return $this->handleWithExceptions(fn() => $handler->handle($id));
    }

    /**
     * Отменить заказ.
     *
     * @param int $id
     * @param CancelOrderHandler $handler
     * @return JsonResponse
     */
    public function cancel(int $id, CancelOrderHandler $handler): JsonResponse
    {
        return $this->handleWithExceptions(fn() => $handler->handle($id));
    }

    /**
     * Возобновить отменённый заказ.
     *
     * @param int $id
     * @param ResumeOrderHandler $handler
     * @return JsonResponse
     */
    public function resume(int $id, ResumeOrderHandler $handler): JsonResponse
    {
        return $this->handleWithExceptions(fn() => $handler->handle($id));
    }
}
