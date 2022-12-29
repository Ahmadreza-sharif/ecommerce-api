<?php

namespace App\Http\Resources\Cart;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartStorageResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "product_count" => $this->product_count,
            "product" => new ProductResource($this->product),
            "price" => $this->price
        ];
    }
}
