<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = ActivityLog::with('user')->latest('created_at');
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }
        $logs = $query->paginate(30);
        return view('activity.index', compact('logs'));
    }
}