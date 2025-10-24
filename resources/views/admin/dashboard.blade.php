@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <!-- Page Title -->
    <h1 class="text-2xl font-bold text-admin-text mb-6">Dashboard</h1>

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <!-- Total Students -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-sm text-gray-500 font-semibold">Total Students</h2>
                <i class="fas fa-user-graduate text-admin-primary"></i>
            </div>
            <p class="text-3xl font-bold text-gray-800">1,250</p>
        </div>

        <!-- Total Teachers -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-sm text-gray-500 font-semibold">Total Teachers</h2>
                <i class="fas fa-chalkboard-teacher text-admin-primary"></i>
            </div>
            <p class="text-3xl font-bold text-gray-800">75</p>
        </div>

        <!-- Roadmaps Created -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-sm text-gray-500 font-semibold">Roadmaps Created</h2>
                <i class="fas fa-link text-admin-primary"></i>
            </div>
            <p class="text-3xl font-bold text-gray-800">320</p>
        </div>

        <!-- Active Sessions -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-sm text-gray-500 font-semibold">Active Sessions</h2>
                <i class="fas fa-play-circle text-admin-primary"></i>
            </div>
            <p class="text-3xl font-bold text-gray-800">150</p>
        </div>
    </div>

    <!-- Payment Summary -->
    <h2 class="text-xl font-semibold text-admin-text mb-4">Payment Summary</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Total Revenue -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center">
                <h3 class="text-gray-500 font-semibold">Total Revenue</h3>
                <i class="fas fa-dollar-sign text-green-500"></i>
            </div>
            <p class="text-3xl font-bold mt-2 text-gray-800">$15,000</p>
        </div>

        <!-- Total Commission -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center">
                <h3 class="text-gray-500 font-semibold">Total Commission</h3>
                <i class="fas fa-receipt text-admin-primary"></i>
            </div>
            <p class="text-3xl font-bold mt-2 text-gray-800">$1,500</p>
        </div>
    </div>
</div>
@endsection
