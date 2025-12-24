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
use App\Models\GigSubtopic;
use App\Models\GigTopic;

class GigController extends Controller
{
    /**
     * Display a listing of the teacher's gigs.
     */
    public function index()
    {
        $gigs = Auth::user()->gigs()
            ->with(['languages', 'subjects.subject', 'subjects.topics.topic', 'subjects.topics.subtopics'])
            ->latest()
            ->get()
            ->map(function ($gig) {
                // Duration calculation
                $totalMinutes = $gig->subjects
                    ->flatMap(fn($subject) => $subject->topics
                        ->flatMap(fn($topic) => [$topic->duration] + $topic->subtopics->pluck('duration')->toArray())
                    )
                    ->sum();
                $gig->total_duration_formatted = $this->formatTime($totalMinutes);

                // Price calculation (sum all subtopic prices, default 0 if null)
                $prices = $gig->subjects
                    ->flatMap(fn($subject) => $subject->topics
                        ->flatMap(fn($topic) => $topic->subtopics->pluck('price')->map(fn($p) => $p ?? 0)->toArray())
                    );
                $totalPrice = $prices->sum();
                $minPrice = $prices->min(0);
                $maxPrice = $prices->max(0);
                $gig->price_summary = $totalPrice > 0 ? [
                    'total' => number_format($totalPrice, 2),
                    'range' => $minPrice === $maxPrice ? 'LKR ' . number_format($minPrice, 2) : 'LKR ' . number_format($minPrice, 2) . ' - ' . number_format($maxPrice, 2),
                    'currency' => 'LKR'
                ] : ['total' => '0.00', 'range' => 'Not Set', 'currency' => 'LKR'];

                return $gig;
            });

        return view('teacher.gigs.index', compact('gigs'));
    }

    /**
     * Helper to format minutes to HH MM string.
     */
    private function formatTime($minutes)
    {
        if ($minutes === 0) return '0M';
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        $str = $hours > 0 ? $hours . 'H ' : '';
        $str .= $mins > 0 ? $mins . 'M' : '';
        return trim($str);
    }

    /**
     * Show the form for creating a new gig.
     */
    public function create()
    {
        $grades = range(1, 13);
        $languages = Subject::distinct()->where('status', 'active')->pluck('language')->sort()->values();

        return view('teacher.gigs.create', compact('grades', 'languages'));
    }

