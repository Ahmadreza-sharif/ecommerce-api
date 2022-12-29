<?php

namespace App\Http\Controllers\api\v1\Category;

use App\Http\Controllers\api\traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\Category\CategoryRequest;
use App\Http\Requests\Api\V1\Admin\Category\CreateCategoryRequest;
use App\Http\Requests\Api\V1\Admin\Category\StatusCategoryRequest;
use App\Http\Requests\Api\V1\Admin\Category\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category as Category;

class CategoryController extends Controller
{

    # CREATE
    public function store(CreateCategoryRequest $request)
    {
        # CREATE
        $category = category::create([
            'name' => $request->input('title'),
            'slug' => $request->input('slug'),
            'description' => $request->input('desc'),
            'status' => $request->input('level'),
        ]);

        # CATEGORY RESOURCE
        $categoryResource = new CategoryResource($category);

        # SEND RESPONSE
        return $this->sendSuccess($categoryResource, __('general.category.add'));
    }

    # UPDATE
    public function update(UpdateCategoryRequest $request)
    {
        # BIND VALUE NEW OR OLD
        $product = category::find($request->category_id);
        $product->update([
            'name' => $request->input('title'),
            'slug' => $request->input('slug'),
            'description' => $request->input('desc'),
            'status' => $request->input('level'),
        ]);

        # CATEGORY RESOURCE
        $categoryResource = new CategoryResource($product);

        # RESPONSE
        return $this->sendSuccess($categoryResource, __('general.category.update'));
    }

    # DELETE
    public function destroy(CategoryRequest $request)
    {
        # DELETE RECORD
        $category = category::where('id', $request->cat_id)->delete();

        # SEND RESPONSE WITH OBJ OF DELETED RECORD
        return $this->sendSuccess([], __('general.category.delete'));
    }

    # SELECT
    public function show(CategoryRequest $request)
    {
        # FIND
        $category = category::find($request->input('category_id'));

        # CATEGORY RESOURCE
        $categoryResource = new CategoryResource($category);

        # SEND RESPONSE
        return $this->sendSuccess($categoryResource, __('general.category.select'));
    }

    public
    function showAll()
    {
        # SELECT ALL
        $category = category::all();

        # CATEGORY COLLECTION
        $categoryCollection = new CategoryCollection($category);

        # SEND RESPONSE
        return $this->sendSuccess($categoryCollection, __('general.category.select-all'));
    }

    public function status(StatusCategoryRequest $request)
    {
        # FIND
        $category = category::find($request->input('id'));

        # CHANGE STATUS
        $category->status = $category->status == category::ACTIVE ? category::IN_ACTIVE : category::ACTIVE;
        $category->save();

        # RESOURCE CATEGORY
        $categoryResource = new CategoryResource($category);

        # SEND RESPONSE
        return $this->sendSuccess($categoryResource, __('general.category.status'));
    }
}
