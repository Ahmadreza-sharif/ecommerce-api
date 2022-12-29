<?php

namespace App\Services\Payment\Lib;

use App\Http\Controllers\api\traits\ApiResponder;
use App\Http\Controllers\api\v1\Payment\PaymentController;
use App\Http\Requests\Api\V1\Voucher\ValidateVoucherRequest;
use App\Models\Discount;
use App\Models\order;
use App\Models\Payment;
use App\Services\Discount\DiscountService;
use App\Services\Payment\Contract\PaymentInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ZarinPayment implements PaymentInterface
{
    use ApiResponder;

    public function payment($request)
    {
        $order = order::find($request->input('order_id'));
        $url = 'https://api.zarinpal.com/pg/v4/payment/request.json';
//        $url = 'https://sandbox.zarinpal.com/pg/StartPay/';
//        $url = 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl';

        if ($order->discount_amount) {
            $price = $order->price - $order->discount_amount;
        }else{
            $price = $order->price;
        }

        $data = [
            "merchant_id" => "17b885b3-530a-4164-8311-887627e4db4f",
            "amount" => $price,
            "callback_url" => route('verify'),
            "description" => "Payment For Order number $order->id",
            "metadata" => ["email" => "info@email.com", "mobile" => "09121234567"]
        ];

        # SEND REQUEST TO ZARINPAL
        $client = $this->SendReq($url, $data);

        # GET AUTHORITY AND CODE
        $data = json_decode($client)->data;

        # SAVE AUTHORITY
        if ($data->code == 100) {
            $order->payment()->create([
                "order_id" => $order->id,
                "authority" => $data->authority,
                "payment_method" => $request->input('payment_method')
            ]);
            dd("Location: https://www.zarinpal.com/pg/StartPay/" . $data->authority);
            return redirect("https://www.zarinpal.com/pg/StartPay/" . $data->authority);
        }
        return $this->sendError('', __('general.payment.fail'));
    }

    public function verify($request)
    {
        # GET AUTHORITY FROM ZARIN RESPONSE

        $url = 'https://api.zarinpal.com/pg/v4/payment/verify.json';
        $payment = Payment::where('Authority', $request->input('Authority'))->first();

        if ($payment->authority == $request->input('Authority')) {
            $data = [
                "merchant_id" => "17b885b3-530a-4164-8311-887627e4db4f",
                "amount" => $payment->order->price,
                "authority" => $request->input('Authority')
            ];

            $client = $this->SendReq($url, $data);
        }

        $data = json_decode($client);

        # GET PAYMENT STATUS FORM ZARIN
        if (!empty($data->data) && isset($data->data->code)) {
            $payment->order()->where("status", 0)->update([
                "status" => 1
            ])->save();
            return $this->sendSuccess($payment->order, __('general.payment.success'));
        } else {
            return $this->sendError($data->errors, __('general.payment.fail'));
        }
    }

    public function SendReq($url, $data)
    {
        return Http::post($url, $data)->body();
    }
}
