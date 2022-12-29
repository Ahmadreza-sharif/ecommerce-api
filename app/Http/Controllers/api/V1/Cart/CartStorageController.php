<?php

namespace App\Http\Controllers\api\v1\Cart;

use App\Http\Controllers\api\traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Cart\CartRequest;
use App\Http\Requests\Api\V1\Cart\CreateCartRequest;
use App\Http\Requests\Api\V1\Cart\UpdateCartRequest;
use App\Http\Resources\Cart\CartStorageCollection;
use App\Http\Resources\Cart\CartStorageResource;
use App\Models\CartStorage;
use App\Services\Cart\Contract\CartInterface;

class CartStorageController extends Controller
{

    private $cartInterface;

    public function __construct(
        CartInterface $cartInterface
    )
    {
        $this->cartInterface = $cartInterface;
    }


    public function store(CreateCartRequest $request)
    {
        $cart = $this->cartInterface->addItem($request->input('product'), $request->input('count'),$request->input('price'));

        # CART STORAGE RESOURCE
        $cartResource = new CartStorageResource($cart);

        # SEND RESPONSE
        return $this->sendSuccess($cartResource, __('general.cart.add'));
    }


    public function update(UpdateCartRequest $request)
    {
        # UPDATE
        $cart = $this->cartInterface->updateCount($request->input('product'), $request->input('count'));

        # CART STORAGE RESOURCE
        $cartResource = new CartStorageResource($cart);

        # SEND RESPONSE
        return $this->sendSuccess($cartResource, __('general.cart.update'));
    }


    public function destroy(CartRequest $request)
    {
        # DELETE
        $this->cartInterface->deleteItem($request->id);

        # SEND RESPONSE
        return $this->sendSuccess('', __('general.cart.delete'));
    }

    public function showAll(CartStorage $cartStorage)
    {
        # SELECT ALL
        $cartItems = $this->cartInterface->all();

        # CARD COLLECTION
        $cartCollection = new CartStorageCollection($cartItems);

        # SEND RESPONSE
        return $this->sendSuccess($cartCollection, __('general.cart.select-all'));
    }

    public function destroyAll(CartStorage $cartStorage)
    {
        # GET USER ID
        $user = auth('sanctum')->user()->id;

        # DELETE ALL
        CartStorage::where('user_id', $user)->delete();

        # SEND RESPONSE
        return $this->sendSuccess('', __('general.cart.delete-all'));

    }
}
