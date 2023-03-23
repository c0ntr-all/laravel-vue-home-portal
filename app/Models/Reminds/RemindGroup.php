<?php

namespace App\Models\Reminds;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RemindGroup extends Model
{
    use HasFactory;

    public function reminds(): HasMany
    {
        return $this->hasMany(Remind::class);
    }
}
