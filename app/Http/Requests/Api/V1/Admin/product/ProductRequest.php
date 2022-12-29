<?php

namespace App\Http\Requests\Api\V1\Admin\product;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
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
            "product_id" => "required|exists:products,id"
        ];
    }

    public function attributes()
    {
        return $this->getLang(['product_id' => "ایدی محصول"],['product_id' => 'product id']);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }
}
