@extends('layouts.teacher')

@section('title', $gig->title)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">{{ $gig->title }}</h1>
            <p class="text-sm text-gray-600 mt-1">Grade {{ $gig->grade }} • Status: <span class="capitalize {{ $gig->status === 'active' ? 'text-green-600' : ($gig->status === 'pending' ? 'text-blue-600' : 'text-gray-600') }}">{{ $gig->status }}</span></p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('teacher.gigs.edit', $gig) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
                Edit Gig
            </a>
            <a href="{{ route('teacher.gigs') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm font-medium">
                Back to Gigs
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Description</h2>
        <p class="text-gray-700">{{ $gig->description }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Languages</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($gig->languages as $language)
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $language->language }}</span>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Subjects & Topics</h3>
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