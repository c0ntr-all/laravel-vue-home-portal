<?php

namespace App\Models\Reminds;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RemindGroup extends Model
{
    use HasFactory;

    public $table = 'reminds_groups';
    public $timestamps = false;

    public $fillable = [
        'user_id',
        'name',
        'content',
        'color',
    ];

    public function reminds(): HasMany
    {
        return $this->hasMany(Remind::class, 'group_id', 'id');
    }
}
