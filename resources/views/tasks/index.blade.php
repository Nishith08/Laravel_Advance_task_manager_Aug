<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">Your Tasks</h3>
                    <a href="{{ route('tasks.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition ease-in-out duration-150">
                        Add New Task
                    </a>
                </div>

                <!-- Filters and Search -->
                <form method="GET" action="{{ route('tasks.index') }}" class="mb-6 bg-gray-50 p-4 rounded-lg shadow-inner flex flex-wrap items-end gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                        <select name="priority" id="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                    <div>
                        <label for="due_date_filter" class="block text-sm font-medium text-gray-700">Due Date</label>
                        <select name="due_date_filter" id="due_date_filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All</option>
                            <option value="before_today" {{ request('due_date_filter') == 'before_today' ? 'selected' : '' }}>Before Today</option>
                            <option value="today" {{ request('due_date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="after_today" {{ request('due_date_filter') == 'after_today' ? 'selected' : '' }}>After Today</option>
                        </select>
                    </div>
                    <div class="flex-grow">
                        <label for="search" class="block text-sm font-medium text-gray-700">Search (Title/Description)</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search tasks..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition ease-in-out duration-150">
                            Apply Filters
                        </button>
                        <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition ease-in-out duration-150">
                            Reset
                        </a>
                    </div>
                </form>

                @if($tasks->isEmpty())
                    <p class="text-gray-600 text-center">No tasks found matching your criteria.</p>
                @else
                    <div class="mt-6">
                        {{ $tasks->links() }}
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($tasks as $task)
                            <x-task-card :task="$task" />
                        @endforeach
                    </div>

                    
                @endif
            </div>
        </div>
    </div>
</x-app-layout>