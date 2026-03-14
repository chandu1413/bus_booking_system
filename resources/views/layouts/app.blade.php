<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ProjectFlow') — Project Management SaaS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-item:hover { background: rgba(255,255,255,0.1); }
        .sidebar-item.active { background: rgba(255,255,255,0.2); border-left: 3px solid #fff; }
    </style>
    @stack('head')
</head>
<body class="h-full bg-gray-50 font-sans">

@auth
<div class="flex h-full">
    <!-- Sidebar -->
    <div class="w-64 bg-gradient-to-b from-indigo-700 to-indigo-900 text-white flex flex-col fixed h-full z-40">
        <div class="p-6 border-b border-indigo-600">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-rocket text-indigo-600 text-lg"></i>
                </div>
                <span class="text-xl font-bold">ProjectFlow</span>
            </a>
        </div>
        <nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home w-5 mr-3"></i> Dashboard
            </a>
            <a href="{{ route('projects.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <i class="fas fa-folder-open w-5 mr-3"></i> Projects
            </a>
            <a href="{{ route('activity.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('activity.*') ? 'active' : '' }}">
                <i class="fas fa-history w-5 mr-3"></i> Activity
            </a>
            @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
            <div class="pt-4 pb-2">
                <p class="text-indigo-300 text-xs font-semibold uppercase tracking-wider px-4">Administration</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-bar w-5 mr-3"></i> Admin Panel
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users w-5 mr-3"></i> Users
            </a>
            @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.roles.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <i class="fas fa-shield-alt w-5 mr-3"></i> Roles
            </a>
            @endif
            <a href="{{ route('admin.reports.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-pie w-5 mr-3"></i> Reports
            </a>
            @endif
        </nav>
        <div class="p-4 border-t border-indigo-600">
            <div class="flex items-center space-x-3 mb-3">
                <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full" alt="{{ auth()->user()->name }}">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-indigo-300 truncate">{{ auth()->user()->roles->first()?->name }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left flex items-center px-3 py-2 text-sm text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg transition-all">
                    <i class="fas fa-sign-out-alt w-5 mr-2"></i> Sign out
                </button>
            </form>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex-1 ml-64 flex flex-col min-h-screen">
        <!-- Top bar -->
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                @hasSection('breadcrumb')<div class="text-sm text-gray-500 mt-0.5">@yield('breadcrumb')</div>@endif
            </div>
            <div class="flex items-center space-x-4">
                @if(auth()->user()->unreadNotifications->count())
                <div class="relative">
                    <button class="text-gray-500 hover:text-gray-700 relative">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ auth()->user()->unreadNotifications->count() }}</span>
                    </button>
                </div>
                @endif
                <span class="text-sm text-gray-600">{{ date('l, M d Y') }}</span>
            </div>
        </header>

        <!-- Flash messages -->
        <div class="px-8 pt-4">
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center mb-4">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
            @endif
        </div>

        <main class="flex-1 px-8 py-4">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-gray-200 px-8 py-4 text-center text-sm text-gray-400">
            © {{ date('Y') }} ProjectFlow. Built with ❤️ by <a href="https://laracopilot.com/" target="_blank" class="hover:underline text-indigo-500">LaraCopilot</a>
        </footer>
    </div>
</div>
@else
<main>@yield('content')</main>
@endauth

@stack('scripts')
</body>
</html>
