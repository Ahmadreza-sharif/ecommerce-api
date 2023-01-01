<?php

namespace App\Http\Requests\Api\V1\Slider;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use function Symfony\Component\Translation\t;

class StoreSliderRequest extends FormRequest
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
            'picture' => 'required|image|mimes:jpg,svj,png,jpeg',
            'text' => 'required',
            'url' => 'required',
            'status' => "boolean"
        ];
    }

    public function attributes()
    {
        $fa = [
            'picture' => 'عکس',
            'text' => 'متن',
            'url' => 'لینک',
            'status' => 'وضعیت',
        ];
        return $this->getLang($fa,[]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }
}
