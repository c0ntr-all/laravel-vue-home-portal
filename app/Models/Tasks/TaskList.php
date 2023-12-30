<?php

namespace App\Models\Tasks;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;

/**
 * App\Models\Tasks\TaskList
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection<int, \App\Models\Tasks\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read User $user
 * @method static \Database\Factories\Tasks\TaskListFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TaskList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskList query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskList whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskList whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskList whereUserId($value)
 * @mixin \Eloquent
 */
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
