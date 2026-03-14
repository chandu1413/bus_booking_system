<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin()) {
            abort(403, 'Access denied.');
        }

        $stats = Cache::remember('admin_stats', 600, function () {
            return [
                'total_users'           => User::count(),
                'active_users'          => User::where('is_active', true)->count(),
                'total_projects'        => Project::count(),
                'active_projects'       => Project::where('status', 'Active')->count(),
                'completed_projects'    => Project::where('status', 'Completed')->count(),
                'on_hold_projects'      => Project::where('status', 'OnHold')->count(),
                'total_tasks'           => Task::count(),
                'completed_tasks'       => Task::where('status', 'done')->count(),
                'overdue_tasks'         => Task::where('due_date', '<', today())->whereNotIn('status', ['done'])->count(),
                'tasks_todo'            => Task::where('status', 'todo')->count(),
                'tasks_in_progress'     => Task::where('status', 'in_progress')->count(),
                'tasks_in_review'       => Task::where('status', 'in_review')->count(),
            ];
        });

        $tasksByPriority = Task::select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->pluck('total', 'priority');

        $projectsPerMonth = Project::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('count(*) as total')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        $topUsers = User::withCount(['tasks as completed_tasks' => fn($q) => $q->where('status', 'done')])
            ->orderByDesc('completed_tasks')
            ->take(5)
            ->get();

        $recentActivity = ActivityLog::with('user')
            ->latest('created_at')
            ->take(15)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'tasksByPriority', 'projectsPerMonth', 'topUsers', 'recentActivity'
        ));
    }
}