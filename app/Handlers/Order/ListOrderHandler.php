<?php

namespace App\Handlers\Order;

use App\Http\Filters\OrderFilter;
use App\Http\Requests\OrderListRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final readonly class ListOrderHandler
{
    /**
     * Обрабатывает запрос на получение списка заказов с фильтрацией и пагинацией.
     *
     * @param OrderListRequest $request
     * @return AnonymousResourceCollection
     * @throws BindingResolutionException
     */
    public function handle(OrderListRequest $request): AnonymousResourceCollection
    {
        $data = $request->all();

        // Создаём фильтр через сервис-контейнер, передаём queryParams в конструктор
        $filter = app()->make(OrderFilter::class, [
            'queryParams' => array_filter($data) // убираем пустые значения
        ]);

        // Применяем фильтрацию и пагинацию
        $orders = Order::filter($filter)
            ->paginate($data['perPage'] ?? 10);

        return OrderResource::collection($orders);
    }
}
