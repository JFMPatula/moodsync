<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        try {
            $todos = Todo::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json($todos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch todos'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'task' => 'required|string|max:255',
            ]);

            $todo = Todo::create([
                'task' => $validated['task'],
                'user_id' => Auth::id(),
                'completed' => false
            ]);

            return response()->json($todo, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create todo'], 500);
        }
    }

    public function update(Request $request, Todo $todo)
    {
        try {
            if ($todo->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'task' => 'sometimes|required|string|max:255',
                'completed' => 'sometimes|required|boolean',
            ]);

            $todo->update($validated);

            return response()->json($todo);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update todo'], 500);
        }
    }

    public function destroy(Todo $todo)
    {
        try {
            if ($todo->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $todo->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete todo'], 500);
        }
    }
} 