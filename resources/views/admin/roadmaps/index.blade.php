@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <!-- Page Title -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-admin-text">Roadmaps</h1>
        <a href="{{ route('admin.roadmaps.create') }}"
           class="bg-admin-primary hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
            <i class="fas fa-plus"></i> Add New Roadmap
        </a>
    </div>

    <!-- Search + Filters -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <!-- Search -->
        <div class="flex items-center bg-white border border-gray-200 rounded-lg w-full md:w-1/2 px-3 py-2 shadow-sm">
            <i class="fas fa-search text-gray-400 mr-2"></i>
            <input type="text" id="search" placeholder="Search roadmaps..."
                   class="w-full outline-none border-0 focus:ring-0 text-gray-700">
        </div>

        <!-- Filters -->
        <div class="flex gap-3">
            <select id="filter-grade" class="border border-gray-200 rounded-lg px-3 py-2 bg-white shadow-sm text-gray-600">
                <option value="">Grade</option>
                @for ($i = 1; $i <= 13; $i++)
                    <option value="Grade {{ $i }}">Grade {{ $i }}</option>
                @endfor
            </select>
            <select id="filter-language" class="border border-gray-200 rounded-lg px-3 py-2 bg-white shadow-sm text-gray-600">
                <option value="">Language</option>
                <option value="Sinhala">Sinhala</option>
                <option value="English">English</option>
                <option value="Tamil">Tamil</option>
            </select>
        </div>
    </div>

    <!-- Roadmap Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="py-3 px-6 text-gray-600 text-sm font-semibold">Grade</th>
                    <th class="py-3 px-6 text-gray-600 text-sm font-semibold">Total Subjects</th>
                    <th class="py-3 px-6 text-gray-600 text-sm font-semibold">Total Topics</th> <!-- New column -->
                    <th class="py-3 px-6 text-gray-600 text-sm font-semibold">Total Subtopics</th>
                    <th class="py-3 px-6 text-gray-600 text-sm font-semibold">Language</th>
                    <th class="py-3 px-6 text-gray-600 text-sm font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="roadmap-table-body">
                @foreach ($grades as $gradeData)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition" data-grade="{{ $gradeData['grade'] }}" data-language="{{ $gradeData['language'] }}">
                        <td class="py-3 px-6 text-gray-800">{{ $gradeData['grade'] }}</td>
                        <td class="py-3 px-6 text-gray-800">{{ $gradeData['total_subjects'] }}</td>
                        <td class="py-3 px-6 text-gray-800">{{ $gradeData['total_topics'] }}</td> <!-- Display total topics -->
                        <td class="py-3 px-6 text-gray-800">{{ $gradeData['total_subtopics'] }}</td>
                        <td class="py-3 px-6 text-gray-800">{{ $gradeData['language'] }}</td>
                        <td class="py-3 px-6 text-right space-x-3">
                            <a href="{{ route('admin.roadmaps.view', ['grade' => str_replace(' ', '-', $gradeData['grade']), 'language' => $gradeData['language']]) }}" class="text-blue-600 font-medium hover:underline">View</a>
                            <a href="{{ route('admin.roadmaps.create') }}?grade={{ urlencode($gradeData['grade']) }}" class="text-green-600 font-medium hover:underline">Edit</a>
                            <a href="#" class="delete-roadmap text-red-600 font-medium hover:underline" data-grade="{{ $gradeData['grade'] }}" data-language="{{ $gradeData['language'] }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Filter functionality
        const searchInput = document.getElementById('search');
        const filterGrade = document.getElementById('filter-grade');
        const filterLanguage = document.getElementById('filter-language');
        const tableRows = document.querySelectorAll('#roadmap-table-body tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const gradeFilter = filterGrade.value.toLowerCase();
            const languageFilter = filterLanguage.value.toLowerCase();

            tableRows.forEach(row => {
                const grade = row.getAttribute('data-grade').toLowerCase();
                const language = row.getAttribute('data-language').toLowerCase();
                const matchesSearch = grade.includes(searchTerm) || language.includes(searchTerm);
                const matchesGrade = !gradeFilter || grade === gradeFilter;
                const matchesLanguage = !languageFilter || language === languageFilter;

                if (matchesSearch && matchesGrade && matchesLanguage) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterTable);
        filterGrade.addEventListener('change', filterTable);
        filterLanguage.addEventListener('change', filterTable);

        // Delete confirmation
        document.querySelectorAll('.delete-roadmap').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const grade = this.getAttribute('data-grade');
                const language = this.getAttribute('data-language');

                if (confirm(`Are you sure you want to delete the roadmap for ${grade} (${language})?`)) {
                    fetch(`/admin/roadmaps/delete/${encodeURIComponent(grade)}/${encodeURIComponent(language)}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Roadmap deleted successfully!');
                            this.closest('tr').remove();
                        } else {
                            alert('Cannot delete roadmap: Active topics exist.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    });
</script>
@endsection