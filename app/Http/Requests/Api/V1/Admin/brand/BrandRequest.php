<?php

namespace App\Http\Requests\Api\V1\Admin\brand;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BrandRequest extends FormRequest
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
     * Get the validreturnation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'brand_id' => "integer|required|exists:brands,id",
        ];
    }

    public function attributes()
    {
        $fa = [
            'brand_id' => "ایدی برند"
        ];

        return $this->getLang($fa,[]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }
}
