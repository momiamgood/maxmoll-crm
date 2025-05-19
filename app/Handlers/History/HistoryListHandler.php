<?php

namespace App\Handlers\History;

use App\Http\Filters\HistoryFilter;
use App\Http\Requests\HistoryListRequest;
use App\Http\Resources\HistoryResource;
use App\Models\History;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class HistoryListHandler
{
    /**
     * Обрабатывает запрос на получение истории изменений по остаткам с фильтрацией и пагинацией.
     *
     * @param HistoryListRequest $request
     * @return AnonymousResourceCollection
     * @throws BindingResolutionException
     */
    public function handle(HistoryListRequest $request): AnonymousResourceCollection
    {
        $data = $request->all();
        $filter = app()->make(HistoryFilter::class, [
            'queryParams' => array_filter($data)
        ]);

        $orders = History::filter($filter)
            ->paginate($data['perPage'] ?? 10);

        return HistoryResource::collection($orders);
    }
}
