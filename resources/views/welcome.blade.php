{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Student Portal - Learn Smarter, Grow Faster</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#2563eb",    // blue-600
                        secondary: "#3b82f6",  // blue-500
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased bg-gradient-to-br from-blue-50 via-white to-yellow-50 min-h-screen">

<div class="min-h-screen flex flex-col">

    <!-- Header -->
    <header class="border-b border-gray-200 bg-white/80 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5c-2.4 0-4.622-.71-6.16-1.922L12 14z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-blue-600 font-bold text-lg">Student Portal</div>
                        <div class="text-xs text-gray-500">Learn Smarter, Grow Faster</div>
                    </div>
                </div>

                <div class="flex gap-3">
                    @if (Route::has('login'))
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition shadow-sm">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                    Your Learning Journey Starts Here
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Follow the Sri Lankan syllabus step-by-step, track your progress, and connect with expert teachers whenever you need help.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition shadow-lg flex items-center gap-2">
                        Get Started Free
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-blue-600 border-2 border-blue-200 rounded-xl hover:border-blue-300 transition">
                        I Have an Account
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-3xl p-8 shadow-2xl transform rotate-2">
                    <div class="bg-white rounded-2xl p-8 transform -rotate-2 text-center">
                        <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6 text-5xl">
                            
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Grade 1–13</h3>
                        <p class="text-gray-600">Complete Sri Lankan Syllabus</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-4xl font-bold text-center mb-12">Why Students Love Our Platform</h2>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Follow Your Syllabus Step-by-Step</h3>
                <p class="text-gray-600">
                    Every topic organized exactly as your school syllabus. Never wonder what to learn next.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Track Progress & Learn Smarter</h3>
                <p class="text-gray-600">
                    Visual progress tracking keeps you motivated. See exactly how much you’ve mastered.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Connect with Teachers When You Need Help</h3>
                <p class="text-gray-600">
                    Stuck on a topic? Request one-on-one sessions with expert teachers instantly.
                </p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl p-12 text-white text-center">
            <h2 class="text-4xl font-bold mb-12">Getting Started is Easy</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                        1
                    </div>
                    <h3 class="text-xl font-bold mb-2">Sign Up Free</h3>
                    <p class="text-blue-100">Create your account in seconds</p>
                </div>
                <div>
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                        2
                    </div>
                    <h3 class="text-xl font-bold mb-2">Choose Your Grade & Subjects</h3>
                    <p class="text-blue-100">Tell us what you're studying</p>
                </div>
                <div>
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                        3
                    </div>
                    <h3 class="text-xl font-bold mb-2">Start Learning!</h3>
                    <p class="text-blue-100">Follow your personalized roadmap</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-200 bg-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422A12.083 12.083 0 0112 21.5c-2.4 0-4.622-.71-6.16-1.922L12 14z"></path>
                            </svg>
                        </div>
                        <span class="text-blue-600 font-bold">Student Portal</span>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Empowering Sri Lankan students to achieve their academic goals.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Platform</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-blue-600">How it Works</a></li>
                        <li><a href="#" class="hover:text-blue-600">Features</a></li>
                        <li><a href="#" class="hover:text-blue-600">Subjects</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Support</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-blue-600">Help Center</a></li>
                        <li><a href="#" class="hover:text-blue-600">Contact Us</a></li>
                        <li><a href="#" class="hover:text-blue-600">FAQs</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-blue-600">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-blue-600">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-blue-600">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-200 mt-8 pt-8 text-center text-sm text-gray-600">
                <p>© 2025 Student Portal. All rights reserved. Made with ❤️ in Sri Lanka</p>
            </div>
        </div>
    </footer>

</div>
</body>
</html>