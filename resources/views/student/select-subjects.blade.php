<x-app-layout>
<div class="p-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Select Subjects — {{ $grade }} / {{ $language }}</h1>

    <form method="POST" action="{{ route('student.store-subjects') }}">
        @csrf
        <input type="hidden" name="grade" value="{{ $grade }}">
        <input type="hidden" name="language" value="{{ $language }}">

        <p class="text-sm text-gray-600 mb-4">Choose one subsubject for mains that have subsubjects. Main subjects without subsubjects can be selected directly.</p>

        <div class="space-y-4">
            @foreach($mainSubjects as $main)
                @php
                    // find selected child (if any)
                    $selectedChild = null;
                    if ($main->children && $main->children->count()) {
                        foreach ($main->children as $sub) {
                            if (in_array($sub->id, $current ?? [])) {
                                $selectedChild = $sub;
                                break;
                            }
                        }
                    }
                    // is this main itself selected (no children case)
                    $selectedMain = in_array($main->id, $current ?? []);
                    // visual classes
                    $blockBase = 'border rounded p-3';
                    $selectedBlock = 'border-2 border-green-500 bg-green-50';
                    $blockClass = ($selectedChild || $selectedMain) ? $blockBase . ' ' . $selectedBlock : $blockBase;
                @endphp

                <div class="{{ $blockClass }}">
                    <div class="font-semibold mb-2">
                        {{ $main->subject_name }} <span class="text-xs text-gray-500">({{ $main->subject_code }})</span>
                    </div>

                    @if($main->children && $main->children->count())
                        @if($selectedChild)
                            <!-- already selected subsubject: show non-editable display and include hidden input so value submits -->
                            <div class="text-sm text-gray-700">
                                <strong>Selected:</strong>
                                <span class="ml-2">{{ $selectedChild->subject_name }} <span class="text-xs text-gray-500">({{ $selectedChild->subject_code }})</span></span>
                            </div>
                            <input type="hidden" name="subject_ids[]" value="{{ $selectedChild->id }}">
                        @else
                            <!-- choose one subsubject (single-select) -->
                            <label class="block text-sm text-gray-700 mb-1">Choose a subsubject</label>
                            <select name="subject_ids[]" class="w-full border rounded px-2 py-2">
                                <option value="">-- Select subsubject for {{ $main->subject_name }} --</option>
                                @foreach($main->children as $sub)
                                    <option value="{{ $sub->id }}" {{ in_array($sub->id, $current ?? []) ? 'selected' : '' }}>
                                        {{ $sub->subject_name }} ({{ $sub->subject_code }})
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    @else
                        @if($selectedMain)
                            <!-- main subject already selected: show non-editable selected block -->
                            <div class="text-sm text-gray-700">
                                <strong>Selected</strong>
                                <span class="ml-2">{{ $main->subject_name }} <span class="text-xs text-gray-500">({{ $main->subject_code }})</span></span>
                            </div>
                            <input type="hidden" name="subject_ids[]" value="{{ $main->id }}">
                        @else
                            <!-- main without children: allow selecting (checkbox) -->
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="subject_ids[]" value="{{ $main->id }}">
                                <span>Select {{ $main->subject_name }} ({{ $main->subject_code }})</span>
                            </label>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('student.select-subjects') }}" class="px-4 py-2 border rounded text-gray-700">Back</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Confirm Subjects</button>
        </div>
    </form>
</div>
</x-app-layout>