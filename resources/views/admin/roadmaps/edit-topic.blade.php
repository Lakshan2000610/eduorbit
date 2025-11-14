
@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <div class="mb-6">
        <button onclick="history.back()" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:underline">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <h1 class="text-2xl font-bold mt-4">Edit Topic</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.roadmaps.update-topic', $topic->id) }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Topic Code</label>
                <input name="topic_suffix" value="{{ old('topic_suffix', preg_replace('/^.*-/', '', $topic->topic_code)) }}"
                       class="w-full px-3 py-2 border rounded-lg"
                       {{ $lockCode ? 'readonly' : '' }}>
                @if($lockCode)
                    <p class="text-xs text-gray-500 mt-1">Cannot change code — topic has subtopics.</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Topic Name</label>
                <input name="topic_name" value="{{ old('topic_name', $topic->topic_name) }}" required
                       class="w-full px-3 py-2 border rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" class="w-full px-3 py-2 border rounded-lg">{{ old('description', $topic->description) }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-admin-primary text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection