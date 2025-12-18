<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduOrbit - Teacher Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js for dropdowns and sidebar toggle -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

<div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">

    <!-- Left Sidebar -->
    <aside :class="sidebarOpen ? 'w-64' : 'w-0 md:w-16'" 
           class="bg-white border-r border-gray-200 transition-all duration-300 overflow-hidden flex flex-col">

        <!-- Logo Area -->
        <div class="p-6 border-b border-gray-200 flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-xl">EO</span>
            </div>
            <div :class="!sidebarOpen && 'hidden md:hidden'">
                <h1 class="text-xl font-bold text-indigo-600">EduOrbit</h1>
                <p class="text-xs text-gray-600">Teacher Portal</p>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('teacher.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('teacher.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="flex-1" :class="!sidebarOpen && 'md:hidden'">Dashboard</span>
            </a>

            <a href="{{ route('teacher.gigs') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('teacher.gigs*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="flex-1" :class="!sidebarOpen && 'md:hidden'">My Gigs</span>
            </a>

            <a href="{{ route('teacher.requests') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('teacher.requests') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <span class="flex-1" :class="!sidebarOpen && 'md:hidden'">Student Requests</span>
                @if(($pendingRequests ?? 0) > 0)
                    <span class="bg-red-600 text-white text-xs px-2 py-1 rounded-full">{{ $pendingRequests }}</span>
                @endif
            </a>

            <a href="{{ route('teacher.students') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('teacher.students') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="flex-1" :class="!sidebarOpen && 'md:hidden'">My Students</span>
            </a>

            <a href="{{ route('teacher.calendar') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('teacher.calendar') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="flex-1" :class="!sidebarOpen && 'md:hidden'">Session Calendar</span>
            </a>

            <a href="{{ route('teacher.earn') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('teacher.earn') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="flex-1" :class="!sidebarOpen && 'md:hidden'">Earnings & Payments</span>
            </a>

            <a href="{{ route('teacher.messages') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('teacher.messages') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <span class="flex-1" :class="!sidebarOpen && 'md:hidden'">Messages</span>
            </a>

            <a href="{{ route('teacher.notifications') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('teacher.notifications') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="flex-1" :class="!sidebarOpen && 'md:hidden'">Notifications</span>
                @if(($unreadNotifications ?? 0) > 0)
                    <span class="bg-red-600 text-white text-xs px-2 py-1 rounded-full">{{ $unreadNotifications }}</span>
                @endif
            </a>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Top Header -->
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <!-- Hamburger Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Page Title -->
                <h2 class="text-xl font-semibold capitalize">
                    @yield('title', Str::title(str_replace('-', ' ', request()->segment(2) ?? 'dashboard')))
                </h2>
            </div>

            <div class="flex items-center gap-4">
                <!-- Notification Bell -->
                <a href="{{ route('teacher.notifications') }}" class="relative p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    @if(($unreadNotifications ?? 0) > 0)
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-600 rounded-full"></span>
                    @endif
                </a>

                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-3 hover:bg-gray-100 px-3 py-2 rounded-lg transition">
                        <img src="{{ asset('images/profile.png') }}" alt="Profile" class="w-9 h-9 rounded-full ring-2 ring-gray-300">
                        <div class="text-left hidden md:block">
                            <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600">Teacher</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50">
                        <a href="{{ route('teacher.profile') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profile
                        </a>
                        <a href="{{ route('teacher.settings') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Settings
                        </a>
                        <hr class="my-2 border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-gray-100 text-left">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>