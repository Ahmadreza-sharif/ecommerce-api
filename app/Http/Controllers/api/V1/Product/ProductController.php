<?php

namespace App\Http\Controllers\api\v1\Product;

use App\Http\Controllers\api\traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\product\CreateProductRequest;
use App\Http\Requests\Api\V1\Admin\product\ProductRequest;
use App\Http\Requests\Api\V1\Admin\product\StatusProductRequest;
use App\Http\Requests\Api\V1\Admin\product\UpdateProductRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;

class productController extends Controller
{

    # CREATE
    public function store(CreateProductRequest $request)
    {

        # CREATE
        $product = Product::create($request->all());

        # PRODUCT RESOURCE
        $productResource = new ProductResource($product);

        # SEND RESPONSE
        return $this->sendSuccess($productResource, __('general.product.add'));
    }

    # UPDATE
    public function update(UpdateProductRequest $request)
    {
        # FIND RECORD
        $product = product::find($request->input('product_id'));

        # BIND PARAMETERS
        $product->update([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'brand_id' => $request->input('brand_id'),
            'category_id' => $request->input('category_id'),
            'status' => $request->input('status'),
            'price' => $request->input('price'),
            'status_store' => $request->input('status_store'),
            'key_words' => $request->input('key_words'),
            'view_count' => $request->input('view_count'),
            'code' => $request->input('code'),
            'sell_count' => $request->input('sell_count')
        ]);

        # PRODUCT RESOURCE
        $ProductResource = new ProductResource($product);

        # RESPONSE
        return $this->sendSuccess($ProductResource, __('general.product.update'));
    }

    # DELETE
    public function destroy(ProductRequest $request)
    {
        # FIND AND DELETE
        product::where('id', $request->input('product_id'))->delete();

        # SEND RESPONSE
        return $this->sendSuccess('', __('general.product.delete'));
    }

    # SELECT ONE
    public function show(ProductRequest $request)
    {
        # FIND
        $product = product::find($request->input('product_id'));

        # PRODUCT RESOURCE
        $productResource = new ProductResource($product);

        # SEND RESPONSE
        return $this->sendSuccess($productResource, __('general.product.select'));
    }

    # SELECT ALL
    public function showAll()
    {
        # GET ALL PRODUCTS
        $product = product::all();

        # PRODUCT COLLECTION
        $productCollection = new ProductCollection($product);

        # SEND RESPONSE
        return $this->sendSuccess($productCollection, __('general.product.select-all'));
    }

    # CHANGE STATUS
    public function status(StatusProductRequest $request)
    {
        # FIND
        $product = product::find($request->input('id'));

        # CHANGE STATUS
        $product->status = $product->status == product::ACTIVE ? product::IN_ACTIVE : product::ACTIVE;
        $product->save();

        # RESOURCE CATEGORY
        $productResource = new ProductResource($product);

        # SEND RESPONSE
        return $this->sendSuccess($productResource, __('general.product.status'));
    }
}
