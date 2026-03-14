<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view_reports') && !auth()->user()->isSuperAdmin()) abort(403);

        $projectStats = Project::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status');

        $taskStats = Task::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status');

        $priorityStats = Task::select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')->pluck('total', 'priority');

        $monthlyTasks = Task::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as total'),
            DB::raw('sum(case when status = "done" then 1 else 0 end) as completed')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $userPerformance = User::select('users.id', 'users.name')
            ->withCount([
                'tasks as assigned_tasks',
                'tasks as completed_tasks' => fn($q) => $q->where('status', 'done'),
            ])
            ->having('assigned_tasks', '>', 0)
            ->orderByDesc('completed_tasks')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'projectStats', 'taskStats', 'priorityStats', 'monthlyTasks', 'userPerformance'
        ));
    }
}