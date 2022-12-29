<?php

namespace App\Http\Requests\Api\V1\Role;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class storeRoleRequest extends FormRequest
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
            'key' => 'required',
            'name' => 'required',
            'system_role' => 'required'
        ];
    }

    public function attributes()
    {
        $en = [
            'system_role' => 'system role'
        ];

        $fa = [
            'key' => 'کلید',
            'name' => 'اسم',
            'system_role' => 'نقش'
        ];

        return $this->getLang($fa,$en);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }
}
