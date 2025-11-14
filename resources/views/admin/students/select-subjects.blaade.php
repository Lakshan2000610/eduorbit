
@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Select Subjects</h1>

    <form method="POST" action="{{ route('student.store-subjects') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium">Grade</label>
                <select name="grade" required class="w-full border rounded px-3 py-2">
                    @for($i=1;$i<=13;$i++)
                        <option value="Grade {{ $i }}" {{ (isset($grade) && $grade == "Grade $i") ? 'selected' : '' }}>Grade {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Language</label>
                <select name="language" required class="w-full border rounded px-3 py-2">
                    <option value="Sinhala" {{ (isset($language) && $language=='Sinhala') ? 'selected' : '' }}>Sinhala</option>
                    <option value="English" {{ (isset($language) && $language=='English') ? 'selected' : '' }}>English</option>
                    <option value="Tamil" {{ (isset($language) && $language=='Tamil') ? 'selected' : '' }}>Tamil</option>
                </select>
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-4">Select the subsubjects (or main subjects without subsubjects) you want to learn.</p>

        <div class="space-y-4">
            @foreach($mainSubjects as $main)
                <div class="border rounded p-3">
                    <div class="font-semibold">{{ $main->subject_name }} <span class="text-xs text-gray-500">({{ $main->subject_code }})</span></div>

                    @if($main->children && $main->children->count())
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($main->children as $sub)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="subject_ids[]" value="{{ $sub->id }}"
                                        {{ in_array($sub->id, $current ?? []) ? 'checked' : '' }} />
                                    <span>{{ $sub->subject_name }} ({{ $sub->subject_code }})</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="subject_ids[]" value="{{ $main->id }}"
                                    {{ in_array($main->id, $current ?? []) ? 'checked' : '' }} />
                                <span>Select {{ $main->subject_name }}</span>
                            </label>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('student.dashboard') }}" class="px-4 py-2 border rounded text-gray-700">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Confirm Subjects</button>
        </div>
    </form>
</div>
@endsection