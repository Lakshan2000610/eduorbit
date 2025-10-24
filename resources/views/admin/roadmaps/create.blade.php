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
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-admin-primary focus:border-transparent transition-all">
                    <option value="">Select Grade</option>
                    @for ($i = 1; $i <= 13; $i++)
                        <option value="Grade {{ $i }}">Grade {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                <select id="language" name="language"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-admin-primary focus:border-transparent transition-all">
                    <option value="">Select Language</option>
                    <option value="Sinhala">Sinhala</option>
                    <option value="English">English</option>
                    <option value="Tamil">Tamil</option>
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
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 bg-admin-primary hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition"
                        onclick="event.preventDefault(); 
                                 const grade = document.getElementById('grade').value;
                                 const language = document.getElementById('language').value;
                                 if (!grade || !language) { alert('Please select both grade and language first.'); return; }
                                 document.getElementById('selectedGrade').value = grade;
                                 document.getElementById('selectedLanguage').value = language;
                                 this.closest('form').submit();">
                    <i class="fas fa-plus"></i> Add New Subject
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
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($subjects as $subject)
                        <tr>
                            <td class="px-4 py-3">{{ $subject->subject_code }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $subject->subject_name }}</td>
                            <td class="px-4 py-3 text-center">{{ $subject->topics->count() }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full cursor-pointer"
                                      onclick="this.innerText = this.innerText === 'Active' ? 'Inactive' : 'Active'; this.className = this.innerText === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';">
                                    {{ ucfirst($subject->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.roadmaps.manage-topics', $subject->id) }}" class="text-admin-primary hover:underline text-sm">
                                    <i class="fas fa-list mr-1"></i> Manage Topics
                                </a>
                                <a href="#" class="text-gray-400 hover:text-gray-600"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection