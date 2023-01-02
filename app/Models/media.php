<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class media extends Model
{
    use HasFactory;
    protected $fillable = ['name','mediable_id','mediable_type'];
    public $timestamps = false;

    public function media()
    {
        return $this->morphTo();
    }
}
