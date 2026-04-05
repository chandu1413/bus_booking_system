@extends('layouts.operator_welcome')

@section('title', 'Profile Status')
@section('page-title', 'Operator Profile')

@section('breadcrumb')
    {!! '<a href="' . route('operator.dashboard') . '" class="text-indigo-600 hover:underline">Dashboard</a> / Profile' !!}
@endsection

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-8 bg-white shadow-md rounded-lg border border-gray-100">
    <div class="text-center py-6">
        
        {{-- Status: Suspended --}}
        @if($status == $OperatorStatus::SUSPENDED)
            <div class="text-orange-500 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Account Suspended</h2>
            <p class="mt-2 text-gray-600">Your profile access has been temporarily suspended. Please contact support for more information regarding your account status.</p>

        {{-- Status: Rejected --}}
        @elseif($status == $OperatorStatus::REJECTED)
            <div class="text-red-500 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Application Rejected</h2>
            <p class="mt-2 text-gray-600">We regret to inform you that your application was not approved at this time. Please check your email for specific feedback or requirements.</p>

        {{-- Status: Pending/Under Review (Default) --}}
        @else
            <div class="text-indigo-500 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Profile Updated</h2>
            <p class="mt-2 text-gray-600">Your application has been submitted successfully! Our team is currently reviewing your details. You will receive an update within <b>24 hours</b>.</p>
        @endif

        {{-- <div class="mt-8">
            <a href="{{ route('operator.dashboard') }}" class="inline-flex items-center px-6 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Return to Dashboard
            </a>
        </div> --}}
    </div>
</div>
@endsection
