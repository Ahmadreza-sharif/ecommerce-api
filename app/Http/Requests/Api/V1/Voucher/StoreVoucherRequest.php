<?php

namespace App\Http\Requests\Api\V1\Voucher;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreVoucherRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "code" => "required",
            "uses" => "required|integer",
            "voucher_amount" => "required",
            "voucher_type" => "required|in:PERCENT,PRICE",
            "type" => "required|in:VOUCHER,GIFT"
        ];
    }

    public function attributes()
    {
        $fa =  [
            "code" => "کد",
            "uses" => "تعداد استفاده",
            "voucher_amount" => "مقدار تخفیف",
            "voucher_type" => "نوع کد تخفیف",
            "type" => "نوع"
        ];

        return $this->getLang($fa,[]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('',$validator->getMessageBag()->first()));
    }
}
