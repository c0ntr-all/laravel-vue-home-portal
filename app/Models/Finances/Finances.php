<?php

namespace App\Models\Finances;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Finances\FinancesShop;

class Finances extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'id',
        'type',
        'user_id',
        'recipient',
        'summ',
        'comment',
        'done_at',
        'created_at'
    ];
    public function shop()
    {
        return $this->hasOne(FinancesShop::class, 'id', 'recipient');
    }
}
