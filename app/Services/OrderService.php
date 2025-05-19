<?php

namespace App\Services;

use App\DTO\Order\OrderCreateDTO;
use App\DTO\Order\OrderUpdateDTO;
use App\DTO\OrderItem\OrderItemDTO;
use App\Enums\HistoryChangeReasonsEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\StockOperationTypeEnum;
use App\Events\HistoryChangeEvent;
use App\Exceptions\OrderServiceException;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Throwable;

class OrderService
{
    /**
     * Создает товар и его позиции.
     *
     * Добавляет лог в таблицу histories с информацией по изменению количества и типом операции create
     *
     * @param OrderCreateDTO $orderCreateDTO
     * @return mixed
     * @throws OrderServiceException
     */
    public function create(OrderCreateDTO $orderCreateDTO): mixed
    {
        DB::beginTransaction();

        try {
            $order = Order::create([
                'customer' => $orderCreateDTO->customer,
                'status' => OrderStatusEnum::ACTIVE,
                'warehouse_id' => $orderCreateDTO->warehouseId
            ]);

            /** @var OrderItemDTO $orderItem */
            foreach ($orderCreateDTO->orderItems as $orderItem) {
                OrderItems::create([
                    'product_id' => $orderItem->productId,
                    'count' => $orderItem->count,
                    'order_id' => $order->id,
                ]);

                $this->decrementStock(
                    $orderItem->productId,
                    $orderCreateDTO->warehouseId,
                    $orderItem->count,
                    HistoryChangeReasonsEnum::CREATE
                );
            }

            DB::commit();
            return $order;
        } catch (Throwable $exception) {
            DB::rollBack();
            throw new OrderServiceException('Order creation error: ' . $exception->getMessage());
        }
    }

    /**
     * Увеличивает остаток товара на складе.
     *
     * Используется при возврате товара (например, при отмене или обновлении заказа).
     * Обновляет количество и логирует изменение в историю.
     *
     * @param int $productId
     * @param int $warehouseId
     * @param int $count Количество для возврата
     * @param string $reason Причина изменения (например: UPDATE, CANCEL)
     */
    private function incrementStock(int $productId, int $warehouseId, int $count, string $reason): void
    {
        $stock = Stock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->firstOrFail();

        $before = $stock->stock;
        $after = $before + $count;

        $this->triggerChangeStockHistory($productId, $warehouseId, $before, $after, $count, $reason);
        $stock->update(['stock' => $after]);
    }

    /**
     * Уменьшает остаток товара на складе.
     *
     * Выполняет проверку на наличие товара и достаточное количество.
     * Если операция валидна, уменьшает остаток и записывает событие в историю.
     *
     * @param int $productId
     * @param int $warehouseId
     * @param int $count Количество для списания
     * @param string $reason Причина изменения (например: CREATE, UPDATE)
     * @throws ConflictHttpException Если товар не найден или остатков недостаточно
     */
    private function decrementStock(int $productId, int $warehouseId, int $count, string $reason): void
    {
        $stock = Stock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();

        if (!$stock) {
            throw new ConflictHttpException(
                message: 'The warehouse does not sell goods.'
            );
        }

        $before = $stock->stock;
        $after = $before - $count;

        if ($after < 0) {
            throw new ConflictHttpException(
                message: 'Insufficient quantity of goods in stock.');
        }

        $this->triggerChangeStockHistory($productId, $warehouseId, $before, $after, -$count, $reason);
        $stock->update(['stock' => $after]);
    }

    /**
     * Отправляет событие для записи изменения остатка в историю.
     *
     * Определяет тип операции (списание или пополнение) по знаку изменения.
     *
     * @param int $productId
     * @param int $warehouseId
     * @param int $before Количество до изменения
     * @param int $after Количество после изменения
     * @param int $count Абсолютное значение изменения (отрицательное — списание, положительное — пополнение)
     * @param string $reason Причина изменения (CREATE, UPDATE, CANCEL и т.д.)
     */
    private function triggerChangeStockHistory(
        int    $productId,
        int    $warehouseId,
        int    $before,
        int    $after,
        int    $count,
        string $reason
    ): void
    {
        $type = $count < 0
            ? StockOperationTypeEnum::DECREMENT->value
            : StockOperationTypeEnum::INCREMENT->value;

        HistoryChangeEvent::dispatch(
            $productId,
            $warehouseId,
            $type,
            $before,
            $after,
            $count,
            $reason
        );
    }
}
