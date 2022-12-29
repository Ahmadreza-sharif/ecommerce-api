<?php

namespace App\Http\Resources\Voucher;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class VoucherCollection extends ResourceCollection
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
                "code" => $item->code,
                "start_at" => $item->start_at,
                "expire_at" => $item->expire_at,
                "uses" => $item->uses,
                "max_uses" => $item->max_uses,
                "voucher_amount" => $item->voucher_amount,
                "voucher_type" => $item->voucher_type,
                "type" => $item->type
            ];
        });
    }
}
