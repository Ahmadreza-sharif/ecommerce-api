<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class OrderStatusCollection extends ResourceCollection
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
                "order_id" => $item->id,
                "user" => $item->user->name,
                "price" => $item->price,
                "order_status" => $item->getStatus(),
            ];
        });
    }
}
