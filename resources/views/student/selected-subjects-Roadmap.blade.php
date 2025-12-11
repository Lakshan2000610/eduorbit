{{-- resources/views/student/selected-subjects-Roadmap.blade.php --}}
@extends('layouts.student')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    @php
        $user = Auth::user();
        $selections = $selections ?? collect();
    @endphp

    <!-- Header -->
    <div class="mb-10 text-center">
        <h1 class="text-5xl font-bold text-gray-900 mb-4">My Learning Roadmap</h1>
        <p class="text-xl text-gray-600">
            Track your progress across all your subjects • 
            <span class="font-medium text-blue-600">Grade {{ $grade ?? '—' }} • {{ $language ?? '—' }} Medium</span>
        </p>
    </div>

    <!-- Change Selection Button -->
    <div class="text-center mb-10">
        <a href="{{ route('student.select-subjects') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition">
            Change Grade / Subjects
        </a>
    </div>

    @if($selections->isEmpty())
        <!-- Empty State -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-16 text-center shadow-inner">
            <div class="w-24 h-24 mx-auto mb-6 bg-white/60 backdrop-blur-sm rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-700 mb-3">No subjects selected yet</h3>
            <p class="text-gray-600 mb-8">Start your learning journey by selecting your grade and subjects.</p>
            <a href="{{ route('student.select-subjects') }}"
               class="px-8 py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg transition transform hover:scale-105">
                Select Subjects Now
            </a>
        </div>
    @else
        <!-- Subject Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            @foreach($selections as $selection)
                @php
                    $subject = $selection->subject;
                    // Use DB-stored progress instead of dummy random value
                    $progress = isset($selection->progress) ? (int) $selection->progress : 0;
                    $isMain = is_null($subject->parent_id);

                    // Fixed ternary with parentheses
                    $gradientClass = $progress >= 70 
                        ? 'bg-gradient-to-br from-green-500 to-green-600'
                        : ($progress >= 40 
                            ? 'bg-gradient-to-br from-blue-500 to-blue-600'
                            : 'bg-gradient-to-br from-gray-500 to-gray-600');
                @endphp

                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                    <!-- Header -->
                    <div class="p-6 text-white {{ $gradientClass }}">
                        <div class="flex items-start justify-between mb-5">
                            <div>
                                <h3 class="text-2xl font-bold mb-2">{{ $subject->subject_name }}</h3>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium backdrop-blur-sm
                                    {{ $isMain ? 'bg-white/20' : 'bg-yellow-400/30 text-yellow-900' }}">
                                    {{ $isMain ? 'Main Subject' : 'Optional Subject' }}
                                </span>
                            </div>
                            <div class="p-3 bg-white/20 rounded-xl">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Progress -->
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-2">
                                <span>Progress</span>
                                <span class="font-bold">{{ $progress }}%</span>
                            </div>
                            <div class="h-3 bg-white/25 rounded-full overflow-hidden">
                                <div class="h-full bg-white rounded-full transition-all duration-700"
                                     style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                        <p class="text-sm opacity-90">24 topics • Grade {{ $grade }}</p>
                    </div>

                    <!-- Body -->
                    <div class="p-6">
                        <div class="mb-6">
                            <p class="text-sm text-gray-600 mb-3">Recent topics:</p>
                            <div class="space-y-2">
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    <span>Introduction to Algebra</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                                    <span>Basic Geometry</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('student.progress', $subject->id) }}"
                               class="flex-1 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 text-center flex items-center justify-center gap-2 shadow-lg transition transform hover:scale-105">
                                View Full Roadmap
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                            <a href="#"
                               class="p-3 border-2 border-blue-500 text-blue-600 rounded-xl hover:bg-blue-50 transition"
                               title="Find a Teacher">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Achievement Banner -->
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-3xl p-10 text-white shadow-2xl text-center">
            <div class="max-w-3xl mx-auto">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                </div>
                <h2 class="text-4xl font-bold mb-4">Great Job!</h2>
                <p class="text-2xl opacity-95">
                    You've explored <strong>{{ $selections->count() * 6 }}</strong> topics so far!
                </p>
                <p class="mt-4 text-yellow-100">Keep learning — you're doing amazing!</p>
            </div>
        </div>
    @endif
</div>
@endsection