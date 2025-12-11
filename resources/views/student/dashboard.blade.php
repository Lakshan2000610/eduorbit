{{-- resources/views/student/dashboard.blade.php --}}
@extends('layouts.student')

@section('title', 'My Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    @php
        // You can replace this with real data from your controller later
        $student = Auth::user();
        $grade = $student->grade ?? '11';
        $language = $student->language ?? 'Sinhala';
        $subjectCount = $student->subjects?->count() ?? 7;
        $overallProgress = 68; // You can calculate this from actual progress

        $progressData = [
            ['subject' => 'Mathematics', 'progress' => 72],
            ['subject' => 'Science', 'progress' => 85],
            ['subject' => 'English', 'progress' => 58],
            ['subject' => 'ICT', 'progress' => 44],
            ['subject' => 'History', 'progress' => 61],
        ];

        $performanceData = [
            ['week' => 'Week 1', 'progress' => 20, 'score' => 65],
            ['week' => 'Week 2', 'progress' => 35, 'score' => 72],
            ['week' => 'Week 3', 'progress' => 50, 'score' => 78],
            ['week' => 'Week 4', 'progress' => 68, 'score' => 85],
        ];

        $recommendedMaterials = [
            ['title' => 'Fractions Masterclass', 'type' => 'video', 'subject' => 'Mathematics', 'duration' => '45 min'],
            ['title' => 'States of Matter Worksheet', 'type' => 'document', 'subject' => 'Science', 'duration' => '20 min'],
            ['title' => 'Grammar Essentials', 'type' => 'video', 'subject' => 'English', 'duration' => '30 min'],
        ];
    @endphp

    <!-- Greeting -->
    <div class="mb-10">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">
            Hi {{ Str::ucfirst(explode(' ', $student->name)[0] ?? 'Student') }}!
        </h1>
        <p class="text-xl text-gray-600">
            Keep up the great work! You're making excellent progress.
        </p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Grade & Language -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center gap-4 mb-3">
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422A12.083 12.083 0 0112 21.5c-2.4 0-4.622-.71-6.16-1.922L12 14z"/>
                    </svg>
                </div>
                <span class="text-blue-100 text-sm">Grade & Language</span>
            </div>
            <div class="text-4xl font-bold mb-1">Grade {{ $grade }}</div>
            <div class="text-blue-100">{{ $language }} Medium</div>
        </div>

        <!-- Subjects -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center gap-4 mb-3">
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-green-100 text-sm">Active Subjects</span>
            </div>
            <div class="text-4xl font-bold mb-1">{{ $subjectCount }}</div>
            <div class="text-green-100">You're studying</div>
        </div>

        <!-- Overall Progress -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center gap-4 mb-3">
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <span class="text-yellow-100 text-sm">Overall Progress</span>
            </div>
            <div class="text-4xl font-bold mb-1">{{ $overallProgress }}%</div>
            <div class="text-yellow-100">Keep pushing!</div>
        </div>

        <!-- Last Studied -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center gap-4 mb-3">
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-purple-100 text-sm">Last Studied</span>
            </div>
            <div class="text-2xl font-bold mb-1">Fractions</div>
            <div class="text-purple-100">Mathematics • 2 days ago</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid lg:grid-cols-2 gap-8 mb-10">
        <!-- Subject Progress Chart -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Subject Progress</h2>
            <div class="h-80">
                <canvas id="subjectProgressChart"></canvas>
            </div>
        </div>

        <!-- Progress vs Performance -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Progress & Exam Performance</h2>
            <div class="h-80">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recommended Materials -->
    <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 mb-10">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Recommended for You</h2>
            <a href="#" class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                View All
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach($recommendedMaterials as $item)
                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-xl transition-shadow group cursor-pointer">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-4
                        {{ $item['type'] === 'video' ? 'bg-red-100' : 'bg-blue-100' }}">
                        @if($item['type'] === 'video')
                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 7l4 4-4 4V7z"/>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @endif
                    </div>
                    <h3 class="font-bold text-lg mb-1 group-hover:text-blue-600 transition">{{ $item['title'] }}</h3>
                    <p class="text-gray-600 text-sm mb-3">{{ $item['subject'] }}</p>
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $item['duration'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid md:grid-cols-2 gap-6">
        <a href="#"
           class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl p-8 hover:shadow-2xl transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2">Continue Learning</h3>
                    <p class="text-blue-100 text-lg">Pick up right where you left off</p>
                </div>
                <svg class="w-8 h-8 mt-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <a href="#"
           class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-2xl p-8 hover:shadow-2xl transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2">Get Help from Teachers</h3>
                    <p class="text-green-100 text-lg">Live sessions & 1-on-1 help</p>
                </div>
                <svg class="w-8 h-8 mt-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
    </div>
</div>

{{-- Chart.js for beautiful charts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Subject Progress Bar Chart
    new Chart(document.getElementById('subjectProgressChart'), {
        type: 'bar',
        data: {
            labels: @json(collect($progressData)->pluck('subject')),
            datasets: [{
                label: 'Progress %',
                data: @json(collect($progressData)->pluck('progress')),
                backgroundColor: '#1E88E5',
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } }
            }
        }
    });

    // Progress vs Performance Line Chart
    new Chart(document.getElementById('performanceChart'), {
        type: 'line',
        data: {
            labels: @json(collect($performanceData)->pluck('week')),
            datasets: [
                {
                    label: 'Learning Progress',
                    data: @json(collect($performanceData)->pluck('progress')),
                    borderColor: '#1E88E5',
                    backgroundColor: 'rgba(30, 136, 229, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Exam Score',
                    data: @json(collect($performanceData)->pluck('score')),
                    borderColor: '#43A047',
                    backgroundColor: 'rgba(67, 160, 71, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } }
        }
    });
});
</script>
@endsection