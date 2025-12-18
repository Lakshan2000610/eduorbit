@extends('layouts.teacher')

@section('title', 'Student Requests')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold">Student Learning Requests</h1>
        <p class="text-gray-600 mt-2">Review and respond to student tutoring requests</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-3xl font-bold text-orange-600">5</p>
            <p class="text-gray-600">Pending Requests</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-3xl font-bold text-green-600">12</p>
            <p class="text-gray-600">Accepted</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-3xl font-bold">17</p>
            <p class="text-gray-600">Total Requests</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <img src="{{ asset('images/student1.jpg') }}" class="w-16 h-16 rounded-full">
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <div>
                                <h4 class="font-semibold">Amara Perera</h4>
                                <p class="text-sm text-gray-600">Grade 11</p>
                            </div>
                            <p class="text-sm text-gray-500">Dec 15, 2025</p>
                        </div>
                        <div class="mt-3 space-x-2">
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">Physics</span>
                            <span class="bg-gray-100 px-3 py-1 rounded-full text-sm">Mechanics</span>
                        </div>
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm">Hi, I'm struggling with Newton's laws and projectile motion. Can you help?</p>
                        </div>
                        <div class="mt-4 flex gap-3">
                            <button class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">Accept</button>
                            <button class="flex-1 bg-gray-200 py-2 rounded-lg hover:bg-gray-300">Reject</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Repeat for more requests -->
    </div>
</div>
@endsection