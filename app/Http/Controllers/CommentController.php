<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Task;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(private ActivityLogService $logService) {}

    public function store(StoreCommentRequest $request, Task $task)
    {
        $comment = $task->comments()->create([
            'user_id' => auth()->id(),
            'body'    => $request->body,
        ]);
        $this->logService->log(auth()->user(), $task, 'commented', 'Added a comment');
        return back()->with('success', 'Comment posted.');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        $request->validate(['body' => 'required|string|max:5000']);
        $comment->update(['body' => $request->body]);
        return back()->with('success', 'Comment updated.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}