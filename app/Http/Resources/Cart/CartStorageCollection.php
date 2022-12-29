<?php

namespace App\Http\Resources\Cart;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class CartStorageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return $this->getCollection();
    }

    public function getCollection(): Collection
    {
        return $this->collection->map(function ($item) {
            return [
                "product" => new ProductResource($item->product),
                "count" => $item->product_count
            ];
        });
    }
}
