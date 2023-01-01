<?php

namespace App\Http\Requests\Api\V1\Admin\Category;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\App;

class CreateCategoryRequest extends FormRequest
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
            'title' => "required",
            'slug' => "required",
            'desc' => "required",
            'level' => "required",
            'picture' => 'required|image|mimes:jpg,svj,png,jpeg|max:1024',
        ];
    }

    public function attributes()
    {
        $fa = [
            'title' => "عنوان",
            'slug' => "نامک",
            'desc' => "توضیحات",
            'level' => "وضعیت",
            'picture' => "عکس",
        ];

        $en = [
            'slug' => "slug",
            'desc' => "description",
            'level' => "status",
        ];

        return $this->getLang($fa,$en);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }
}
