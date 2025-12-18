@extends('layouts.teacher')

@section('title', 'Add New Gig')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('teacher.gigs') }}" class="text-indigo-600 hover:text-indigo-700 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to My Gigs
        </a>
        <h1 class="text-3xl font-bold">Add New Teaching Gig</h1>
        <p class="text-gray-600 mt-2">Fill in the details to create a new teaching offering</p>
    </div>

    <form action="{{ route('teacher.gigs.store') }}" method="POST" class="space-y-8">
        @csrf

        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-xl font-semibold mb-6">Basic Information</h2>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gig Title *</label>
                    <input type="text" name="title" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., O/L Physics - Mechanics Masterclass">
                    @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" rows="5" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Explain what students will learn, your teaching style, and why they should choose you..."></textarea>
                    @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grade *</label>
                        <select name="grade" id="grade" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Grade</option>
                            @foreach(range(1, 13) as $g)
                                <option value="{{ $g }}">Grade {{ $g }}</option>
                            @endforeach
                        </select>
                        @error('grade') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Languages You Will Teach In *</label>
                    <div class="space-y-3" id="languages-container">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="languages[]" value="Sinhala" id="lang-sinhala" class="w-5 h-5 text-indigo-600 rounded" disabled>
                            <span>Sinhala</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="languages[]" value="Tamil" id="lang-tamil" class="w-5 h-5 text-indigo-600 rounded" disabled>
                            <span>Tamil</span>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="languages[]" value="English" id="lang-english" class="w-5 h-5 text-indigo-600 rounded" disabled>
                            <span>English</span>
                        </label>
                    </div>
                    @error('languages') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subjects * (Select multiple)</label>
                    <div id="subjects-container" class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-3 bg-gray-50" style="display: none;">
                        <!-- Subjects will be loaded here -->
                    </div>
                    @error('selected_subjects') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Topics (Select multiple)</label>
                    <div id="topics-container" class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-3 bg-gray-50" style="display: none;">
                        <!-- Topics will be loaded here -->
                    </div>
                    @error('selected_topics') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subtopics (Optional, Select multiple)</label>
                    <div id="subtopics-container" class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-3 bg-gray-50" style="display: none;">
                        <!-- Subtopics will be loaded here -->
                    </div>
                    @error('selected_subtopics') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-xl font-semibold mb-6">Teaching Details</h2>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Session Duration (minutes) *</label>
                    <select name="session_duration" required class="w-full md:w-64 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="30">30 minutes</option>
                        <option value="45">45 minutes</option>
                        <option value="60" selected>60 minutes</option>
                        <option value="90">90 minutes</option>
                        <option value="120">120 minutes</option>
                    </select>
                    @error('session_duration') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 bg-indigo-600 text-white py-4 rounded-lg hover:bg-indigo-700 font-semibold text-lg">
                Submit Gig for Approval
            </button>
            <a href="{{ route('teacher.gigs') }}" class="flex-1 text-center bg-gray-200 py-4 rounded-lg hover:bg-gray-300 font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gradeSelect = document.getElementById('grade');
    const langCheckboxes = document.querySelectorAll('input[name="languages[]"]');
    const subjectsContainer = document.getElementById('subjects-container');
    const topicsContainer = document.getElementById('topics-container');
    const subtopicsContainer = document.getElementById('subtopics-container');

    let selectedSubjects = [];
    let selectedTopics = [];
    let selectedSubtopics = [];

    function getSelectedLanguages() {
        return Array.from(langCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
    }

    function getSelectedIds(containerId) {
        const checkboxes = document.querySelectorAll(`${containerId} input[type="checkbox"]:checked`);
        return Array.from(checkboxes).map(cb => cb.value).filter(id => id);
    }

    function populateCheckboxes(container, items, name, selectedIds = []) {
        container.innerHTML = '';
        if (items.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-sm col-span-2">No items available.</p>';
            return;
        }
        items.forEach(item => {
            const label = document.createElement('label');
            label.className = 'flex items-center gap-2 p-2 border rounded cursor-pointer hover:bg-gray-100';
            label.innerHTML = `
                <input type="checkbox" name="${name}[]" value="${item.id}" class="w-4 h-4 text-indigo-600 rounded" ${selectedIds.includes(item.id.toString()) ? 'checked' : ''}>
                <span class="text-sm">${item.subject_name || item.topic_name || item.subtopic_name}</span>
            `;
            container.appendChild(label);
        });
    }

    function loadSubjects() {
        const grade = gradeSelect.value;
        const languages = getSelectedLanguages();
        if (!grade || languages.length === 0) {
            subjectsContainer.style.display = 'none';
            return;
        }
        fetch(`/teacher/gig-subjects?grade=${grade}&languages=${languages.join(',')}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                }
                return res.json();
            })
            .then(subjects => {
                populateCheckboxes(subjectsContainer, subjects, 'selected_subjects', selectedSubjects);
                subjectsContainer.style.display = 'grid';
                selectedSubjects = getSelectedIds('#subjects-container');
            })
            .catch(err => {
                console.error('Error loading subjects:', err);
                subjectsContainer.innerHTML = '<p class="text-red-500 text-sm col-span-2">Error loading subjects. Please try again.</p>';
                subjectsContainer.style.display = 'grid';
            });
    }

    function loadTopics() {
        const subjectIds = getSelectedIds('#subjects-container');
        if (subjectIds.length === 0) {
            topicsContainer.style.display = 'none';
            return;
        }
        fetch(`/teacher/gig-topics?subject_ids=${subjectIds.join(',')}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                }
                return res.json();
            })
            .then(topics => {
                populateCheckboxes(topicsContainer, topics, 'selected_topics', selectedTopics);
                topicsContainer.style.display = 'grid';
                selectedTopics = getSelectedIds('#topics-container');
            })
            .catch(err => {
                console.error('Error loading topics:', err);
                topicsContainer.innerHTML = '<p class="text-red-500 text-sm col-span-2">Error loading topics. Please try again.</p>';
                topicsContainer.style.display = 'grid';
            });
    }

    function loadSubtopics() {
        const topicIds = getSelectedIds('#topics-container');
        if (topicIds.length === 0) {
            subtopicsContainer.style.display = 'none';
            return;
        }
        fetch(`/teacher/gig-subtopics?topic_ids=${topicIds.join(',')}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                }
                return res.json();
            })
            .then(subtopics => {
                populateCheckboxes(subtopicsContainer, subtopics, 'selected_subtopics', selectedSubtopics);
                subtopicsContainer.style.display = 'grid';
                selectedSubtopics = getSelectedIds('#subtopics-container');
            })
            .catch(err => {
                console.error('Error loading subtopics:', err);
                subtopicsContainer.innerHTML = '<p class="text-red-500 text-sm col-span-2">Error loading subtopics. Please try again.</p>';
                subtopicsContainer.style.display = 'grid';
            });
    }

    function updateLanguageState() {
        const grade = gradeSelect.value;
        const enabled = !!grade;
        langCheckboxes.forEach(cb => {
            cb.disabled = !enabled;
            if (!enabled) cb.checked = false;
        });
        if (!enabled) {
            subjectsContainer.style.display = 'none';
            topicsContainer.style.display = 'none';
            subtopicsContainer.style.display = 'none';
            selectedSubjects = [];
            selectedTopics = [];
            selectedSubtopics = [];
        }
    }

    function updateSubjectState() {
        const grade = gradeSelect.value;
        const languages = getSelectedLanguages();
        const enabled = !!grade && languages.length > 0;
        if (!enabled) {
            subjectsContainer.style.display = 'none';
            topicsContainer.style.display = 'none';
            subtopicsContainer.style.display = 'none';
            selectedSubjects = [];
            selectedTopics = [];
            selectedSubtopics = [];
        } else {
            loadSubjects();
        }
    }

    function updateTopicState() {
        selectedSubjects = getSelectedIds('#subjects-container');
        if (selectedSubjects.length === 0) {
            topicsContainer.style.display = 'none';
            subtopicsContainer.style.display = 'none';
            selectedTopics = [];
            selectedSubtopics = [];
        } else {
            loadTopics();
        }
    }

    function updateSubtopicState() {
        selectedTopics = getSelectedIds('#topics-container');
        if (selectedTopics.length === 0) {
            subtopicsContainer.style.display = 'none';
            selectedSubtopics = [];
        } else {
            loadSubtopics();
        }
    }

    // Event listeners for checkboxes in containers
    function attachCheckboxListeners(containerId, updateFunc) {
        const observer = new MutationObserver(() => {
            document.querySelectorAll(`${containerId} input[type="checkbox"]`).forEach(cb => {
                cb.addEventListener('change', updateFunc);
            });
        });
        observer.observe(document.querySelector(containerId), { childList: true, subtree: true });
    }

    attachCheckboxListeners('#subjects-container', updateTopicState);
    attachCheckboxListeners('#topics-container', updateSubtopicState);

    gradeSelect.addEventListener('change', () => {
        updateLanguageState();
        updateSubjectState();
    });

    langCheckboxes.forEach(cb => cb.addEventListener('change', updateSubjectState));

    // Initial state
    updateLanguageState();
});
</script>
@endsection