@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3">
            <button onclick="history.back()" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:underline">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <h1 class="text-3xl font-bold text-admin-text">Add/Edit Subtopic Details & Resources</h1>
        </div>
        <p class="text-sm text-admin-text-secondary mt-1">
            Manage the details, content, and learning outcomes for this subtopic.
        </p>
    </div>

    <!-- Subtopic Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.roadmaps.store-subtopic', $topic->id) }}" method="POST" class="space-y-6" id="subtopicForm">
            @csrf

            <!-- Debug Output (remove after testing) -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Subtopic Code -->
            <div>
                <label for="subtopic_code" class="block text-sm font-medium text-gray-700 mb-1">Subtopic Code</label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-gray-200 rounded-l-lg">
                        {{ $topic->topic_code }}-
                    </span>
                    <input type="text" id="subtopic_code" name="subtopic_code" required
                           class="rounded-none rounded-r-lg w-full px-3 py-2 border border-gray-200 bg-white text-gray-700"
                           placeholder="e.g., at32" value="{{ old('subtopic_code') }}">
                </div>
            </div>

            <!-- Subtopic Name -->
            <div>
                <label for="subtopic_name" class="block text-sm font-medium text-gray-700 mb-1">Subtopic Name</label>
                <input type="text" id="subtopic_name" name="subtopic_name" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                       placeholder="e.g., Introduction to JavaScript" value="{{ old('subtopic_name') }}">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description"
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                          placeholder="A brief overview of the subtopic...">{{ old('description') }}</textarea>
            </div>

            <!-- Content & Resources -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Content & Resources</h2>
                <div id="contents-container">
                    @if(old('contents'))
                        @foreach(old('contents') as $i => $c)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Content Title</label>
                                <input type="text" name="contents[{{ $i }}][title]" required
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" value="{{ $c['title'] ?? '' }}">

                                <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Content Type</label>
                                <select name="contents[{{ $i }}][type]" required
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                                    <option value="">Select Type</option>
                                    <option value="text" {{ (isset($c['type']) && $c['type'] == 'text') ? 'selected' : '' }}>Text</option>
                                    <option value="video" {{ (isset($c['type']) && $c['type'] == 'video') ? 'selected' : '' }}>Video</option>
                                    <option value="image" {{ (isset($c['type']) && $c['type'] == 'image') ? 'selected' : '' }}>Image</option>
                                </select>

                                <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Content</label>
                                <textarea name="contents[{{ $i }}][content]" required
                                          class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">{{ $c['content'] ?? '' }}</textarea>
                            </div>
                        @endforeach
                    @else
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Content Title</label>
                            <input type="text" name="contents[0][title]" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" placeholder="e.g., What is a variable?">

                            <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Content Type</label>
                            <select name="contents[0][type]" required
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                                <option value="">Select Type</option>
                                <option value="text">Text</option>
                                <option value="video">Video</option>
                                <option value="image">Image</option>
                            </select>

                            <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Content</label>
                            <textarea name="contents[0][content]" required
                                      class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                                      placeholder="Enter text, video URL, or link here..."></textarea>
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addContentField()" class="text-admin-primary text-sm hover:underline">
                    + Add Content
                </button>
            </div>

            <!-- Learning Outcomes -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Learning Outcomes</h2>
                <div id="outcomes-container">
                    @if(old('learning_outcomes'))
                        @foreach(old('learning_outcomes') as $i => $lo)
                            <div class="flex items-center gap-2 mb-2">
                                <input type="checkbox" class="h-4 w-4 text-admin-primary focus:ring-admin-primary border-gray-300 rounded">
                                <input type="text" name="learning_outcomes[{{ $i }}][outcome]" required
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" value="{{ $lo['outcome'] ?? '' }}">
                                <button type="button" class="text-red-600" onclick="this.parentElement.remove()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <input type="text" id="new-outcome" class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 mt-2"
                       placeholder="Add a new learning outcome">
                <button type="button" onclick="addLearningOutcome()" class="text-admin-primary text-sm hover:underline mt-2">
                    + Add
                </button>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-2">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300" onclick="history.back()">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-admin-primary text-white rounded-lg hover:bg-blue-700">
                    Save Subtopic
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let contentIndex = {{ count(old('contents', [])) ? count(old('contents', [])) : 1 }};
    let outcomeIndex = {{ count(old('learning_outcomes', [])) }};

    function addContentField() {
        const container = document.getElementById('contents-container');
        const newField = document.createElement('div');
        newField.className = 'mb-4';
        newField.innerHTML = `
            <label class="block text-sm font-medium text-gray-700 mb-1">Content Title</label>
            <input type="text" name="contents[${contentIndex}][title]" required
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" placeholder="e.g., What is a variable?">

            <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Content Type</label>
            <select name="contents[${contentIndex}][type]" required
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                <option value="">Select Type</option>
                <option value="text">Text</option>
                <option value="video">Video</option>
                <option value="image">Image</option>
            </select>

            <label class="block text-sm font-medium text-gray-700 mt-2 mb-1">Content</label>
            <textarea name="contents[${contentIndex}][content]" required
                      class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" placeholder="Enter text, video URL, or link here..."></textarea>
        `;
        container.appendChild(newField);
        contentIndex++;
    }

    function addLearningOutcome() {
        const container = document.getElementById('outcomes-container');
        const input = document.getElementById('new-outcome');
        if (input.value.trim()) { // Ensure input is not empty
            const newOutcome = document.createElement('div');
            newOutcome.className = 'flex items-center gap-2 mb-2';
            newOutcome.innerHTML = `
                <input type="checkbox" class="h-4 w-4 text-admin-primary focus:ring-admin-primary border-gray-300 rounded">
                <input type="text" name="learning_outcomes[${outcomeIndex}][outcome]" required
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" value="${input.value}">
                <button type="button" class="text-red-600" onclick="this.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newOutcome);
            input.value = '';
            outcomeIndex++;
        }
    }
</script>
@endsection