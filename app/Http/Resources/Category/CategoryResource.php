<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'slug'  => $this->slug,
            'desc'  => $this->description,
            'status'  => $this->getStatus(),
            'picture' => $this->pic
        ];
    }
}
