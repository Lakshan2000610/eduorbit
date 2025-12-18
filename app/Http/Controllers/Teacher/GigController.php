<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Gig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Subtopic;

class GigController extends Controller
{
    public function index()
    {
        $gigs = Auth::user()->gigs()->latest()->get();

        return view('teacher.gigs.index', compact('gigs'));
    }

    public function create()
    {
        $grades = range(1, 13);

        // Get distinct languages from subjects table
        $languages = Subject::distinct()->where('status', 'active')->pluck('language')->sort()->values();

        return view('teacher.gigs.create', compact('grades', 'languages'));
    }

    public function getSubjects(Request $request)
    {
        
        $grade = $request->get('grade');
        $languages = $request->get('languages') ? explode(',', $request->get('languages')) : [];
        
        if (!$grade || empty($languages)) {
            return response()->json([]);
        }
        
        $subjects = subject::where('grade', $grade)
            ->whereIn('language', $languages)
            ->orderBy('subject_name')
            ->get(['id', 'subject_name']);
        
        
        return response()->json($subjects);
    }

    public function getTopics(Request $request)
    {
        $subjectIds = $request->get('subject_ids') ? explode(',', $request->get('subject_ids')) : [];

        if (empty($subjectIds)) {
            return response()->json([]);
        }

        $topics = Topic::whereIn('subject_id', $subjectIds)
            ->orderBy('topic_name')
            ->get(['id', 'topic_name']);

        return response()->json($topics);
    }

    public function getSubtopics(Request $request)
    {
        $topicIds = $request->get('topic_ids') ? explode(',', $request->get('topic_ids')) : [];

        if (empty($topicIds)) {
            return response()->json([]);
        }

        $subtopics = Subtopic::whereIn('topic_id', $topicIds)
            ->orderBy('subtopic_name')
            ->get(['id', 'subtopic_name']);

        return response()->json($subtopics);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'grade' => 'required|integer|between:1,13',
            'languages' => 'required|array|min:1',
            'languages.*' => 'in:Sinhala,Tamil,English',
            'session_duration' => 'required|integer|min:30|max:180',
            'selected_subjects' => 'required|array|min:1',
            'selected_subjects.*' => 'exists:subjects,id',
            'selected_topics' => 'nullable|array',
            'selected_topics.*' => 'nullable|exists:topics,id',
            'selected_subtopics' => 'nullable|array',
            'selected_subtopics.*' => 'nullable|exists:subtopics,id',
        ]);

        Auth::user()->gigs()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'grade' => $validated['grade'],
            'languages' => $validated['languages'],
            'session_duration' => $validated['session_duration'],
            'selected_subjects' => $validated['selected_subjects'],
            'selected_topics' => $validated['selected_topics'] ?? null,
            'selected_subtopics' => $validated['selected_subtopics'] ?? null,
            'status' => 'active',
        ]);

        return redirect()->route('teacher.gigs')->with('success', 'Gig created successfully!');
    }

    public function edit(Gig $gig)
    {
        if ($gig->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $grades = range(1, 13);

        // Get distinct languages from subjects table
        $languages = Subject::distinct()->where('status', 'active')->pluck('language')->sort()->values();

        return view('teacher.gigs.edit', compact('gig', 'grades', 'languages'));
    }

    public function update(Request $request, Gig $gig)
    {
        if ($gig->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'grade' => 'required|integer|between:1,13',
            'languages' => 'required|array|min:1',
            'languages.*' => 'in:Sinhala,Tamil,English',
            'session_duration' => 'required|integer|min:30|max:180',
            'selected_subjects' => 'required|array|min:1',
            'selected_subjects.*' => 'exists:subjects,id',
            'selected_topics' => 'nullable|array',
            'selected_topics.*' => 'nullable|exists:topics,id',
            'selected_subtopics' => 'nullable|array',
            'selected_subtopics.*' => 'nullable|exists:subtopics,id',
        ]);

        $gig->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'grade' => $validated['grade'],
            'languages' => $validated['languages'],
            'session_duration' => $validated['session_duration'],
            'selected_subjects' => $validated['selected_subjects'],
            'selected_topics' => $validated['selected_topics'] ?? null,
            'selected_subtopics' => $validated['selected_subtopics'] ?? null,
        ]);

        return redirect()->route('teacher.gigs')->with('success', 'Gig updated successfully!');
    }

    public function updateStatus(Request $request, Gig $gig)
    {
        if ($gig->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:active,draft,disabled'
        ]);

        $gig->update(['status' => $request->status]);

        return back()->with('success', 'Gig status updated successfully!');
    }
}