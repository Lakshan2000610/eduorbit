<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduOrbit - Student</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Top Navbar -->
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Logo -->
                <div class="flex items-center gap-4">
                    <a href="{{ Route::has('student.dashboard') ? route('student.dashboard') : url('/') }}" class="flex items-center gap-3">
                        <img src="/favicon.ico" alt="logo" class="h-10 w-10 rounded">
                        <span class="font-bold text-gray-800 text-lg">EduOrbit</span>
                    </a>
                </div>

                <!-- Right: Links + icons + profile -->
                <div class="flex items-center gap-4">
                    <nav class="hidden sm:flex items-center gap-4">
                        <a href="{{ Route::has('student.dashboard') ? route('student.dashboard') : url('/') }}" class="text-gray-700 hover:text-indigo-600">Home</a>

                        <a href="{{ Route::has('student.selected-subjects') ? route('student.selected-subjects') : '#' }}" class="text-gray-700 hover:text-indigo-600">Roadmap</a>

                        @if(Route::has('teacher.dashboard'))
                            <a href="{{ route('teacher.dashboard') }}" class="text-gray-700 hover:text-indigo-600">Teacher</a>
                        @else
                            <a href="#" class="text-gray-700 hover:text-indigo-600">Teacher</a>
                        @endif

                        @if(Route::has('student.schedules'))
                            <a href="{{ route('student.schedules') }}" class="text-gray-700 hover:text-indigo-600">Schedules</a>
                        @else
                            <a href="#" class="text-gray-700 hover:text-indigo-600">Schedules</a>
                        @endif
                    </nav>

                    <!-- Notification icon -->
                    <button class="relative p-2 rounded hover:bg-gray-100" title="Notifications" onclick="document.getElementById('notif-popup')?.classList.toggle('hidden')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        {{-- optional badge --}}
                        {{-- <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 text-xs font-bold text-white bg-red-500 rounded-full">3</span> --}}
                    </button>

                    <!-- Notifications popup (hidden by default) -->
                    <div id="notif-popup" class="hidden absolute right-6 mt-12 w-80 bg-white border rounded shadow-lg z-40">
                        <div class="p-3 text-sm text-gray-600">No new notifications</div>
                    </div>

                    <!-- Profile -->
                    <div class="relative">
                        <button id="profile-btn" class="flex items-center gap-2 p-1 rounded hover:bg-gray-100" onclick="document.getElementById('profile-menu').classList.toggle('hidden')">
                            @if(auth()->user() && isset(auth()->user()->profile_photo_url))
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="avatar" class="h-9 w-9 rounded-full object-cover">
                            @else
                                <div class="h-9 w-9 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}
                                </div>
                            @endif
                        </button>

                        <!-- Dropdown -->
                        <div id="profile-menu" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50">
                            <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                            <a href="{{ Route::has('settings') ? route('settings') : '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <script>
        // close dropdowns when clicking outside
        document.addEventListener('click', function(e){
            const profileBtn = document.getElementById('profile-btn');
            const menu = document.getElementById('profile-menu');
            const notif = document.getElementById('notif-popup');
            if (menu && profileBtn && !profileBtn.contains(e.target) && !menu.contains(e.target)) menu.classList.add('hidden');
            if (notif && !notif.contains(e.target)) notif.classList.add('hidden');
        });
    </script>

</body>
</html>
