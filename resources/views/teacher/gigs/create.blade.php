@extends('layouts.teacher')

@section('title', 'Add New Gig')

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h1 class="mb-6">Add Teacher Gig</h1>
        <form id="gigForm" method="POST" action="{{ route('teacher.gigs.store') }}" class="space-y-4">
            @csrf
            <!-- Basic Information -->
            <div>
                <label for="title" class="block text-gray-700 mb-2">
                    Gig Title *
                </label>
                <input
                    id="title"
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter gig title"
                    required
                />
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="description" class="block text-gray-700 mb-2">
                    Description *
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Describe your teaching gig"
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <!-- Grade Selection -->
            <div>
                <label for="grade" class="block text-gray-700 mb-2">
                    Select Grade *
                </label>
                <select
                    id="grade"
                    name="grade"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                    <option value="">Choose a grade</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade }}" {{ old('grade') == $grade ? 'selected' : '' }}>Grade {{ $grade }}</option>
                    @endforeach
                </select>
                @error('grade')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div> <!-- Close border-b div, keep form open -->

        <!-- Language and Subject Selection -->
        <div id="selectionSection" style="display: none;">
            <!-- Language Selection - Top Row -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="mb-4 text-gray-700">Select Languages</h2>
                <div class="grid grid-cols-3 gap-4" id="languageGrid">
                    @foreach($languages as $language)
                        <div
                            data-language="{{ $language }}"
                            class="p-6 border-2 rounded-lg cursor-pointer transition-all border-gray-300 hover:border-blue-400 hover:bg-gray-50 language-card"
                        >
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded border-2 border-gray-300 flex items-center justify-center language-check">
                                    <!-- Check icon will be added via JS -->
                                </div>
                                <span class="text-gray-700">{{ $language }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Subject Selection Columns + Sidebar -->
            <div id="subjectSection" style="display: none;" class="flex">
                <!-- Sidebar - Selected Subjects -->
                <div class="w-64 bg-gray-50 border-r border-gray-200 p-4">
                    <h3 class="text-sm text-gray-600 mb-3 uppercase">Selected Subjects</h3>
                    <div id="selectedSubjectsList" class="space-y-2">
                        <p class="text-sm text-gray-400 italic">No subjects selected yet</p>
                    </div>
                </div>
                <!-- Main Content Area -->
                <div class="flex-1 p-6">
                    <div id="subjectSelectionView">
                        <h2 class="mb-4 text-gray-700">Select Subjects by Language</h2>
                        <div id="subjectsGrid" class="grid grid-cols-3 gap-4">
                            <!-- Subjects will be populated via JS -->
                        </div>
                    </div>
                    <div id="topicSelectionView" style="display: none;">
                        <div class="mb-4">
                            <button
                                type="button"
                                id="backToSubjects"
                                class="text-blue-500 hover:underline text-sm"
                            >
                                ← Back to Subjects
                            </button>
                        </div>
                        <h2 id="topicHeader" class="mb-4 text-gray-700">
                            <!-- Header will be set via JS -->
                        </h2>
                        <div class="space-y-4">
                            <!-- Available Topics -->
                            <div>
                                <h3 class="mb-3 text-gray-600">Available Topics</h3>
                                <div id="topicsGrid" class="grid grid-cols-2 gap-3">
                                    <!-- Topics will be populated via JS -->
                                </div>
                            </div>
                            <!-- Selected Topics with Duration -->
                            <div id="selectedTopicsSection" style="display: none;" class="pt-4 border-t border-gray-200">
                                <h3 class="mb-3 text-gray-600">Selected Topics & Duration</h3>
                                <div id="selectedTopicsList" class="space-y-3">
                                    <!-- Selected topics will be populated via JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div id="submitSection" style="display: none;" class="p-6 border-t border-gray-200 flex justify-end">
            <button
                type="button"
                id="submitGig"
                class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors"
            >
                Add Teacher Gig
            </button>
        </div>
        </form> <!-- Close form here -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Duration options for subtopics dropdown
    const durationOptions = [
        { value: 0, text: 'None' },
        { value: 15, text: '15 minutes' },
        { value: 30, text: '30 minutes' },
        { value: 45, text: '45 minutes' },
        { value: 60, text: '1 hour' },
        { value: 120, text: '2 hours' },
        { value: 180, text: '3 hours' },
        { value: 1440, text: '1 day' }
    ];

    // Format time function for display (e.g., 1H 30M)
    function formatTime(minutes) {
        if (minutes === 0) return '0M';
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        let str = '';
        if (hours > 0) str += `${hours}H `;
        if (mins > 0) str += `${mins}M`;
        return str.trim();
    }

    // State
    let selectedLanguages = [];
    let subjectSelections = [];
    let activeSubject = null;
    let expandedTopic = null;
    let availableSubjects = []; // Fetched subjects
    let availableTopics = []; // For active subject
    let subtopicsMap = {}; // topic_id -> [{id, subtopic_name}]

    // DOM Elements
    const gradeSelect = document.getElementById('grade');
    const selectionSection = document.getElementById('selectionSection');
    const subjectSection = document.getElementById('subjectSection');
    const submitSection = document.getElementById('submitSection');
    const form = document.getElementById('gigForm');
    const subjectsGrid = document.getElementById('subjectsGrid');
    const subjectSelectionView = document.getElementById('subjectSelectionView');
    const topicSelectionView = document.getElementById('topicSelectionView');
    const backToSubjectsBtn = document.getElementById('backToSubjects');
    const topicHeader = document.getElementById('topicHeader');
    const topicsGrid = document.getElementById('topicsGrid');
    const selectedTopicsSection = document.getElementById('selectedTopicsSection');
    const selectedTopicsList = document.getElementById('selectedTopicsList');
    const selectedSubjectsList = document.getElementById('selectedSubjectsList');
    const submitGigBtn = document.getElementById('submitGig');

    // Event Listeners
    gradeSelect.addEventListener('change', toggleSelectionSection);
    backToSubjectsBtn.addEventListener('click', () => setActiveSubject(null));
    submitGigBtn.addEventListener('click', (e) => {
        e.preventDefault();
        handleSubmit();
    });

    // Language toggles
    document.addEventListener('click', (e) => {
        if (e.target.closest('.language-card')) {
            const card = e.target.closest('.language-card');
            const language = card.dataset.language;
            toggleLanguage(language);
        }
    });

    // Subject checkboxes
    document.addEventListener('change', (e) => {
        if (e.target.type === 'checkbox' && e.target.classList.contains('subject-checkbox')) {
            const subjectId = parseInt(e.target.value);
            const language = e.target.dataset.language;
            if (e.target.checked) {
                toggleSubject(language, subjectId);
            } else {
                // Handle uncheck
                subjectSelections = subjectSelections.filter(s => s.subject_id !== subjectId);
                populateSubjects();
                updateSelectedSubjectsList();
                updateSubmitVisibility();
            }
        }
    });

    // Topic checkboxes - Handle both check and uncheck
    document.addEventListener('change', (e) => {
        if (e.target.type === 'checkbox' && e.target.classList.contains('topic-checkbox')) {
            const topicId = parseInt(e.target.value);
            if (e.target.checked) {
                handleTopicSelect(topicId);
            } else {
                // Handle uncheck: remove topic and its subtopics
                removeTopicFromSubject(topicId);
            }
        }
    });

    // Subtopic checkboxes - Handle both check and uncheck
    document.addEventListener('change', (e) => {
        if (e.target.type === 'checkbox' && e.target.classList.contains('subtopic-checkbox')) {
            const topicId = parseInt(e.target.dataset.topic);
            const subtopicId = parseInt(e.target.value);
            if (e.target.checked) {
                handleSubtopicSelect(topicId, subtopicId);
            } else {
                // Handle uncheck
                const topicObj = activeSubject.topics.find(t => t.topic_id === topicId);
                if (topicObj) {
                    topicObj.subtopics = topicObj.subtopics.filter(st => st.subtopic_id !== subtopicId);
                    recalculateTopicDuration(topicId);
                }
                const selIndex = subjectSelections.findIndex(s => s.subject_id === activeSubject.subject_id);
                if (selIndex > -1) {
                    subjectSelections[selIndex] = activeSubject;
                }
                populateTopics();
            }
        }
    });

    // Subtopic duration select change
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('subtopic-duration-select')) {
            const topicId = parseInt(e.target.dataset.topic);
            const subtopicId = parseInt(e.target.dataset.subtopic);
            const duration = parseInt(e.target.value) || 0;
            updateSubtopicDuration(topicId, subtopicId, duration);
        }
    });

    // Topic duration input change - set subtopics to None
    document.addEventListener('input', (e) => {
        if (e.target.classList.contains('topic-duration')) {
            const topicId = parseInt(e.target.dataset.topic);
            const value = parseInt(e.target.value) || 0;
            // Set all subtopics to 0 (None)
            const topicObj = activeSubject.topics.find(t => t.topic_id === topicId);
            if (topicObj) {
                topicObj.subtopics.forEach(st => st.duration = 0);
            }
            // Update selects to None
            const selects = document.querySelectorAll(`.subtopic-duration-select[data-topic="${topicId}"]`);
            selects.forEach(select => {
                select.value = '0';
            });
            updateSelectedTopicsList();
        }
    });

    // Remove buttons and toggle
    document.addEventListener('click', (e) => {
        if (e.target.closest('.remove-topic')) {
            const topicId = parseInt(e.target.closest('.remove-topic').dataset.topic);
            removeTopicFromSubject(topicId);
        } else if (e.target.closest('.remove-subtopic')) {
            const topicId = parseInt(e.target.closest('.remove-subtopic').dataset.topic);
            const subtopicId = parseInt(e.target.closest('.remove-subtopic').dataset.subtopic);
            removeSubtopicFromTopic(topicId, subtopicId);
        } else if (e.target.closest('.subtopics-toggle')) {
            const topicId = parseInt(e.target.closest('.subtopics-toggle').dataset.topic);
            const content = document.querySelector(`.subtopics-content[data-topic="${topicId}"]`);
            if (content) {
                const isHidden = content.style.display === 'none';
                content.style.display = isHidden ? 'block' : 'none';
                // Toggle icon
                const icon = e.target.closest('.subtopics-toggle').querySelector('svg path');
                if (icon) {
                    icon.setAttribute('d', isHidden ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7');
                }
            }
        }
    });

    // Topic expansion buttons
    document.addEventListener('click', (e) => {
        if (e.target.closest('.toggle-subtopics')) {
            const topicId = parseInt(e.target.closest('.toggle-subtopics').dataset.topic);
            toggleTopicExpansion(topicId);
        }
    });

    // Sidebar clicks
    document.addEventListener('click', (e) => {
        if (e.target.closest('.sidebar-item')) {
            const subjectId = parseInt(e.target.closest('.sidebar-item').dataset.subject);
            const selection = subjectSelections.find(s => s.subject_id === subjectId);
            if (selection) {
                setActiveSubject(selection);
            }
        }
    });

    // Functions
    function toggleSelectionSection() {
        const hasGrade = gradeSelect.value.trim() !== '';
        selectionSection.style.display = hasGrade ? 'block' : 'none';
        if (hasGrade) {
            subjectSection.style.display = 'none';
            submitSection.style.display = 'none';
            loadSubjects(); // Load subjects if languages already selected
        }
    }

    function toggleLanguage(language) {
        const index = selectedLanguages.indexOf(language);
        if (index > -1) {
            selectedLanguages.splice(index, 1);
            subjectSelections = subjectSelections.filter(s => s.language !== language);
            availableSubjects = availableSubjects.filter(s => !selectedLanguages.includes(s.language)); // Filter UI
            if (activeSubject && activeSubject.language === language) {
                setActiveSubject(null);
            }
        } else {
            selectedLanguages.push(language);
        }
        updateLanguageUI();
        loadSubjects(); // Reload subjects based on new languages
        updateSelectedSubjectsList();
        updateSubmitVisibility();
    }

    function updateLanguageUI() {
        document.querySelectorAll('.language-card').forEach(card => {
            const lang = card.dataset.language;
            const isSelected = selectedLanguages.includes(lang);
            card.className = `p-6 border-2 rounded-lg cursor-pointer transition-all ${isSelected ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-blue-400 hover:bg-gray-50'} language-card`;
            const checkDiv = card.querySelector('.language-check');
            checkDiv.className = `w-6 h-6 rounded border-2 ${isSelected ? 'border-blue-500 bg-blue-500' : 'border-gray-300'} flex items-center justify-center language-check`;
            checkDiv.innerHTML = isSelected ? 
                '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20,6 9,17 4,12"></polyline></svg>' : 
                '';
        });
    }

    async function loadSubjects() {
        const grade = gradeSelect.value.trim();
        if (!grade || selectedLanguages.length === 0) {
            subjectsGrid.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`{{ route('teacher.gig-subjects') }}?grade=${grade}&languages=${selectedLanguages.join(',')}`);
            availableSubjects = await response.json();
            populateSubjects();
            if (selectedLanguages.length > 0) {
                subjectSection.style.display = 'flex';
            }
        } catch (error) {
            console.error('Error loading subjects:', error);
        }
    }

    function populateSubjects() {
        subjectsGrid.innerHTML = '';
        // Group subjects by language
        const subjectsByLang = {};
        availableSubjects.forEach(subject => {
            if (!subjectsByLang[subject.language]) {
                subjectsByLang[subject.language] = [];
            }
            subjectsByLang[subject.language].push(subject);
        });

        Object.keys(subjectsByLang).forEach(language => {
            const langDiv = document.createElement('div');
            langDiv.className = 'border border-gray-300 rounded-lg p-4';
            langDiv.innerHTML = `
                <h3 class="pb-3 mb-3 border-b border-gray-200 text-gray-700">${language}</h3>
                <div class="space-y-2">
                    <p class="text-sm text-gray-600 mb-2">Subjects</p>
                </div>
            `;
            const subjectsDiv = langDiv.querySelector('div.space-y-2');
            subjectsByLang[language].forEach(subject => {
                const isSelected = subjectSelections.some(s => s.subject_id === subject.id);
                const label = document.createElement('label');
                label.className = 'flex items-start gap-3 p-2 rounded hover:bg-gray-50 cursor-pointer';
                label.innerHTML = `
                    <input type="checkbox" class="mt-1 w-4 h-4 subject-checkbox" data-language="${language}" value="${subject.id}" ${isSelected ? 'checked' : ''}>
                    <span class="text-sm text-gray-700">${subject.subject_name}</span>
                `;
                subjectsDiv.appendChild(label);
            });
            subjectsGrid.appendChild(langDiv);
        });
    }

    function toggleSubject(language, subjectId) {
        const existing = subjectSelections.find(s => s.subject_id === subjectId);
        if (existing) {
            subjectSelections = subjectSelections.filter(s => s.subject_id !== subjectId);
        } else {
            const subject = availableSubjects.find(s => s.id === subjectId);
            if (subject) {
                const newSelection = {
                    language: language,
                    subject_id: subject.id,
                    subject_name: subject.subject_name,
                    topics: []
                };
                subjectSelections.push(newSelection);
            }
        }
        populateSubjects(); // Re-check checkboxes
        updateSelectedSubjectsList();
        updateSubmitVisibility();
    }

    function setActiveSubject(selection) {
        activeSubject = selection;
        if (activeSubject) {
            subjectSelectionView.style.display = 'none';
            topicSelectionView.style.display = 'block';
            topicHeader.textContent = `Select topics for ${activeSubject.subject_name} (${activeSubject.language})`;
            loadTopicsAndSubtopics();
        } else {
            subjectSelectionView.style.display = 'block';
            topicSelectionView.style.display = 'none';
        }
        updateSelectedSubjectsList();
    }

    async function loadTopicsAndSubtopics() {
        if (!activeSubject || !activeSubject.subject_id) return;

        try {
            // Fetch topics
            const topicsResponse = await fetch(`{{ route('teacher.gig-topics') }}?subject_ids=${activeSubject.subject_id}`);
            availableTopics = await topicsResponse.json();

            // Fetch subtopics for all topics
            if (availableTopics.length > 0) {
                const topicIds = availableTopics.map(t => t.id).join(',');
                const subtopicsResponse = await fetch(`{{ route('teacher.gig-subtopics') }}?topic_ids=${topicIds}`);
                const allSubtopics = await subtopicsResponse.json();
                subtopicsMap = {};
                allSubtopics.forEach(st => {
                    if (!subtopicsMap[st.topic_id]) {
                        subtopicsMap[st.topic_id] = [];
                    }
                    subtopicsMap[st.topic_id].push({ id: st.id, name: st.subtopic_name });
                });
            } else {
                subtopicsMap = {};
            }

            // Add availableTopics to activeSubject for reference
            activeSubject.availableTopics = availableTopics;

            populateTopics();
        } catch (error) {
            console.error('Error loading topics/subtopics:', error);
        }
    }

    function populateTopics() {
        if (!activeSubject) return;
        topicsGrid.innerHTML = '';
        availableTopics.forEach(topic => {
            const isSelected = activeSubject.topics.some(t => t.topic_id === topic.id);
            const hasSubtopics = subtopicsMap[topic.id] && subtopicsMap[topic.id].length > 0;
            const isExpanded = expandedTopic === topic.id;
            const card = document.createElement('div');
            card.className = 'border border-gray-300 rounded-lg overflow-hidden';
            card.innerHTML = `
                <div class="flex items-center justify-between p-4 cursor-pointer transition-all ${isSelected ? 'bg-gray-100' : 'bg-white hover:bg-gray-50'}">
                    <div class="flex items-center gap-3 flex-1">
                        <input type="checkbox" class="w-4 h-4 topic-checkbox" value="${topic.id}" ${isSelected ? 'checked' : ''}>
                        <span class="${isSelected ? 'text-gray-500' : 'text-gray-700'}">${topic.topic_name}</span>
                    </div>
                    ${hasSubtopics ? `
                        <button type="button" class="px-3 py-1 text-sm text-blue-500 hover:bg-blue-50 rounded toggle-subtopics" data-topic="${topic.id}">
                            ${isExpanded ? 'Hide' : 'View'} Subtopics
                        </button>
                    ` : ''}
                </div>
                <div class="subtopics-section px-4 pb-4 bg-gray-50 border-t border-gray-200" style="display: ${isExpanded ? 'block' : 'none'};">
                    <p class="text-sm text-gray-600 mb-2 mt-3">Subtopics (Optional):</p>
                    <div class="grid grid-cols-2 gap-2">
                        ${subtopicsMap[topic.id]?.map(subtopic => {
                            const topicObj = activeSubject.topics.find(t => t.topic_id === topic.id);
                            const isSubtopicSel = topicObj?.subtopics?.some(st => st.subtopic_id === subtopic.id) || false;
                            return `
                                <label class="flex items-center gap-2 p-2 rounded cursor-pointer text-sm ${isSubtopicSel ? 'bg-blue-100 text-blue-700' : 'bg-white hover:bg-gray-100 text-gray-700'}">
                                    <input type="checkbox" class="w-4 h-4 subtopic-checkbox" data-topic="${topic.id}" value="${subtopic.id}" ${isSubtopicSel ? 'checked' : ''}>
                                    <span>${subtopic.name}</span>
                                </label>
                            `;
                        }).join('') || ''}
                    </div>
                </div>
            `;
            topicsGrid.appendChild(card);
        });
        updateSelectedTopicsList();
    }

    function toggleTopicExpansion(topicId) {
        expandedTopic = expandedTopic === topicId ? null : topicId;
        populateTopics();
    }

    function handleTopicSelect(topicId) {
        if (!activeSubject) return;
        const topicExists = activeSubject.topics.find(t => t.topic_id === topicId);
        if (!topicExists) {
            const topicName = activeSubject.availableTopics.find(t => t.id === topicId)?.topic_name;
            if (topicName) {
                activeSubject.topics.push({
                    topic_id: topicId,
                    topic_name: topicName,
                    duration: 0, // Initial 0, will be calculated
                    subtopics: []
                });
            }
        }
        // Auto-select all available subtopics if they exist
        if (subtopicsMap[topicId] && subtopicsMap[topicId].length > 0) {
            expandedTopic = topicId;  // Auto-expand
            const topicObj = activeSubject.topics.find(t => t.topic_id === topicId);
            if (topicObj) {
                subtopicsMap[topicId].forEach(subtopic => {
                    if (!topicObj.subtopics.some(st => st.subtopic_id === subtopic.id)) {
                        topicObj.subtopics.push({
                            subtopic_id: subtopic.id,
                            subtopic_name: subtopic.name,
                            duration: 30 // Default 30 min
                        });
                    }
                });
                recalculateTopicDuration(topicId);
            }
        }
        // Update the selection in array
        const selIndex = subjectSelections.findIndex(s => s.subject_id === activeSubject.subject_id);
        if (selIndex > -1) {
            subjectSelections[selIndex] = activeSubject;
        }
        populateTopics();
        updateSelectedSubjectsList();
    }

    function handleSubtopicSelect(topicId, subtopicId) {
        if (!activeSubject) return;
        const topicObj = activeSubject.topics.find(t => t.topic_id === topicId);
        if (!topicObj) return;
        const subtopicExists = topicObj.subtopics.find(st => st.subtopic_id === subtopicId);
        if (!subtopicExists) {
            const subtopicName = subtopicsMap[topicId]?.find(s => s.id === subtopicId)?.name;
            if (subtopicName) {
                topicObj.subtopics.push({
                    subtopic_id: subtopicId,
                    subtopic_name: subtopicName,
                    duration: 30 // Default 30 min
                });
                recalculateTopicDuration(topicId);
            }
        }
        // Update selection
        const selIndex = subjectSelections.findIndex(s => s.subject_id === activeSubject.subject_id);
        if (selIndex > -1) {
            subjectSelections[selIndex] = activeSubject;
        }
        populateTopics();
    }

    function updateSubtopicDuration(topicId, subtopicId, duration) {
        if (!activeSubject) return;
        const topicObj = activeSubject.topics.find(t => t.topic_id === topicId);
        if (topicObj) {
            const subObj = topicObj.subtopics.find(st => st.subtopic_id === subtopicId);
            if (subObj) {
                subObj.duration = duration;
            }
            recalculateTopicDuration(topicId);
        }
        const selIndex = subjectSelections.findIndex(s => s.subject_id === activeSubject.subject_id);
        if (selIndex > -1) {
            subjectSelections[selIndex] = activeSubject;
        }
        updateSelectedTopicsList();
    }

    function recalculateTopicDuration(topicId) {
        const topicObj = activeSubject.topics.find(t => t.topic_id === topicId);
        if (topicObj) {
            const totalMinutes = topicObj.subtopics.reduce((sum, st) => sum + (st.duration || 0), 0);
            topicObj.duration = totalMinutes;
            // Update display input
            const topicInput = document.querySelector(`.topic-duration[data-topic="${topicId}"]`);
            if (topicInput) {
                topicInput.value = totalMinutes;
            }
            // Update formatted span
            const formattedSpan = document.querySelector(`.topic-formatted[data-topic="${topicId}"]`);
            if (formattedSpan) {
                formattedSpan.textContent = `(${formatTime(totalMinutes)})`;
            }
        }
    }

    function updateTopicDuration(topicId, duration) {
        if (!activeSubject) return;
        const topicObj = activeSubject.topics.find(t => t.topic_id === topicId);
        if (topicObj) {
            topicObj.duration = duration;
            // Set all subtopics to 0 when manual topic duration changes
            topicObj.subtopics.forEach(st => st.duration = 0);
            // Update subtopic selects to None
            const selects = document.querySelectorAll(`.subtopic-duration-select[data-topic="${topicId}"]`);
            selects.forEach(select => select.value = '0');
        }
        const selIndex = subjectSelections.findIndex(s => s.subject_id === activeSubject.subject_id);
        if (selIndex > -1) {
            subjectSelections[selIndex] = activeSubject;
        }
        updateSelectedTopicsList();
    }

    function removeTopicFromSubject(topicId) {
        if (!activeSubject) return;
        activeSubject.topics = activeSubject.topics.filter(t => t.topic_id !== topicId);
        const selIndex = subjectSelections.findIndex(s => s.subject_id === activeSubject.subject_id);
        if (selIndex > -1) {
            subjectSelections[selIndex] = activeSubject;
        }
        if (activeSubject.topics.length === 0) {
            // Optionally remove subject if no topics
            subjectSelections = subjectSelections.filter(s => s.subject_id !== activeSubject.subject_id);
            setActiveSubject(null);
        } else {
            setActiveSubject(activeSubject); // Re-render
        }
        updateSelectedSubjectsList();
        updateSubmitVisibility();
    }

    function removeSubtopicFromTopic(topicId, subtopicId) {
        if (!activeSubject) return;
        const topicObj = activeSubject.topics.find(t => t.topic_id === topicId);
        if (topicObj) {
            topicObj.subtopics = topicObj.subtopics.filter(st => st.subtopic_id !== subtopicId);
            recalculateTopicDuration(topicId);
        }
        const selIndex = subjectSelections.findIndex(s => s.subject_id === activeSubject.subject_id);
        if (selIndex > -1) {
            subjectSelections[selIndex] = activeSubject;
        }
        setActiveSubject(activeSubject); // Re-render
    }

    function updateSelectedTopicsList() {
        if (!activeSubject || activeSubject.topics.length === 0) {
            selectedTopicsSection.style.display = 'none';
            return;
        }
        selectedTopicsSection.style.display = 'block';
        selectedTopicsList.innerHTML = '';
        activeSubject.topics.forEach(topicObj => {
            const topicDiv = document.createElement('div');
            topicDiv.className = 'bg-green-50 border border-green-200 rounded-lg p-4';
            topicDiv.innerHTML = `
                <div class="flex items-center gap-4 mb-3">
                    <div class="flex-1">
                        <span class="text-gray-700">${topicObj.topic_name}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 whitespace-nowrap">Duration:</label>
                        <input type="number" min="0" class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 topic-duration" data-topic="${topicObj.topic_id}" value="${topicObj.duration}">
                        <span class="ml-1 text-sm text-gray-500">min</span>
                        <span class="topic-formatted ml-1 text-sm italic" data-topic="${topicObj.topic_id}">(${formatTime(topicObj.duration)})</span>
                        <button type="button" class="p-2 text-red-500 hover:bg-red-100 rounded-lg remove-topic" data-topic="${topicObj.topic_id}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                </div>
                ${topicObj.subtopics.length > 0 ? `
                    <div class="pl-6 space-y-2 border-l-2 border-green-300">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600 mb-2">Selected Subtopics:</p>
                            <button type="button" class="subtopics-toggle p-1" data-topic="${topicObj.topic_id}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="subtopics-content space-y-2" data-topic="${topicObj.topic_id}" style="display: block;">
                            ${topicObj.subtopics.map(subtopicObj => `
                                <div class="flex items-center gap-4 bg-white border border-gray-200 rounded-lg p-3">
                                    <div class="flex-1">
                                        <span class="text-sm text-gray-700">${subtopicObj.subtopic_name}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <select class="w-32 px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 subtopic-duration-select" data-topic="${topicObj.topic_id}" data-subtopic="${subtopicObj.subtopic_id}">
                                            ${durationOptions.map(opt => `<option value="${opt.value}" ${subtopicObj.duration === opt.value ? 'selected' : ''}>${opt.text}</option>`).join('')}
                                        </select>
                                        <button type="button" class="p-1 text-red-500 hover:bg-red-100 rounded remove-subtopic" data-topic="${topicObj.topic_id}" data-subtopic="${subtopicObj.subtopic_id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
            `;
            selectedTopicsList.appendChild(topicDiv);
        });
    }

    function updateSelectedSubjectsList() {
        if (subjectSelections.length === 0) {
            selectedSubjectsList.innerHTML = '<p class="text-sm text-gray-400 italic">No subjects selected yet</p>';
            return;
        }
        selectedSubjectsList.innerHTML = subjectSelections.map(selection => {
            const isActive = activeSubject && activeSubject.subject_id === selection.subject_id;
            return `
                <div data-subject="${selection.subject_id}" class="p-3 rounded-lg cursor-pointer transition-colors sidebar-item ${isActive ? 'bg-blue-500 text-white' : 'bg-white hover:bg-gray-100'}">
                    <div class="text-xs text-${isActive ? 'white' : 'gray-500'} mb-1">${selection.language}</div>
                    <div class="text-sm ${isActive ? 'text-white' : 'text-gray-700'}">${selection.subject_name}</div>
                    ${selection.topics.length > 0 ? `<div class="text-xs mt-1 ${isActive ? 'text-blue-100' : 'text-gray-500'}">${selection.topics.length} topic(s)</div>` : ''}
                </div>
            `;
        }).join('');
    }

    function updateSubmitVisibility() {
        const hasGrade = gradeSelect.value.trim() !== '';
        const hasSelections = subjectSelections.length > 0;
        submitSection.style.display = (hasGrade && hasSelections) ? 'flex' : 'none';
    }

    function handleSubmit() {
        if (!gradeSelect.value || subjectSelections.length === 0) {
            alert('Please select a grade and at least one subject with topics.');
            return;
        }

        // Clear ONLY previous languages and structured_data hiddens (preserve _token!)
        form.querySelectorAll('input[name="languages[]"], input[name="structured_data"]').forEach(el => el.remove());

        // Add languages
        selectedLanguages.forEach(lang => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'languages[]';
            inp.value = lang;
            form.appendChild(inp);
        });

        // Add structured data
        const structuredInput = document.createElement('input');
        structuredInput.type = 'hidden';
        structuredInput.name = 'structured_data';
        structuredInput.value = JSON.stringify({
            selections: subjectSelections
        });
        form.appendChild(structuredInput);

        // Finally submit
        form.submit();
    }

});
</script>
@endsection