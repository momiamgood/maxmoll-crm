<?php

namespace App\Handlers\Order;

use App\Enums\OrderStatusEnum;
use App\Exceptions\OrderServiceException;
use App\Services\OrderService;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class ResumeOrderHandler
{
    /**
     * @param OrderService $orderService
     */
    public function __construct(
        private OrderService $orderService
    )
    {}

    /**
     * Возобновляет заказ, возвращает его в статус active.
     *
     * Проверки:
     * * - Если заказ не найден, выбрасывается 404
     * * - Если заказ не canceled — выбрасывается 409
     *
     * @param int $id
     * @return array
     * @throws OrderServiceException
     */
    public function handle(int $id): array
    {
        $order = $this->orderService->findById($id);

        if (is_null($order)) {
            throw new NotFoundHttpException(
                message: "Order with id: $id does not exists."
            );
        }

        if ($order->status !== OrderStatusEnum::CANCELED->value) {
            throw new ConflictHttpException(
                message: "Only cancelled orders can be resumed."
            );
        }

        $this->orderService->resume($id);

        return [
            'message' => "Order with was resumed.",
            'orderId' => $order->id
        ];
    }
}
