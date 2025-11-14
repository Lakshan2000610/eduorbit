@extends('layouts.student')

@section('content')
    <div class="p-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold">My Roadmap</h1>
                <p class="text-sm text-gray-500">Grade: {{ $grade ?? '-' }} &nbsp; | &nbsp; Language: {{ $language ?? '-' }}</p>
            </div>
            <div>
                <a href="{{ route('student.select-subjects') }}" class="px-3 py-2 border rounded text-gray-700">Change selections</a>
            </div>
        </div>

        @if($selections->isEmpty())
            <div class="bg-white shadow rounded p-6 text-center">
                <p class="text-gray-600">No subjects selected yet.</p>
                <a href="{{ route('student.select-subjects') }}" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded">Select subjects</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($selections as $sel)
                    <div class="bg-white border rounded p-4 flex items-center justify-between">
                        <div>
                            <div class="font-semibold text-gray-800">{{ $sel->subject->subject_name ?? '—' }}</div>
                            <div class="text-sm text-gray-500">{{ $sel->subject->subject_code ?? '' }}</div>
                        </div>
                        <div class="text-sm text-gray-500">
                            Selected on {{ $sel->created_at->format('Y-m-d') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection