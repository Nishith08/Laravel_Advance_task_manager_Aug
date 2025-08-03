@props(['task'])

<div class="bg-white rounded-lg shadow-md p-6 flex flex-col justify-between h-full">
    <div>
        <h4 class="text-xl font-semibold text-gray-800 mb-2">{{ $task->title }}</h4>
        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $task->description ?? 'No description provided.' }}</p>

        <div class="flex items-center justify-between text-sm mb-2">
            <span class="font-medium text-gray-700">Created By:</span>
            <span class="text-gray-600">{{ $task->user_id === auth()->id() ? 'You' : $task->user->name }}</span>
        </div>
        
        <div class="flex items-center justify-between text-sm mb-2">
            <span class="font-medium text-gray-700">Due Date:</span>
            <span class="text-gray-600">{{ $task->due_date->format('M d, Y') }}</span>
        </div>
        

        <div class="flex items-center justify-between text-sm mb-2">
            <span class="font-medium text-gray-700">Status:</span>
            <span class="px-2 py-1 rounded-full text-xs font-semibold
                @if($task->status == 'pending') bg-yellow-100 text-yellow-800
                @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                @else bg-green-100 text-green-800 @endif">
                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
            </span>
        </div>

        <div class="flex items-center justify-between text-sm mb-4">
            <span class="font-medium text-gray-700">Priority:</span>
            <span class="px-2 py-1 rounded-full text-xs font-semibold
                @if($task->priority == 'low') bg-green-100 text-green-800
                @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800 @endif">
                {{ ucfirst($task->priority) }}
            </span>
        </div>
    </div>

    <div class="flex justify-end space-x-2 mt-4">
        <a href="{{ route('tasks.show', $task) }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition ease-in-out duration-150 text-sm">
            View
        </a>
        <a href="{{ route('tasks.edit', $task) }}" class="px-3 py-1 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 transition ease-in-out duration-150 text-sm">
            Edit
        </a>
        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition ease-in-out duration-150 text-sm">
                Delete
            </button>
        </form>
    </div>
</div>