<?php

namespace App\Services\Cart;

use App\Http\Resources\Cart\CartStorageResource;
use App\Models\Product as product;
use App\Services\Cart\Contract\CartInterface;
use Illuminate\Support\Facades\Facade;

class CartStorage implements CartInterface
{
    public function addItem($product_id, $product_count,$product_price)
    {

        # GET USER PRODUCTS
        $userProduct = $this->get($product_id);

        # GET PRODUCT AND CHECK EXISTS OR NOT
        if (!is_null($userProduct)) {
            # UPDATE
            return $this->updateCount($product_id, $product_count,);
        }

        # FIND PRODUCT FOR PRICE
        $price = product::find($product_id);


        # CREATE NEW RECORD IN DB
        $cartItem = \App\Models\CartStorage::create([
            'product_id' => $product_id,
            'product_count' => $product_count,
            'user_id' => $this->userId(),
            'price' => $price->price
        ]);

        # RESOURCE CART
        return $cartItem;

    }

    public function updateCount($product_id, $product_count)
    {
        # FIND USER
        $user = $this->userId();

        # FIND ITEM
        $cartItem = $this->get($product_id);

        # UPDATE ITEM
        $cartItem->update([
            'product_count' => $product_count
        ]);

        # RETURN ITEM
        return $cartItem;
    }

    public function deleteItem($product_id)
    {
        # GET USER AND ITEM
        $item = $this->get($product_id);

        # DELETE
        $item->delete();

        return 1;
    }

    public function clear()
    {
        # GET USER ID
        $user_id = $this->userId();

        # SELECT ALL RETURN DATA
        return  \App\Models\CartStorage::where('user_id', $user_id)->delete();
    }

    public function all()
    {
        # GET USER ID
        $user_id = $this->userId();

        # SELECT ALL
        $items = \App\Models\CartStorage::where('user_id', $user_id)->get();


        # RETURN DATA
        return $items;

    }

    public function get($product_id)
    {
        $user = $this->userId();
        return \App\Models\CartStorage::where(['user_id' => $user, 'product_id' => $product_id])->first();
    }

    public function userId()
    {
        return auth('sanctum')->user()->id;
    }

}
