<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;

class ApiTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        if (is_null($tasks->first())) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No product found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'Tasks are retrieved successfully.',
            'data' => $tasks,
        ];

        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'description' => 'required',
        'status' => 'required|in:pending,completed',
        'due_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'errors' => $validator->errors(),
            ], 403); 
        }

        $validatedData = $validator->validated();

        // Add the 'user_id' manually using the ID of the authenticated user
        $validatedData['user_id'] = Auth::id();

        $task = Task::create($validatedData);

        $response = [
            'status' => 'success',
            'message' => 'Task is added successfully.',
            'data' => $task,
        ];

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);
  
        if (is_null($task)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product is not found!',
            ], 200);
        }

        $response = [
            'status' => 'success',
            'message' => 'Task is retrieved successfully.',
            'data' => $task,
        ];
        
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required|in:pending,completed',
            'due_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'errors' => $validator->errors(),
            ], 403);
        }

        $validatedData = $validator->validated();

        // Use unset to remove 'user_id' from the array if it exists.
        unset($validatedData['user_id']);

        $task = Task::find($id);

        if (is_null($task)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Task is not found!',
            ], 200);
        }

        // Directly pass the $validatedData array to the update method.
        $task->update($validatedData);

        $response = [
            'status' => 'success',
            'message' => 'Product is updated successfully.',
            'data' => $task,
        ];

        return response()->json($response, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
  
        if (is_null($task)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product is not found!',
            ], 200);
        }

        task::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Product is deleted successfully.'
            ], 200);
    }
}
