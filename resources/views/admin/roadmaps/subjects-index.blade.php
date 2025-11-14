@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Subjects — {{ $grade }} ({{ $language }})</h1>
            <p class="text-sm text-gray-500">List of main & subsubjects for selected grade and language.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.roadmaps.create') }}?grade={{ urlencode($grade) }}&language={{ urlencode($language) }}" class="bg-admin-primary text-white px-3 py-2 rounded">Add Main Subject</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-2 px-3">Code</th>
                    <th class="py-2 px-3">Name</th>
                    <th class="py-2 px-3">Type</th>
                    <th class="py-2 px-3">Parent</th>
                    <th class="py-2 px-3">Status</th>
                    <th class="py-2 px-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $s)
                    <tr class="border-t">
                        <td class="py-2 px-3">{{ $s->subject_code }}</td>
                        <td class="py-2 px-3">{{ $s->subject_name }}</td>
                        <td class="py-2 px-3">{{ $s->is_subsubject ? 'Subsubject' : 'Main' }}</td>
                        <td class="py-2 px-3">{{ $s->parent ? $s->parent->subject_name : '-' }}</td>
                        <td class="py-2 px-3">{{ $s->status ?? '-' }}</td>
                        <td class="py-2 px-3 text-right">
                            @if(!$s->is_subsubject)
                                <a href="{{ route('admin.roadmaps.add-subject') }}?grade={{ urlencode($grade) }}&language={{ urlencode($language) }}&is_subsubject=1&parent_id={{ $s->id }}" class="text-indigo-600 mr-3">Add Subsubject</a>
                            @endif
                            <a href="{{ route('admin.roadmaps.manage-topics', $s->id) }}" class="text-blue-600">Manage Topics</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-4 px-3 text-center" colspan="6">No subjects found for this grade/language.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection