<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';
    protected $fillable = [
        'code','uses','max_uses','voucher_amount','voucher_type','type','start_at','expire_at'
    ];

    use HasFactory;

    public function products()
    {
        return $this->morphedByMany(product::class,'voucherables');
    }

    public function categories()
    {
        return $this->morphedByMany(category::class,'voucherables');
    }

    public function brands()
    {
        return $this->morphedByMany(brand::class,'voucherables');
    }
}
