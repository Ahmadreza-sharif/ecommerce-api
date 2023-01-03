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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class productController extends Controller
{

    # CREATE
    public function store(CreateProductRequest $request)
    {
        /*
         * get data from user
         * validate data
         * ============================================
         * create product and insert main photo
         * insert gallery
         * response
         */

        $product = Product::create([
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
            'sell_count' => $request->input('sell_count'),
            'picture' => $this->putStorage("/products", $request->file('picture')),
        ]);

        foreach ($request->file('more_pictures') as $item) {
            $product->media()->create(['name' => $this->putStorage("/products",$item)]);
        }

        return $this->sendSuccess(new ProductResource($product), __('general.product.add'));
    }

    # UPDATE
    public function update(UpdateProductRequest $request)
    {
        /*
         * get data from user
         * validate data
         * ============================================
         * find product
         * get picture and gallery
         * delete existing picture
         * delete existing gallery
         * save uploaded data
         * update data and file names
         * send response
         */

        $product = product::find($request->input('product_id'));

        $picture = $product->picture;
        $gallery = $product->media();

        $this->deleteStorage($picture);

        foreach ($gallery->get() as $galleryItem) {
            $this->deleteStorage($galleryItem->name);
        }

        $gallery->delete();

        foreach ($request->file('more_pictures') as $item) {
            $gallery->create(['name' => $this->putStorage("/products", $item)]);
        }

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
            'sell_count' => $request->input('sell_count'),
            "picture" => $this->putStorage('/products',$request->file('picture')),
        ]);
        $product->save();

        return $this->sendSuccess($product, 'Product Updated Successfully');
    }

    # DELETE
    public function destroy(ProductRequest $request)
    {
        /*
         * get product id
         * validate data
         * ============================================
         * find product
         * delete main picture
         * delete gallery
         * delete product
         * response
        */

        $product = product::find($request->input('product_id'));

        Storage::delete($product->picture);

        foreach ($product->more_pictures as $picture) {
            Storage::delete($picture);
        }

        return $this->sendSuccess('', __('general.product.delete'));
    }

    # SELECT ONE
    public function show(ProductRequest $request)
    {
        /*
         * get product id
         * validate data
         * ============================================
         * find product
         * send to resource
         * response
        */

        $product = product::find($request->input('product_id'));

        return $this->sendSuccess(new ProductResource($product), __('general.product.select'));
    }

    # SELECT ALL
    public function showAll()
    {
        /*
         * select all product
         * send to collection
         * response
        */

        $product = product::all();

        return $this->sendSuccess(new ProductCollection($product), __('general.product.select-all'));
    }

    # CHANGE STATUS
    public function status(StatusProductRequest $request)
    {
        /*
         * get product id
         * validate data
         * ============================================
         * find product
         * change status
         * send to resource
         * response
        */

        $product = product::find($request->input('id'));

        $product->status = $product->status == product::ACTIVE ? product::IN_ACTIVE : product::ACTIVE;
        $product->save();

        return $this->sendSuccess(new ProductResource($product), __('general.product.status'));
    }

    public function amazingProduct()
    {
        $product = Product::where('status', '=', 1)->orderBy('price', 'Asc')->take(15)->get();

        return $this->sendSuccess($product, 'Amazing products');
    }

    public function mostSales()
    {
        $product = Product::where('status', '=', 1)->orderBy('sell_count', 'Desc')->take(15)->get();

        return $this->sendSuccess($product, 'most Sales Product');
    }

    public function newProduct()
    {
        $product = Product::where('status', '=', 1)->orderBy('created_at', 'Asc')->take(15)->get();

        return $this->sendSuccess($product, 'most new products');
    }

    public function mostFavorite()
    {
        $product = Product::withCount('likes')->where('status', '=', 1)->orderBy('likes_count', 'Desc')->take(15)->get();

        return $this->sendSuccess($product, 'most new products');
    }

    public function likeProduct(ProductRequest $request)
    {
        $product = Product::find($request->input('product_id'));

        if ($this->likeExists($product)->exists()) {
            $res = $this->likeExists($product)->delete();
            return $this->sendSuccess('', 'like deleted successfully.');
        } else {
            $product->likes()->create([
                'user_id' => Auth::id()
            ]);
            return $this->sendSuccess('', 'Product liked successfully.');
        }

    }

    public function likeExists($product)
    {
        return $product->likes()->where('user_id', Auth::id());
    }


}
