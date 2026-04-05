@extends('layouts.operator_app')
@section('title', 'Operator Dashboard')
@section('page-title', 'Operator Dashboard')
@section('breadcrumb', 'Platform-wide analytics and system health')
@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
   
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Task Status Breakdown -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Task Status Breakdown</h3>
        <div class="space-y-3">
             
        </div>
    </div>

    <!-- Priority Distribution -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Tasks by Priority</h3>
        <div class="space-y-3">
            
        </div>
    </div>

    <!-- Top Performers -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Top Performers</h3>
        <div class="space-y-3">
            
        </div>
    </div>
</div>

<!-- Project Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
    
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mt-6">
    <div class="flex items-center justify-between p-5 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">Recent Activity</h3>
        <a href="{{ route('activity.index') }}" class="text-sm text-indigo-600 hover:underline">View all</a>
    </div>
    <div class="divide-y divide-gray-100">
        
    </div>
</div>
@endsection
