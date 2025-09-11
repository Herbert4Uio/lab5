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

    public function store(Request $request)
    {
        $task = $this->tasks->create([
            'title' => $request->title,
            'completed' => $request->completed
        ]);

        return response()->json($task, 201);
    }
}
