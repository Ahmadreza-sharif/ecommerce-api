<?php

namespace App\Http\Requests\Api\V1\Slider;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSliderRequest extends FormRequest
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
            'slider_id' => 'required|exists:sliders,id',
            'picture' => 'image|mimes:jpg,svj,png,jpeg',
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
            'slider_id' => "شناسه اسلاید"
        ];
        return $this->getLang($fa, ['slider_id' => 'slider id']);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError('', $validator->getMessageBag()->first()));
    }
}
