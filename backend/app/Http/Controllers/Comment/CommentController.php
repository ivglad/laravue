<?php

namespace App\Http\Controllers\Comment;

use App\Enums\CommentModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentDestroyRequest;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Http\Resources\Comments\CommentResource;
use App\Http\Responses\AccessDeniedJsonResponse;
use App\Http\Responses\DeletedJsonResponse;
use App\Models\Comment\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request): CommentResource
    {
        $attr = $request->validated();
        $attr['user_id'] = Auth::id();
        $attr['commentable_type'] = CommentModel::from($attr['commentable_type'])->name();
        $comment = Comment::create($attr);
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateRequest $request, Comment $comment): CommentResource|AccessDeniedJsonResponse
    {
        if ($comment->user_id === Auth::id()) {
            $comment->update($request->validated());
            return new CommentResource($comment);
        }
        return new AccessDeniedJsonResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentDestroyRequest $request): DeletedJsonResponse|AccessDeniedJsonResponse
    {
        $ids = $request->validated()['ids'];
        $comments = Comment::whereIn('id', $ids)->get();
        foreach ($comments as $comment)
        if ($comment->user_id === Auth::id()) {
            $comment->delete();
            return new DeletedJsonResponse();
        }
        return new AccessDeniedJsonResponse();
    }
}
