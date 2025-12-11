{{-- resources/views/student/select-grade.blade.php or wherever you have it --}}
<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-yellow-50 py-12 px-4">
        <div class="max-w-4xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 mb-6 shadow-xl">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Let’s Get Started!</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Please select your current grade and preferred language to see your personalized learning roadmap.
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 border border-gray-100">
                <form method="POST" action="{{ route('student.select-subjects.preview') }}" x-data="{ grade: '', language: '' }">
                    @csrf

                    <!-- Grade Selection -->
                    <div class="mb-12">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Select Your Grade</h2>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-5">
                            @for($i = 1; $i <= 13; $i++)
                                <label 
                                    @click="grade = 'Grade {{ $i }}'"
                                    :class="grade === 'Grade {{ $i }}' 
                                        ? 'bg-blue-500 text-white shadow-lg ring-4 ring-blue-200 scale-105' 
                                        : 'bg-white text-gray-700 hover:bg-blue-50 hover:border-blue-400'"
                                    class="cursor-pointer border-2 border-gray-200 rounded-2xl py-6 text-center font-bold text-lg transition-all duration-300 transform hover:scale-105"
                                >
                                    Grade {{ $i }}
                                    <input type="radio" name="grade" value="Grade {{ $i }}" x-model="grade" class="hidden" required />
                                </label>
                            @endfor

                            <!-- Other Option -->
                            <label 
                                @click="grade = 'Other'"
                                :class="grade === 'Other' 
                                    ? 'bg-blue-500 text-white shadow-lg ring-4 ring-blue-200 scale-105' 
                                    : 'bg-white text-gray-700 hover:bg-blue-50 hover:border-blue-400'"
                                class="cursor-pointer border-2 border-gray-200 rounded-2xl py-6 text-center font-bold text-lg transition-all duration-300 transform hover:scale-105"
                            >
                                Other
                                <input type="radio" name="grade" value="Other" x-model="grade" class="hidden" />
                            </label>
                        </div>
                    </div>

                    <!-- Language Selection -->
                    <div x-show="grade" x-transition class="mb-12">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Preferred Language</h2>
                        <div class="flex flex-wrap justify-center gap-6">
                            @foreach(['English', 'Sinhala', 'Tamil'] as $lang)
                                <label 
                                    @click="language = '{{ $lang }}'"
                                    :class="language === '{{ $lang }}' 
                                        ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-xl' 
                                        : 'bg-gray-50 text-gray-700 hover:bg-blue-50'"
                                    class="cursor-pointer rounded-2xl px-10 py-5 text-xl font-semibold transition-all duration-300 transform hover:scale-105"
                                >
                                    {{ $lang }}
                                    <input type="radio" name="language" value="{{ $lang }}" x-model="language" class="hidden" required />
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-10">
                        <a href="{{ url('/') }}" 
                           class="text-gray-600 hover:text-gray-800 font-medium text-lg transition flex items-center gap-2">
                            Back to Home
                        </a>

                        <button 
                            type="submit"
                            :disabled="!grade || !language"
                            :class="(!grade || !language) 
                                ? 'bg-gray-300 text-gray-500 cursor-not-allowed' 
                                : 'bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold shadow-xl'"
                            class="px-10 py-4 rounded-2xl text-xl transition-all transform hover:scale-105 disabled:scale-100 disabled:opacity-60"
                        >
                            Next: Choose Subjects →
                        </button>
                    </div>
                </form>
            </div>

            <!-- Fun note -->
            <p class="text-center text-gray-500 mt-10 text-sm">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Log in here</a>
            </p>
        </div>
    </div>
</x-app-layout>