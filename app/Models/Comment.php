<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'content',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = ['user_name'];

    public function comment()
    {
        return $this->morphTo();
    }

    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value);
        return $date->format('Y-m-d H:i');
    }

    public function getUserNameAttribute()
    {
        return $this->user()->get()[0]->name;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
