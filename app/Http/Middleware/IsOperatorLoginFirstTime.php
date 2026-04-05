<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\OperatorStatus;

class IsOperatorLoginFirstTime
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        if (!$user->hasRole('Operator')) {
            return $next($request);
        }

        $operator = $user->operator;

        if (!$operator) {
            return $next($request);
        }

        // ✅ Prevent redirect loop
        if ($request->routeIs(
            'operator.dashboard',
            'operator.profile',
            'operator.profile.message'
        )) {
            return $next($request);
        }

        // ✅ Enum checks (FIXED)
        if ($operator->status === OperatorStatus::PENDING) {
            return redirect()
                ->route('operator.profile', $operator)
                ->with('warning', 'Your account is pending approval.');
        }

        if ($operator->status === OperatorStatus::SUSPENDED) {
            return redirect()
                ->route('operator.profile', $operator)
                ->with('error', 'Your account has been suspended.');
        }

        // First login check
        if ($operator->is_login_first_time == 0) {
            return redirect()
                ->route('operator.profile', $operator)
                ->with('info', 'Welcome! Please complete your profile.');
        }

        return $next($request);
    }
}