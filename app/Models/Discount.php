<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = "discount";
    use HasFactory;
    protected $fillable = ['code','start_at','expire_at','uses','max_uses','discount_amount','type','discount_type',
        "user_id"];


}
