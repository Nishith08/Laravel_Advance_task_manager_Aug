<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Task Details: ') . $task->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Task Information</h3>
                        <p class="mb-1"><strong class="text-gray-700">Title:</strong> {{ $task->title }}</p>
                        <p class="mb-1"><strong class="text-gray-700">Description:</strong> {{ $task->description ?? 'N/A' }}</p>
                        <p class="mb-1"><strong class="text-gray-700">Due Date:</strong> {{ $task->due_date->format('M d, Y') }}</p>
                        <p class="mb-1"><strong class="text-gray-700">Status:</strong>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($task->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </p>
                        <p class="mb-1"><strong class="text-gray-700">Priority:</strong>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($task->priority == 'low') bg-green-100 text-green-800
                                @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </p>
                        <p class="mb-1"><strong class="text-gray-700">Created By:</strong> {{ $task->user_id === auth()->id() ? 'You' : $task->user->name }}</p>
                        <p class="mb-1"><strong class="text-gray-700">Created At:</strong> {{ $task->created_at->format('M d, Y H:i A') }}</p>
                        <p class="mb-1"><strong class="text-gray-700">Last Updated:</strong> {{ $task->updated_at->format('M d, Y H:i A') }}</p>

                        <div class="flex space-x-2 mt-6">
                            <a href="{{ route('tasks.edit', $task) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition ease-in-out duration-150">
                                Edit Task
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition ease-in-out duration-150">
                                    Delete Task
                                </button>
                            </form>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-2">Activity Log</h3>
                        @if($activityLogs->isEmpty())
                            <p class="text-gray-600">No activity logs for this task.</p>
                        @else
                            <div class="bg-gray-50 rounded-lg shadow-inner p-4 max-h-96 overflow-y-auto">
                                <ul class="divide-y divide-gray-200">
                                    @foreach($activityLogs as $log)
                                        <li class="py-3">
                                            <p class="text-sm text-gray-800">
                                                <span class="font-medium">{{ $log->user->name }}</span>
                                                <span class="text-gray-600"> {{ $log->action }}</span>
                                                <span class="text-gray-400 text-xs ml-2">{{ $log->created_at->format('M d, Y H:i A') }}</span>
                                            </p>
                                            @if($log->description)
                                                <p class="text-xs text-gray-500 mt-1">{{ $log->description }}</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>