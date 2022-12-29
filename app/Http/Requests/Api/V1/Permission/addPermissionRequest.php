<?php

namespace App\Http\Requests\Api\V1\Permission;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class addPermissionRequest extends FormRequest
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
            "permissions" => "required|array",
            "permissions.*" => "required|integer|distinct|exists:permissions,id",
            "role_id" => "required|exists:roles,id"
        ];
    }

    public function attributes()
    {
        $fa = [
            "permissions" => "دسترسی ها",
            "permissions.*" => "شناسه دسترسی",
            "role_id" => "ایدی نقش"
        ];

        $en = [
            "permissions" => "permissions",
            "permissions.*" => "permission_id",
            "role" => "role id",
        ];

        return $this->getLang($fa,$en);
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }

}
