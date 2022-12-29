<?php

namespace App\Http\Resources\Brand;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Product\ProductCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'title' => $this->name,
            'slug' => $this->slug,
            'avatar' => $this->logo,
            'status' => $this->getStatus(),
            'category' => new CategoryResource($this->category),
        ];
    }
}
