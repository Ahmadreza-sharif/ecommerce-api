<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Comment\ProductCommentCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd(new CommentCollection($this->comments));
//        dd(new CommentCollection($this->comments()->where('status','=',1)->get()));
        return [
            'title' => $this->name,
            'slug' => $this->slug,
            'desc' => $this->description,
            'price' => $this->price,
            'word' => $this->key_words,
            'view' => $this->view_count,
            'Product_number' => $this->code,
            'sell_count' => $this->sell_count,
            "status" => $this->getStatus(),
            "category" => new CategoryResource($this->category),
            "brand" => new BrandResource($this->brand),
            "Liked" => $this->favorites()->count(),
            "comments" => new ProductCommentCollection($this->comments)
        ];
    }
}
