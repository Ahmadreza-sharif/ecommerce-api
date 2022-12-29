<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'likes';
    protected $fillable = ['likeable_id','likeable_type','user_id'];

    public function likeable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        $this->belongsTo(User::class,'id','user_id');
    }
}
