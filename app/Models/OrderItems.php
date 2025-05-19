<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItems extends Pivot
{
    /** @var array */
    protected $guarded = [];

    /** @var bool */
    public $timestamps = false;

    protected $table =  'order_items';

    /**
     * Связь с заказом.
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
