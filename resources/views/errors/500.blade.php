@extends('layouts.app')
@section('title', '500 Server Error')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="text-center">
        <div class="text-9xl font-black text-gray-200 mb-4">500</div>
        <h1 class="text-2xl font-bold text-gray-700 mb-2">Server Error</h1>
        <p class="text-gray-500 mb-8">Something went wrong on our end. Please try again later.</p>
        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">Go Home</a>
    </div>
</div>
@endsection
