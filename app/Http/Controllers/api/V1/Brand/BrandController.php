<?php

namespace App\Http\Controllers\api\v1\Brand;

use App\Http\Controllers\api\traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\brand\BrandRequest;
use App\Http\Requests\Api\V1\Admin\brand\CreateBrandRequest;
use App\Http\Requests\Api\V1\Admin\brand\UpdateBrandRequest;
use App\Http\Resources\Brand\BrandCollection;
use App\Http\Resources\Brand\BrandResource;
use App\Models\Brand as Brand;

class brandController extends Controller
{

    #CREATE
    public function store(CreateBrandRequest $request)
    {

        # CREATE
        $brand = brand::create([
            'name' => $request->input('title'),
            'slug' => $request->input('slug'),
            'logo' => $request->input('pic'),
            'status' => $request->input('st'),
            'category_id' => $request->input('category_id')
        ]);

        # BRAND RESOURCE
        $brandResource = new brandResource($brand);

        # SEND RESPONSE
        return $this->sendSuccess($brandResource, __('general.brand.add'));
    }

    #UPDATE
    public function update(UpdateBrandRequest $request)
    {

        # BIND VALUE
        $brand = brand::find($request->brand_id);
        $brand->update([
            'name' => $request->input('title'),
            'slug' => $request->input('slug'),
            'logo' => $request->input('pic'),
            'status' => $request->input('st'),
            'category_id' => $request->input('category_id')
        ]);

        # BRAND RESOURCE
        $brandResource = new BrandResource($brand);

        # RESPONSE
        return $this->sendSuccess($brandResource, __("general.brand.update"));
    }

    # DELETE
    public function destroy(BrandRequest $request)
    {
        brand::where('id', $request->brand_Id)->delete();
        return $this->sendSuccess('', __("general.brand.delete"));
    }

    # SELECT
    public function show(BrandRequest $request)
    {
        # SELECT OBJ , VALIDATE
        $brand = brand::find($request->input('brand_id'));

        # BRAND RESOURCE
        $brandResource = new BrandResource($brand);

        # SEND RESPONSE
        return $this->sendSuccess($brandResource, __("general.brand.select"));
    }

    # SELECT ALL
    public function showAll()
    {
        # SELECT ALL
        $brand = brand::with(['category'])->get();

        # BRAND RESOURCE
        $brandResource = new BrandCollection($brand);

        # SEND RESPONSE
        return $this->sendSuccess($brandResource, __('general.brand.selectAll'));
    }

    # CHANGE STATUS
    public function status(BrandRequest $request)
    {
        # FIND RECORD
        $brand = brand::find($request->input('brand_id'));

        # CHANGE STATUS
        $brand->status = $brand->status == brand::ACTIVE ? brand::IN_ACTIVE : brand::ACTIVE;
        $brand->save();

        # RESOURCE BRAND
        $brandResource = new BrandResource($brand);

        # SEND RESPONSE
        return $this->sendSuccess($brandResource, __("general.brand.status"));
    }
}
