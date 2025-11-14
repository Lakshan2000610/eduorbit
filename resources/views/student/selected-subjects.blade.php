@extends('layouts.student')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Your Subjects for Grade {{ $grade ?? '-' }}</h1>
            <p class="text-gray-600 text-sm">
                Select a subject to view its topics and start learning.
                <span class="ml-2 text-blue-600 font-medium">Language: {{ $language ?? '-' }}</span>
            </p>
        </div>
        <div class="mt-3 sm:mt-0">
            <a href="{{ route('student.select-subjects') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                Change Selection
            </a>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-8">
        <div class="relative flex-1">
            <input type="text" placeholder="🔍 Find a subject" 
                class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M9.5 17a7.5 7.5 0 1 1 7.5-7.5A7.51 7.51 0 0 1 9.5 17z" />
            </svg>
        </div>
    </div>

    {{-- Subject Cards --}}
    @if($selections->isEmpty())
        <div class="bg-white shadow-sm rounded-xl p-8 text-center">
            <p class="text-gray-500 mb-4">No subjects selected yet.</p>
            <a href="{{ route('student.select-subjects') }}" 
               class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                Select Subjects
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($selections as $sel)
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                                 stroke="currentColor" class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold text-gray-800">{{ $sel->subject->subject_name ?? '—' }}</h3>
                            <p class="text-sm text-gray-500">{{ $sel->subject->subject_code ?? '' }}</p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 mb-4">
                        {{ $sel->subject->description ?? 'Learn and explore this subject’s topics.' }}
                    </p>

                    <div class="h-1.5 w-full bg-gray-200 rounded-full mb-4">
                        <div class="h-1.5 bg-yellow-400 rounded-full w-1/4"></div>
                    </div>

                    <div class="flex justify-between gap-2">
                        <a href="#" 
                           class="flex-1 text-center bg-blue-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                           View Topics
                        </a>
                        <a href="#" 
                           class="flex-1 text-center bg-yellow-400 text-white py-2 rounded-lg text-sm font-medium hover:bg-yellow-500 transition">
                           Find a Tutor
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
