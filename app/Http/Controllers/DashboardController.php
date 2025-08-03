<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Task Summary
        $totalTasks = $user->isAdmin() ? Task::count() : $user->tasks()->count();
        $pendingTasks = $user->isAdmin() ? Task::where('status', 'pending')->count() : $user->tasks()->where('status', 'pending')->count();
        $completedTasks = $user->isAdmin() ? Task::where('status', 'completed')->count() : $user->tasks()->where('status', 'completed')->count();

        // Overdue tasks count
        $overdueTasksCount = $user->isAdmin()
            ? Task::where('due_date', '<', now()->toDateString())
                ->whereNotIn('status', ['completed'])
                ->count()
            : $user->tasks()
                ->where('due_date', '<', now()->toDateString())
                ->whereNotIn('status', ['completed'])
                ->count();

        // Recent activity logs (last 5 actions)
        $recentActivityLogs = TaskActivityLog::with(['task', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'completedTasks',
            'overdueTasksCount',
            'recentActivityLogs'
        ));
    }
}
