<?php

namespace App\Http\Resources\Brand;

use App\Http\Resources\Category\CategoryResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class BrandCollection extends ResourceCollection
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
                'title' => $item->name,
                'slug' => $item->slug,
                'avatar' => $item->logo,
                'status' => $item->getStatus(),
                'category' => new CategoryResource($item->category),

            ];
        });
    }
}
