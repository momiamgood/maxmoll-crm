<?php

namespace App\Handlers\Order;

use App\DTO\Order\OrderCreateDTO;
use App\Exceptions\OrderServiceException;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;

final readonly class CreateOrderHandler
{
    /**
     * @param OrderService $orderService
     */
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Обрабатывает создание нового заказа.
     *
     * Преобразует данные из запроса в DTO и делегирует создание заказа сервису.
     *
     * @param OrderRequest $request
     * @return array
     * @throws OrderServiceException
     */
    public function handle(OrderRequest $request): array
    {
        $orderCreateDTO = OrderCreateDTO::instance(
            data: $request->all()
        );

        $order = $this->orderService->create($orderCreateDTO);

        return [
            'orderId' => $order->id,
        ];
    }
}
