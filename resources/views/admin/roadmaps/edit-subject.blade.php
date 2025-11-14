@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <div class="mb-6">
        <button onclick="history.back()" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:underline">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <h1 class="text-2xl font-bold mt-4">Edit Subject</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.roadmaps.update-subject', $subject->id) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                    <select name="grade" required class="w-full px-3 py-2 border border-gray-200 rounded-lg">
                        <option value="">Select Grade</option>
                        @for ($i = 1; $i <= 13; $i++)
                            <option value="Grade {{ $i }}" {{ (old('grade', $subject->grade) == "Grade $i") ? 'selected' : '' }}>
                                Grade {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                    <select name="language" required class="w-full px-3 py-2 border border-gray-200 rounded-lg">
                        <option value="Sinhala" {{ old('language', $subject->language) == 'Sinhala' ? 'selected' : '' }}>Sinhala</option>
                        <option value="English" {{ old('language', $subject->language) == 'English' ? 'selected' : '' }}>English</option>
                        <option value="Tamil" {{ old('language', $subject->language) == 'Tamil' ? 'selected' : '' }}>Tamil</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject Name</label>
                    <input name="subject_name" value="{{ old('subject_name', $subject->subject_name) }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject Code</label>
                    <input name="subject_code" value="{{ old('subject_code', $subject->subject_code) }}" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg"
                           {{ $lockCode ? 'readonly' : '' }} />
                    @if($lockCode)
                        <p class="text-xs text-gray-500 mt-1">Subject code cannot be changed because this subject has topics or subsubjects.</p>
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" class="w-full px-3 py-2 border border-gray-200 rounded-lg">{{ old('description', $subject->description) }}</textarea>
            </div>

            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="status" value="active" {{ (old('status', $subject->status) == 'active') ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex justify-end gap-2">
                <button type="submit" class="bg-admin-primary text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection