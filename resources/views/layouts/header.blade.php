<header class="bg-white border-b border-gray-200 px-8 py-2 flex items-center justify-between sticky top-0 z-30">
    <div>
        <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        @hasSection('breadcrumb')
            <div class="text-sm text-gray-500 mt-0.5">@yield('breadcrumb')</div>
        @endif
    </div>
    <div class="flex items-center space-x-4">
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
    </div>
</header>

<!-- Flash messages -->
@if (session('success'))
<div class="px-8 pt-4">
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center mb-4">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
         </div>
    @endif
    @if (session('error'))
    <div class="px-8 pt-4">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    </div>
    @endif
