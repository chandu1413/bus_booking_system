<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Booking') — Bus Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 3px solid #fff;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>

<body class="h-full bg-gray-50 font-sans">
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    @auth
        <div class="flex h-full">
            <!-- Sidebar -->
             

            <!-- Main content -->
            <div class="flex-1 ml-64x flex flex-col min-h-screen">
                <!-- Top bar -->
                <header
                    class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
                    <div>
                        <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                        @hasSection('breadcrumb')
                            <div class="text-sm text-gray-500 mt-0.5">@yield('breadcrumb')</div>
                        @endif
                    </div>
                    <div class="flex items-center space-x-6">
                        @if (auth()->user()->unreadNotifications->count())
                            <div class="relative">
                                <button class="text-gray-500 hover:text-gray-700 relative">
                                    <i class="fas fa-bell text-lg"></i>
                                    <span
                                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ auth()->user()->unreadNotifications->count() }}</span>
                                </button>
                            </div>
                        @endif
                        <span class="text-sm text-gray-600">{{ date('l, M d Y') }}</span>
                        <div class="flex items-center space-x-3 border-l border-gray-200 pl-6">
                            <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full"
                                alt="{{ auth()->user()->name }}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->roles->first()?->name }}</p>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all">
                                <i class="fas fa-sign-out-alt w-4 mr-2"></i> Sign out
                            </button>
                        </form>
                    </div>
                </header>

                <!-- Flash messages -->
                <div class="px-8 pt-4">
                    @if (session('success'))
                        <div
                            class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center mb-4">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div
                            class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center mb-4">
                            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                        </div>
                    @endif
                </div>

                <main class="flex-1  px-3 py-2">
                    @yield('content')
                </main>

                <footer class="bg-white border-t border-gray-200 px-8 py-4 text-center text-sm text-gray-400">
                    © {{ date('Y') }} Bus Booking System. All rights reserved.
                </footer>
            </div>
        </div>
    @else
        <main>@yield('content')</main>
    @endauth



    @stack('scripts')
</body>

</html>
