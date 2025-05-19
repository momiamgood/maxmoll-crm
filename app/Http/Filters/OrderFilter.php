<?php

namespace App\Http\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    const ID = 'id',
        DATE_FROM = 'dateFrom',
        DATE_TO = 'dateTo',
        WAREHOUSE = 'warehouseId',
        CUSTOMER = 'customer',
        STATUS = 'status',
        WITH_PRODUCTS = 'withProducts',
        WITH_WAREHOUSE = 'withWarehouse';

    /**
     * Получаем массив методов фильтрации
     *
     * @return array[]
     */
    protected function getCallbacks(): array
    {
        return [
            self::ID => [$this, 'id'],
            self::WAREHOUSE => [$this, 'warehouseId'],
            self::DATE_TO => [$this, 'dateTo'],
            self::DATE_FROM => [$this, 'dateFrom'],
            self::CUSTOMER => [$this, 'customer'],
            self::STATUS => [$this, 'status'],
            self::WITH_WAREHOUSE => [$this, 'withWarehouse'],
            self::WITH_PRODUCTS => [$this, 'withProducts'],
        ];
    }

    /**
     * Фильтр по id
     *
     * @param Builder $builder
     * @param int $value
     * @return void
     */
    public function id(Builder $builder, int $value): void
    {
        $builder->where('id', $value);
    }

    /**
     * Фильтр по дате создания
     *
     * @param Builder $builder
     * @param string $value
     * @return void
     */
    public function dateFrom(Builder $builder, string $value): void
    {
        $dateFrom = Carbon::parse($value)->startOfDay();

        $builder->where('created_at', '>=', $dateFrom);
    }

    /**
     * Фильтр по дате создания (до)
     *
     * @param Builder $builder
     * @param string $value
     * @return void
     */
    public function dateTo(Builder $builder, string $value): void
    {
        $date = Carbon::parse($value)->endOfDay();

        $builder->where('created_at', '<=', $date);
    }

    /**
     * Фильтр по складу
     *
     * @param Builder $builder
     * @param int $value
     * @return void
     */
    public function warehouseId(Builder $builder, int $value): void
    {
        $builder->where('warehouse_id', $value);
    }

    /**
     * Фильтр по статусу
     *
     * @param Builder $builder
     * @param string $value
     * @return void
     */
    public function status(Builder $builder, string $value): void
    {
        $builder->where('status', $value);
    }

    /**
     * Фильтр по заказчику
     *
     * @param Builder $builder
     * @param string $value
     * @return void
     */
    public function customer(Builder $builder, string $value): void
    {
        $builder->where('customer', 'like', "%$value%");
    }

    /**
     * Подгрузка элементов заказа.
     *
     * @param Builder $builder
     * @return void
     */
    public function withProducts(Builder $builder): void
    {
        $builder->with('products');
    }

    /**
     * Подгрузка информации о складе.
     *
     * @param Builder $builder
     * @return void
     */
    public function withWarehouse(Builder $builder): void
    {
        $builder->with('warehouse');
    }
}
