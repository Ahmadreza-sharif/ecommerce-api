<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    const PENDING = 0 ;
    const SENDING = 1 ;
    const DELIVERED = 2 ;


    protected $fillable = ['user_id','status','price','discount_amount','voucher_id'];
    use HasFactory;

    public function getStatus()
    {
        return [
            self::PENDING => 'waiting',
            self::SENDING => 'Sending',
            self::DELIVERED => 'delivered',
        ][$this->status];
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function voucher()
    {
        return $this->hasOne(Voucher::class,'id','voucher_id');
    }
}
