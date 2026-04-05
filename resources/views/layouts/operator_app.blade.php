<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Booking') — Bus Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">

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
            @include('layouts.operator_sidebar')

            <!-- Main content -->
            <div class="flex-1 ml-64 flex flex-col min-h-screen">
                <!-- Top bar -->
                 @include('layouts.header')

                <main class="flex-1 p-2">
                    @yield('content')
                </main>

                <footer class="bg-white border-t border-gray-200 px-8 py-2 text-center text-sm text-gray-400">
                    © {{ date('Y') }} Bus Booking System. All rights reserved.
                </footer>
            </div>
        </div>
    @else
        <main>@yield('content')</main>
    @endauth


    <script src="{{ asset('vendors/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>

    @stack('scripts')
</body>

</html>
