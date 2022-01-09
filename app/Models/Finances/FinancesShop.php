<?php

namespace App\Models\Finances;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancesShop extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'image',
        'value',
        'update_at',
    ];

    public static function randColor()
    {
        return sprintf('#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255));
    }

    public function isImage($item)
    {
        return (strlen($item) == 7 && strpos($item, '#') === 0) ? false : true;
    }
}
