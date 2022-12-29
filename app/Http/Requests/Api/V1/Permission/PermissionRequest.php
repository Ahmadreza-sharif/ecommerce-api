<?php

namespace App\Http\Requests\Api\V1\Permission;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
            'role_id' => 'required|exists:roles,id'
        ];
    }

    public function attributes()
    {
        return $this->getLang(['role_id' => 'ایدی نفش'],['role_id' => 'role id']);
    }
}
