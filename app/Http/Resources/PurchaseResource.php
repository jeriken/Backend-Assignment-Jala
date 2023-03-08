<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->product_id,
            'invoice' => $this->invoice,
            'quantity' => $this->quantity,
            'harga' => $this->harga,
            'products' => new ProductResource($this->products),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
