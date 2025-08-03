<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Jobs\TaskReminderJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendTaskRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds tasks due tomorrow and dispatches reminder emails.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = now()->addDay()->toDateString();

        $tasksToRemind = Task::where('due_date', $tomorrow)
                             ->whereNotIn('status', ['completed'])
                             ->get();

        $tasksFound = $tasksToRemind->count();
        $emailsDispatched = 0;

        $this->info("Found {$tasksFound} tasks due tomorrow (not completed).");

        foreach ($tasksToRemind as $task) {
            TaskReminderJob::dispatch($task);
            $emailsDispatched++;
            $this->line("Dispatched reminder for task: '{$task->title}' (ID: {$task->id}) to {$task->user->email}");
        }

        $this->info("Summary: {$tasksFound} tasks found, {$emailsDispatched} emails dispatched to queue.");
        Log::info("Task reminder command ran. Found {$tasksFound} tasks, dispatched {$emailsDispatched} reminders.");

        return Command::SUCCESS;
    }
}