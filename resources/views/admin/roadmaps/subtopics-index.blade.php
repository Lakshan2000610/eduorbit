@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <button onclick="history.back()" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:underline">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <div>
                <h1 class="text-2xl font-bold">Subtopics — {{ $topic->topic_name }} ({{ $topic->topic_code }})</h1>
                <p class="text-sm text-gray-500">All subtopics for this topic.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.roadmaps.add-subtopic', $topic->id) }}" class="bg-admin-primary text-white px-3 py-2 rounded">Add Subtopic</a>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6">
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-2 px-3">ID</th>
                    <th class="py-2 px-3">Code</th>
                    <th class="py-2 px-3">Name</th>
                    <th class="py-2 px-3">Resources</th>
                    <th class="py-2 px-3">Learning Outcomes</th>
                    <th class="py-2 px-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subtopics as $s)
                    <tr class="border-t">
                        <td class="py-2 px-3">{{ $s->id }}</td>
                        <td class="py-2 px-3">{{ $s->subtopic_code }}</td>
                        <td class="py-2 px-3">{{ $s->subtopic_name }}</td>
                        <td class="py-2 px-3">{{ $s->resources_count }}</td>
                        <td class="py-2 px-3">{{ $s->outcomes_count }}</td>
                        <td class="py-2 px-3 text-right">
                            <a href="{{ route('admin.roadmaps.edit-subtopic', $s->id) }}" class="text-gray-600 mr-3">Edit</a>

                            <form action="{{ route('admin.roadmaps.delete-subtopic', $s->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this subtopic?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td class="py-4 px-3 text-center" colspan="6">No subtopics.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection