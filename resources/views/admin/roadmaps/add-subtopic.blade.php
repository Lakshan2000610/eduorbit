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
        <form action="{{ route('admin.roadmaps.store-subtopic', $topic->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="subtopicForm">
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

            <!-- Resources -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Resources</h2>
                <div id="resources-container">
                    @if(old('contents'))
                        @foreach(old('contents') as $i => $c)
                            <div class="resource-item mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-medium text-gray-700">Resource {{ $i + 1 }}</h3>
                                    <button type="button" class="text-red-600 hover:text-red-800" onclick="removeResource(this)">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>

                                <!-- Resource Title -->
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Resource Title</label>
                                    <input type="text" name="contents[{{ $i }}][title]" 
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" 
                                           placeholder="e.g., Introduction Video" value="{{ $c['title'] ?? '' }}">
                                </div>

                                <!-- Resource Type -->
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Resource Type</label>
                                    <select name="contents[{{ $i }}][type]" required class="resource-type-select"
                                            onchange="updateResourceField(this)"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                                        <option value="">Select Type</option>
                                        <option value="text" {{ (isset($c['type']) && $c['type'] == 'text') ? 'selected' : '' }}>Text</option>
                                        <option value="video" {{ (isset($c['type']) && $c['type'] == 'video') ? 'selected' : '' }}>Video</option>
                                        <option value="image" {{ (isset($c['type']) && $c['type'] == 'image') ? 'selected' : '' }}>Image</option>
                                    </select>
                                </div>

                                <!-- Resource Content (Dynamic) -->
                                <div class="mb-3">
                                    <label class="resource-label block text-sm font-medium text-gray-700 mb-1">Content</label>
                                    
                                    <!-- Text Content -->
                                    <textarea name="contents[{{ $i }}][content]" 
                                              class="resource-textarea w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                                              placeholder="Enter text content here..."
                                              style="display: {{ (isset($c['type']) && $c['type'] == 'text') ? 'block' : 'none' }}">{{ $c['content'] ?? '' }}</textarea>

                                    <!-- Video File Upload -->
                                    <input type="file" name="contents[{{ $i }}][file]" 
                                           class="resource-file-input w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                                           accept="video/*"
                                           style="display: {{ (isset($c['type']) && $c['type'] == 'video') ? 'block' : 'none' }}">
                                    <small class="text-gray-500 video-help" style="display: {{ (isset($c['type']) && $c['type'] == 'video') ? 'block' : 'none' }}">Supported: MP4, WebM, OGG (Max 500MB)</small>

                                    <!-- Image File Upload -->
                                    <input type="file" name="contents[{{ $i }}][file]" 
                                           class="resource-file-input w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                                           accept="image/*"
                                           style="display: {{ (isset($c['type']) && $c['type'] == 'image') ? 'block' : 'none' }}">
                                    <small class="text-gray-500 image-help" style="display: {{ (isset($c['type']) && $c['type'] == 'image') ? 'block' : 'none' }}">Supported: JPG, PNG, GIF, WebP (Max 10MB)</small>
                                </div>

                                <!-- Resource Icon Preview -->
                                <div class="mt-2 p-2 bg-blue-50 rounded flex items-center gap-2 text-xs text-blue-700">
                                    <i class="fas fa-info-circle"></i>
                                    <span class="resource-preview">
                                        @if(isset($c['type']))
                                            @if($c['type'] == 'text')
                                                <i class="fas fa-file-alt"></i> Text resource
                                            @elseif($c['type'] == 'video')
                                                <i class="fas fa-video"></i> Video resource
                                            @elseif($c['type'] == 'image')
                                                <i class="fas fa-image"></i> Image resource
                                            @endif
                                        @else
                                            Select a type to begin
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="resource-item mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-medium text-gray-700">Resource 1</h3>
                                <button type="button" class="text-red-600 hover:text-red-800" onclick="removeResource(this)" style="display: none;">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>

                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Resource Title</label>
                                <input type="text" name="contents[0][title]" 
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" 
                                       placeholder="e.g., Introduction Video">
                            </div>

                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Resource Type</label>
                                <select name="contents[0][type]" required class="resource-type-select"
                                        onchange="updateResourceField(this)"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                                    <option value="">Select Type</option>
                                    <option value="text">Text</option>
                                    <option value="video">Video</option>
                                    <option value="image">Image</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="resource-label block text-sm font-medium text-gray-700 mb-1">Content</label>
                                
                                <textarea name="contents[0][content]" 
                                          class="resource-textarea w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                                          placeholder="Enter text content here..."
                                          style="display: none;"></textarea>

                                <input type="file" name="contents[0][file]" 
                                       class="resource-file-input w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                                       accept="video/*"
                                       style="display: none;">
                                <small class="text-gray-500 video-help" style="display: none;">Supported: MP4, WebM, OGG (Max 500MB)</small>

                                <input type="file" name="contents[0][file]" 
                                       class="resource-file-input w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                                       accept="image/*"
                                       style="display: none;">
                                <small class="text-gray-500 image-help" style="display: none;">Supported: JPG, PNG, GIF, WebP (Max 10MB)</small>
                            </div>

                            <div class="mt-2 p-2 bg-blue-50 rounded flex items-center gap-2 text-xs text-blue-700">
                                <i class="fas fa-info-circle"></i>
                                <span class="resource-preview">Select a type to begin</span>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addResourceField()" class="text-blue-600 hover:underline text-sm">
                    + Add Resource
                </button>
            </div>

            <!-- Learning Outcomes -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Learning Outcomes</h2>
                <div id="outcomes-container">
                    @if(old('learning_outcomes'))
                        @foreach(old('learning_outcomes') as $i => $lo)
                            <div class="flex items-center gap-2 mb-2">
                                <select name="learning_outcomes[{{ $i }}][difficulty_level]"
                                        class="px-2 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 text-xs">
                                    <option value="easy" {{ (isset($lo['difficulty_level']) && $lo['difficulty_level'] == 'easy') ? 'selected' : '' }}>Easy</option>
                                    <option value="medium" {{ (isset($lo['difficulty_level']) && $lo['difficulty_level'] == 'medium') ? 'selected' : '' }}>Medium</option>
                                    <option value="hard" {{ (isset($lo['difficulty_level']) && $lo['difficulty_level'] == 'hard') ? 'selected' : '' }}>Hard</option>
                                </select>
                                <input type="text" name="learning_outcomes[{{ $i }}][outcome]" required
                                       class="flex-1 px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" 
                                       value="{{ $lo['outcome'] ?? '' }}">
                                <button type="button" class="text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="flex gap-2 mt-3">
                    <input type="text" id="new-outcome" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                           placeholder="Add a new learning outcome">
                    <select id="new-outcome-level" class="px-2 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 text-xs">
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                    <button type="button" onclick="addLearningOutcome()" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        Add
                    </button>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-2">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300" onclick="history.back()">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save Subtopic
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let resourceIndex = {{ count(old('contents', [])) ? count(old('contents', [])) : 1 }};
    let outcomeIndex = {{ count(old('learning_outcomes', [])) }};

    function updateResourceField(selectElement) {
        const container = selectElement.closest('.resource-item');
        const type = selectElement.value;
        const textarea = container.querySelector('.resource-textarea');
        const fileInputs = container.querySelectorAll('.resource-file-input');
        const videoHelp = container.querySelector('.video-help');
        const imageHelp = container.querySelector('.image-help');
        const preview = container.querySelector('.resource-preview');
        const label = container.querySelector('.resource-label');

        // Hide all by default
        textarea.style.display = 'none';
        fileInputs.forEach(f => f.style.display = 'none');
        if (videoHelp) videoHelp.style.display = 'none';
        if (imageHelp) imageHelp.style.display = 'none';

        if (type === 'text') {
            textarea.style.display = 'block';
            textarea.setAttribute('required', 'required');
            textarea.placeholder = 'Enter text content here...';
            label.textContent = 'Content (Text)';
            preview.innerHTML = '<i class="fas fa-file-alt"></i> Text resource';
        } else if (type === 'video') {
            fileInputs[0].style.display = 'block';
            fileInputs[0].setAttribute('required', 'required');
            if (videoHelp) videoHelp.style.display = 'block';
            label.textContent = 'Video File';
            preview.innerHTML = '<i class="fas fa-video"></i> Video resource';
        } else if (type === 'image') {
            fileInputs[1].style.display = 'block';
            fileInputs[1].setAttribute('required', 'required');
            if (imageHelp) imageHelp.style.display = 'block';
            label.textContent = 'Image File';
            preview.innerHTML = '<i class="fas fa-image"></i> Image resource';
        } else {
            textarea.removeAttribute('required');
            fileInputs.forEach(f => f.removeAttribute('required'));
            label.textContent = 'Content';
            preview.textContent = 'Select a type to begin';
        }
    }

    function addResourceField() {
        const container = document.getElementById('resources-container');
        const newField = document.createElement('div');
        newField.className = 'resource-item mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50';
        newField.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-medium text-gray-700">Resource ${resourceIndex + 1}</h3>
                <button type="button" class="text-red-600 hover:text-red-800" onclick="removeResource(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Resource Title</label>
                <input type="text" name="contents[${resourceIndex}][title]" 
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" 
                       placeholder="e.g., Introduction Video">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Resource Type</label>
                <select name="contents[${resourceIndex}][type]" required class="resource-type-select"
                        onchange="updateResourceField(this)"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                    <option value="">Select Type</option>
                    <option value="text">Text</option>
                    <option value="video">Video</option>
                    <option value="image">Image</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="resource-label block text-sm font-medium text-gray-700 mb-1">Content</label>
                
                <textarea name="contents[${resourceIndex}][content]" 
                          class="resource-textarea w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                          placeholder="Enter text content here..."
                          style="display: none;"></textarea>

                <input type="file" name="contents[${resourceIndex}][file]" 
                       class="resource-file-input w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                       accept="video/*"
                       style="display: none;">
                <small class="text-gray-500 video-help" style="display: none;">Supported: MP4, WebM, OGG (Max 500MB)</small>

                <input type="file" name="contents[${resourceIndex}][file]" 
                       class="resource-file-input w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                       accept="image/*"
                       style="display: none;">
                <small class="text-gray-500 image-help" style="display: none;">Supported: JPG, PNG, GIF, WebP (Max 10MB)</small>
            </div>

            <div class="mt-2 p-2 bg-blue-50 rounded flex items-center gap-2 text-xs text-blue-700">
                <i class="fas fa-info-circle"></i>
                <span class="resource-preview">Select a type to begin</span>
            </div>
        `;
        container.appendChild(newField);
        resourceIndex++;
    }

    function removeResource(btn) {
        btn.closest('.resource-item').remove();
    }

    function addLearningOutcome() {
        const container = document.getElementById('outcomes-container');
        const input = document.getElementById('new-outcome');
        const level = document.getElementById('new-outcome-level');
        if (input.value.trim()) {
            const newOutcome = document.createElement('div');
            newOutcome.className = 'flex items-center gap-2 mb-2';
            newOutcome.innerHTML = `
                <select name="learning_outcomes[${outcomeIndex}][difficulty_level]"
                        class="px-2 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 text-xs">
                    <option value="easy" ${level.value === 'easy' ? 'selected' : ''}>Easy</option>
                    <option value="medium" ${level.value === 'medium' ? 'selected' : ''}>Medium</option>
                    <option value="hard" ${level.value === 'hard' ? 'selected' : ''}>Hard</option>
                </select>
                <input type="text" name="learning_outcomes[${outcomeIndex}][outcome]" required
                       class="flex-1 px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" value="${input.value}">
                <button type="button" class="text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newOutcome);
            input.value = '';
            outcomeIndex++;
        }
    }

    // Initialize all resource fields on page load
    document.querySelectorAll('.resource-type-select').forEach(select => {
        if (select.value) {
            updateResourceField(select);
        }
    });
</script>
@endsection