@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <!-- Page Header -->
    <div class="flex items-center gap-2 mb-8">
        <a href="{{ route('admin.roadmaps.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-admin-text">Roadmap Details: {{ $grade }} ({{ $language }})</h1>
            <p class="text-sm text-admin-text-secondary mt-1">
                View the hierarchical structure of subjects, topics, and subtopics.
            </p>
        </div>
    </div>

    <!-- Tree Structure -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Curriculum Tree</h2>
        <ul class="list-none pl-0">
            @foreach ($subjects as $subject)
                <li class="mb-4">
                    <div class="flex items-center gap-2 bg-gray-50 p-3 rounded-lg">
                        <span class="font-medium text-gray-800">{{ $subject->subject_name }} ({{ $subject->subject_code }})</span>
                        <span class="text-sm text-gray-500">Status: {{ ucfirst($subject->status) }}</span>
                    </div>
                    @if ($subject->topics->count() > 0)
                        <ul class="list-none pl-6 mt-2">
                            @foreach ($subject->topics as $topic)
                                <li class="mb-2">
                                    <div class="flex items-center gap-2 bg-gray-100 p-2 rounded-lg">
                                        <span class="text-gray-700">{{ $topic->topic_name }} ({{ $topic->topic_code }})</span>
                                    </div>
                                    @if ($topic->subtopics->count() > 0)
                                        <ul class="list-none pl-6 mt-1">
                                            @foreach ($topic->subtopics as $subtopic)
                                                <li class="text-gray-600">{{ $subtopic->subtopic_name }} ({{ $subtopic->subtopic_code }})</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection