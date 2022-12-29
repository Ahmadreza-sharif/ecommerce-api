<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($this->getCollection());
        return $this->getCollection();
    }

    public function getCollection(): Collection
    {
//        dd($this);
        return $this->collection->map(function ($item) {
            return [
                'body' => $item->body,
                'status' => $item->getStatus(),
                'created_at' => $item->created_at,
                'Name' => new UserCommentResource($item->user),
                'Product' => new ProductResource($item->commentable)
            ];
        });
    }
}
