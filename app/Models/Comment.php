<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $fillable = ['body','status','user_id','parent_id'];

    const PENDING = 0 ;
    const ACCEPTED = 1 ;
    const REJECTED = 2 ;

    public function getStatus()
    {
        return [
            self::PENDING => 'انتظار',
            self::ACCEPTED => 'تایید شده',
            self::REJECTED => 'رد شده'
        ][$this->status];
    }


    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class,'likeable');
    }
}
