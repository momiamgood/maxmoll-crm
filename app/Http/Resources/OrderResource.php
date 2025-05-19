<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer,
            'status' => $this->status,
            'createdAt' => $this->created_at,
            'completedAt' => $this->completed_at,
            'warehouse' => new WarehouseResource($this->whenLoaded('warehouse')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
