<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $projects = Cache::remember("user_projects_{$user->id}", 300, function () use ($user) {
            return Project::forUser($user)
                ->with(['owner', 'tasks'])
                ->latest()
                ->take(6)
                ->get();
        });

        $myTasks = Task::where('assignee_id', $user->id)
            ->whereNotIn('status', ['done'])
            ->with('project')
            ->orderBy('due_date')
            ->take(10)
            ->get();

        $overdueTasks = Task::where('assignee_id', $user->id)
            ->where('due_date', '<', today())
            ->whereNotIn('status', ['done'])
            ->count();

        $recentActivity = ActivityLog::with('user')
            ->latest('created_at')
            ->take(10)
            ->get();

        $stats = [
            'total_projects'    => Project::forUser($user)->count(),
            'active_projects'   => Project::forUser($user)->where('status', 'Active')->count(),
            'my_tasks'          => Task::where('assignee_id', $user->id)->whereNotIn('status', ['done'])->count(),
            'completed_tasks'   => Task::where('assignee_id', $user->id)->where('status', 'done')->count(),
            'overdue_tasks'     => $overdueTasks,
        ];

        return view('dashboard', compact('projects', 'myTasks', 'stats', 'recentActivity'));
    }
}