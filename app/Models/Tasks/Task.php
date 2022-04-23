<?php

namespace App\Models\Tasks;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'list_id',
        'title',
        'content',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
