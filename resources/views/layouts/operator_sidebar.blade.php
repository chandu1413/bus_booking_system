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
                    <a href="{{ route('dashboard') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home w-5 mr-3"></i> Dashboard
                    </a>
                    <a href="{{ route('operator.buses.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                        <i class="fas fa-folder-open w-5 mr-3"></i> Buses
                    </a>
                    <a href="{{ route('projects.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                        <i class="fas fa-folder-open w-5 mr-3"></i> Projects
                    </a>
                     
                    <a href="{{ route('activity.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('activity.*') ? 'active' : '' }}">
                        <i class="fas fa-history w-5 mr-3"></i> Activity
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users w-5 mr-3"></i> Users
                    </a>
                    @if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                        <div class="pt-4 pb-2">
                            <p class="text-indigo-300 text-xs font-semibold uppercase tracking-wider px-4">Administration
                            </p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}"
                            class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar w-5 mr-3"></i> Admin Panel
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                            class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users w-5 mr-3"></i> Users
                        </a>

                        <a href="{{ route('admin.reports.index') }}"
                            class="sidebar-item flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-pie w-5 mr-3"></i> Reports
                        </a>
                    @endif
                </nav>
                <div class="p-4 border-t border-indigo-600">
                    <div class="flex items-center space-x-3 mb-3">
                        <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full"
                            alt="{{ auth()->user()->name }}">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-indigo-300 truncate">{{ auth()->user()->roles->first()?->name }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center px-3 py-2 text-sm text-indigo-200 hover:text-white hover:bg-indigo-600 rounded-lg transition-all">
                            <i class="fas fa-sign-out-alt w-5 mr-2"></i> Sign out
                        </button>
                    </form>
                </div>
            </div>