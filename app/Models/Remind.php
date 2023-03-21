<?php

namespace App\Models;

use App\Models\Traits\HasDates;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class Remind extends Model
{
    use HasFactory;
    use HasDates;

    protected $fillable = [
        'title',
        'content',
        'datetime',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function getItems(): Collection
    {
        return Remind::orderByDesc('is_active')->orderByDesc('datetime')->get();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTimeLeftAttribute()
    {
        $startdate = Carbon::now();
        $enddate = Carbon::parse($this->datetime);

        return $enddate->diffForHumans($startdate, CarbonInterface::DIFF_RELATIVE_TO_NOW, false, 4);
    }
}
