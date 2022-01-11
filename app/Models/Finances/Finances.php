<?php

namespace App\Models\Finances;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\User;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getItems(): Collection
    {
        // Пока все берем
        return Finances::all();
    }
}
