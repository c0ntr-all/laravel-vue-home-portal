<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'updated_at',
        'deleted_at',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }
}
