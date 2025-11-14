@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-admin-background p-6">
    <!-- Page Header -->
    <div class="flex items-center gap-2 mb-8">
        <a href="{{ route('admin.roadmaps.create') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-admin-text">Manage Topics for: {{ $subject->subject_name }} ({{ $subject->subject_code }})</h1>
            <p class="text-sm text-admin-text-secondary mt-1">
                Manage the curriculum by adding, editing, and arranging topics.
            </p>
        </div>
    </div>

    <!-- Topic Management -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Topic Management</h2>
                <p class="text-sm text-gray-500">
                    Add, edit, and organize topics for this subject.
                </p>
            </div>

            <button type="button" onclick="openModal()"
                    class="inline-flex items-center justify-center gap-2 bg-admin-primary hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition">
                <i class="fas fa-plus"></i> Add New Topic
            </button>
        </div>

        <!-- Topic Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-t border-gray-100">
                <thead class="bg-gray-50 text-gray-700 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 font-medium">Topic Code</th>
                        <th class="px-4 py-3 font-medium">Topic Name</th>
                        <th class="px-4 py-3 font-medium">Subtopics</th>
                        <th class="px-4 py-3 font-medium">Subtopic Count</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($topics as $topic)
                        <tr>
                            <td class="px-4 py-3">{{ $topic->topic_code }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $topic->topic_name }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.roadmaps.subtopics.index', $topic->id) }}" class="text-admin-primary hover:underline">
                                    View Subtopics
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ $topic->subtopics->count() }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.roadmaps.edit-topic', $topic->id) }}" class="text-gray-600 hover:text-gray-800"><i class="fas fa-edit"></i></a>

                                <form action="{{ route('admin.roadmaps.delete-topic', $topic->id) }}" method="POST" class="inline-block" onsubmit="return confirm('This will delete the topic and all its subtopics. Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 text-sm text-gray-600">
            Showing 1 to {{ $topics->count() }} of {{ $topics->count() }} results
            <div class="float-right">
                <a href="#" class="px-2 py-1 border border-gray-200 rounded text-gray-600"><i class="fas fa-chevron-left"></i></a>
                <a href="#" class="px-2 py-1 bg-admin-primary text-white rounded mx-1">1</a>
                <a href="#" class="px-2 py-1 border border-gray-200 rounded text-gray-600 mx-1">2</a>
                <a href="#" class="px-2 py-1 border border-gray-200 rounded text-gray-600 mx-1">3</a>
                <a href="#" class="px-2 py-1 border border-gray-200 rounded text-gray-600"><i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Add Topic Modal -->
    <div id="addTopicModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <button type="button" onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Add New Topic</h3>

            <form action="{{ route('admin.roadmaps.store-topic', $subject->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="topic_suffix" class="block text-sm font-medium text-gray-700 mb-1">Topic Code</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-100 border border-gray-200 rounded-l-lg">
                            {{ $subject->subject_code }}-
                        </span>
                        <input type="text" id="topic_suffix" name="topic_suffix" required
                               class="rounded-none rounded-r-lg w-full px-3 py-2 border border-gray-200 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-admin-primary focus:border-transparent transition-all"
                               placeholder="e.g., AT32">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="topic_name" class="block text-sm font-medium text-gray-700 mb-1">Topic Name</label>
                    <input type="text" id="topic_name" name="topic_name" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-admin-primary focus:border-transparent transition-all"
                           placeholder="Enter topic name">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description"
                              class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-admin-primary focus:border-transparent transition-all"
                              placeholder="Enter topic description"></textarea>
                </div>

                <!-- Resources (Optional) -->
                <div class="mb-4">
                    <h4 class="text-sm font-bold text-gray-800 mb-2">Resources (Optional)</h4>
                    <div id="resources-container">
                        <div class="flex gap-2 mb-2">
                            <select name="resources[0][type]"
                                    class="w-1/3 px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                                <option value="">Type</option>
                                <option value="text">Text</option>
                                <option value="video">Video</option>
                                <option value="image">Image</option>
                            </select>
                            <input type="text" name="resources[0][content]"
                                   class="w-2/3 px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"
                                   placeholder="Enter content or URL">
                        </div>
                    </div>
                    <button type="button" onclick="addResourceField()" class="text-admin-primary text-sm hover:underline">
                        + Add Another Resource
                    </button>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-admin-primary text-white rounded-lg hover:bg-blue-700">
                        Save Topic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Resource field adder
    let resourceIndex = 1;
    function addResourceField() {
        const container = document.getElementById('resources-container');
        const newField = document.createElement('div');
        newField.className = 'flex gap-2 mb-2';
        newField.innerHTML = `
            <select name="resources[${resourceIndex}][type]" class="w-1/3 px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700">
                <option value="">Type</option>
                <option value="text">Text</option>
                <option value="video">Video</option>
                <option value="image">Image</option>
            </select>
            <input type="text" name="resources[${resourceIndex}][content]" class="w-2/3 px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" placeholder="Enter content or URL">
        `;
        container.appendChild(newField);
        resourceIndex++;
    }

    // Modal control
    function openModal() {
        const modal = document.getElementById('addTopicModal');
        modal.classList.remove('hidden');
    }
    function closeModal() {
        const modal = document.getElementById('addTopicModal');
        modal.classList.add('hidden');
    }
</script>
@endsection
