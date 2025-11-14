<x-app-layout>
<div class="p-6 max-w-3xl mx-auto text-center">
    <h1 class="text-3xl font-bold mb-2">Let’s Get Started</h1>
    <p class="text-gray-600 mb-6">Please select your current grade to view your personalized syllabus roadmap.</p>

    <form method="POST" action="{{ route('student.select-subjects.preview') }}" x-data="{ grade: '', language: '' }">
        @csrf

        {{-- Grade Selection --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8">
            @for($i = 1; $i <= 13; $i++)
                <div 
                    @click="grade = 'Grade {{ $i }}'"
                    :class="grade === 'Grade {{ $i }}' ? 'border-blue-500 text-blue-500 ring-2 ring-blue-300' : 'border-gray-300 text-gray-700 hover:border-blue-400 hover:text-blue-500'"
                    class="cursor-pointer border rounded-xl py-4 font-semibold transition duration-200"
                >
                    Grade {{ $i }}
                    <input type="radio" name="grade" value="Grade {{ $i }}" x-model="grade" class="hidden" />
                </div>
            @endfor
            <div 
                @click="grade = 'Other'"
                :class="grade === 'Other' ? 'border-blue-500 text-blue-500 ring-2 ring-blue-300' : 'border-gray-300 text-gray-700 hover:border-blue-400 hover:text-blue-500'"
                class="cursor-pointer border rounded-xl py-4 font-semibold transition duration-200"
            >
                Other
                <input type="radio" name="grade" value="Other" x-model="grade" class="hidden" />
            </div>
        </div>

        {{-- Language Selection --}}
        <div x-show="grade" class="mb-8 transition duration-300">
            <h2 class="text-xl font-semibold mb-4">Select Language</h2>
            <div class="flex flex-wrap justify-center gap-4">
                @foreach(['English', 'Tamil', 'Sinhala'] as $lang)
                    <div 
                        @click="language = '{{ $lang }}'"
                        :class="language === '{{ $lang }}' ? 'bg-blue-500 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:border-blue-400 hover:text-blue-500'"
                        class="cursor-pointer rounded-lg px-6 py-2 font-medium transition duration-200"
                    >
                        {{ $lang }}
                        <input type="radio" name="language" value="{{ $lang }}" x-model="language" class="hidden" />
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end mt-8 gap-3">
            <a href="{{ url('/') }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">Cancel</a>
            <button type="submit" 
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
                :disabled="!grade || !language"
                :class="(!grade || !language) ? 'opacity-50 cursor-not-allowed' : ''"
            >
                Next: Choose Subjects
            </button>
        </div>
    </form>
</div>
</x-app-layout>
