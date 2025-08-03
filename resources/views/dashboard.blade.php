<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Task Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div class="bg-blue-100 p-4 rounded-lg shadow-md">
                        <p class="text-gray-600">Total Tasks</p>
                        <p class="text-3xl font-bold text-blue-800">{{ $totalTasks }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg shadow-md">
                        <p class="text-gray-600">Pending Tasks</p>
                        <p class="text-3xl font-bold text-yellow-800">{{ $pendingTasks }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg shadow-md">
                        <p class="text-gray-600">Completed Tasks</p>
                        <p class="text-3xl font-bold text-green-800">{{ $completedTasks }}</p>
                    </div>
                </div>

                <div class="mt-8 bg-red-100 p-4 rounded-lg shadow-md text-center">
                    <p class="text-gray-600">Overdue Tasks</p>
                    <p class="text-3xl font-bold text-red-800">{{ $overdueTasksCount }}</p>
                </div>

                <h3 class="text-lg font-semibold mt-8 mb-4">Recent Activity Logs</h3>
                @if($recentActivityLogs->isEmpty())
                    <p class="text-gray-600">No recent activity.</p>
                @else
                    <div class="bg-gray-50 rounded-lg shadow-md p-4">
                        <ul class="divide-y divide-gray-200">
                            @foreach($recentActivityLogs as $log)
                                <li class="py-3 flex items-center justify-between">
                                    <div>
                                        <span class="font-medium text-gray-800">{{ $log->user->name }}</span>
                                        <span class="text-gray-600"> {{ $log->action }} 
                                            @if($log->task)
                                                task "{{ $log->task->title }}"
                                            @else
                                                task (ID: {{ $log->task_id ?? 'N/A' }})
                                            @endif
                                        </span>
                                        @if($log->description)
                                            <p class="text-sm text-gray-500 ml-4">{{ $log->description }}</p>
                                        @endif
                                    </div>
                                    <span class="text-sm text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
