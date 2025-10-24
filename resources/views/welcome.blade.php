{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EduOrbit') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#2F6BFF",
                        accent: "#FFC83D",
                        secondary: "#7AB8FF",
                        "text-primary": "#1E1E1E",
                        "text-secondary": "#595959",
                        background: "#FAFAFA",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                        body: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "12px",
                        lg: "16px",
                        xl: "20px",
                    },
                    boxShadow: {
                        soft: "0 4px 12px 0 rgba(0, 0, 0, 0.05)",
                        "soft-hover": "0 6px 16px 0 rgba(0, 0, 0, 0.08)",
                    },
                },
            },
        };
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="antialiased bg-background text-text-primary">
    <div class="flex min-h-screen w-full flex-col overflow-x-hidden">
        {{-- Header --}}
        <header class="sticky top-0 z-50 w-full border-b border-gray-200/50 bg-background/80 backdrop-blur-sm">
            <div class="container mx-auto flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078L44 7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094L4 42.4379Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h2 class="font-display text-2xl font-bold text-text-primary">EduOrbit</h2>
                </div>
                <nav class="hidden items-center gap-8 md:flex">
                    <a href="/about" class="text-sm font-medium text-text-secondary transition-colors hover:text-primary">About</a>
                    <a href="/roadmap" class="text-sm font-medium text-text-secondary transition-colors hover:text-primary">Road Map</a>
                </nav>
                @if (Route::has('login'))
                <div class="flex items-center gap-3">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded bg-primary/10 px-4 py-2 text-sm font-bold text-primary transition-all hover:bg-primary/20 hover:shadow-soft-hover">Log Out</button>
                        </form>
                    @else
                        <button class="rounded bg-primary/10 px-4 py-2 text-sm font-bold text-primary transition-all hover:bg-primary/20 hover:shadow-soft-hover">
                            <a href="{{ route('login') }}">Log In</a>
                        </button>
                        @if (Route::has('register'))
                            <button class="rounded bg-primary px-4 py-2 text-sm font-bold text-white shadow-soft transition-all hover:scale-105 hover:shadow-soft-hover">
                                <a href="{{ route('register') }}">Sign Up</a>
                            </button>
                        @endif
                    @endauth
                </div>
                @endif
            </div>
        </header>

        <main class="flex-grow">
            {{-- Hero Section --}}
            <section class="relative py-20 md:py-32">
                <div class="container mx-auto px-6 text-center">
                    <div class="mx-auto max-w-3xl">
                        <h1 class="font-display text-4xl font-bold tracking-tight text-text-primary md:text-6xl">Unlock Your Academic Potential</h1>
                        <p class="mt-6 text-lg text-text-secondary">Personalized learning paths, live sessions with expert educators, and AI-powered study tools designed for Sri Lankan students and teachers.</p>
                        <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                            <button class="w-full rounded bg-primary px-8 py-3 text-base font-bold text-white shadow-soft transition-all hover:scale-105 hover:shadow-soft-hover sm:w-auto">
                                <a href="{{ route('register') }}">Get Started Now</a>
                            </button>
                            <button class="flex w-full items-center justify-center gap-2 rounded border border-gray-200 px-8 py-3 text-base font-bold text-text-secondary shadow-soft transition-all hover:bg-gray-50 hover:shadow-soft-hover sm:w-auto">
                                <svg class="h-5 w-5" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039L38.828 6.173C34.521 2.379 29.632 0 24 0C10.745 0 0 10.745 0 24s10.745 24 24 24s24-10.745 24-24c0-3.734-.871-7.229-2.389-10.417z" fill="#FFC107"></path>
                                    <path d="M6.306 14.691c-1.996 3.666-3.306 7.823-3.306 12.309c0 1.916.326 3.754.896 5.481L14.99 22.48l-8.684-7.789z" fill="#FF3D00"></path>
                                    <path d="M24 48c5.632 0 10.521-1.78 14.827-4.827L31.065 34.01C28.784 35.539 26.51 36 24 36c-5.223 0-9.651-3.343-11.303-8H4.896C6.626 36.328 14.666 42 24 42s17.374-5.672 19.104-14h-8.104c-.313 1.25-.712 2.428-1.18 3.529L24 48z" fill="#4CAF50"></path>
                                    <path d="M43.611 20.083L43.611 20.083L29.986 20.083L29.986 20.083L43.611 20.083z" fill="#1976D2" transform="translate(0,0)"></path>
                                </svg>
                                Sign Up with Google
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            {{-- How It Works --}}
            <section class="bg-primary/5 py-20 md:py-32" id="how-it-works">
                <div class="container mx-auto px-6">
                    <div class="text-center">
                        <h2 class="font-display text-3xl font-bold text-text-primary md:text-4xl">How EduOrbit Works</h2>
                        <p class="mx-auto mt-4 max-w-2xl text-text-secondary">A simple, three-step process to begin your personalized learning journey.</p>
                    </div>
                    <div class="mt-16 grid gap-8 md:grid-cols-3">
                        <div class="rounded-lg bg-background p-8 text-center shadow-soft">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <span class="material-symbols-outlined text-4xl">route</span>
                            </div>
                            <h3 class="mt-6 font-display text-xl font-bold text-text-primary">Personalized Roadmaps</h3>
                            <p class="mt-2 text-text-secondary">Explore grade-based roadmaps tailored to the Sri Lankan curriculum, guiding you through key concepts and skills.</p>
                        </div>
                        <div class="rounded-lg bg-background p-8 text-center shadow-soft">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <span class="material-symbols-outlined text-4xl">smart_display</span>
                            </div>
                            <h3 class="mt-6 font-display text-xl font-bold text-text-primary">Engaging Live Sessions</h3>
                            <p class="mt-2 text-text-secondary">Participate in interactive live sessions led by experienced educators, covering a wide range of subjects and topics.</p>
                        </div>
                        <div class="rounded-lg bg-background p-8 text-center shadow-soft">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <span class="material-symbols-outlined text-4xl">psychology</span>
                            </div>
                            <h3 class="mt-6 font-display text-xl font-bold text-text-primary">AI-Powered Study Tools</h3>
                            <p class="mt-2 text-text-secondary">Utilize our AI tools for personalized feedback, practice questions, and insights to enhance your learning experience.</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- For Students --}}
            <section class="py-20 md:py-32" id="students">
                <div class="container mx-auto space-y-24 px-6">
                    <div class="grid items-center gap-12 md:grid-cols-2">
                        <div class="order-2 md:order-1">
                            <h2 class="font-display text-3xl font-bold text-text-primary">For Students: Achieve Your Goals</h2>
                            <p class="mt-4 text-text-secondary">EduOrbit empowers you with personalized roadmaps, live sessions, and AI tools to excel in your studies and achieve your academic goals. See a preview of your potential roadmap.</p>
                            <div class="mt-8 space-y-4 rounded-lg border border-gray-200/80 bg-white p-6 shadow-soft">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-secondary/20 text-secondary">
                                        <span class="material-symbols-outlined">looks_one</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-text-primary">Term 1: Algebra Fundamentals</h4>
                                        <p class="text-sm text-text-secondary">Mastering linear equations and functions.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-secondary/20 text-secondary">
                                        <span class="material-symbols-outlined">looks_two</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-text-primary">Term 2: Geometry & Shapes</h4>
                                        <p class="text-sm text-text-secondary">Understanding geometric principles and proofs.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-secondary/20 text-secondary">
                                        <span class="material-symbols-outlined">looks_3</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-text-primary">Term 3: Advanced Topics</h4>
                                        <p class="text-sm text-text-secondary">Introduction to calculus and statistics.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="order-1 md:order-2">
                            <img alt="Student studying with EduOrbit platform" class="w-full rounded-lg object-cover shadow-soft" src="https://via.placeholder.com/500x400?text=Student+Using+EduOrbit" />
                        </div>
                    </div>
                </div>
            </section>

            {{-- For Teachers --}}
            <section class="bg-primary/5 py-20 md:py-32" id="teachers">
                <div class="container mx-auto px-6">
                    <div class="grid items-center gap-12 md:grid-cols-2">
                        <div class="order-2 md:order-1">
                            <img alt="Teacher using EduOrbit tools" class="w-full rounded-lg object-cover shadow-soft" src="https://via.placeholder.com/500x400?text=Teacher+Using+EduOrbit" />
                        </div>
                        <div class="order-1 md:order-2">
                            <h2 class="font-display text-3xl font-bold text-text-primary">For Teachers: Enhance Your Teaching</h2>
                            <p class="mt-4 text-text-secondary">EduOrbit provides teachers with resources to create engaging lessons, track student progress, and leverage AI to support their teaching efforts.</p>
                            <ul class="mt-6 space-y-3 text-text-secondary">
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span> Create & Assign Custom Roadmaps
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span> Monitor Student Performance
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check_circle</span> Access a Library of Teaching Materials
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {{-- For Schools & Partners --}}
            <section class="py-20 md:py-32" id="schools">
                <div class="container mx-auto px-6">
                    <div class="grid items-center gap-12 md:grid-cols-2">
                        <div class="order-2 md:order-1">
                            <h2 class="font-display text-3xl font-bold text-text-primary">Empower Your Institution with EduOrbit</h2>
                            <p class="mt-4 text-text-secondary">Seamlessly integrate the platform into your curriculum.</p>
                            <ul class="mt-6 space-y-4 text-text-secondary">
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined mt-1 text-primary">school</span>
                                    <div>
                                        <h4 class="font-bold text-text-primary">Streamlined Student Onboarding</h4>
                                        <p>Easily enroll and manage students within the platform.</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined mt-1 text-primary">assignment</span>
                                    <div>
                                        <h4 class="font-bold text-text-primary">Curriculum Alignment Support</h4>
                                        <p>We work with you to align our roadmaps with your specific curriculum needs.</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined mt-1 text-primary">dashboard</span>
                                    <div>
                                        <h4 class="font-bold text-text-primary">Dedicated Admin Dashboards</h4>
                                        <p>Monitor school-wide progress and manage users with powerful admin tools.</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="material-symbols-outlined mt-1 text-primary">brush</span>
                                    <div>
                                        <h4 class="font-bold text-text-primary">Custom Branding Options</h4>
                                        <p>Customize the platform with your school's branding for a cohesive experience.</p>
                                    </div>
                                </li>
                            </ul>
                            <div class="mt-10">
                                <button class="rounded bg-primary px-8 py-3 text-base font-bold text-white shadow-soft transition-all hover:scale-105 hover:shadow-soft-hover">
                                    Partner With Us
                                </button>
                            </div>
                        </div>
                        <div class="order-1 md:order-2">
                            <img alt="School building and diverse group of people" class="w-full rounded-lg object-cover shadow-soft" src="https://via.placeholder.com/500x400?text=School+&+Partners" />
                        </div>
                    </div>
                </div>
            </section>

            {{-- Testimonials --}}
            <section class="bg-primary/5 py-20 md:py-32" id="testimonials">
                <div class="container mx-auto px-6">
                    <div class="text-center">
                        <h2 class="font-display text-3xl font-bold text-text-primary md:text-4xl">Loved by Students & Teachers</h2>
                        <p class="mx-auto mt-4 max-w-2xl text-text-secondary">See what our users are saying about their experience with EduOrbit.</p>
                    </div>
                    <div class="mt-16 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-lg bg-background p-6 shadow-soft">
                            <div class="flex items-center gap-4">
                                <img alt="Nimal Perera" class="h-12 w-12 rounded-full object-cover" src="https://via.placeholder.com/48x48?text=NP" />
                                <div>
                                    <h4 class="font-bold text-text-primary">Nimal Perera</h4>
                                    <p class="text-sm text-text-secondary">Grade 11 Student</p>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center">
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star</span>
                            </div>
                            <p class="mt-4 text-text-secondary">"EduOrbit has transformed my learning experience. The personalized roadmaps are incredibly helpful, and the live sessions are engaging."</p>
                        </div>
                        <div class="rounded-lg bg-background p-6 shadow-soft">
                            <div class="flex items-center gap-4">
                                <img alt="Samantha Silva" class="h-12 w-12 rounded-full object-cover" src="https://via.placeholder.com/48x48?text=SS" />
                                <div>
                                    <h4 class="font-bold text-text-primary">Samantha Silva</h4>
                                    <p class="text-sm text-text-secondary">Math Teacher</p>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center">
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star_half</span>
                            </div>
                            <p class="mt-4 text-text-secondary">"The AI study tools that provide targeted feedback are fantastic. It's a great platform for both students and educators."</p>
                        </div>
                        <div class="rounded-lg bg-background p-6 shadow-soft">
                            <div class="flex items-center gap-4">
                                <img alt="Rohan Fernando" class="h-12 w-12 rounded-full object-cover" src="https://via.placeholder.com/48x48?text=RF" />
                                <div>
                                    <h4 class="font-bold text-text-primary">Rohan Fernando</h4>
                                    <p class="text-sm text-text-secondary">School Administrator</p>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center">
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star</span>
                                <span class="material-symbols-outlined text-accent">star</span>
                            </div>
                            <p class="mt-4 text-text-secondary">"As a teacher, EduOrbit helped me create more effective lessons. The admin features for roadmap management are also very useful."</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Pricing --}}
            <section class="py-20 md:py-32" id="pricing">
                <div class="container mx-auto px-6">
                    <div class="text-center">
                        <h2 class="font-display text-3xl font-bold text-text-primary md:text-4xl">Simple, Transparent Pricing</h2>
                        <p class="mx-auto mt-4 max-w-2xl text-text-secondary">Choose the plan that's right for you. Get started for free.</p>
                    </div>
                    <div class="mx-auto mt-16 grid max-w-sm gap-8 md:max-w-none md:grid-cols-2 lg:grid-cols-3">
                        <div class="flex flex-col rounded-lg border border-gray-200/80 bg-white p-8 shadow-soft">
                            <h3 class="font-display text-xl font-bold text-text-primary">Basic</h3>
                            <p class="mt-2 text-text-secondary">For casual learners</p>
                            <p class="mt-6">
                                <span class="text-5xl font-bold text-text-primary">Free</span>
                            </p>
                            <ul class="mt-8 space-y-3 text-text-secondary">
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check</span>Access to basic roadmaps
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check</span>Limited live sessions
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check</span>Basic AI study tools
                                </li>
                            </ul>
                            <button class="mt-auto w-full rounded bg-primary/10 px-6 py-3 font-bold text-primary transition-colors hover:bg-primary/20">
                                Get Started
                            </button>
                        </div>
                        <div class="flex flex-col rounded-lg border-2 border-primary bg-white p-8 shadow-soft ring-4 ring-primary/20">
                            <div class="flex items-center justify-between">
                                <h3 class="font-display text-xl font-bold text-text-primary">Premium</h3>
                                <span class="rounded-full bg-accent px-3 py-1 text-xs font-bold text-text-primary">Most Popular</span>
                            </div>
                            <p class="mt-2 text-text-secondary">For dedicated students</p>
                            <p class="mt-6 flex items-baseline gap-1">
                                <span class="text-5xl font-bold text-text-primary">$9</span>
                                <span class="text-text-secondary">/ month</span>
                            </p>
                            <ul class="mt-8 space-y-3 text-text-secondary">
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check</span>Access to all roadmaps
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check</span>Unlimited live sessions
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check</span>Advanced AI study tools
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check</span>Priority support
                                </li>
                            </ul>
                            <button class="mt-auto w-full rounded bg-primary px-6 py-3 font-bold text-white shadow-soft transition-all hover:scale-105 hover:shadow-soft-hover">
                                Upgrade to Premium
                            </button>
                        </div>
                        <div class="flex flex-col rounded-lg border border-gray-200/80 bg-white p-8 shadow-soft">
                            <h3 class="font-display text-xl font-bold text-text-primary">For Schools</h3>
                            <p class="mt-2 text-text-secondary">For institutions and groups</p>
                            <p class="mt-6">
                                <span class="text-5xl font-bold text-text-primary">Custom</span>
                            </p>
                            <ul class="mt-8 space-y-3 text-text-secondary">
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check</span>All Premium features
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check</span>Admin dashboard
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check</span>Bulk seat licensing
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">check</span>Dedicated account manager
                                </li>
                            </ul>
                            <button class="mt-auto w-full rounded bg-primary/10 px-6 py-3 font-bold text-primary transition-colors hover:bg-primary/20">
                                Contact Sales
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CTA --}}
            <section class="bg-secondary/10">
                <div class="container mx-auto px-6 py-20">
                    <div class="mx-auto max-w-4xl rounded-lg bg-gradient-to-r from-primary to-secondary p-10 text-center shadow-lg">
                        <h2 class="font-display text-3xl font-bold text-white">Ready to Elevate Your Learning Journey?</h2>
                        <p class="mt-4 text-lg text-white/80">Join EduOrbit today and unlock your full academic potential with our personalized learning platform.</p>
                        <div class="mt-8">
                            <button class="rounded bg-white px-8 py-3 font-bold text-primary shadow-soft transition-all hover:scale-105 hover:shadow-soft-hover">
                                <a href="{{ route('register') }}">Sign Up Now for Free</a>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        {{-- Footer --}}
        <footer class="bg-background">
            <div class="container mx-auto px-6 py-12">
                <div class="flex flex-col items-center justify-between gap-8 md:flex-row">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 42.4379C4 42.4379 14.0962 36.0744 24 41.1692C35.0664 46.8624 44 42.2078 44 42.2078L44 7.01134C44 7.01134 35.068 11.6577 24.0031 5.96913C14.0971 0.876274 4 7.27094 4 7.27094L4 42.4379Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <h2 class="font-display text-2xl font-bold text-text-primary">EduOrbit</h2>
                    </div>
                    <nav class="flex flex-wrap justify-center gap-6 text-sm font-medium text-text-secondary">
                        <a class="transition-colors hover:text-primary" href="/about">About</a>
                        <a class="transition-colors hover:text-primary" href="#">Contact</a>
                        <a class="transition-colors hover:text-primary" href="#">Terms of Service</a>
                        <a class="transition-colors hover:text-primary" href="#">Privacy Policy</a>
                    </nav>
                </div>
                <div class="mt-8 border-t border-gray-200/50 pt-8 text-center text-sm text-text-secondary">
                    <p>© 2025 EduOrbit. All rights reserved. Made with ❤️ in Sri Lanka.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>