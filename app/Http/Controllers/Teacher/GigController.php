<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Gig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Subtopic;
use App\Models\SubtopicPricing;

class GigController extends Controller
{
public function index()
{
    $gigs = Auth::user()->gigs()
        ->with(['languages', 'subjects.subject', 'subjects.topics.topic', 'subjects.topics.subtopics.subtopic'])
        ->latest()
        ->get()
        ->map(function ($gig) {
            $totalMinutes = $gig->subjects
                ->flatMap(fn($subject) => $subject->topics
                    ->flatMap(fn($topic) => [$topic->duration] + $topic->subtopics->pluck('duration')->toArray())
                )
                ->sum();
            $gig->total_duration_formatted = $this->formatTime($totalMinutes); // Add helper below
            return $gig;
        });

    return view('teacher.gigs.index', compact('gigs'));
}

// Add this helper method to GigController
private function formatTime($minutes)
{
    if ($minutes === 0) return '0M';
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;
    $str = $hours > 0 ? $hours . 'H ' : '';
    $str .= $mins > 0 ? $mins . 'M' : '';
    return trim($str);
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
        $gradeNumber = $request->get('grade'); // e.g., "1", "11"
        
        // Convert to the format stored in DB: "Grade 1", "Grade 11"
        $grade = $gradeNumber ? 'Grade ' . $gradeNumber : null;

        $languages = $request->get('languages') ? explode(',', $request->get('languages')) : [];

        if (!$grade || empty($languages)) {
            return response()->json([]);
        }

        $subjects = Subject::where('grade', $grade)
            ->whereIn('language', $languages)
            ->where('status', 'active')
            ->orderBy('subject_name')
            ->get(['id', 'subject_name', 'language']);

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
            ->get(['id', 'subtopic_name', 'topic_id']);

        return response()->json($subtopics);
    }

    public function getSubtopicPricing(Request $request)
    {
        $subtopicIds = $request->get('subtopic_ids') ? explode(',', $request->get('subtopic_ids')) : [];

        if (empty($subtopicIds)) {
            return response()->json([]);
        }

        $pricings = SubtopicPricing::whereIn('subtopic_id', $subtopicIds)
            ->get(['subtopic_id', 'min_price', 'max_price', 'currency'])
            ->keyBy('subtopic_id');

        return response()->json($pricings);
    }

public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'grade'            => 'required|integer|between:1,13',
            'languages'        => 'required|array|min:1',
            'languages.*'      => 'in:Sinhala,English,Tamil',
            'structured_data'  => 'required|json',
        ]);

        // Decode structured selections from frontend
        $selections = json_decode($request->structured_data, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($selections['selections'] ?? [])) {
            return back()->withErrors(['structured_data' => 'Invalid structured data.']);
        }

        $selections = $selections['selections'];

        if (empty($selections)) {
            return back()->withErrors(['structured_data' => 'At least one subject with topics is required.']);
        }

        // Create main gig
        $gig = Auth::user()->gigs()->create([
            'title'            => $validated['title'],
            'description'      => $validated['description'],
            'grade'            => $validated['grade'],
            'status'           => 'pending',
        ]);

        // Save languages
        foreach ($validated['languages'] as $language) {
            $gig->languages()->create(['language' => $language]);
        }

        // Save subjects + topics + subtopics with durations AND prices
        foreach ($selections as $selection) {
            if (empty($selection['topics'] ?? [])) {  // Skip empty topics
                continue;
            }
            $gigSubject = $gig->subjects()->create([
                'subject_id' => $selection['subject_id'],
            ]);

            foreach ($selection['topics'] as $topicData) {
                $gigTopic = $gigSubject->topics()->create([
                    'topic_id' => $topicData['topic_id'],
                    'duration' => $topicData['duration'] ?? 1,
                ]);

                if (!empty($topicData['subtopics'] ?? [])) {
                    foreach ($topicData['subtopics'] as $subtopicData) {
                        $gigTopic->subtopics()->create([  // Updated to include price
                            'subtopic_id' => $subtopicData['subtopic_id'],
                            'duration'    => $subtopicData['duration'] ?? 1,
                            'price'       => $subtopicData['price'] ?? null,  // From frontend slider
                        ]);
                    }
                }
            }
        }

        return redirect()->route('teacher.gigs')->with('success', 'Gig created successfully and pending approval!');
    }
    
 public function show(Gig $gig)
    {
        if ($gig->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load with all relations
        $gig->load([
            'languages',
            'subjects.subject',
            'subjects.topics.topic',
            'subjects.topics.subtopics.subtopic'
        ]);

        return view('teacher.gigs.show', compact('gig'));
    }

    public function edit(Gig $gig)
    {
        if ($gig->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $grades = range(1, 13);

        // Get distinct languages from subjects table
        $languages = Subject::distinct()->where('status', 'active')->pluck('language')->sort()->values();

        // Pre-load existing data for JS to repopulate
        $existingLanguages = $gig->languages->pluck('language')->toArray();
        $existingSelections = [];

        foreach ($gig->subjects as $gigSubject) {
            $selection = [
                'language' => $gigSubject->subject->language ?? 'Unknown',
                'subject_id' => $gigSubject->subject_id,
                'subject_name' => $gigSubject->subject->subject_name ?? 'Unknown',
                'topics' => []
            ];

            foreach ($gigSubject->topics as $gigTopic) {
                $topicData = [
                    'topic_id' => $gigTopic->topic_id,
                    'topic_name' => $gigTopic->topic->topic_name ?? 'Unknown',
                    'duration' => $gigTopic->duration,
                    'subtopics' => []
                ];

                foreach ($gigTopic->subtopics as $gigSubtopic) {
                    $topicData['subtopics'][] = [
                        'subtopic_id' => $gigSubtopic->subtopic_id,
                        'subtopic_name' => $gigSubtopic->subtopic->subtopic_name ?? 'Unknown',
                        'duration' => $gigSubtopic->duration
                    ];
                }

                $selection['topics'][] = $topicData;
            }

            $existingSelections[] = $selection;
        }

        return view('teacher.gigs.edit', compact('gig', 'grades', 'languages', 'existingLanguages', 'existingSelections'));
    }


// Update the update method in GigController.php
public function update(Request $request, Gig $gig)
{
    if ($gig->teacher_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $gig->update([
        'title' => $validated['title'],
        'description' => $validated['description'],
    ]);

    return redirect()->route('teacher.gigs')->with('success', 'Gig updated successfully!');
}
    public function updateStatus(Request $request, Gig $gig)
    {
        if ($gig->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:draft,pending,active,rejected,disabled'
        ]);

        $gig->update(['status' => $request->status]);

        return back()->with('success', 'Gig status updated successfully!');
    }

    public function destroy(Gig $gig)
    {
        if ($gig->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $gig->delete();  // Cascades to relations

        return back()->with('success', 'Gig deleted successfully!');
    }
}