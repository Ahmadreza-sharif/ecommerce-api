<?php

namespace App\Http\Resources\Slider;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class SliderCollection extends ResourceCollection
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
                'picture' => $item->file_name,
                'url' => $item->url,
                'text' => $item->alt,
                'created at' => $item->created_at
            ];
        });
    }
}
