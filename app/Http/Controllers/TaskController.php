<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Jobs\TaskReminderJob;

class TaskController extends Controller
{
    public function __construct()
    {
        // Apply middleware to protect task routes
        // 'auth' ensures only logged-in users can access
        // 'admin' middleware is applied to specific methods if needed for admin-only task management (e.g., viewing all tasks)
        $this->middleware('auth');
    }

    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tasksQuery = $user->isAdmin() ? Task::query() : $user->tasks();

        // Filtering
        if ($request->filled('status')) {
            $tasksQuery->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $tasksQuery->where('priority', $request->priority);
        }
        if ($request->filled('due_date_filter')) {
            if ($request->due_date_filter === 'before_today') {
                $tasksQuery->where('due_date', '<', now()->toDateString());
            } elseif ($request->due_date_filter === 'after_today') {
                $tasksQuery->where('due_date', '>', now()->toDateString());
            } elseif ($request->due_date_filter === 'today') {
                $tasksQuery->where('due_date', now()->toDateString());
            }
        }

        // Search
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $tasksQuery->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', $searchTerm)
                      ->orWhere('description', 'like', $searchTerm);
            });
        }

        // Order by due date by default, then priority
        $tasks = $tasksQuery->orderBy('due_date', 'asc')
                            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')") // Custom order for priority
                            ->paginate(10)
                            ->withQueryString();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
        ]);

        $task = Auth::user()->tasks()->create($validated);

        $task->logActivity('created', 'Task created.', Auth::user());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        // Authorize that the user owns the task or is an admin
        if (Auth::user()->id !== $task->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load activity logs with the user who performed the action
        $activityLogs = $task->activityLogs()->with('user')->latest()->get();

        return view('tasks.show', compact('task', 'activityLogs'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        // Authorize that the user owns the task or is an admin
        if (Auth::user()->id !== $task->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        // Authorize that the user owns the task or is an admin
        if (Auth::user()->id !== $task->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Store original attributes before update to detect changes
        $originalAttributes = $task->getOriginal(); 

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
        ]);

        $task->update($validated);

        // Get dirty attributes *after* update to compare with original
        $changes = array_diff_assoc($task->getAttributes(), $originalAttributes);
        // Exclude 'updated_at' from changes as it always changes
        unset($changes['updated_at']);

        // Log activity based on changes
        if (!empty($changes)) {
            $description = 'Task updated. Changes: ';
            $changedFields = [];
            foreach ($changes as $key => $newValue) {
                $oldValue = $originalAttributes[$key] ?? 'N/A'; // Use original attributes
                $changedFields[] = "{$key} from '{$oldValue}' to '{$newValue}'";
            }
            $description .= implode(', ', $changedFields) . '.';
            $task->logActivity('updated', $description, Auth::user());

            // Specific log for status change
            if (isset($changes['status']) && $changes['status'] !== ($originalAttributes['status'] ?? null)) {
                $task->logActivity('status_changed', "Status changed from '{$originalAttributes['status']}' to '{$changes['status']}'.", Auth::user());
            }
        } else {
            // Optional: Log if no changes were made, useful for debugging
            // $task->logActivity('no_change', 'Task viewed/saved with no changes.', Auth::user());
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        // Authorize that the user owns the task or is an admin
        if (Auth::user()->id !== $task->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Store task details before deletion for logging purposes
        $deletedTaskTitle = $task->title;
        $deletedTaskId = $task->id;

        // Log activity *before* deletion
        \App\Models\TaskActivityLog::create([
            'task_id' => $deletedTaskId, // Use the stored ID
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'description' => 'Task "' . $deletedTaskTitle . '" (ID: ' . $deletedTaskId . ') deleted.',
        ]);
        
        // Perform the deletion *after* logging
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}