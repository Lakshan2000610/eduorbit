@extends('layouts.teacher')

@section('title', 'Edit Gig: ' . $gig->title)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Edit {{ $gig->title }}</h1>
            <p class="text-sm text-gray-600 mt-1">Update title, description, durations, and prices. All changes saved with one button.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('teacher.gigs.show', $gig) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm font-medium">
                View Gig
            </a>
            <a href="{{ route('teacher.gigs') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">
                Back to Gigs
            </a>
        </div>
    </div>

    {{-- SINGLE FORM for everything --}}
    <form id="gigForm" method="POST" action="{{ route('teacher.gigs.update', $gig) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        {{-- Basic Fields --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Gig Title *
                    </label>
                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title', $gig->title) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Enter gig title"
                        required
                    />
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description *
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Describe your teaching gig"
                        required
                    >{{ old('description', $gig->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Structure Editor --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Edit Structure: Durations & Prices</h2>
            <p class="text-sm text-gray-600 mb-4">Adjust durations and prices below. Changes will be saved when you submit the form.</p>
            <div id="editStructureSection">
                <!-- Will be populated by JS with existing data -->
            </div>
            {{-- HIDDEN FIELD for structure updates --}}
            <input type="hidden" id="structured_updates" name="structured_updates" value="">
        </div>

        {{-- SINGLE SUBMIT BUTTON --}}
        <div class="bg-white rounded-lg shadow p-6 border-t border-gray-200">
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-semibold text-lg">
                    Update Gig (Title, Description, Durations & Prices)
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Duration options (same as create)
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

    // Format functions (same as create)
    function formatTime(minutes) {
        if (minutes === 0) return '0M';
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        let str = '';
        if (hours > 0) str += `${hours}H `;
        if (mins > 0) str += `${mins}M`;
        return str.trim();
    }

    function formatPrice(price) {
        return `LKR ${parseFloat(price).toLocaleString()}`;
    }

    // Pre-loaded data from controller
    const existingSelections = @json($existingSelections);

    // State (pre-populate with existing)
    let subjectSelections = existingSelections.map(selection => ({
        ...selection,
        topics: selection.topics.map(topic => ({
            ...topic,
            subtopics: topic.subtopics.map(sub => ({
                ...sub
            }))
        }))
    }));

    // DOM Elements
    const editStructureSection = document.getElementById('editStructureSection');
    const structuredUpdatesInput = document.getElementById('structured_updates');
    const form = document.getElementById('gigForm');

    // Render function for edit structure
    function renderEditStructure() {
        let html = '<div class="space-y-4">';
        subjectSelections.forEach((selection, selIndex) => {
            html += `
                <div class="border-l-4 border-indigo-500 pl-4 pb-6">
                    <h4 class="font-medium text-gray-900 mb-2">${selection.subject_name} (${selection.language})</h4>
                    <div class="space-y-3">
            `;
            selection.topics.forEach((topic, topicIndex) => {
                const hasSubtopics = topic.subtopics.length > 0;
                const topicTotalDuration = topic.subtopics.reduce((sum, st) => sum + (st.duration || 0), 0);
                html += `
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium">${topic.topic_name}</span>
                            <div class="flex items-center gap-2">
                                <label class="text-sm text-gray-600">Duration:</label>
                                <input type="number" min="0" class="w-20 px-2 py-1 border border-gray-300 rounded topic-duration-edit" 
                                       data-sel-index="${selIndex}" data-topic-index="${topicIndex}" value="${topic.duration}">
                                <span class="text-sm text-gray-500">min</span>
                                <span class="topic-formatted-edit ml-1 text-sm italic" data-sel-index="${selIndex}" data-topic-index="${topicIndex}">(${formatTime(topicTotalDuration)})</span>
                            </div>
                        </div>
                `;
                if (hasSubtopics) {
                    html += `
                        <div class="ml-4 space-y-2">
                            <p class="text-sm text-gray-600 mb-2">Subtopics:</p>
                    `;
                    topic.subtopics.forEach((subtopic, subIndex) => {
                        const currentPrice = subtopic.price || subtopic.min_price;
                        html += `
                            <div class="flex items-center gap-4 bg-white border border-gray-200 rounded p-3">
                                <div class="flex-1">
                                    <span class="text-sm text-gray-700">${subtopic.subtopic_name}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <select class="w-32 px-2 py-1 border border-gray-300 rounded subtopic-duration-select-edit" 
                                            data-sel-index="${selIndex}" data-topic-index="${topicIndex}" data-sub-index="${subIndex}">
                                        ${durationOptions.map(opt => `<option value="${opt.value}" ${subtopic.duration === opt.value ? 'selected' : ''}>${opt.text}</option>`).join('')}
                                    </select>
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs text-gray-600">Price:</label>
                                        <input type="range" min="${subtopic.min_price}" max="${subtopic.max_price}" step="1" 
                                               value="${currentPrice}" class="w-20 subtopic-price-slider-edit" 
                                               data-sel-index="${selIndex}" data-topic-index="${topicIndex}" data-sub-index="${subIndex}">
                                        <span class="text-xs text-gray-700 ml-1 subtopic-price-display" 
                                              data-sel-index="${selIndex}" data-topic-index="${topicIndex}" data-sub-index="${subIndex}">${formatPrice(currentPrice)}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += `</div>`;
                } else {
                    html += `<p class="text-sm text-gray-500 italic ml-4">No subtopics selected</p>`;
                }
                html += `</div>`;
            });
            html += `</div></div>`;
        });
        html += '</div>';
        editStructureSection.innerHTML = html;
    }

    // Event Listeners for Edit (local changes only)
    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('subtopic-duration-select-edit')) {
            const selIndex = parseInt(e.target.dataset.selIndex);
            const topicIndex = parseInt(e.target.dataset.topicIndex);
            const subIndex = parseInt(e.target.dataset.subIndex);
            const duration = parseInt(e.target.value) || 0;
            updateSubtopicDurationLocal(selIndex, topicIndex, subIndex, duration);
        }
    });

    document.addEventListener('input', (e) => {
        if (e.target.classList.contains('subtopic-price-slider-edit')) {
            const selIndex = parseInt(e.target.dataset.selIndex);
            const topicIndex = parseInt(e.target.dataset.topicIndex);
            const subIndex = parseInt(e.target.dataset.subIndex);
            const price = parseFloat(e.target.value);
            updateSubtopicPriceLocal(selIndex, topicIndex, subIndex, price);
            // Update display
            e.target.nextElementSibling.textContent = formatPrice(price);
        } else if (e.target.classList.contains('topic-duration-edit')) {
            const selIndex = parseInt(e.target.dataset.selIndex);
            const topicIndex = parseInt(e.target.dataset.topicIndex);
            const duration = parseInt(e.target.value) || 0;
            updateTopicDurationLocal(selIndex, topicIndex, duration);
        }
    });

    // Local update functions (no AJAX)
    function updateSubtopicDurationLocal(selIndex, topicIndex, subIndex, duration) {
        const subtopic = subjectSelections[selIndex].topics[topicIndex].subtopics[subIndex];
        subtopic.duration = duration;
        recalculateTopicDurationLocal(selIndex, topicIndex);
        renderEditStructure(); // Re-render to update formatted spans
    }

    function updateSubtopicPriceLocal(selIndex, topicIndex, subIndex, price) {
        const subtopic = subjectSelections[selIndex].topics[topicIndex].subtopics[subIndex];
        subtopic.price = price;
    }

    function updateTopicDurationLocal(selIndex, topicIndex, duration) {
        subjectSelections[selIndex].topics[topicIndex].duration = duration;
        // Set subtopics to 0 if manual override
        subjectSelections[selIndex].topics[topicIndex].subtopics.forEach(st => st.duration = 0);
        renderEditStructure();
    }

    function recalculateTopicDurationLocal(selIndex, topicIndex) {
        const topic = subjectSelections[selIndex].topics[topicIndex];
        const total = topic.subtopics.reduce((sum, st) => sum + (st.duration || 0), 0);
        topic.duration = total;
    }

    // Form submit - collect and add hidden updates
    form.addEventListener('submit', (e) => {
        // Collect changes (only if changed from original)
        const updates = [];
        subjectSelections.forEach((sel, selIndex) => {
            sel.topics.forEach((topic, topicIndex) => {
                // Topic duration change (if manual override)
                const subSum = topic.subtopics.reduce((sum, st) => sum + (st.duration || 0), 0);
                if (topic.duration !== subSum) {
                    updates.push({
                        gig_topic_id: topic.gig_topic_id,
                        duration: topic.duration
                    });
                }
                // Subtopic changes
                topic.subtopics.forEach((subtopic, subIndex) => {
                    // Compare to original from DB (assume stored in data-original attr or initial state)
                    // For simplicity, always include current values (controller will handle no-change)
                    updates.push({
                        gig_subtopic_id: subtopic.gig_subtopic_id,
                        duration: subtopic.duration,
                        price: subtopic.price
                    });
                });
            });
        });

        structuredUpdatesInput.value = JSON.stringify(updates);
    });

    // Initial render
    renderEditStructure();
});
</script>
@endsection