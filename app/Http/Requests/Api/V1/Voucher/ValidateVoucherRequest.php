<?php

namespace App\Http\Requests\Api\V1\Voucher;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;

class ValidateVoucherRequest extends FormRequest
{
    use ApiResponder;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function validateVoucher()
    {
        $now = Carbon::now()->toDateTimeString();
        $voucher = \App\Models\Voucher::where('code', "=", $this->voucher_code)->whereColumn('uses', '<=', 'max_uses')
            ->where('expire_at', ">=", date($now))->where('start_at', "<=", date($now))->first();

        if ($voucher) {
            return $voucher;
        } else {
            throw new HttpResponseException($this->sendError('', __('general.voucher.invalid-voucher')));
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "voucher_code" => "nullable|exists:vouchers,code",
            "order_id" => "required|exists:orders,id"
        ];
    }

    public function attributes()
    {
        $fa = [
            "voucher_code" => "کد تخفیف",
            "order_id" => "ایدی سفارش"
        ];

        return $this->getLang($fa, []);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }
}
