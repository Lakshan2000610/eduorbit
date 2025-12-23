@extends('layouts.teacher')

@section('title', 'Edit Gig: ' . $gig->title)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Edit {{ $gig->title }}</h1>
            <p class="text-sm text-gray-600 mt-1">Update title and description only. Structure cannot be changed.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('teacher.gigs.show', $gig) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm font-medium">
                View Gig
            </a>
            <a href="{{ route('teacher.gigs') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
                Back to Gigs
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('teacher.gigs.update', $gig) }}" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')
        <div class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Gig Title *
                </label>
                <input
                    id="title"
                    type="text"
                    name="title"
                    value="{{ old('title', $gig->title) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Enter gig title"
                    required
                />
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description *
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Describe your teaching gig"
                    required
                >{{ old('description', $gig->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                    Update Gig
                </button>
            </div>
        </div>
    </form>

    <!-- Read-only display of current structure with formatted durations -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Current Structure (Read-only)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium mb-3">Languages</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($gig->languages as $language)
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $language->language }}</span>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium mb-3">Grade</h3>
                <p class="text-gray-700">Grade {{ $gig->grade }}</p>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-medium mb-3">Subjects & Topics</h3>
            <div class="space-y-4">
                @foreach($gig->subjects as $subject)
                    <div class="border-l-4 border-indigo-500 pl-4">
                        <h4 class="font-medium text-gray-900 mb-2">{{ $subject->subject->subject_name ?? 'Unknown' }} ({{ $subject->subject->language ?? 'Unknown' }})</h4>
                        <div class="space-y-3">
                            @foreach($subject->topics as $topic)
                                <div class="bg-gray-50 p-3 rounded">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium">{{ $topic->topic->topic_name ?? 'Unknown' }}</span>
                                        <span class="text-sm text-gray-600">Duration: {{ $topic->duration }} min ({{ number_format($topic->duration / 60, 1) }}H)</span>
                                    </div>
                                    @if($topic->subtopics->count() > 0)
                                        <div class="ml-4 space-y-1">
                                            <p class="text-sm text-gray-600 mb-2">Subtopics:</p>
                                            @foreach($topic->subtopics as $subtopic)
                                                <div class="flex justify-between text-sm">
                                                    <span>• {{ $subtopic->subtopic->subtopic_name ?? 'Unknown' }}</span>
                                                    <span class="text-gray-500">({{ $subtopic->duration }} min ({{ number_format($subtopic->duration / 60, 1) }}H))</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 italic">No subtopics selected</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection