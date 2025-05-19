<?php

namespace App\DTO\Order;

use App\DTO\OrderItem\OrderItemDTO;

class OrderUpdateDTO
{
    /**
     * Статическая фабрика для создания DTO из массива данных.
     *
     * @param array $data
     * @param int $id
     * @return OrderUpdateDTO
     */
    public static function instance(array $data, int $id): self
    {
        $orderItems = array_map(
            fn(array $item) => OrderItemDTO::instance($item),
            $data['items']
        );

        return new self(
            id: $id,
            customer: $data['customer'],
            warehouseId: $data['warehouseId'],
            orderItems: $orderItems
        );
    }

    /**
     * @param int $id
     * @param string $customer
     * @param int $warehouseId
     * @param OrderItemDTO[] $orderItems
     */
    public function __construct(
        public int    $id,
        public string $customer,
        public int    $warehouseId,
        public array  $orderItems
    )
    {
    }
}
