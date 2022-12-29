<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
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
                'name' => $item->name,
                'slug' => $item->slug,
                'description' => $item->description,
                'price' => $item->price,
                'word' => $item->key_words,
                'view' => $item->view_count,
                'Product_number' => $item->code,
                'sell_count' => $item->sell_count,
                'status' => $item->getStatus(),
            ];
        });
    }
}
