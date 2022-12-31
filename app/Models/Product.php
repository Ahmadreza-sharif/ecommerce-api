<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const ACTIVE = 1;
    const IN_ACTIVE = 0;
    protected $fillable = ['slug','name','description','status','key_words','price','status_store','view_count','code',
        'sell_count','category_id','brand_id','picture','more_pictures'];

    public function getStatus(){
        return [
            self::IN_ACTIVE => 'غیر فعال',
            self::ACTIVE => 'فعال',
        ][$this->status];
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vouchers()
    {
        return $this->morphToMany(Voucher::class,'voucherables');
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class,'favorites');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable')->where('status','=',1);
    }
}
