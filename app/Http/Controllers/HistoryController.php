<?php

namespace App\Http\Controllers;

use App\Handlers\History\HistoryListHandler;
use App\Http\Requests\HistoryListRequest;
use Illuminate\Http\JsonResponse;

class HistoryController extends Controller
{
    /**
     * Получить историю изменений остатков товаров на складе.
     *
     * Поддерживаемые параметры запроса:
     * - dateFrom (string, optional): начальная дата фильтра по дате изменения
     * - dateTo (string, optional): конечная дата фильтра по дате изменения
     * - warehouseId (int, optional): ID склада
     * - productId (int, optional): ID товара
     *
     * @param HistoryListRequest $request
     * @param HistoryListHandler $handler
     * @return JsonResponse
     */
    public function index(HistoryListRequest $request, HistoryListHandler $handler): JsonResponse
    {
        return $this->handleWithExceptions(fn() => $handler->handle($request));
    }
}
