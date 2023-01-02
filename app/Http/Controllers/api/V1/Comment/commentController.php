<?php

namespace App\Http\Controllers\api\V1\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Comment\CommentRequest;
use App\Http\Requests\Api\V1\Comment\statusCommentRequest;
use App\Http\Requests\Api\V1\Comment\StoreCommentRequest;
use App\Http\Requests\Api\V1\Like\StoreLikeRequest;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class commentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        $product = Product::find($request->product_id);
        $comment = $product->comments()->create([
            'body' => $request->input('body'),
            'user_id' => Auth::id(),
            'status' => 0,
            'parent_id' => $request->input('parent_id')
        ]);

        $commentResource = new CommentResource($comment);


        return $this->sendSuccess($commentResource, 'Comment Created Successfully.');
    }

    public function destroy(CommentRequest $request)
    {
        Comment::find($request->input('comment_id'))->delete();
        return $this->sendSuccess('', 'Comment Deleted Successfully');
    }

    public function showAllProduct()
    {
        $comments = Auth::user()->comments;
//        dd($comments);
        $collectionComments = new CommentCollection($comments);
//        dd($collectionComments);
        return $this->sendSuccess($collectionComments, 'Your Comments:');
    }

    public function showAll()
    {
        $comments = Comment::all();
        $collectionComments = new CommentCollection($comments);
        return $this->sendSuccess($collectionComments, 'All Comments');
    }

    public function status(statusCommentRequest $request)
    {
        $comment = Comment::find($request->input('comment_id'));
        $comment->update([
            'status' => $request->input('status')
        ]);
        $commentResource = new CommentResource($comment);
        return $this->sendSuccess($commentResource, ['status updated successfully']);
    }

    public function likeComment(StoreLikeRequest $request)
    {
        # CHECK LIKE EXISTS OR NOT
        # TRUE : DELETE LIKE
        # FALSE : ADD LIKE

        $comment = Comment::find($request->input('comment_id'));

        if ($this->likeExists($comment)->exists()) {
            $res = $this->likeExists($comment)->delete();
            return $this->sendSuccess('', 'like deleted successfully.');
        } else {
            $comment->likes()->create([
                'user_id' => Auth::id()
            ]);
            return $this->sendSuccess('', 'Comment liked successfully.');
        }

        # SEND RESPONSE SUCCESSFULLY
    }

    public function likeExists($comment)
    {
        return $comment->likes()->where('user_id', Auth::id());
    }
}
