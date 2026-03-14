<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $request->validate(['title' => 'required|string|max:255']);
        $task->subtasks()->create(['title' => $request->title]);
        return back()->with('success', 'Subtask added.');
    }

    public function update(Request $request, Task $task, Subtask $subtask)
    {
        $this->authorize('update', $task);
        $request->validate([
            'title'        => 'sometimes|string|max:255',
            'is_completed' => 'sometimes|boolean',
        ]);
        $subtask->update($request->only(['title', 'is_completed']));
        return back()->with('success', 'Subtask updated.');
    }

    public function destroy(Task $task, Subtask $subtask)
    {
        $this->authorize('update', $task);
        $subtask->delete();
        return back()->with('success', 'Subtask removed.');
    }
}