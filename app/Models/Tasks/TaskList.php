<?php

namespace App\Models\Tasks;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;

class TaskList extends Model
{
    use HasFactory;
    use HasDates;

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'list_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getItems(): Collection
    {
        // Аксессор user_name не работает, потому что используется прямой запрос без использования модели
        return TaskList::with('tasks.comments')->get();
    }
}
