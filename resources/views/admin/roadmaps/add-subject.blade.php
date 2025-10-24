@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-admin-text">Add Subject</h1>
        <p class="text-sm text-admin-text-secondary mt-1">
            Add a new subject for the selected grade.
        </p>
    </div>

    <!-- Subject Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Subject Details</h2>

        <form action="{{ route('admin.roadmaps.store-subject') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Grade and Language (Same Line, Read-Only) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                    <input type="text" id="grade" name="grade" value="{{ request()->get('grade') }}" readonly
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-700 focus:outline-none transition-all">
                </div>
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                    <input type="text" id="language" name="language" value="{{ request()->get('language') }}" readonly
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-700 focus:outline-none transition-all">
                </div>
            </div>

            <!-- Subject Name and Code (Same Line) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="subject_name" class="block text-sm font-medium text-gray-700 mb-1">Subject Name</label>
                    <input type="text" id="subject_name" name="subject_name" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-admin-primary focus:border-transparent transition-all"
                           placeholder="Enter subject name">
                </div>
                <div>
                    <label for="subject_code" class="block text-sm font-medium text-gray-700 mb-1">Subject Code</label>
                    <input type="text" id="subject_code" name="subject_code" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-admin-primary focus:border-transparent transition-all"
                           placeholder="Enter subject code (e.g., MATH101)">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-admin-primary focus:border-transparent transition-all"
                          placeholder="Enter subject description"></textarea>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" required
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-admin-primary focus:border-transparent transition-all">
                    <option value="">Select Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                        class="bg-admin-primary hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg w-full flex items-center justify-center gap-2 transition">
                    <i class="fas fa-save"></i> Save Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection