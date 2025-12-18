@extends('layouts.teacher')

@section('title', 'My Gigs')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">My Gigs</h1>
            <p class="text-gray-600 mt-1">Manage your teaching offerings</p>
        </div>
        <a href="{{ route('teacher.gigs.create') }}"
           class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 flex items-center gap-2 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Teaching Gig
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(Auth::user()->gigs->count() === 0)
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <p class="text-gray-500 text-lg mb-4">You haven't created any gigs yet.</p>
            <a href="{{ route('teacher.gigs.create') }}" class="text-indigo-600 hover:underline font-medium">
                Create your first gig →
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach(Auth::user()->gigs as $gig)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-semibold">{{ $gig->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Grade {{ $gig->grade }} • {{ $gig->subject }}
                            </p>
                        </div>

                        <!-- Status Dropdown -->
                        <form action="{{ route('teacher.gigs.update-status', $gig) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="px-4 py-2 rounded-lg border text-sm font-medium focus:ring-2 focus:ring-indigo-500
                                        {{ $gig->status === 'active' ? 'bg-green-100 text-green-800 border-green-300' :
                                           ($gig->status === 'draft' ? 'bg-yellow-100 text-yellow-800 border-yellow-300' :
                                           'bg-gray-100 text-gray-800 border-gray-300') }}">
                                <option value="active" {{ $gig->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="draft" {{ $gig->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="disabled" {{ $gig->status === 'disabled' ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </form>
                    </div>

                    <p class="text-gray-600 mb-4 line-clamp-3">{{ $gig->description }}</p>

                    <div class="text-sm space-y-2 text-gray-600">
                        <p><strong>Languages:</strong> {{ implode(', ', $gig->languages) }}</p>
                        <p><strong>Duration:</strong> {{ $gig->session_duration }} minutes</p>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <a href="#" class="flex-1 text-center bg-gray-100 py-2 rounded-lg hover:bg-gray-200 text-sm font-medium">
                            View Details
                        </a>
                        <a href="#" class="flex-1 text-center bg-indigo-100 text-indigo-700 py-2 rounded-lg hover:bg-indigo-200 text-sm font-medium">
                            Edit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection