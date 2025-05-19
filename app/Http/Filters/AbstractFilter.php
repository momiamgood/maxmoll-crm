<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Базовый абстрактный фильтр для Eloquent-запросов.
 *
 * Реализует интерфейс FilterInterface и обрабатывает параметры фильтрации,
 * вызывая методы, указанные в getCallbacks().
 */
abstract class AbstractFilter implements FilterInterface
{
    /**
     * Массив параметров фильтрации, переданных из запроса.
     *
     * @var array
     */
    private array $queryParams = [];

    /**
     * Инициализирует фильтр с параметрами фильтрации.
     *
     * @param array $queryParams Параметры фильтрации (ключ => значение)
     */
    public function __construct(array $queryParams)
    {
        $this->queryParams = $queryParams;
    }

    /**
     * Применяет фильтры к Eloquent-запросу.
     *
     * Вызывает соответствующие методы-фильтры, определённые в getCallbacks(),
     * если параметр присутствует в queryParams.
     *
     * @param Builder $builder Экземпляр Eloquent-запроса
     * @return void
     */
    public function apply(Builder $builder): void
    {
        foreach ($this->getCallbacks() as $name => $callback) {
            if (isset($this->queryParams[$name])) {
                call_user_func($callback, $builder, $this->queryParams[$name]);
            }
        }
    }

    /**
     * Возвращает массив фильтрующих методов в формате:
     * ['field_name' => [$this, 'methodName']]
     *
     * Метод должен быть реализован в дочерних классах.
     *
     * @return array
     */
    abstract protected function getCallbacks(): array;
}
