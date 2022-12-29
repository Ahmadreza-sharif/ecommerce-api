<?php

namespace App\Http\Requests\Api\V1\Admin\product;

use Illuminate\Foundation\Http\FormRequest;

class StatusProductRequest extends FormRequest
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
            'id' => 'required|exists:products,id'
        ];
    }

    public function attributes()
    {
        return $this->getLang(['id' => "ایدی"],[]);
    }
}
