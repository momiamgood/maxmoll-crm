<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product' => ProductResource::collection($this->whenLoaded('product')),
            'warehouse' => new WarehouseResource($this->whenLoaded('warehouse')),
            'before' => $this->before,
            'after' => $this->after,
            'count' => $this->quantity_change,
            'reason' => $this->reason,
            'operationType' => $this->operation_type,
            'createdAt' => $this->created_at
        ];
    }
}
