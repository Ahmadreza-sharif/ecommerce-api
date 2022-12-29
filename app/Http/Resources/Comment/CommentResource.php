<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'body' => $this->body,
            'user_id' => new UserCommentResource($this->user),
            'created_at' => $this->created_at,
            'status' => $this->getStatus(),
            'created_at' => $this->created_at
        ];
    }
}
