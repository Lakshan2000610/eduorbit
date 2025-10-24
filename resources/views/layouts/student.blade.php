<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduOrbit - Student</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">

    <!-- Top Navbar -->
    <nav class="bg-white border-b border-gray-200 px-6 py-3 flex justify-between items-center shadow-sm">
        <div class="text-xl font-bold text-indigo-600">EduOrbit</div>
        
        <div class="flex items-center gap-6">
            <a href="{{ route('student.dashboard') }}" class="text-gray-700 hover:text-indigo-600 font-medium">Home</a>
            <a href="#" class="text-gray-700 hover:text-indigo-600 font-medium">Roadmap</a>
            <a href="#" class="text-gray-700 hover:text-indigo-600 font-medium">Report</a>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button onclick="toggleDropdown()" class="flex items-center focus:outline-none">
                    <img src="{{ asset('images/profile.png') }}" alt="Profile" class="w-8 h-8 rounded-full">
                    <span class="ml-2 font-medium text-gray-700">{{ Auth::user()->name ?? 'Student' }}</span>
                </button>
                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-md py-2">
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="p-6">
        @yield('content')
    </main>

    <script>
        function toggleDropdown() {
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        }
    </script>
</body>
</html>
