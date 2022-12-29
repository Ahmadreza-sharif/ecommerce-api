<?php

namespace App\Services\Payment\Contract;

use App\Http\Controllers\api\v1\Payment\PaymentController;
use App\Http\Requests\Api\V1\Payment\PaymentRequest;

interface PaymentInterface
{
    public function payment($request);

    public function verify($request);
}
