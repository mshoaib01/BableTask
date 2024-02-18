<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Display a listing of tasks
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.list', compact('tasks'));
    }

    // Show the form for creating a new task
    public function create()
    {
        return view('tasks.create');
    }

    // Store a newly created task in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required|in:pending,completed',
            'due_date' => 'required|date',
        ]);

        // Add the 'user_id' manually using the ID of the authenticated user
        $validatedData['user_id'] = Auth::id();

        Task::create($validatedData);

        return redirect()->route('tasks.list')->with('success', 'Task created successfully.');
    }

        // Show the form for editing the specified task
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    // Update the specified task in storage
public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required',
        'status' => 'required|in:pending,completed',
        'due_date' => 'required|date',
    ]);

    $task = Task::findOrFail($id);
    $task->update($request->except(['user_id']));

    return redirect()->route('tasks.list')->with('success', 'Task updated successfully.');
}

// Remove the specified task from storage
public function destroy($id)
{
    $task = Task::findOrFail($id);
    $task->delete();

    return redirect()->route('tasks.list')->with('success', 'Task deleted successfully.');
}



}
