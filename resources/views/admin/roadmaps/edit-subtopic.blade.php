@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <div class="mb-6">
        <button onclick="history.back()" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:underline"><i class="fas fa-arrow-left"></i> Back</button>
        <h1 class="text-2xl font-bold mt-4">Edit Subtopic</h1>
    </div>

    <div class="bg-white rounded-xl p-6">
        <form action="{{ route('admin.roadmaps.update-subtopic', $subtopic->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subtopic Code</label>
                <input name="subtopic_code" value="{{ old('subtopic_code', $subtopic->subtopic_code) }}" class="w-full px-3 py-2 border rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subtopic Name</label>
                <input name="subtopic_name" value="{{ old('subtopic_name', $subtopic->subtopic_name) }}" class="w-full px-3 py-2 border rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" class="w-full px-3 py-2 border rounded-lg">{{ old('description', $subtopic->description) }}</textarea>
            </div>

            <!-- For brevity: resources and outcomes lists (replace-on-save) -->
            <div>
                <h3 class="font-semibold mb-2">Resources</h3>
                <div id="resources-container">
                    @foreach($subtopic->resources as $i => $r)
                        <div class="resource-item mb-4 p-3 border rounded-lg bg-gray-50" data-index="{{ $i }}">
                            <input type="hidden" name="resources[{{ $i }}][id]" value="{{ $r->id }}">
                            <div class="flex gap-2 mb-2 items-center">
                                <select name="resources[{{ $i }}][type]" class="w-1/4 px-2 py-1 border rounded resource-type-select" onchange="updateResourceField(this)">
                                    <option value="text" {{ $r->type === 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="video" {{ $r->type === 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="image" {{ $r->type === 'image' ? 'selected' : '' }}>Image</option>
                                </select>

                                <input type="text" name="resources[{{ $i }}][title]" value="{{ old("resources.$i.title", $r->title ?? '') }}" placeholder="Title (optional)" class="flex-1 px-2 py-1 border rounded" />
                                <button type="button" class="text-red-600 hover:text-red-800" onclick="this.closest('.resource-item').remove()">Remove</button>
                            </div>

                            <!-- Content areas -->
                            <div class="resource-fields">
                                <!-- Text -->
                                <textarea name="resources[{{ $i }}][content]" class="resource-textarea w-full px-2 py-2 border rounded mb-2" style="display: {{ $r->type === 'text' ? 'block' : 'none' }};">{{ old("resources.$i.content", $r->content) }}</textarea>

                                <!-- File input (shared name resources[i][file]) -->
                                <div class="file-inputs" style="display: {{ $r->type === 'text' ? 'none' : 'block' }};">
                                    <input type="file" name="resources[{{ $i }}][file]" accept="video/*,image/*" class="resource-file-input block w-full mb-1" />
                                    <small class="text-gray-500 block mb-2">
                                        @if($r->type === 'video') Supported: MP4, WebM, OGG (Max 500MB)
                                        @elseif($r->type === 'image') Supported: JPG, PNG, GIF, WEBP (Max 10MB)
                                        @endif
                                    </small>

                                    <div class="existing-preview">
                                        @if($r->type === 'video' && $r->url)
                                            <video controls class="w-full rounded max-h-64 bg-black">
                                                <source src="{{ asset('storage/' . $r->url) }}">
                                                Your browser does not support the video tag.
                                            </video>
                                        @elseif($r->type === 'image' && $r->url)
                                            <img src="{{ asset('storage/' . $r->url) }}" alt="preview" class="w-full rounded max-h-48 object-contain bg-gray-100" />
                                        @elseif($r->type !== 'text' && $r->url)
                                            <div class="text-sm text-gray-600 break-words">{{ $r->url }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div id="new-resources"></div>
                </div>
                <button type="button" onclick="addResourceEdit()" class="text-admin-primary text-sm mt-2">+ Add resource</button>
            </div>

            <div>
                <h3 class="font-semibold mb-2">Learning Outcomes</h3>
                <div id="outcomes-container">
                    @foreach($subtopic->learningOutcomes as $j => $lo)
                        <div class="mb-2">
                            <input name="learning_outcomes[{{ $j }}][outcome]" value="{{ $lo->outcome }}" class="w-full px-2 py-1 border rounded mb-1" />
                            <select name="learning_outcomes[{{ $j }}][difficulty_level]" class="px-2 py-1 border rounded">
                                <option value="easy" {{ $lo->difficulty_level == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ $lo->difficulty_level == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ $lo->difficulty_level == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                        </div>
                    @endforeach
                    <div id="new-outcomes"></div>
                </div>
                <button type="button" onclick="addOutcomeEdit()" class="text-admin-primary text-sm mt-2">+ Add outcome</button>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-admin-primary text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    let resIndex = {{ $subtopic->resources->count() }};
    function updateResourceField(select){
        const container = select.closest('.resource-item');
        const type = select.value;
        const textarea = container.querySelector('.resource-textarea');
        const fileInputs = container.querySelector('.file-inputs');
        const preview = container.querySelector('.existing-preview');

        if(type === 'text'){
            if(textarea) textarea.style.display = 'block';
            if(fileInputs) fileInputs.style.display = 'none';
        } else {
            if(textarea) textarea.style.display = 'none';
            if(fileInputs) fileInputs.style.display = 'block';
        }
        // update small helper text based on type
        if(fileInputs){
            const small = fileInputs.querySelector('small');
            if(small){
                small.textContent = type === 'video' ? 'Supported: MP4, WebM, OGG (Max 500MB)' : 'Supported: JPG, PNG, GIF, WEBP (Max 10MB)';
            }
        }
    }

    function addResourceEdit(){
        const container = document.getElementById('new-resources');
        const idx = resIndex++;
        const html = `<div class="resource-item mb-4 p-3 border rounded-lg bg-gray-50" data-index="${idx}">
            <div class="flex gap-2 mb-2 items-center">
                <select name="resources[${idx}][type]" class="w-1/4 px-2 py-1 border rounded resource-type-select" onchange="updateResourceField(this)">
                    <option value="text">Text</option>
                    <option value="video">Video</option>
                    <option value="image">Image</option>
                </select>
                <input type="text" name="resources[${idx}][title]" placeholder="Title (optional)" class="flex-1 px-2 py-1 border rounded" />
                <button type="button" class="text-red-600 hover:text-red-800" onclick="this.closest('.resource-item').remove()">Remove</button>
            </div>
            <div class="resource-fields">
                <textarea name="resources[${idx}][content]" class="resource-textarea w-full px-2 py-2 border rounded mb-2" style="display:block;" placeholder="Enter text content"></textarea>
                <div class="file-inputs" style="display:none;">
                    <input type="file" name="resources[${idx}][file]" accept="video/*,image/*" class="resource-file-input block w-full mb-1" />
                    <small class="text-gray-500 block mb-2">Select file</small>
                    <div class="existing-preview"></div>
                </div>
            </div>
        </div>`;
        container.insertAdjacentHTML('beforeend', html);
    }

    let outIndex = {{ $subtopic->learningOutcomes->count() }};
    function addOutcomeEdit(){
        const container = document.getElementById('new-outcomes');
        const idx = outIndex++;
        const html = `<div class="mb-2"><input name="learning_outcomes[${idx}][outcome]" class="w-full px-2 py-1 border rounded mb-1" />
            <select name="learning_outcomes[${idx}][difficulty_level]" class="px-2 py-1 border rounded">
                <option value="easy">Easy</option><option value="medium" selected>Medium</option><option value="hard">Hard</option>
            </select></div>`;
        container.insertAdjacentHTML('beforeend', html);
    }

    // initialize existing selects on load
    document.querySelectorAll('.resource-type-select').forEach(select => {
        updateResourceField(select);
        // attach change listener for file preview
        const container = select.closest('.resource-item');
        const fileInput = container ? container.querySelector('.resource-file-input') : null;
        if(fileInput){
            fileInput.addEventListener('change', function(e){
                const file = this.files[0];
                const previewEl = container.querySelector('.existing-preview');
                if(!file || !previewEl) return;
                const url = URL.createObjectURL(file);
                // clear
                previewEl.innerHTML = '';
                if(file.type.startsWith('image/')){
                    previewEl.innerHTML = `<img src="${url}" class="w-full rounded max-h-48 object-contain bg-gray-100" />`;
                } else if(file.type.startsWith('video/')){
                    previewEl.innerHTML = `<video controls class="w-full rounded max-h-64 bg-black"><source src="${url}"></video>`;
                } else {
                    previewEl.textContent = file.name;
                }
            });
        }
    });
    
</script>
@endsection