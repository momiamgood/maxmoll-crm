<?php

namespace App\Listeners;

use App\Events\HistoryChangeEvent;
use App\Models\History;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HistoryChangeListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
    )
    {
    }

    /**
     * Создаем запись о движении товара.
     *
     * @param HistoryChangeEvent $event
     * @return void
     */
    public function handle(HistoryChangeEvent $event): void
    {
        History::create([
            'product_id' => $event->productId,
            'warehouse_id' => $event->warehouseId,
            'quantity_change' => $event->count,
            'before' => $event->before,
            'after' => $event->after,
            'operation_type' => $event->operationType,
            'reason' => $event->reason
        ]);
    }
}
