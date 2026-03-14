@extends('layouts.app')
@section('title', '404 Not Found')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="text-center">
        <div class="text-9xl font-black text-gray-200 mb-4">404</div>
        <h1 class="text-2xl font-bold text-gray-700 mb-2">Page Not Found</h1>
        <p class="text-gray-500 mb-8">The page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">Go Home</a>
    </div>
</div>
@endsection
