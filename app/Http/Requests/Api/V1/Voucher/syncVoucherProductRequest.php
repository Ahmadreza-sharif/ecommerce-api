<?php

namespace App\Http\Requests\Api\V1\Voucher;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class syncVoucherProductRequest extends FormRequest
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
            "voucher_id" => "required|exists:vouchers,id",
            "brand_id" => "exists:brands,id",
            "category_id" => "exists:categories,id",
            "product_id" => "exists:products,id",
        ];
    }

    public function attributes()
    {
        $fa =  [
            "voucher_id" => "ایدی کد تخفیف",
            "brand_id" => "ایدی برند",
            "category_id" => "ایدی دسته بندی",
            "product_id" => "ایدی محصول",
        ];
        return $this->getLang($fa,[]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('',$validator->getMessageBag()->first()));
    }
}
