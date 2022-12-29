<?php

namespace App\Http\Requests\Api\V1\Voucher;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateVoucherRequest extends FormRequest
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
            "id" => "required|exists:vouchers,id",
            "code" => "required",
            "start_at" =>"required|date",
            "expire_at" =>"required",
            "uses" => "required|integer",
            "max_uses" => "required|integer",
            "voucher_amount" => "required|integer",
            "voucher_type" => "required|in:PERCENT,PRICE",
            "type" => "required|in:VOUCHER,GIFT"
        ];
    }

    public function attributes()
    {
        $fa =  [
            "id" => "ایدی",
            "code" => "کد",
            "start_at" =>"شروع کد",
            "expire_at" =>"منقضی شدن",
            "uses" => "تعداد استفاده",
            "max_uses" => "حداقل استفاده",
            "voucher_amount" => "مقدار تخفیف",
            "voucher_type" => "نوع کد تخفیف",
            "type" => "نوع کد"
        ];
        return $this->getLang($fa,[]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('',$validator->getMessageBag()->first()));
    }
}
