<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $user = User::where('email', 'user@example.com')->first();

        // Admin's tasks
        if ($admin) {
            Task::factory()->count(5)->create([
                'user_id' => $admin->id,
                'due_date' => now()->addDays(rand(-5, 10))->toDateString(), // Mix of past/future dates
                'status' => ['pending', 'in_progress', 'completed'][array_rand(['pending', 'in_progress', 'completed'])],
                'priority' => ['low', 'medium', 'high'][array_rand(['low', 'medium', 'high'])],
            ]);

            // An overdue task for admin
            Task::factory()->create([
                'user_id' => $admin->id,
                'title' => 'Admin Overdue Task',
                'description' => 'This task is past its due date.',
                'due_date' => now()->subDays(2)->toDateString(),
                'status' => 'pending',
                'priority' => 'high',
            ]);

            // A task due tomorrow for admin (for reminder testing)
            Task::factory()->create([
                'user_id' => $admin->id,
                'title' => 'Admin Task Due Tomorrow',
                'description' => 'This task should trigger a reminder.',
                'due_date' => now()->addDay()->toDateString(),
                'status' => 'pending',
                'priority' => 'medium',
            ]);
        }

        // Regular user's tasks
        if ($user) {
            Task::factory()->count(7)->create([
                'user_id' => $user->id,
                'due_date' => now()->addDays(rand(-7, 14))->toDateString(), // Mix of past/future dates
                'status' => ['pending', 'in_progress', 'completed'][array_rand(['pending', 'in_progress', 'completed'])],
                'priority' => ['low', 'medium', 'high'][array_rand(['low', 'medium', 'high'])],
            ]);

            // An overdue task for regular user
            Task::factory()->create([
                'user_id' => $user->id,
                'title' => 'User Overdue Task',
                'description' => 'This task is past its due date.',
                'due_date' => now()->subDays(3)->toDateString(),
                'status' => 'in_progress',
                'priority' => 'high',
            ]);

            // A task due tomorrow for regular user (for reminder testing)
            Task::factory()->create([
                'user_id' => $user->id,
                'title' => 'User Task Due Tomorrow',
                'description' => 'This task should trigger a reminder.',
                'due_date' => now()->addDay()->toDateString(),
                'status' => 'pending',
                'priority' => 'low',
            ]);
        }
    }
}
