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
        $date = date('Y-m');
        $fileName = Storage::put("$date/products/", $request->file('picture'));
        $filesName = [];
        foreach ($request->file('more_pictures') as $item){
            $filesName[] = Storage::put("$date/products", $item);
        }

        $jsonFileNames = json_encode($filesName);

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
            'more_pictures' => $jsonFileNames,
        ]);

        # PRODUCT RESOURCE
        $productResource = new ProductResource($product);

        # SEND RESPONSE
        return $this->sendSuccess($productResource, __('general.product.add'));
    }

    # UPDATE
    public function update(UpdateProductRequest $request)
    {
        $date = date('Y-m');
        # FIND RECORD
        $product = product::find($request->input('product_id'));

        $fileName = $product->picture;
        if (!empty($request->file('picture'))) {
            Storage::delete($fileName);
            $fileName = Storage::put("$date/products/",$request->file('picture'));
        }
        $filesName = json_decode($product->more_pictures);
        if (!empty($request->file('more_pictures'))){
            $filesName = [];
            $files = json_decode($product->more_pictures);
            foreach ($files as $file){
                Storage::delete($files);
            }
            foreach ($request->file('more_pictures') as $item){
                $filesName[] = Storage::put("$date/products",$item);
            }
        }
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
            'sell_count' => $request->input('sell_count'),
            "picture" => $fileName,
            "more_pictures" => json_encode($filesName)
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
        $product = product::find($request->input('product_id'));

        Storage::delete($product->picture);

        $pictures = json_decode($product->more_pictures);
        foreach ($pictures as $picture){
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
        $product = Product::where('status','=',1)->orderBy('price','Asc')->take(15)->get();

        return $this->sendSuccess($product,'Amazing products');
    }

    public function mostSales()
    {
        $product = Product::where('status','=',1)->orderBy('sell_count','Desc')->take(15)->get();

        return $this->sendSuccess($product,'most Sales Product');
    }

    public function newProduct()
    {
        $product = Product::where('status','=',1)->orderBy('created_at','Asc')->take(15)->get();

        return $this->sendSuccess($product,'most new products');
    }

    public function mostFavorite()
    {
        $product = Product::withCount('likes')->where('status','=',1)->orderBy('likes_count','Desc')->take(15)->get();

        return $this->sendSuccess($product,'most new products');
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
