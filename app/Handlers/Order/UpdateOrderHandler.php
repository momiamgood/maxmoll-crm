<?php

namespace App\Handlers\Order;

use App\DTO\Order\OrderUpdateDTO;
use App\Exceptions\orderServiceException;
use App\Services\OrderService;

final readonly class UpdateOrderHandler
{
    /**
     * @param OrderService $orderService
     */
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Обновляет заказ по переданным данным.
     *
     * @param array $requestData
     * @param int $id
     * @return array
     * @throws orderServiceException
     */
    public function handle(array $requestData, int $id): array
    {
        $orderUpdateDTO = OrderUpdateDTO::instance(
            data: $requestData,
            id: $id
        );

        $order = $this->orderService->update($orderUpdateDTO);

        return [
            'message' => 'Order updated successfully.',
            'orderId' => $order->id,
        ];
    }
}
