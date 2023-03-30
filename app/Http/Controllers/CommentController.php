<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreRequest;
use App\Http\Resources\Comment\CommentResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class CommentController extends Controller {

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        /** @var Model $class */
        $class = Relation::getMorphedModel($data['commentable_type']);
        if (!$class) {
            throw new \Exception('Class not found');
        }

        $model = $class::find($data['commentable_id']);
        if (!$model) {
            throw new \Exception('Model not found');
        }

        $comment = $model->comments()->create([
            'user_id' => auth()->id(),
            'content' => $data['content']
        ]);

        return new CommentResource($comment);
    }
}
