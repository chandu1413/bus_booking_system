@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
                <i class="fas fa-rocket text-indigo-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white">ProjectFlow</h1>
            <p class="text-indigo-200 mt-1">Sign in to your workspace</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <form method="POST" action="/login">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', 'admin@projectflow.com') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" value="password" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm">
                        <input type="checkbox" name="remember" class="mr-2 rounded"> Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">Forgot password?</a>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </form>
            <p class="text-center text-sm text-gray-600 mt-6">Don't have an account? <a href="{{ route('register') }}" class="text-indigo-600 font-medium hover:underline">Create one</a></p>
        </div>

        <!-- Test Credentials -->
        <div class="mt-6 bg-white/10 backdrop-blur rounded-xl p-4 text-white text-sm">
            <p class="font-semibold mb-2"><i class="fas fa-key mr-1"></i> Test Credentials (password: <code class="bg-white/20 px-1 rounded">password</code>)</p>
            <div class="space-y-1 text-indigo-100">
                <p>🔴 SuperAdmin: <code>superadmin@projectflow.com</code></p>
                <p>🟠 Admin: <code>admin@projectflow.com</code></p>
                <p>🟢 Member: <code>alice@projectflow.com</code></p>
            </div>
        </div>
    </div>
</div>
@endsection
