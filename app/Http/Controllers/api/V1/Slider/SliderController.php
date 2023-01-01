<?php

namespace App\Http\Controllers\api\V1\Slider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Slider\SliderRequest;
use App\Http\Requests\Api\V1\Slider\StoreSliderRequest;
use App\Http\Requests\Api\V1\Slider\UpdateSliderRequest;
use App\Http\Resources\Slider\SliderCollection;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function store(StoreSliderRequest $request)
    {
        $fileName = Storage::put($this->time . "/sliders" , $request->file('picture'));

        $slider = Slider::create([
            'file_name' => $fileName,
            'url' => $request->input('url'),
            'alt' => $request->input('text'),
            'status' => $request->input('status')
        ]);

        return $this->sendSuccess($slider,__('general.slider.store'));
    }

    public function destroy(SliderRequest $request)
    {
        $slider = Slider::find($request->input('slider_id'));
        Storage::delete($slider->file_name);

        $slider->delete();
        return $this->sendSuccess([],__('general.slider.destroy'));
    }

    public function update(UpdateSliderRequest $request)
    {
        $slider = Slider::find($request->input('slider_id'));

        $fileName = $slider->file_name;
//        dd($slider->file_name);

        if (!empty($request->file('picture'))){
            Storage::delete($slider->file_name);
            $fileName = Storage::put($this->time . "/sliders" , $request->file('picture'));
        }

        $slider->update([
            'file_name' => $fileName,
            'url' => $request->input('url'),
            'alt' => $request->input('text'),
            'status' => $request->input('status'),
        ]);

        return $this->sendSuccess([],__('general.slider.update'));
    }

    public function showAll()
    {
        $slider = Slider::all()->sortByDesc('created_at');

        $sliderCollection = new SliderCollection($slider);

        return $this->sendSuccess($sliderCollection,'All Slider pages');
    }

    public function show()
    {
        $slider = Slider::where('status','=',1)->get();

        $sliderCollection = new SliderCollection($slider);

        return $this->sendSuccess($sliderCollection,'Active Slides:');
    }
}