    /**
     * AJAX: Fetch subjects for selected grade and languages.
     */
    public function getSubjects(Request $request)
    {
        $gradeNumber = $request->get('grade');
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

    /**
     * AJAX: Fetch topics for selected subjects.
     */
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

    /**
     * AJAX: Fetch subtopics for selected topics.
     */
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

    /**
     * AJAX: Fetch pricing (min/max) for subtopics.
     */
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

    /**
     * Store a newly created gig.
     */
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

        $selections = json_decode($request->structured_data, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($selections['selections'] ?? [])) {
            return back()->withErrors(['structured_data' => 'Invalid structured data.']);
        }

        $selections = $selections['selections'];

        if (empty($selections)) {
            return back()->withErrors(['structured_data' => 'At least one subject with topics is required.']);
        }

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

        // Save subjects + topics + subtopics with durations and prices
        foreach ($selections as $selection) {
            if (empty($selection['topics'] ?? [])) {
                continue;
            }
            $gigSubject = $gig->subjects()->create([
                'subject_id' => $selection['subject_id'],
            ]);

            foreach ($selection['topics'] as $topicData) {
                $gigTopic = $gigSubject->topics()->create([
                    'topic_id' => $topicData['topic_id'],
                    'duration' => $topicData['duration'] ?? 0,
                ]);

                if (!empty($topicData['subtopics'] ?? [])) {
                    foreach ($topicData['subtopics'] as $subtopicData) {
                        $gigTopic->subtopics()->create([
                            'subtopic_id' => $subtopicData['subtopic_id'],
                            'duration'    => $subtopicData['duration'] ?? 0,
                            'price'       => $subtopicData['price'] ?? 0,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('teacher.gigs')->with('success', 'Gig created successfully and pending approval!');
    }

    /**
     * Display the specified gig.
     */
    public function show(Gig $gig)
    {
        if ($gig->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $gig->load([
            'languages',
            'subjects.subject',
            'subjects.topics.topic',
            'subjects.topics.subtopics.subtopic'
        ]);

        // Price calculation
        $prices = $gig->subjects
            ->flatMap(fn($subject) => $subject->topics
                ->flatMap(fn($topic) => $topic->subtopics->pluck('price')->map(fn($p) => $p ?? 0)->toArray())
            );
        $totalPrice = $prices->sum();
        $minPrice = $prices->min(0);
        $maxPrice = $prices->max(0);
        $gig->price_summary = $totalPrice > 0 ? [
            'total' => number_format($totalPrice, 2),
            'range' => $minPrice === $maxPrice ? 'LKR ' . number_format($minPrice, 2) : 'LKR ' . number_format($minPrice, 2) . ' - ' . number_format($maxPrice, 2),
            'currency' => 'LKR'
        ] : ['total' => '0.00', 'range' => 'Not Set', 'currency' => 'LKR'];

        return view('teacher.gigs.show', compact('gig'));
    }

    /**
     * Show the form for editing the specified gig.
     */
    public function edit(Gig $gig)
    {
        if ($gig->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $grades = range(1, 13);
        $languages = Subject::distinct()->where('status', 'active')->pluck('language')->sort()->values();

        // Pre-load existing data for JS to repopulate
        $existingLanguages = $gig->languages->pluck('language')->toArray();
        $existingSelections = [];

        // Load all subtopic IDs for pricing fetch
        $allSubtopicIds = $gig->subjects
            ->flatMap(fn($s) => $s->topics
                ->flatMap(fn($t) => $t->subtopics->pluck('subtopic_id'))
            )
            ->unique()
            ->values();

        $pricings = [];
        if ($allSubtopicIds->isNotEmpty()) {
            $pricings = SubtopicPricing::whereIn('subtopic_id', $allSubtopicIds)
                ->get(['subtopic_id', 'min_price', 'max_price', 'currency'])
                ->keyBy('subtopic_id');
        }

        foreach ($gig->subjects as $gigSubject) {
            $selection = [
                'language' => $gigSubject->subject->language ?? 'Unknown',
                'subject_id' => $gigSubject->subject_id,
                'subject_name' => $gigSubject->subject->subject_name ?? 'Unknown',
                'topics' => []
            ];

            foreach ($gigSubject->topics as $gigTopic) {
                $topicData = [
                    'gig_topic_id' => $gigTopic->id,
                    'topic_id' => $gigTopic->topic_id,
                    'topic_name' => $gigTopic->topic->topic_name ?? 'Unknown',
                    'duration' => $gigTopic->duration ?? 0,
                    'subtopics' => []
                ];

                foreach ($gigTopic->subtopics as $gigSubtopic) {
                    $pricing = $pricings->get($gigSubtopic->subtopic_id) ?? (object)['min_price' => 0, 'max_price' => 0, 'currency' => 'LKR'];
                    $topicData['subtopics'][] = [
                        'gig_subtopic_id' => $gigSubtopic->id,
                        'subtopic_id' => $gigSubtopic->subtopic_id,
                        'subtopic_name' => $gigSubtopic->subtopic->subtopic_name ?? 'Unknown',
                        'duration' => $gigSubtopic->duration ?? 0,
                        'price' => $gigSubtopic->price ?? 0,
                        'min_price' => $pricing->min_price ?? 0,
                        'max_price' => $pricing->max_price ?? 0,
                        'currency' => $pricing->currency ?? 'LKR'
                    ];
                }

                $selection['topics'][] = $topicData;
            }

            $existingSelections[] = $selection;
        }

        return view('teacher.gigs.edit', compact('gig', 'grades', 'languages', 'existingLanguages', 'existingSelections'));
    }

    /**
     * Update the specified gig (title, desc, durations, prices).
     */
    public function update(Request $request, Gig $gig)
    {
        if ($gig->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'structured_updates' => 'nullable|json', // For durations/prices
        ]);

        // Update basic fields
        $gig->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        // Handle structure updates if provided
        if ($request->has('structured_updates')) {
            $updates = json_decode($validated['structured_updates'], true);
            if (is_array($updates)) {
                foreach ($updates as $update) {
                    if (isset($update['gig_subtopic_id'])) {
                        // Update subtopic
                        GigSubtopic::where('id', $update['gig_subtopic_id'])
                            ->whereHas('gigTopic.gigSubject.gig', fn($q) => $q->where('id', $gig->id))
                            ->update([
                                'duration' => $update['duration'] ?? null,
                                'price' => $update['price'] ?? null,
                            ]);

                        // Recalc parent topic duration if duration changed
                        if (isset($update['duration'])) {
                            $gigSubtopic = GigSubtopic::find($update['gig_subtopic_id']);
                            if ($gigSubtopic) {
                                $totalDuration = $gigSubtopic->gigTopic->subtopics->sum('duration');
                                $gigSubtopic->gigTopic->update(['duration' => $totalDuration]);
                            }
                        }
                    } elseif (isset($update['gig_topic_id'])) {
                        // Update topic duration directly
                        GigTopic::where('id', $update['gig_topic_id'])
                            ->whereHas('gigSubject.gig', fn($q) => $q->where('id', $gig->id))
                            ->update(['duration' => $update['duration'] ?? null]);
                    }
                }
            }
        }

        return redirect()->route('teacher.gigs')->with('success', 'Gig updated successfully!');
    }

    /**
     * Update the status of the specified gig.
     */
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

    /**
     * Remove the specified gig.
     */
    public function destroy(Gig $gig)
    {
        if ($gig->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $gig->delete();  // Cascades to relations

        return back()->with('success', 'Gig deleted successfully!');
    }
}