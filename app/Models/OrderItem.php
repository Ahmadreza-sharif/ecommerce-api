<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderItem extends Model
{
    protected $fillable = ['order_id','product_id','count','price','Discount'];
    use HasFactory;

    public function product()
    {
        return $this->hasOne(product::class,'id','product_id');
    }
}
