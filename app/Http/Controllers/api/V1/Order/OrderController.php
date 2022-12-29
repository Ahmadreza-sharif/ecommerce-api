<?php

namespace App\Http\Controllers\api\v1\Order;

use App\Http\Controllers\api\traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Order\StoreOrderRequest;
use App\Http\Requests\Api\V1\Order\UpdateOrderRequest;
use App\Http\Requests\Api\V1\Payment\PaymentRequest;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Order\OrderStatusCollection;
use App\Models\Order;

class OrderController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = auth('sanctum')->user();
    }

    public function store(\App\Services\Cart\CartStorage $cartStorage, StoreOrderRequest $request)
    {

        # GET CART
        $cart = $cartStorage->all();

        # IF CART IS EMPTY SEND ERROR
        if (!$cart->first()) {
            return $this->sendError('', __('general.cart.empty'));
        }

        $price = 0;

        foreach ($cart as $item) {
            $amount = $item->product_count * $item->price;
            $price += $amount;
        }

        # CREATE ORDER AND RETURN OBJ
        $order = $this->user->order()->create([
            "status" => Order::PENDING,
            "price" => $price
        ]);

        # CREATE ORDER ITEMS
        foreach ($cart as $cartItem) {
            $order->orderItem()->create([
                'product_id' => $cartItem->product_id,
                'count' => $cartItem->product_count,
            ]);
        }

        # TRUNCATE CART
        $cartStorage->clear();

        # ORDER RESOURCES
        $orderResource = new OrderResource($order);

        # SEND RESPONSE WITH OJB OF ORDER
        return $this->sendSuccess($orderResource, __('general.order.add'));
    }
    public function show()
    {
        # CHECK HAVE ORDER FOR THIS USER OR NOT
        $orders = \App\Models\Order::all();

        # ORDER RESOURCE
        $orderResource = new OrderStatusCollection($orders);

        # SEND RESPONSE
        return $this->sendSuccess($orderResource, __('general.order.select-all'));
    }

    public function destroy(PaymentRequest $request)
    {
        # FIND ORDERS
        $orders = \App\Models\Order::where('user_id', $this->user)->where('id', $request->input('order_id'))
            ->whereIn('status', [0]);

        # IF ORDER EXISTS :
        if ($orders->exists()) {

            # DELETE
            $orders->delete();

            # RESPONSE
            return $this->sendSuccess('', __('general.order.delete'));
        }

        # SEND RESPONSE
        return $this->sendError('', __('general.order.delete-failed'));
    }

    public function update(UpdateOrderRequest $request)
    {

        # UPDATE
        $order = Order::find($request->input('order_id'));
        $order->update([
            'status' => $request->input('status')
        ]);
        $order->save();

        # ORDER RESOURCE
        $orderResource = new OrderResource($order);
        # SEND RESPONSE
        return $this->sendSuccess($orderResource, __('general.order.update'));
    }
}
