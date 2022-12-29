<?php

namespace App\Http\Requests\Api\V1\Admin\Category;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRequest extends FormRequest
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
            'category_id' => "integer|required|exists:categories,id",
        ];
    }

    public function attributes()
    {

        $fa = [
            'category_id' => "شناسه دسته بندی",
        ];

        return $this->getLang($fa,['category_id' => 'category id']);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }
}
