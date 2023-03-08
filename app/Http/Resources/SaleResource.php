<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'invoice' => $this->invoice,
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'products' => new ProductResource($this->products),
            'users' => new UserResource($this->users),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
