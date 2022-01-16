<?php

namespace App\Models\Tasks;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskItem extends Model
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
}
