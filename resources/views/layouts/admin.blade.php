<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduOrbit - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite('resources/css/app.css')
    <style>
        /* Custom CSS to fix sidebar and header */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 64px; /* Adjusted width to match your design */
            background-color: #4b5e97; /* Indigo-700 */
            color: white;
            transition: width 0.3s;
            z-index: 1000; /* Ensure it stays above other content */
        }

        .sidebar.expanded {
            width: 256px; /* Wider when expanded */
        }

        .sidebar .nav-item {
            display: none;
        }

        .sidebar.expanded .nav-item {
            display: block;
        }

        .top-nav {
            position: fixed;
            top: 0;
            right: 0;
            left: 64px; /* Align with sidebar width */
            height: 60px; /* Height of the navbar */
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #e5e7eb;
            z-index: 1000; /* Ensure it stays above main content */
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0 20px;
        }

        .main-content {
            margin-left: 64px; /* Offset for sidebar */
            margin-top: 60px; /* Offset for top navbar */
            flex: 1;
            padding: 20px;
            overflow-y: auto; /* Allow scrolling for main content */
            height: calc(100vh - 60px); /* Adjust height considering navbar */
        }

        /* Dropdown styling */
        #dropdownMenu {
            position: absolute;
            top: 100%;
            right: 0;
        }

        /* Ensure content doesn't overlap with fixed elements */
        @media (min-width: 768px) {
            .sidebar {
                width: 256px; /* Default expanded width on larger screens */
            }

            .sidebar .nav-item {
                display: block;
            }

            .top-nav {
                left: 256px; /* Adjust for expanded sidebar */
            }

            .main-content {
                margin-left: 256px; /* Adjust for expanded sidebar */
            }
        }
    </style>
</head>
<body class="flex bg-gray-50 min-h-screen">
    <!-- Sidebar -->
    <aside class="sidebar w-64 bg-indigo-700 text-white flex flex-col justify-between" id="sidebar">
        <div>
            <div class="p-6 text-2xl font-bold">EduOrbit</div>
            <nav class="space-y-2 px-4">
                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-3 rounded hover:bg-indigo-600 nav-item">Home</a>
                <a href="{{ route('admin.students') }}" class="block py-2 px-3 rounded hover:bg-indigo-600 nav-item">Students</a>
                <a href="{{ route('admin.teachers') }}" class="block py-2 px-3 rounded hover:bg-indigo-600 nav-item">Teachers</a>
                <a href="{{ route('admin.roadmaps.index') }}" class="block py-2 px-3 rounded hover:bg-indigo-600 nav-item">Roadmap</a>
                <a href="{{ route('admin.pricing-management.index') }}" class="block py-2 px-3 rounded hover:bg-indigo-600 nav-item">Pricing Management</a>
                <a href="#" class="block py-2 px-3 rounded hover:bg-indigo-600 nav-item">Analysis</a>
                <a href="#" class="block py-2 px-3 rounded hover:bg-indigo-600 nav-item">Settings</a>
            </nav>
        </div>
        <div class="p-4 border-t border-indigo-600">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left py-2 px-3 rounded hover:bg-indigo-600 nav-item">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col">
        <!-- Top Navbar -->
        <header class="top-nav">
            <button class="relative">
                <span class="material-icons text-gray-700">notifications</span>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
            </button>
            <div class="relative">
                <button onclick="toggleDropdown()" class="flex items-center focus:outline-none">
                    <img src="{{ asset('images/profile.png') }}" alt="Profile" class="w-8 h-8 rounded-full">
                    <span class="ml-2 font-medium text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</span>
                </button>
                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-md py-2">
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        }

        // Optional: Add sidebar toggle functionality
        document.getElementById('sidebar').addEventListener('click', function(e) {
            if (window.innerWidth < 768 && e.target.tagName !== 'A') {
                this.classList.toggle('expanded');
                document.querySelector('.top-nav').style.left = this.classList.contains('expanded') ? '256px' : '64px';
                document.querySelector('.main-content').style.marginLeft = this.classList.contains('expanded') ? '256px' : '64px';
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('dropdownMenu');
            if (!e.target.closest('.relative')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>