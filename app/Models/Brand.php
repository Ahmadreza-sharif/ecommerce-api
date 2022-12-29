<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    const ACTIVE = 1;
    const IN_ACTIVE = 0;
    use HasFactory;
    protected $fillable = ['name','slug','logo','status','category_id'];

    public function getStatus(){
        return [
            self::IN_ACTIVE => 'غیر فعال',
            self::ACTIVE => 'فعال',
            ][$this->status];
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vouchers()
    {
        return $this->morphToMany(Voucher::class,'voucherables');
    }
}
