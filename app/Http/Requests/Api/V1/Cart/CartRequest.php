<?php

namespace App\Http\Requests\Api\V1\Cart;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CartRequest extends FormRequest
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
            "id" => "required|exists:cart_storages,id"
        ];
    }

    public function attributes()
    {
        return $this->getLang(['id' => 'ایدی'],[]) ;
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('',$validator->getMessageBag()->first()));
    }
}
