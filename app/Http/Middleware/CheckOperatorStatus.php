<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\OperatorStatus;

class CheckOperatorStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasRole('Operator')) {
            return $next($request);
        }

        $operator = $user->operator;

        if (!$operator) {
            return $next($request);
        }

        // ✅ Prevent redirect loop
        if ($request->routeIs('operator.profile.message', 'operator.profile')) {
            return $next($request);
        }

        $status = $operator->status;

        // Submitted → wait for approval
        if ($status === OperatorStatus::SUBMITTED) {
            return redirect()
                ->route('operator.profile.message', $operator)
                ->with('error', 'Your profile is pending approval.');
        }

        // Pending or Rejected → update profile
        if (in_array($status, [OperatorStatus::PENDING, OperatorStatus::REJECTED])) {
            return redirect()
                ->route('operator.profile', $operator)
                ->with('error', 'Please update your profile.');
        }

        return $next($request);
    }
}