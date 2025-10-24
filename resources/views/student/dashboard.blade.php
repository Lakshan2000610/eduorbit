{{-- resources/views/student/dashboard.blade.php --}}
@extends('layouts.student')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800">Welcome, {{ Auth::user()->name }}!</h1>
    <p class="mt-4 text-gray-600">This is your student dashboard.</p>
@endsection
