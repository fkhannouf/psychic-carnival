<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $todos = Todo::latest()->get();
        
        if ($request->wantsJson()) {
            return response()->json($todos);
        }
        
        return view('todos.index', compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $todo = Todo::create($validated);

        if ($request->wantsJson()) {
            return response()->json($todo, 201);
        }

        return redirect()->route('todos.index')
            ->with('success', 'Todo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Todo $todo)
    {
        if ($request->wantsJson()) {
            return response()->json($todo);
        }
        
        return view('todos.show', compact('todo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'completed' => 'boolean',
        ]);

        $todo->update($validated);

        if ($request->wantsJson()) {
            return response()->json($todo);
        }

        return redirect()->route('todos.index')
            ->with('success', 'Todo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Todo $todo)
    {
        $todo->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Todo deleted successfully'], 200);
        }

        return redirect()->route('todos.index')
            ->with('success', 'Todo deleted successfully.');
    }

    /**
     * Toggle the completed status of a todo.
     */
    public function toggle(Request $request, Todo $todo)
    {
        $todo->update(['completed' => !$todo->completed]);

        if ($request->wantsJson()) {
            return response()->json($todo);
        }

        return redirect()->route('todos.index')
            ->with('success', 'Todo status updated.');
    }
}
