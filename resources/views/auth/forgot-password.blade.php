@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-600 to-purple-800 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Reset Password</h2>
        <p class="text-gray-600 mb-6">Enter your email and we'll send you a reset link.</p>
        @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg">Send Reset Link</button>
        </form>
        <p class="text-center text-sm mt-4"><a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Back to login</a></p>
    </div>
</div>
@endsection
