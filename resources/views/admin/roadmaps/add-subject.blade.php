@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-admin-text">Add Subject</h1>
        <p class="text-sm text-admin-text-secondary mt-1">
            Add a new subject for the selected grade.
        </p>
        <div class="mt-3 flex items-center gap-3">
            <button onclick="history.back()" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:underline">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <a href="{{ route('admin.roadmaps.create') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:underline">
                <i class="fas fa-arrow-left"></i> Roadmaps
            </a>
        </div>
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

            <!-- Main / Subsubject toggle -->
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" id="is_subsubject" name="is_subsubject" value="1" class="h-5 w-5"
                           {{ old('is_subsubject', request()->get('is_subsubject')) ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700">Create as a subsubject (subcategory)</span>
                </label>
                <p id="toggle-help" class="text-sm text-gray-500">
                    If unchecked the subject will be a main subject. If checked, choose a parent main subject below.
                </p>
            </div>

            <!-- Parent Main Subject (shown only when is_subsubject checked) -->
            <div id="parent-subject-block" style="display:none;">
                <label for="parent_subject_id" class="block text-sm font-medium text-gray-700 mb-1">Parent Main Subject</label>
                <select id="parent_subject_id" name="parent_subject_id"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                    <option value="">Select main subject</option>

                    @if(isset($mainSubjectsAll) && $mainSubjectsAll->count())
                        @foreach($mainSubjectsAll as $s)
                            @php
                                $hasTopics = $s->topics()->count() > 0;
                            @endphp
                            <option value="{{ $s->id }}"
                                    {{ old('parent_subject_id', request()->get('parent_id')) == $s->id ? 'selected' : '' }}
                                    {{ $hasTopics ? 'disabled' : '' }}>
                                {{ $s->subject_name }} ({{ $s->subject_code }}) 
                                {{ $hasTopics ? ' — has topics (cannot be a parent)' : '' }}
                            </option>
                        @endforeach
                    @else
                        <option value="">No main subjects found for this grade/language</option>
                    @endif
                </select>

                <p class="text-xs text-gray-500 mt-2">
                    Only main subjects without topics can be parents for subsubjects. If a main subject already has topics,
                    create topics under its subsubjects instead, or remove topics if you want to convert it to a parent.
                </p>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isSubCheckbox = document.getElementById('is_subsubject');
        const parentBlock = document.getElementById('parent-subject-block');

        function toggleParentBlock() {
            if (isSubCheckbox && isSubCheckbox.checked) {
                parentBlock.style.display = '';
            } else {
                parentBlock.style.display = 'none';
                const select = document.getElementById('parent_subject_id');
                if (select) select.value = '';
            }
        }

        if (isSubCheckbox) {
            isSubCheckbox.addEventListener('change', toggleParentBlock);
            toggleParentBlock();
        }
    });
</script>
@endsection