<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use Filterable;

    /** @var bool */
    public $timestamps = false;

    /** @var array */
    protected $guarded = [];

    /**
     * Связь с элементами заказа.
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItems::class);
    }

    /**
     * Связь со складом.
     *
     * @return BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Связь с товарами, включенными в заказ.
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Product::class,
            table: 'order_items',
            foreignPivotKey: 'order_id',
            relatedPivotKey: 'product_id',
        )
            ->as('count')
            ->withPivot('count')
            ->using(OrderItems::class);
    }
}
