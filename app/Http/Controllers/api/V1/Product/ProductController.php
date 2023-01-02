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
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class productController extends Controller
{

    # CREATE
    public function store(CreateProductRequest $request)
    {
        # INSERT MAIN PHOTO
        $fileName = Storage::put("$this->time/products", $request->file('picture'));

        # CREATE
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
            'picture' => $fileName,
        ]);

        # INSERT MORE PHOTOS
        foreach ($request->file('more_pictures') as $item) {
            $moreFile = Storage::put("$this->time/products", $item);
            $product->media()->create(['name' => $moreFile]);
        }

        # PRODUCT RESOURCE
        $productResource = new ProductResource($product);

        # SEND RESPONSE
        return $this->sendSuccess($productResource, __('general.product.add'));
    }

    # UPDATE
    public function update(UpdateProductRequest $request)
    {
        /*
         * get data from user  = 1
         * validate data = 1
         * ============================================
         * find product = 1
         * get picture and gallery = 1
         * delete existing picture = 1
         * delete existing gallery = 1
         * save uploaded data = 1
         * update data and file names = 1
         * send response = 1
         */

        // find products
        $product = product::find($request->input('product_id'));

        // get pictures
        $picture = $product->picture;
        $gallery = $product->media();

        // delete picture
        Storage::delete($picture);
        $newPicture = Storage::put($this->time . '/products', $request->file('picture'));


        // delete gallery
        foreach ($gallery->get() as $galleryItem) {
            Storage::delete($galleryItem->name);
        }

        $gallery->delete();

        // save uploaded data and insert into db
        foreach ($request->file('more_pictures') as $item) {
            $pic = Storage::put("$this->time/products", $item);
            $gallery->create(['name' => $pic]);
        }

        // update selected record
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
            "picture" => $newPicture,
        ]);
        $product->save();

        // fucking response
        return $this->sendSuccess($product, 'Product Updated Successfully');
    }

    # DELETE
    public function destroy(ProductRequest $request)
    {
        /*
         * get data from user  = 1
         * validate data = 1
         * ============================================
         * find product = 1
         * get picture and gallery = 1
         * delete existing picture = 1
         * delete existing gallery = 1
         * save uploaded data = 1
         * update data and file names = 1
         * send response = 1
        */
        $product = product::find($request->input('product_id'));

        Storage::delete($product->picture);

        $pictures = json_decode($product->more_pictures);
        foreach ($pictures as $picture) {
            Storage::delete($picture);
        }

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
