<?php

namespace App\Models\Tasks;

use App\Models\Traits\HasComments;
use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    use HasDates;
    use HasComments;

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
