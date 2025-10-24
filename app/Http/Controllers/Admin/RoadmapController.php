<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Subtopic;
use App\Models\Content;
use App\Models\LearningOutcome;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoadmapController extends Controller
{
    public function index()
    {
        $grades = DB::table('subjects')
            ->select('grade', 'language')
            ->groupBy('grade', 'language')
            ->get()
            ->map(function ($item) {
                $totalSubjects = Subject::where('grade', $item->grade)
                    ->where('language', $item->language)
                    ->count();

                $totalTopics = Topic::join('subjects', 'topics.subject_id', '=', 'subjects.id')
                    ->where('subjects.grade', $item->grade)
                    ->where('subjects.language', $item->language)
                    ->count();

                $totalSubtopics = Subtopic::join('topics', 'subtopics.topic_id', '=', 'topics.id')
                    ->join('subjects', 'topics.subject_id', '=', 'subjects.id')
                    ->where('subjects.grade', $item->grade)
                    ->where('subjects.language', $item->language)
                    ->count();

                return [
                    'grade' => $item->grade,
                    'language' => $item->language,
                    'total_subjects' => $totalSubjects,
                    'total_topics' => $totalTopics, // Added total topics
                    'total_subtopics' => $totalSubtopics,
                ];
            })
            ->all();

        return view('admin.roadmaps.index', compact('grades'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('admin.roadmaps.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade' => 'required',
            'language' => 'required|in:Sinhala,English,Tamil',
            'subjects' => 'required|integer|min:1',
            'subtopics' => 'required|integer|min:1',
        ]);

        return redirect()->route('admin.roadmaps.index')
                        ->with('success', 'Roadmap created successfully!');
    }

    public function addSubject()
    {
        $grade = request()->get('grade');
        $language = request()->get('language');
        if (!$grade || !$language) {
            return redirect()->route('admin.roadmaps.create')->with('error', 'Please select both grade and language first.');
        }
        return view('admin.roadmaps.add-subject', ['grade' => $grade, 'language' => $language]);
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'grade' => 'required',
            'language' => 'required|in:Sinhala,English,Tamil',
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'required|string|unique:subjects,subject_code|max:10',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'resources.*.type' => 'nullable|in:text,video,image',
            'resources.*.content' => 'nullable',
        ]);

        $subject = Subject::create($request->except('resources'));

        // Save Resources (non-mandatory)
        if ($request->has('resources')) {
            foreach ($request->resources as $resource) {
                if ($resource['type'] && $resource['content']) {
                    Resource::create([
                        'resourceable_id' => $subject->id,
                        'resourceable_type' => Subject::class,
                        'type' => $resource['type'],
                        'content' => $resource['content'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.roadmaps.create')
                        ->with('success', 'Subject added successfully!');
    }

public function manageTopics($subjectId)
{
    $subject = Subject::findOrFail($subjectId);
    $topics = $subject->topics;
    return view('admin.roadmaps.manage-topics', compact('subject', 'topics'));
}

    public function storeTopic(Request $request, $subjectId)
    {
        $request->validate([
            'topic_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'topic_suffix' => 'required|string|max:10',
            'resources.*.type' => 'nullable|in:text,video,image',
            'resources.*.content' => 'nullable',
        ]);

        $subject = Subject::findOrFail($subjectId);
        $topicCode = $subject->subject_code . '-' . $request->topic_suffix;

        $topic = Topic::create([
            'subject_id' => $subjectId,
            'topic_code' => $topicCode,
            'topic_name' => $request->topic_name,
            'description' => $request->description,
        ]);

        // Save Resources (non-mandatory)
        if ($request->has('resources')) {
            foreach ($request->resources as $resource) {
                if ($resource['type'] && $resource['content']) {
                    Resource::create([
                        'resourceable_id' => $topic->id,
                        'resourceable_type' => Topic::class,
                        'type' => $resource['type'],
                        'content' => $resource['content'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.roadmaps.manage-topics', $subjectId)
                        ->with('success', 'Topic added successfully!');
    }

    public function addSubtopic($topicId)
    {
        $topic = Topic::findOrFail($topicId);
        return view('admin.roadmaps.add-subtopic', compact('topic'));
    }

public function storeSubtopic(Request $request, $topicId)
{
    // Validate the request
    $request->validate([
        'subtopic_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'subtopic_code' => 'required|string|max:10',
        'contents' => 'present|array',
        'contents.*.title' => 'required|string|max:255',
        'contents.*.type' => 'required|in:text,video,image',
        'contents.*.content' => 'required',
        'learning_outcomes' => 'present|array',
        'learning_outcomes.*.outcome' => 'required|string',
    ]);

    try {
        $topic = Topic::findOrFail($topicId);
        $subtopicCode = $topic->topic_code . '-' . $request->subtopic_code;

        // Create subtopic
        $subtopic = Subtopic::create([
            'topic_id' => $topicId,
            'subtopic_code' => $subtopicCode,
            'subtopic_name' => $request->subtopic_name,
            'description' => $request->description,
        ]);

        // Save Contents
        if ($request->has('contents') && is_array($request->contents)) {
            foreach ($request->contents as $contentData) {
                if (isset($contentData['title'], $contentData['type'], $contentData['content'])) {
                    Content::create([
                        'subtopic_id' => $subtopic->id,
                        'title' => $contentData['title'],
                        'type' => $contentData['type'],
                        'content' => $contentData['content'],
                    ]);
                }
            }
        }

        // Debug Learning Outcomes
        \Log::info('Learning Outcomes Data Received: ', $request->input('learning_outcomes', []));

        // Save Learning Outcomes
        if ($request->has('learning_outcomes') && is_array($request->learning_outcomes)) {
            foreach ($request->learning_outcomes as $index => $outcomeData) {
                if (isset($outcomeData['outcome']) && !empty($outcomeData['outcome'])) {
                    LearningOutcome::create([
                        'subtopic_id' => $subtopic->id,
                        'outcome' => $outcomeData['outcome'],
                    ]);
                    \Log::info("Inserted Learning Outcome at index $index: " . $outcomeData['outcome']);
                } else {
                    \Log::warning("Invalid or empty outcome at index $index: ", $outcomeData);
                }
            }
        } else {
            \Log::warning('No valid learning_outcomes array found in request.');
        }

        return redirect()->route('admin.roadmaps.manage-topics', $topic->subject_id)
                        ->with('success', 'Subtopic added successfully!');
    } catch (\Exception $e) {
        \Log::error('Error storing subtopic: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to add subtopic. Please try again or contact support.');
    }
}

    public function view($grade, $language)
    {
        $grade = str_replace('-', ' ', $grade);
        $subjects = Subject::where('grade', $grade)->where('language', $language)->with(['topics.subtopics'])->get();

        return view('admin.roadmaps.view', compact('subjects', 'grade', 'language'));
    }

    public function delete($grade, $language)
    {
        $grade = urldecode($grade);
        $language = urldecode($language);

        $subjects = Subject::where('grade', $grade)->where('language', $language)->with('topics')->get();
        $hasActiveTopics = $subjects->flatMap->topics->contains('status', 'active');

        if ($hasActiveTopics) {
            return response()->json(['success' => false, 'message' => 'Cannot delete roadmap: Active topics exist.']);
        }

        Subject::where('grade', $grade)->where('language', $language)->delete();
        return response()->json(['success' => true, 'message' => 'Roadmap deleted successfully.']);
    }

    
}