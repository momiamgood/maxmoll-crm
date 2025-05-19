<?php

namespace App\DTO\OrderItem;

readonly class OrderItemDTO
{
    /**
     * @param array $data
     * @return OrderItemDTO
     */
    public static function instance(array $data): OrderItemDTO
    {
        return new self(
            productId: $data['productId'],
            count: $data['count'],
        );
    }

    /**
     * @param int $productId
     * @param int $count
     */
    public function __construct(
        public int $productId,
        public int $count,
    )
    {
    }
}
