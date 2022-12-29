<?php

namespace App\Http\Requests\Api\V1\Order;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateOrderRequest extends FormRequest
{
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
            "order_id" => "required|exists:orders,id",
            "status" => "required|in:0,1,2"
        ];
    }

    public function attributes()
    {
        $fa = [
            "order_id" => "شناسه سفارش",
            "status" => "وضعیت"
        ];
        return $this->getLang($fa,['order_id' => 'order id']);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('',$validator->getMessageBag()->first()));
    }
}
