<?php

namespace App\Http\Requests\Api\V1\Admin\product;

use App\Http\Controllers\api\traits\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
            'price' => "required",
            'name' => "required",
            "product_id" => 'required|exists:products,id',
            'slug' => "required",
            'description' => "required",
            'brand_id' => "required|exists:brands,id",
            'category_id' => "required|exists:categories,id",
            'status' => "required",
            'status_store' => "required",
            'key_words' => "required",
            'view_count' => "required",
            'code' => "required",
            'sell_count' => "required",
            'picture' => 'required|image|mimes:jpg,svj,png,jpeg|max:1024',
            'more_pictures' => 'required|array',
            'more_pictures.*' => 'required|image|mimes:jpg,svj,png,jpeg|max:1024'
        ];
    }

    public function attributes()
    {
        $fa = [
            'category_id' => "ایدی دسته بندی",
            'name' => "اسم",
            'slug' => "اسلاگ",
            'description' => "توضیحات",
            'brand_id' => "ایدی برند",
            'status' => "وضعیت",
            'price' => "قیمت",
            'status_store' => "وضغیت انبار",
            'key_words' => "کلمات کلیدی",
            'view_count' => "تعداد بازدید",
            'code' => "کد",
            'sell_count' => "تعداد فروش",
            "product_id" => 'ایدی محصول',
            'more_pictures' => 'گالری',
            'picture' => 'عکس'

        ];

        return $this->getLang($fa,['product_id' => 'product id' , 'category_id' => 'category id', 'more_pictures' => 'gallery']);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }
}
