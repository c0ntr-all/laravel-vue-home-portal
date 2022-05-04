<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\DeleteRequest;
use App\Http\Requests\Comment\StoreRequest;
use App\Http\Resources\CommentResource;
use App\Models\Tasks\Task;
use App\Models\Comment;

class CommentController extends Controller {

    protected Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function store(Task $task, StoreRequest $request)
    {
        $comment = $task->comments()->create([
            'content' => $request->comment['content'],
            'user_id' => auth()->id()]
        );

        return new CommentResource($comment);
    }

    public function delete(Task $task, Comment $comment, DeleteRequest $request): void
    {
        $comment->delete();
    }
}
