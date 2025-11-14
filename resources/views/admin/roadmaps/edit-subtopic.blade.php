
@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <div class="mb-6">
        <button onclick="history.back()" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:underline"><i class="fas fa-arrow-left"></i> Back</button>
        <h1 class="text-2xl font-bold mt-4">Edit Subtopic</h1>
    </div>

    <div class="bg-white rounded-xl p-6">
        <form action="{{ route('admin.roadmaps.update-subtopic', $subtopic->id) }}" method="POST" class="space-y-6">
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

            <!-- For brevity: simple resources and outcomes lists (replace-on-save) -->
            <div>
                <h3 class="font-semibold mb-2">Resources</h3>
                <div id="resources-container">
                    @foreach($subtopic->resources as $i => $r)
                        <div class="flex gap-2 mb-2">
                            <select name="resources[{{ $i }}][type]" class="w-1/3 px-2 py-1 border rounded">
                                <option value="text" {{ $r->type === 'text' ? 'selected' : '' }}>Text</option>
                                <option value="video" {{ $r->type === 'video' ? 'selected' : '' }}>Video</option>
                                <option value="image" {{ $r->type === 'image' ? 'selected' : '' }}>Image</option>
                            </select>
                            <input type="text" name="resources[{{ $i }}][content]" value="{{ $r->content }}" class="w-2/3 px-2 py-1 border rounded" />
                        </div>
                    @endforeach
                    <div id="new-resources"></div>
                </div>
                <button type="button" onclick="addResourceEdit()" class="text-admin-primary text-sm">+ Add resource</button>
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
                <button type="button" onclick="addOutcomeEdit()" class="text-admin-primary text-sm">+ Add outcome</button>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-admin-primary text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    let resIndex = {{ $subtopic->resources->count() }};
    function addResourceEdit(){
        const container = document.getElementById('new-resources');
        const idx = resIndex++;
        const html = `<div class="flex gap-2 mb-2">
            <select name="resources[${idx}][type]" class="w-1/3 px-2 py-1 border rounded">
                <option value="text">Text</option><option value="video">Video</option><option value="image">Image</option>
            </select>
            <input type="text" name="resources[${idx}][content]" class="w-2/3 px-2 py-1 border rounded" />
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
</script>
@endsection