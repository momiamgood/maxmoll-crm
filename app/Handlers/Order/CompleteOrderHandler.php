<?php

namespace App\Handlers\Order;

use App\Enums\OrderStatusEnum;
use App\Services\OrderService;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class CompleteOrderHandler
{
    /**
     * @param OrderService $orderService
     */
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Завершает заказ, если он в статусе active.
     *
     * Проверки:
     * - Если заказ не найден, выбрасывается 404
     * - Если заказ не active — выбрасывается 409
     *
     * @param int $id
     * @return array
     */
    public function handle(int $id): array
    {
        $order = $this->orderService->findById($id);

        if (!$order) {
            throw new NotFoundHttpException("Order with id $id does not exist.");
        }

        if ($order->status !== OrderStatusEnum::ACTIVE->value) {
            throw new ConflictHttpException("Only active orders can be completed.");
        }

        $this->orderService->complete($order->id);

        return [
            'message' => 'Order was completed.',
            'orderId' => $order->id,
        ];
    }
}
