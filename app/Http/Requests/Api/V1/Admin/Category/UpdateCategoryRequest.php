<?php

namespace App\Http\Requests\Api\V1\Admin\Category;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCategoryRequest extends FormRequest
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
            'category_id' => "required|exists:categories,id",
            'title' => "required",
            'slug' => "required",
            'desc' => "required",
            'level' => "required",
            'pic' => 'image|mimes:jpg,svj,png,jpeg|max:1024',
            ];
    }

    public function attributes()
    {
        $fa = [
            'category_id' => "ایدی",
            'title' => "نام",
            'slug' => "اسلاگ",
            'desc' => "توضیحات",
            'level' => "وضغیت",
        ];

        return $this->getLang($fa,["desc" => 'description' , "level" => "status"]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('',$validator->getMessageBag()->first()));
    }
}
