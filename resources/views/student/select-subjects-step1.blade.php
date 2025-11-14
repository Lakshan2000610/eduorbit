
<x-app-layout>
<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Choose Grade & Language</h1>

    <form method="POST" action="{{ route('student.select-subjects.preview') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium mb-1">Grade</label>
                <select name="grade" required class="w-full border rounded px-3 py-2">
                    @for($i=1;$i<=13;$i++)
                        <option value="Grade {{ $i }}">Grade {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Language</label>
                <select name="language" required class="w-full border rounded px-3 py-2">
                    <option value="Sinhala">Sinhala</option>
                    <option value="English">English</option>
                    <option value="Tamil">Tamil</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ url('/') }}" class="px-4 py-2 border rounded text-gray-700">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Next: Choose Subjects</button>
        </div>
    </form>
</div>
</x-app-layout>