<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // For logging email sending

class TaskReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;

    /**
     * Create a new job instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // In a real application, you would send an email here.
        // For this example, we'll just log it.
        $userEmail = $this->task->user->email;
        $taskTitle = $this->task->title;
        $dueDate = $this->task->due_date->format('Y-m-d');

        Log::info("Sending reminder email to {$userEmail} for task '{$taskTitle}' due on {$dueDate}.");

        // Example of how you would send a real email (requires Mail configuration)
        /*
        Mail::to($userEmail)->send(new TaskReminderMail($this->task));
        */
    }
}