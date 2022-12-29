<?php

namespace App\Services\Payment;

use App\Http\Controllers\api\v1\Payment\PaymentController;
use App\Services\Discount\DiscountService;
use App\Services\Payment\Contract\PaymentInterface;
use App\Services\Payment\Lib\ZarinPayment;

class Payment implements PaymentInterface
{
    public const ZARINPAL = 1;

    public function payment($request)
    {
        # CALL METHOD
        return $this->getMethod($request)->payment($request);
    }

    public function verify($request)
    {
        $payment = \App\Models\Payment::where('authority',$request->Authority)->first();
        $request->merge(["payment_method" => $payment->payment_method]);
        return $this->getMethod($request)->verify($request);
    }

    public function getMethod($request)
    {
        # GET METHOD ID FROM USER
        # CHECK IN ARRAY AND RETURN RELATED OBJ
        # SAVE IN PROPERTY
        # CALL METHODS IN EACH METHOD
        $methodId= $request->input('payment_method');
        return [
            Payment::ZARINPAL => new ZarinPayment(),
        ][$methodId];
    }
}
