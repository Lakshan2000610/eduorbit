@extends('layouts.teacher')

@section('title', 'My Students')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold">My Students</h1>
        <p class="text-gray-600 mt-2">View and manage your active students</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-3xl font-bold">18</p>
            <p class="text-gray-600">Active Students</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-3xl font-bold">142</p>
            <p class="text-gray-600">Total Sessions</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-3xl font-bold">8</p>
            <p class="text-gray-600">Upcoming</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-3xl font-bold">4.9</p>
            <p class="text-gray-600">Avg. Rating</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Student Card -->
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition cursor-pointer">
            <div class="p-6 flex items-start gap-4">
                <img src="{{ asset('images/student2.jpg') }}" class="w-20 h-20 rounded-full">
                <div class="flex-1">
                    <h4 class="font-semibold">Nethmi Fernando</h4>
                    <p class="text-sm text-gray-600">Grade 12 • Physics</p>
                    <div class="mt-3">
                        <div class="flex justify-between text-sm mb-1">
                            <span>Progress</span>
                            <span>75%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button class="flex-1 bg-gray-100 py-2 rounded text-sm">View Profile</button>
                        <button class="px-4 bg-gray-100 rounded"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection