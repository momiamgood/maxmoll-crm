<?php

namespace App\DTO\Order;

use App\DTO\OrderItem\OrderItemDTO;

class OrderCreateDTO
{
    /**
     * @param array $data
     * @return OrderCreateDTO
     */
    public static function instance(array $data): OrderCreateDTO
    {
        $orderItems = array_map(
            callback: fn (array $orderItem) => OrderItemDTO::instance($orderItem),
            array: $data['items']
        );

        return new self(
            customer: $data['customer'],
            warehouseId: $data['warehouseId'],
            orderItems: $orderItems
        );
    }

    /**
     * @param string $customer
     * @param int $warehouseId
     * @param array $orderItems
     */
    public function __construct(
        public string $customer,
        public int    $warehouseId,
        public array  $orderItems
    )
    {
    }
}
