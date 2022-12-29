<?php

namespace App\Http\Requests\Api\V1\Role;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class updateRoleRequest extends FormRequest
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
            "role_id" => 'required|exists:roles,id',
            "system_role" => "required",
            "key" => 'required',
            "name" => 'required'
        ];
    }

    public function attributes()
    {
        $fa = ['role_id' => 'شناسه نقش','system_role' => 'نقش', 'key' => 'کلید','name' => 'اسم نقش'];
        $en = ['role_id' => 'role id','system_role' => 'system role'];
        return $this->getLang($fa,$en);
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }
}
