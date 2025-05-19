<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @var array */
    protected $guarded = [];

    /**
     * Связь с информацией по остаткам.
     *
     * @return HasMany
     */
    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
