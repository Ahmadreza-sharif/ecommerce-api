<?php

namespace App\Http\Controllers\api\v1\Payment;

use App\Http\Controllers\api\traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Payment\PaymentRequest;
use App\Http\Requests\Api\V1\Voucher\ValidateVoucherRequest;
use App\Models\order;
use App\Services\Discount\DiscountService;
use App\Services\Payment\Contract\PaymentInterface;
use App\Services\Payment\Lib\ZarinPayment;
use App\Services\Payment\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    protected $paymentInterface;

    public function __construct(
        PaymentInterface $paymentInterface
    )
    {
        $this->paymentInterface = $paymentInterface;
    }

    public function unsubmitVoucherCode($order)
    {
        $orderItems = $order->orderItem;

        $voucher = $order->voucher;
        $voucher->update([
            'uses' => $voucher->uses - 1
        ]);

        $order->update([
            'discount_amount' => null,
            'voucher_id' => null
        ]);

        foreach ($orderItems as $item){
            $item->update([
                "Discount" => null
            ]);
        }
    }

    private function getPrice($item,$voucher)
    {
        $price = $item->product->price;
        if ($voucher->voucher_type == "PERCENT"){
            $realPrice = (($price / 100) * $voucher->voucher_amount);
            $product = $item->update([
                'Discount' => $realPrice
            ]);
        }else{
            $realPrice = $voucher->voucher_amount;
            $product = $item->update([
                'Discount' => $realPrice,
            ]);
        }
    }

    private function SubmitVoucherCode($voucher, $order_id)
    {
        $order = order::find($order_id);

        if ($voucher) {
            $voucherProducts = [];
            foreach ($voucher->products()->get() as $item) {
                array_push($voucherProducts, $item->id);
            };

            $voucherBrands = [];
            foreach ($voucher->brands()->get() as $item) {
                array_push($voucherBrands, $item->id);
            };

            $voucherCategory = [];
            foreach ($voucher->categories()->get() as $item) {
                array_push($voucherCategory, $item->id);
            };

            $products = $order->orderItem;
            foreach ($products as $item) {
                if (in_array($item->product->category->id, $voucherCategory)) {
                    $this->getPrice($item, $voucher);
                } elseif (in_array($item->product->brand->id, $voucherBrands)) {
                    $this->getPrice($item, $voucher);
                } elseif (in_array($item->product->id, $voucherProducts)) {
                    $this->getPrice($item, $voucher);
                }
            }

            $discountAmount = 0;
            foreach ($products as $item) {
                $discount = $item->Discount;
                $discountAmount += $discount;
            };
            $order->update([
                "discount_amount" => $discountAmount,
                "voucher_id" => $voucher->id
            ]);

            $voucherUses = $voucher->uses + 1;
            $voucher->update([
                'uses' => $voucherUses
            ]);
            $voucher->save();

            $order->save();

            return $this->sendSuccess('', __('general.voucher.enable-voucher'));
        } else {
            return $this->sendError('', __('general.voucher.invalid-voucher'));
        }
    }




    public function payment(PaymentRequest $request, ValidateVoucherRequest $voucher)
    {
        if ($voucher->has('voucher_code')) {
            $voucherValidated = $voucher->validateVoucher();
            $this->submitVoucherCode($voucherValidated, $request->order_id);
        }
        return $this->paymentInterface->payment($request);
    }


    public function verify(Request $request)
    {
        $verify = $this->paymentInterface->verify($request);

        $order = \App\Models\Payment::where('authority','=',$request->Authority)->first()->order;

        if ($verify->getData()->success == false && !is_null($order->discount_amount)){
            $this->unsubmitVoucherCode($order);
        }

        return $verify;
    }


}
