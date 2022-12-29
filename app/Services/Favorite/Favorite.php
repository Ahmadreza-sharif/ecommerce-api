<?php

namespace App\Services\Favorite;

use App\Http\Resources\Product\ProductCollection;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Favorite
{
    public function getUserFavorite()
    {
        return Auth::user()->favorites();
    }

    public function add($productId)
    {
        $favorite = $this->getUserFavorite()->whereHas("favorites",function (Builder $builder)
        use ($productId){
            $builder->where('product_id',$productId);
        });

        return $this->attachOrDetach($favorite,$productId);
    }

    public function attachOrDetach($favorite,$productId)
    {
        if ($favorite->count() > 0){
            $favorite->detach($productId);
            return false;
        }

        $favorite->attach($productId);
        return true;
    }

    public function list()
    {
        return new ProductCollection($this->getUserFavorite()->whereHas('favorites')->get());
    }

    public function destoryAll()
    {
        $this->getUserFavorite()->whereHas('favorites')->detach();
    }
}
