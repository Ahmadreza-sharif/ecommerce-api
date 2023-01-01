<?php

namespace App\Http\Requests\Api\V1\Slider;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
            "slider_id" => 'required|exists:sliders,id'
        ];
    }

    public function attributes()
    {
        return $this->getLang(['slider_id' => 'شناسه اسلاید'],[]);
    }
}
