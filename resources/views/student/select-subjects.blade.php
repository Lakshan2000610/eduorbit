{{-- resources/views/student/select-subjects-preview.blade.php --}}
<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50 py-12 px-4">
        <div class="max-w-5xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 mb-6 shadow-xl">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">
                    Choose Your Subjects
                </h1>
                <p class="text-xl text-gray-600">
                    <span class="font-semibold">{{ $grade }}</span> • {{ $language }} Medium
                </p>
                <p class="mt-3 text-gray-600 max-w-2xl mx-auto">
                    Select the subjects you are currently studying. For subjects with streams (e.g., Science → Physics/Chemistry), choose one stream.
                </p>
            </div>

            @php
                $hasSubjects = isset($mainSubjects) && $mainSubjects->count() > 0;
            @endphp

            <form method="POST" action="{{ route('student.store-subjects') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="grade" value="{{ $grade }}">
                <input type="hidden" name="language" value="{{ $language }}">

                @if(!$hasSubjects)
                    <!-- No Subjects Found -->
                    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-l-8 border-amber-400 rounded-r-2xl p-8 text-center">
                        <p class="text-2xl font-bold text-amber-800 mb-2">No subjects found</p>
                        <p class="text-amber-700">
                            There are no subjects configured yet for <strong>{{ $grade }}</strong> in <strong>{{ $language }}</strong>.
                        </p>
                    </div>
                @else
                    <!-- Subjects Grid -->
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach($mainSubjects as $main)
                            @php
                                $hasChildren = $main->children && $main->children->count();
                                $selectedChild = null;
                                $selectedMain = in_array($main->id, $current ?? []);

                                if ($hasChildren) {
                                    foreach ($main->children as $sub) {
                                        if (in_array($sub->id, $current ?? [])) {
                                            $selectedChild = $sub;
                                            break;
                                        }
                                    }
                                }

                                $isSelected = $selectedChild || $selectedMain;
                            @endphp

                            <div class="bg-white rounded-2xl shadow-lg border-2 transition-all duration-300
                                        {{ $isSelected ? 'border-green-500 ring-4 ring-green-100' : 'border-gray-200 hover:border-blue-300 hover:shadow-xl' }}">

                                <div class="p-6">
                                    <!-- Main Subject Title -->
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-xl font-bold text-gray-800">
                                            {{ $main->subject_name }}
                                            <span class="text-sm font-normal text-gray-500">({{ $main->subject_code }})</span>
                                        </h3>
                                        @if($isSelected)
                                            <span class="flex items-center gap-2 text-green-600 font-bold">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Selected
                                            </span>
                                        @endif
                                    </div>

                                    @if($hasChildren)
                                        <!-- Has Subsubjects -->
                                        @if($selectedChild)
                                            <!-- Already Selected Subsubject -->
                                            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                                <p class="text-sm text-green-700 font-medium">Selected stream:</p>
                                                <p class="text-lg font-bold text-green-800 mt-1">
                                                    {{ $selectedChild->subject_name }}
                                                    <span class="text-sm font-normal">({{ $selectedChild->subject_code }})</span>
                                                </p>
                                                <input type="hidden" name="subject_ids[]" value="{{ $selectedChild->id }}">
                                            </div>
                                        @else
                                            <!-- Let user choose one -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Choose your stream:
                                                </label>
                                                <select name="subject_ids[]" required
                                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition">
                                                    <option value="">-- Select stream for {{ $main->subject_name }} --</option>
                                                    @foreach($main->children as $sub)
                                                        <option value="{{ $sub->id }}">
                                                            {{ $sub->subject_name }} ({{ $sub->subject_code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @else
                                        <!-- No Children → Simple Checkbox -->
                                        @if($selectedMain)
                                            <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
                                                <p class="text-green-800 font-bold">This subject is selected</p>
                                                <input type="hidden" name="subject_ids[]" value="{{ $main->id }}">
                                            </div>
                                        @else
                                            <label class="flex items-center justify-center gap-3 cursor-pointer py-4">
                                                <input type="checkbox" name="subject_ids[]" value="{{ $main->id }}"
                                                       class="w-6 h-6 text-blue-600 rounded-lg focus:ring-blue-500">
                                                <span class="text-lg font-medium text-gray-700">
                                                    Yes, I study {{ $main->subject_name }}
                                                </span>
                                            </label>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-6 mt-12 bg-white rounded-2xl p-6 shadow-lg border">
                    <a href="{{ route('student.select-subjects') }}"
                       class="text-gray-600 hover:text-gray-900 font-medium text-lg flex items-center gap-2 transition">
                        ← Change Grade / Language
                    </a>

                    @if($hasSubjects)
                        <button type="submit"
                                class="px-10 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold text-xl rounded-2xl shadow-xl transition transform hover:scale-105">
                            Confirm & Continue →
                        </button>
                    @else
                        <a href="{{ route('student.select-subjects') }}"
                           class="px-8 py-3 bg-gray-300 text-gray-600 rounded-xl font-medium">
                            Go Back
                        </a>
                    @endif
                </div>
            </form>

            <!-- Footer Hint -->
            <div class="text-center mt-10 text-gray-500">
                <p>You can always change your subjects later from your dashboard.</p>
            </div>
        </div>
    </div>
</x-app-layout>