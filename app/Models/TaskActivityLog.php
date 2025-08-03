<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'action',
        'description',
    ];

    /**
     * An activity log belongs to a task.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * An activity log belongs to a user (who performed the action).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}