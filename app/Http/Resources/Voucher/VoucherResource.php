<?php

namespace App\Http\Resources\Voucher;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "code" => $this->code,
            "start_at" => $this->start_at,
            "expire_at" => $this->expire_at,
            "uses" => $this->uses,
            "voucher_amount" => $this->voucher_amount,
            "voucher_type" => $this->voucher_type,
            "max_uses" => $this->max_uses,
            "type" => $this->type
        ];
    }
}
