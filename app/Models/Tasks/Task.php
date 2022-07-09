<?php

namespace App\Models\Tasks;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Task extends Model
{
    use HasFactory;
    use HasDates;

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
        return $this->morphMany(Comment::class, 'comment');
    }
}
