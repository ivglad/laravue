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
     * Сохранение комментария определенной модели, которая трансформируется через enum справочника
     *
     * @param CommentStoreRequest $request
     * @return CommentResource
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
     * Обновление своего комментария, обновить чужой нельзя,
     * как и изменить принадлежность комментария к определенной модели и объекту
     *
     * @param CommentUpdateRequest $request
     * @param Comment $comment
     * @return CommentResource|AccessDeniedJsonResponse
     */
    public function update(CommentUpdateRequest $request, Comment $comment): CommentResource|AccessDeniedJsonResponse
    {
        $attr = $request->validated();
        if (Auth::id() === $comment->user_id) {
            $comment->update($attr);
            $comment->refresh();
        } else {
            return new AccessDeniedJsonResponse();
        }
        return new CommentResource($comment);
    }

    /**
     * Удаление комментария по его id, к удалению допускаются только свои комментарии,
     * если не установлена соответствующее право
     *
     * @param CommentDestroyRequest $request
     * @return DeletedJsonResponse|AccessDeniedJsonResponse
     */
    public function destroy(CommentDestroyRequest $request): DeletedJsonResponse|AccessDeniedJsonResponse
    {
        $ids = $request->validated()['ids'];
        if (Auth::user()->can('comment.destroy.other')) {
            Comment::whereIn('id', $ids)->delete();
        }
        else {
            Comment::whereIn('id', $ids)->where('user_id', Auth::id())->delete();
        }
        return new DeletedJsonResponse();
    }
}
