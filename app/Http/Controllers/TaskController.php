<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TaskRepositoryInterface;

class TaskController extends Controller
{
    private $tasks;

    public function __construct(TaskRepositoryInterface $tasks)
    {
        $this->tasks = $tasks;
    }

    public function showAll()
    {
        return response()->json($this->tasks->all(), 200);
    }
    public function show($id)
    {
        $task = $this->tasks->find((int) $id);
        
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        
        return response()->json($task, 200);
    }


    public function store(Request $request)
    {
        $task = $this->tasks->create([
            'title' => $request->title,
            'completed' => $request->completed
        ]);

        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'completed' => 'sometimes|boolean'
        ]);

        $task = $this->tasks->update((int) $id, $validated);
        
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        
        return response()->json($task, 200);
    }

    
    public function destroy($id)
    {
        $deleted = $this->tasks->delete((int) $id);
        
        if (!$deleted) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        
        return response()->json(null, 204);
    }
}
