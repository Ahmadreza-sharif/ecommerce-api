<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payment";
    use HasFactory;
    protected $fillable = ['authority','order_id'];

    public function order()
    {
        return $this->belongsTo(order::class);
    }
}
