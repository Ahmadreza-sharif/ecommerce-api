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
        # CREATE
        $category = Category::create([
            'name' => $request->input('title'),
            'slug' => $request->input('slug'),
            'description' => $request->input('desc'),
            'status' => $request->input('level'),
            'pic' => $this->putStorage('/categories', $request->file('picture')),
        ]);

        # SEND RESPONSE
        return $this->sendSuccess(new CategoryResource($category), __('general.category.add'));
    }

    # UPDATE
    public function update(UpdateCategoryRequest $request)
    {
        # BIND VALUE NEW OR OLD
        $category = Category::find($request->category_id);

        $this->deleteStorage($category->pic);

        $category->update([
            'name' => $request->input('title'),
            'slug' => $request->input('slug'),
            'description' => $request->input('desc'),
            'status' => $request->input('level'),
            'pic' => $this->putStorage('/categories',$request->file('pic'))
        ]);

        # RESPONSE
        return $this->sendSuccess(new CategoryResource($category), __('general.category.update'));
    }

    # DELETE
    public function destroy(CategoryRequest $request)
    {
        # DELETE RECORD
        $category = Category::find($request->category_id);

        $this->deleteStorage($category->pic);

        $category->delete();

        # SEND RESPONSE WITH OBJ OF DELETED RECORD
        return $this->sendSuccess([], __('general.category.delete'));
    }

    # SELECT
    public function show(CategoryRequest $request)
    {
        # FIND
        $category = Category::find($request->input('category_id'));

        # SEND RESPONSE
        return $this->sendSuccess(new CategoryResource($category), __('general.category.select'));
    }

    public function showAll()
    {
        # SELECT ALL
        $category = Category::all();

        dd($category);

        # SEND RESPONSE
        return $this->sendSuccess(new CategoryCollection($category), __('general.category.select-all'));
    }

    public function status(StatusCategoryRequest $request)
    {
        # FIND
        $category = Category::find($request->input('id'));

        # CHANGE STATUS
        $category->status = $category->status == category::ACTIVE ? category::IN_ACTIVE : category::ACTIVE;
        $category->save();

        # SEND RESPONSE
        return $this->sendSuccess(new CategoryResource($category), __('general.category.status'));
    }

    public
    function showAllHome()
    {
        # SELECT ALL
        $category = Category::where('status', '=', 1)->get();

        # SEND RESPONSE
        return $this->sendSuccess(new HomeCategoryCollection($category), __('general.category.select-all'));
    }
}
