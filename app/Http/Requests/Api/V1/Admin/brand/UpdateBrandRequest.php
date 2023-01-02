<?php

namespace App\Http\Requests\Api\V1\Admin\brand;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBrandRequest extends FormRequest
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
            'brand_id' => 'required|exists:brands,id',
            'title' => "required",
            'slug' => "required",
            'st' => "required",
            'category_id' => "required|exists:categories,id",
            "logo" => 'required|image|mimes:jpg,svj,png,jpeg|max:1024'
        ];
    }

    public function attributes()
    {
        $fa = [
            'brand_id' => 'شناسه برند',
            'title' => "تایتل",
            'slug' => "نامک",
            'logo' => "تصویر",
            'st' => "وضعیت",
            'category_id' => "ایدی دسته بندی"
        ];

        return $this->getLang($fa, ["pic" => "avatar", 'st' => 'status']);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }
}
