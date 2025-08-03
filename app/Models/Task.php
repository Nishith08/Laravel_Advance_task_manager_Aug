<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'status',
        'priority',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * A task belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A task can have many activity logs.
     */
    public function activityLogs()
    {
        return $this->hasMany(TaskActivityLog::class);
    }

    /**
     * Log an activity for the task.
     *
     * @param string $action
     * @param string|null $description
     * @param \App\Models\User|null $user
     * @return void
     */
    public function logActivity(string $action, ?string $description = null, ?User $user = null)
    {
        $this->activityLogs()->create([
            'user_id' => $user->id ?? auth()->id(), // Use provided user or authenticated user
            'action' => $action,
            'description' => $description,
        ]);
    }
}
