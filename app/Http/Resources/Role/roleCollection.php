<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class roleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function getCollection(): Collection
    {
        return $this->collection->map(function ($item) {
            return [
                'System Sole' => $item->system_role,
                'Role Key' => $item->key,
                "Role Name" => $item->name
            ];
        });
    }
}
