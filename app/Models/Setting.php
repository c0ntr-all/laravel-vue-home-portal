<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUser;

class Setting extends Model
{
    use HasFactory,
        HasUser;

    public $table = 'users_settings';
    public $fillable = [
        'user_id',
        'model',
        'key',
        'value'
    ];
    public $casts = [
        'value' => 'json'
    ];
}
