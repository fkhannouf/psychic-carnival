@extends('layouts.app')

@section('title', 'Todo List')

@section('content')
<div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <h1 class="text-3xl font-bold mb-6">My Todos</h1>

    @if($todos->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500 text-lg">No todos yet. Create your first one!</p>
            <a href="{{ route('todos.create') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Todo
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($todos as $todo)
                <div class="border rounded-lg p-4 {{ $todo->completed ? 'bg-gray-50' : 'bg-white' }} hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="mr-3">
                                    @csrf
                                    <button type="submit" class="focus:outline-none">
                                        @if($todo->completed)
                                            <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                <div>
                                    <h3 class="text-lg font-semibold {{ $todo->completed ? 'line-through text-gray-500' : 'text-gray-800' }}">
                                        {{ $todo->title }}
                                    </h3>
                                    @if($todo->description)
                                        <p class="text-gray-600 mt-1 {{ $todo->completed ? 'line-through' : '' }}">
                                            {{ $todo->description }}
                                        </p>
                                    @endif
                                    <p class="text-sm text-gray-400 mt-2">
                                        Created {{ $todo->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <a href="{{ route('todos.edit', $todo) }}" class="text-blue-500 hover:text-blue-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this todo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
