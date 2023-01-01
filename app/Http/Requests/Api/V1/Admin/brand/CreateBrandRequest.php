<?php

namespace App\Http\Requests\Api\V1\Admin\brand;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateBrandRequest extends FormRequest
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
            'logo' => 'required|image|mimes:jpg,svj,png,jpeg|max:1024',
            'st' => "required",
            'category_id' => "required|exists:categories,id",
        ];
    }

    public function attributes()
    {
        $fa = [
            'category_id' => "ایدی",
            'title' => "عنوان",
            'slug' => "اسلاگ",
            'logo' => "تصویر",
            'st' => "وضعیت",
        ];

        $en = [
            'category_id' => "category id",
            'pic' => "avatar",
            'st' => "status",
        ];

        return $this->getLang($fa,$en);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('',$validator->getMessageBag()->first()));
    }
}
