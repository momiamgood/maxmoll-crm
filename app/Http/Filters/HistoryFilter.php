<?php

namespace App\Http\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class HistoryFilter extends AbstractFilter
{
    const DATE_TO = 'dateTo',
        DATE_FROM = 'dateFrom',
        WAREHOUSE = 'warehouseId',
        PRODUCT = 'productId';

    /**
     * Получает массив методов фильтрации.
     *
     * @return array[]
     */
    protected function getCallbacks(): array
    {
        return [
            self::WAREHOUSE => [$this, 'warehouseId'],
            self::DATE_TO => [$this, 'dateTo'],
            self::DATE_FROM => [$this, 'dateFrom'],
            self::PRODUCT => [$this, 'productId']
        ];
    }

    /**
     * Фильтрация по id товара.
     *
     * @param Builder $builder
     * @param $value
     * @return Builder
     */
    public function productId(Builder $builder, $value): Builder
    {
        return $builder->where('product_id', $value);
    }

    /**
     * Фильтрация по дате создания (от))
     *
     * @param Builder $builder
     * @param $value
     * @return void
     */
    public function dateFrom(Builder $builder, $value): void
    {
        $dateFrom = Carbon::parse($value)->startOfDay();

        $builder->where('created_at', '>=', $dateFrom);
    }

    /**
     * Фильтрация по дате создания (до)
     *
     * @param Builder $builder
     * @param $value
     * @return void
     */
    public function dateTo(Builder $builder, $value): void
    {
        $date = Carbon::parse($value)->endOfDay();

        $builder->where('created_at', '<=', $date);
    }

    /**
     * Фильтрация по id склада
     *
     * @param Builder $builder
     * @param $value
     * @return void
     */
    public function warehouseId(Builder $builder, $value): void
    {
        $builder->where('warehouse_id', $value);
    }
}
