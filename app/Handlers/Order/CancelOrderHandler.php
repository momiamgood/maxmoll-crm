<?php

namespace App\Handlers\Order;

use App\Enums\OrderStatusEnum;
use App\Exceptions\OrderServiceException;
use App\Services\OrderService;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class CancelOrderHandler
{
    /**
     * @param OrderService $orderService
     */
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Отменяет заказ, если он находится в допустимом статусе.
     *
     * Проверки:
     * - Если заказ не найден, выбрасывается 404 (NotFoundHttpException)
     * - Если заказ уже отменён или завершён — 409 (ConflictHttpException)
     *
     * @param int $id
     * @return array
     * @throws OrderServiceException
     */
    public function handle(int $id): array
    {
        $order = $this->orderService->findById($id);

        if (!$order) {
            throw new NotFoundHttpException("Order with id $id does not exist.");
        }

        // Разрешаем отмену только активных заказов
        if (in_array($order->status, [
            OrderStatusEnum::CANCELED,
            OrderStatusEnum::COMPLETED
        ])) {
            throw new ConflictHttpException("Only active orders can be canceled.");
        }

        $this->orderService->cancel($id);

        return [
            'message' => 'Order was canceled.',
            'orderId' => $order->id,
        ];
    }
}
