@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-admin-text">Add/Edit Roadmap</h1>
        <p class="text-sm text-admin-text-secondary mt-1">
            Create and manage learning paths for students.
        </p>
    </div>

    <!-- Roadmap Details -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Roadmap Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:w-2/3">
            <div>
                <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                <select id="grade" name="grade"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                    <option value="">Select Grade</option>
                    @for ($i = 1; $i <= 13; $i++)
                        <option value="Grade {{ $i }}" {{ (isset($grade) && $grade == "Grade $i") ? 'selected' : '' }}>Grade {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                <select id="language" name="language"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                    <option value="">Select Language</option>
                    <option value="Sinhala" {{ (isset($language) && $language == 'Sinhala') ? 'selected' : '' }}>Sinhala</option>
                    <option value="English" {{ (isset($language) && $language == 'English') ? 'selected' : '' }}>English</option>
                    <option value="Tamil" {{ (isset($language) && $language == 'Tamil') ? 'selected' : '' }}>Tamil</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Subject Management -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Subject Management</h2>
                <p class="text-sm text-gray-500">
                    Add, edit, and organize subjects for the selected grade.
                </p>
            </div>

            <form id="addSubjectForm" action="{{ route('admin.roadmaps.add-subject') }}" method="GET" class="inline">
                <input type="hidden" name="grade" id="selectedGrade">
                <input type="hidden" name="language" id="selectedLanguage">
                <button type="submit" id="addSubjectBtn"
                        class="bg-admin-primary hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Add Subject
                </button>
            </form>
        </div>

        <!-- Search & Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <input type="text" placeholder="Search subjects..."
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-admin-primary focus:border-transparent transition">
            <input type="text" placeholder="Subject Code (e.g., MATH101)"
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-admin-primary focus:border-transparent transition">
            <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-admin-primary focus:border-transparent transition">
                <option value="">Filter by Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <!-- Subject Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-t border-gray-100">
                <thead class="bg-gray-50 text-gray-700 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 font-medium">Subject Code</th>
                        <th class="px-4 py-3 font-medium">Subject Name</th>
                        <th class="px-4 py-3 font-medium text-center">Topics</th>
                        <th class="px-4 py-3 font-medium text-center">Status</th>
                        <th class="px-4 py-3 font-medium text-center">Type</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="subjects-table-body">
                    @foreach ($subjects as $subject)
                        @php
                            $hasChildren = $subject->children()->count() > 0;
                            $topicsCount = $hasChildren ? null : $subject->topics->count();
                        @endphp
                        <tr data-grade="{{ $subject->grade }}" data-language="{{ $subject->language }}"
                            data-name="{{ strtolower($subject->subject_name) }}" data-code="{{ strtolower($subject->subject_code) }}"
                            data-status="{{ $subject->status }}" data-type="{{ $subject->is_subsubject ? 'sub' : 'main' }}">
                            <td class="px-4 py-3">{{ $subject->subject_code }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $subject->subject_name }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($hasChildren)
                                    &mdash;
                                @else
                                    {{ $topicsCount }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full cursor-pointer"
                                      onclick="this.innerText = this.innerText === 'Active' ? 'Inactive' : 'Active'; this.className = this.innerText === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';">
                                    {{ ucfirst($subject->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                {{ $subject->is_subsubject ? 'Subsubject' : 'Main' }}
                                @if($subject->is_subsubject && $subject->parent)
                                    <div class="text-xs text-gray-500">({{ $subject->parent->subject_name }})</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                @if($hasChildren)
                                    <span class="text-gray-400 text-sm opacity-50 cursor-not-allowed">Manage Topics</span>
                                @else
                                    <a href="{{ route('admin.roadmaps.manage-topics', $subject->id) }}" class="text-admin-primary hover:underline text-sm">
                                        <i class="fas fa-list mr-1"></i> Manage Topics
                                    </a>
                                @endif

                                <a href="{{ route('admin.roadmaps.edit-subject', $subject->id) }}" title="Edit" class="ml-3 text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const gradeSelect = document.getElementById('grade');
        const languageSelect = document.getElementById('language');
        const addBtn = document.getElementById('addSubjectBtn');
        const searchInput = document.querySelector('input[placeholder="Search subjects..."]');
        const codeInput = document.querySelector('input[placeholder^="Subject Code"]');
        const statusSelect = document.querySelector('select[name="statusFilter"]') || document.querySelector('select:has(option[value="active"])'); // graceful
        const tableBody = document.getElementById('subjects-table-body');

        function reloadWithParams() {
            const g = gradeSelect ? gradeSelect.value : '';
            const l = languageSelect ? languageSelect.value : '';
            const params = new URLSearchParams(window.location.search);
            if (g) params.set('grade', g); else params.delete('grade');
            if (l) params.set('language', l); else params.delete('language');
            window.location.search = params.toString();
        }

        function syncHiddenInputs() {
            const selectedGrade = document.getElementById('selectedGrade');
            const selectedLanguage = document.getElementById('selectedLanguage');
            if (selectedGrade) selectedGrade.value = gradeSelect.value;
            if (selectedLanguage) selectedLanguage.value = languageSelect.value;

            if (!gradeSelect.value || !languageSelect.value) {
                addBtn.disabled = true;
                addBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                addBtn.disabled = false;
                addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        function applyFilters() {
            const q = searchInput ? searchInput.value.trim().toLowerCase() : '';
            const codeQ = codeInput ? codeInput.value.trim().toLowerCase() : '';
            const gradeFilter = gradeSelect ? gradeSelect.value : '';
            const langFilter = languageSelect ? languageSelect.value : '';
            const statusFilter = document.querySelector('select[name="statusFilter"]') ? document.querySelector('select[name="statusFilter"]').value : '';

            Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
                const name = row.dataset.name || '';
                const code = row.dataset.code || '';
                const g = row.dataset.grade || '';
                const l = row.dataset.language || '';
                const status = row.dataset.status || '';
                let visible = true;

                if (q && !(name.includes(q) || code.includes(q))) visible = false;
                if (codeQ && !code.includes(codeQ)) visible = false;
                if (gradeFilter && g !== gradeFilter) visible = false;
                if (langFilter && l !== langFilter) visible = false;
                if (statusFilter && status !== statusFilter) visible = false;

                row.style.display = visible ? '' : 'none';
            });
        }

        if (gradeSelect) gradeSelect.addEventListener('change', function() { syncHiddenInputs(); reloadWithParams(); });
        if (languageSelect) languageSelect.addEventListener('change', function() { syncHiddenInputs(); reloadWithParams(); });

        if (searchInput) searchInput.addEventListener('input', applyFilters);
        if (codeInput) codeInput.addEventListener('input', applyFilters);
        const statusFilter = document.querySelector('select[name="statusFilter"]');
        if (statusFilter) statusFilter.addEventListener('change', applyFilters);

        // initialize
        syncHiddenInputs();
        applyFilters();
    });
</script>
@endsection