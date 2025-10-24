@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Teachers</h1>
        <p class="text-sm text-gray-600 mt-1">Manage teacher details and registration information.</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 uppercase">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Registered At</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ $teacher->name }}</td>
                            <td class="px-4 py-3">{{ $teacher->email }}</td>
                            <td class="px-4 py-3">{{ $teacher->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="#" class="text-blue-600 hover:underline">Edit</a>
                                <a href="#" class="text-red-600 hover:underline ml-4">Delete</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">No teachers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection