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
use App\Http\Resources\Category\HomeCategoryCollection;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;

class CategoryController extends Controller
{

    # CREATE
    public function store(CreateCategoryRequest $request)
    {
        $date = date('Y-m');
        $fileName = Storage::put("$date/categories", $request->file('picture'));

        # CREATE
        $category = Category::create([
            'name' => $request->input('title'),
            'slug' => $request->input('slug'),
            'description' => $request->input('desc'),
            'status' => $request->input('level'),
            'pic' => $fileName,
        ]);

        # CATEGORY RESOURCE
        $categoryResource = new CategoryResource($category);

        # SEND RESPONSE
        return $this->sendSuccess($categoryResource, __('general.category.add'));
    }

    # UPDATE
    public function update(UpdateCategoryRequest $request)
    {
        $date = date('Y-m');
        # BIND VALUE NEW OR OLD
        $category = Category::find($request->category_id);

        $fileName = $category->pic;

        if (!empty($request->file('pic'))) {
            Storage::delete($category->pic);
            $fileName = Storage::put("$date/categories",$request->file('pic'));
        }

        $category->update([
            'name' => $request->input('title'),
            'slug' => $request->input('slug'),
            'description' => $request->input('desc'),
            'status' => $request->input('level'),
            'pic' => $fileName
        ]);

        # CATEGORY RESOURCE
        $categoryResource = new CategoryResource($category);

        # RESPONSE
        return $this->sendSuccess($categoryResource, __('general.category.update'));
    }

    # DELETE
    public function destroy(CategoryRequest $request)
    {
        # DELETE RECORD
        $category = Category::find($request->category_id);

        Storage::delete($category->pic);

        $category->delete();

        # SEND RESPONSE WITH OBJ OF DELETED RECORD
        return $this->sendSuccess([], __('general.category.delete'));
    }

    # SELECT
    public function show(CategoryRequest $request)
    {
        # FIND
        $category = Category::find($request->input('category_id'));

        # CATEGORY RESOURCE
        $categoryResource = new CategoryResource($category);

        # SEND RESPONSE
        return $this->sendSuccess($categoryResource, __('general.category.select'));
    }

    public
    function showAll()
    {
        # SELECT ALL
        $category = Category::all();

        # CATEGORY COLLECTION
        $categoryCollection = new CategoryCollection($category);

        # SEND RESPONSE
        return $this->sendSuccess($categoryCollection, __('general.category.select-all'));
    }

    public function status(StatusCategoryRequest $request)
    {
        # FIND
        $category = Category::find($request->input('id'));

        # CHANGE STATUS
        $category->status = $category->status == category::ACTIVE ? category::IN_ACTIVE : category::ACTIVE;
        $category->save();

        # RESOURCE CATEGORY
        $categoryResource = new CategoryResource($category);

        # SEND RESPONSE
        return $this->sendSuccess($categoryResource, __('general.category.status'));
    }

    public
    function showAllHome()
    {
        # SELECT ALL
        $category = Category::where('status','=',1)->get();

        # CATEGORY COLLECTION
        $categoryCollection = new HomeCategoryCollection($category);

        # SEND RESPONSE
        return $this->sendSuccess($categoryCollection, __('general.category.select-all'));
    }
}
