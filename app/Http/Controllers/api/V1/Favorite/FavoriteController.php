<?php

namespace App\Http\Controllers\api\V1\Favorite;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Favorite\FavoriteRequest;
use App\Http\Requests\Api\V1\Favorite\StoreFavoriteRequest;
use App\Services\Favorite\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Builder;

class FavoriteController extends Controller
{
    use Favorite;

    public function store(StoreFavoriteRequest $request)
    {
        $status = $this->add($request->input('product_id'));
        return $status
            ? $this->sendSuccess('','Product Added to Favorite Successfully.')
            : $this->sendSuccess('','Product Deleted Form Favorites Successfully.');
    }

    public function showAll()
    {
        return $this->sendSuccess($this->list(),'Your Favorite Products:');
    }

    public function destroyAll()
    {
        $this->destoryAll();
        return $this->sendSuccess('','Favorite list is empty now');
    }
}
