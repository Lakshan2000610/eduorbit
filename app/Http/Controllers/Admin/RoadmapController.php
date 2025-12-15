<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Resource as ResourceModel;
use App\Models\Subtopic;
use App\Models\LearningOutcome as LearningOutcomeModel;
use Illuminate\Support\Str;

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

                // treat NULL as main (older records may have NULL)
                $mainSubjects = Subject::where('grade', $item->grade)
                    ->where('language', $item->language)
                    ->where(function ($q) {
                        $q->where('is_subsubject', false)
                          ->orWhereNull('is_subsubject');
                    })
                    ->count();

                $subSubjects = Subject::where('grade', $item->grade)
                    ->where('language', $item->language)
                    ->where('is_subsubject', true)
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
                    'main_subjects' => $mainSubjects,
                    'sub_subjects' => $subSubjects,
                    'total_topics' => $totalTopics,
                    'total_subtopics' => $totalSubtopics,
                ];
            })
            ->all();

        return view('admin.roadmaps.index', compact('grades'));
    }

    public function create(Request $request)
    {
        // read optional filters from query string
        $grade = $request->query('grade');
        $language = $request->query('language');

        $query = Subject::with(['topics', 'children', 'parent']);

        if ($grade) {
            $query->where('grade', $grade);
        }

        if ($language) {
            $query->where('language', $language);
        }

        // show mains and subsubjects for selected grade/language (or all if not selected)
        $subjects = $query->orderBy('is_subsubject')->orderBy('subject_name')->get();

        return view('admin.roadmaps.create', compact('subjects', 'grade', 'language'));
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

        // all main subjects for this grade+language (non-subsubjects)
        $mainSubjectsAll = Subject::where('grade', $grade)
            ->where('language', $language)
            ->where(function ($q) {
                $q->where('is_subsubject', false)
                  ->orWhereNull('is_subsubject');
            })
            ->orderBy('subject_name')
            ->get();

        // eligible parents: mains that DO NOT have topics (only these may accept subsubjects)
        $eligibleMainSubjects = Subject::where('grade', $grade)
            ->where('language', $language)
            ->where(function ($q) {
                $q->where('is_subsubject', false)
                  ->orWhereNull('is_subsubject');
            })
            ->whereDoesntHave('topics')
            ->orderBy('subject_name')
            ->get();

        return view('admin.roadmaps.add-subject', [
            'grade' => $grade,
            'language' => $language,
            'mainSubjectsAll' => $mainSubjectsAll,
            'mainSubjects' => $eligibleMainSubjects, // used for backwards compatibility
        ]);
    }

    // new: list subjects for a specific grade + language
    public function subjectsIndex($grade, $language)
    {
        $grade = urldecode($grade);
        $language = urldecode($language);

        $subjects = Subject::where('grade', $grade)
            ->where('language', $language)
            ->with('parent') // parent for subsubjects
            ->orderBy('is_subsubject') // show main subjects first
            ->orderBy('subject_name')
            ->get();

        // also provide main subjects for quick add-subsubject links
        $mainSubjects = $subjects->where('is_subsubject', false)->values();

        return view('admin.roadmaps.subjects-index', compact('subjects', 'grade', 'language', 'mainSubjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'grade' => 'required',
            'language' => 'required|in:Sinhala,English,Tamil',
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'required|string|unique:subjects,subject_code|max:20',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'is_subsubject' => 'nullable|boolean',
            'parent_subject_id' => 'nullable|exists:subjects,id',
            'resources.*.type' => 'nullable|in:text,video,image',
            'resources.*.content' => 'nullable',
        ]);

        $isSub = $request->boolean('is_subsubject');

        if ($isSub && !$request->parent_subject_id) {
            return back()->withErrors(['parent_subject_id' => 'Please select a parent main subject for a subsubject.'])->withInput();
        }

        $data = $request->only(['grade','language','subject_name','subject_code','status','description']);
        $data['is_subsubject'] = $isSub;
        $data['parent_subject_id'] = $isSub ? $request->parent_subject_id : null;

        $subject = Subject::create($data);

        // Save Resources (non-mandatory)
        if ($request->has('resources')) {
            foreach ($request->resources as $resource) {
                if (!empty($resource['type']) && !empty($resource['content'])) {
                    ResourceModel::create([
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

        // Business rule: if subject already has subsubjects (children), it must NOT have topics.
        if ($subject->children()->count() > 0) {
            return back()->withErrors(['topic_creation' => 'Cannot add topics to a main subject that already has subsubjects. Create topics under its subsubjects instead.'])->withInput();
        }

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
                    ResourceModel::create([
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
    $request->validate([
        'subtopic_code' => 'required|string|max:50',
        'subtopic_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        // validate title for uploaded/resources arrays
        'contents.*.title' => 'nullable|string|max:255',
        'resources.*.title' => 'nullable|string|max:255',
        // file validation handled where needed
    ]);

    $topic = Topic::findOrFail($topicId);

    $subtopic = Subtopic::create([
        'topic_id' => $topic->id,
        'subtopic_code' => $request->subtopic_code,
        'subtopic_name' => $request->subtopic_name,
        'description' => $request->description,
    ]);

    // Accept either contents[] (add form) or resources[] (edit form)
    if ($request->has('contents') || $request->has('resources')) {
        $inputArray = $request->input('contents') ?? $request->input('resources');

        foreach ($inputArray as $i => $item) {
            $type = $item['type'] ?? null;
            $title = $item['title'] ?? null;
            // coerce title to string or null
            $title = is_string($title) ? trim($title) : null;

            $textContent = $item['content'] ?? null;
            $storedContent = $textContent;

            if ($request->hasFile("contents.$i.file")) {
                $file = $request->file("contents.$i.file");
            } elseif ($request->hasFile("resources.$i.file")) {
                $file = $request->file("resources.$i.file");
            } else {
                $file = null;
            }

            // If a file is uploaded, create resource first (to get id), then store file using resource id
            if ($file) {
                // create placeholder resource record to obtain id
                $res = ResourceModel::create([
                    'resourceable_type' => Subtopic::class,
                    'resourceable_id' => $subtopic->id,
                    'type' => $type,
                    'url' => '',  // Use 'url' for files
                    'title' => $title ?? null,
                ]);

                $path = $this->storeUploadedFileForResource($file, $res, $subtopic);
                $res->url = $path;  // Store path in 'url'
                $res->save();
            } else {
                if (!empty($type) && !empty($storedContent)) {
                    ResourceModel::create([
                        'resourceable_id' => $subtopic->id,
                        'resourceable_type' => Subtopic::class,
                        'type' => $type,
                        'content' => $storedContent,
                        'title' => $title,
                    ]);
                }
            }
        }
    }

    // learning outcomes (if present)
    if ($request->has('learning_outcomes')) {
        foreach ($request->learning_outcomes as $lo) {
            if (!empty($lo['outcome'])) {
                LearningOutcomeModel::create([
                    'subtopic_id' => $subtopic->id,
                    'outcome' => $lo['outcome'],
                    'difficulty_level' => $lo['difficulty_level'] ?? 'medium',
                ]);
            }
        }
    }

    return redirect()->back()->with('success', 'Subtopic created.');
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

    public function editSubject($id)
    {
        $subject = Subject::with(['topics', 'children', 'parent'])->findOrFail($id);

        // if subject has topics or children, subject_code must not be changed
        $lockCode = ($subject->topics()->count() > 0) || ($subject->children()->count() > 0);

        return view('admin.roadmaps.edit-subject', compact('subject', 'lockCode'));
    }

    public function updateSubject(Request $request, $id)
    {
        $subject = Subject::with(['topics','children'])->findOrFail($id);

        $hasTopicsOrChildren = ($subject->topics()->count() > 0) || ($subject->children()->count() > 0);

        // base validation
        $rules = [
            'grade' => 'required',
            'language' => 'required|in:Sinhala,English,Tamil',
            'subject_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ];

        // only allow changing code when there are NO topics/subsubjects
        if (! $hasTopicsOrChildren) {
            $rules['subject_code'] = 'required|string|max:20|unique:subjects,subject_code,' . $subject->id;
        }

        $validated = $request->validate($rules);

        $subject->grade = $validated['grade'];
        $subject->language = $validated['language'];
        $subject->subject_name = $validated['subject_name'];
        $subject->status = $validated['status'];
        $subject->description = $validated['description'] ?? $subject->description;

        if (! $hasTopicsOrChildren && isset($validated['subject_code'])) {
            $subject->subject_code = $validated['subject_code'];
        }

        $subject->save();

        return redirect()->back()->with('success', 'Subject updated successfully.');
    }

    // list subtopics for a topic
    public function subtopicsIndex($topicId)
    {
        $topic = Topic::findOrFail($topicId);
        // eager load counts to avoid N+1
        $subtopics = Subtopic::where('topic_id', $topic->id)
            ->withCount(['resources as resources_count' => function ($q) {
                // no extra conditions
            }])
            ->withCount(['learningOutcomes as outcomes_count'])
            ->orderBy('subtopic_name')
            ->get();

        return view('admin.roadmaps.subtopics-index', compact('topic', 'subtopics'));
    }

    // edit subtopic
    public function editSubtopic($id)
    {
        $subtopic = Subtopic::with(['resources', 'learningOutcomes'])->findOrFail($id);
        return view('admin.roadmaps.edit-subtopic', compact('subtopic'));
    }

    // update subtopic (replace resources & outcomes for simplicity)
    public function updateSubtopic(Request $request, $id)
    {
        $request->validate([
            'subtopic_code' => 'required|string|max:50',
            'subtopic_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'resources.*.type' => 'nullable|in:text,video,image',
            'resources.*.content' => 'nullable|string',
            'resources.*.file' => 'nullable|file|mimes:mp4,webm,ogg,jpg,jpeg,png,gif,webp|max:512000', // <-- allow files
            'learning_outcomes.*.outcome' => 'nullable|string',
            'learning_outcomes.*.difficulty_level' => 'nullable|in:easy,medium,hard',
        ]);

        $subtopic = Subtopic::findOrFail($id);

        $subtopic->update([
            'subtopic_code' => $request->subtopic_code,
            'subtopic_name' => $request->subtopic_name,
            'description' => $request->description,
        ]);

        // Handle resources: update existing, create new, delete removed
        if ($request->has('resources')) {
            $submittedIds = collect($request->resources)->pluck('id')->filter()->toArray();

            foreach ($request->resources as $i => $r) {
                $type = $r['type'] ?? null;
                $title = $r['title'] ?? null;
                $content = $r['content'] ?? null;
                $existingId = $r['id'] ?? null;

                if ($existingId) {
                    // Update existing resource
                    $res = ResourceModel::find($existingId);
                    if ($res) {
                        if ($request->hasFile("resources.$i.file")) {
                            $file = $request->file("resources.$i.file");
                            $path = $this->storeUploadedFileForResource($file, $res, $subtopic);
                            $res->url = $path;
                            $res->content = '';  // Clear content for files
                        }
                        // Update other fields
                        $res->type = $type;
                        $res->title = $title;
                        if ($type === 'text' && !empty($content)) {
                            $res->content = $content;
                            $res->url = null;  // Clear URL for text
                        }
                        $res->save();
                    }
                } else {
                    // Create new resource
                    if ($request->hasFile("resources.$i.file")) {
                        $file = $request->file("resources.$i.file");
                        $res = ResourceModel::create([
                            'resourceable_id' => $subtopic->id,
                            'resourceable_type' => Subtopic::class,
                            'type' => $type,
                            'url' => '',
                            'title' => $title,
                        ]);
                        $path = $this->storeUploadedFileForResource($file, $res, $subtopic);
                        $res->url = $path;
                        $res->save();
                    } elseif (!empty($type) && !empty($content)) {
                        ResourceModel::create([
                            'resourceable_id' => $subtopic->id,
                            'resourceable_type' => Subtopic::class,
                            'type' => $type,
                            'content' => $content,
                            'title' => $title,
                        ]);
                    }
                }
            }

            // Delete resources not in the submitted list
            $subtopic->resources()->whereNotIn('id', $submittedIds)->delete();
        } else {
            // If no resources submitted, delete all
            $subtopic->resources()->delete();
        }

        // Replace learning outcomes (unchanged)
        $subtopic->learningOutcomes()->delete();
        if ($request->has('learning_outcomes')) {
            foreach ($request->learning_outcomes as $lo) {
                $text = trim($lo['outcome'] ?? '');
                if ($text === '') continue;
                LearningOutcomeModel::create([
                    'subtopic_id' => $subtopic->id,
                    'outcome' => $text,
                    'difficulty_level' => $lo['difficulty_level'] ?? 'medium',
                ]);
            }
        }

        return redirect()->route('admin.roadmaps.subtopics.index', $subtopic->topic_id)
                         ->with('success', 'Subtopic updated.');
    }

    // delete subtopic
    public function deleteSubtopic($id)
    {
        $subtopic = Subtopic::findOrFail($id);
        // delete related resources and outcomes if not cascade
        $subtopic->resources()->delete();
        $subtopic->learningOutcomes()->delete();
        $subtopic->delete();

        return redirect()->back()->with('success', 'Subtopic deleted.');
    }

    // EDIT TOPIC
    public function editTopic($id)
    {
        $topic = Topic::withCount('subtopics')->findOrFail($id);
        $lockCode = $topic->subtopics_count > 0; // cannot edit code if has subtopics
        return view('admin.roadmaps.edit-topic', compact('topic', 'lockCode'));
    }

    public function updateTopic(Request $request, $id)
    {
        $topic = Topic::withCount('subtopics')->findOrFail($id);
        $rules = [
            'topic_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
        if ($topic->subtopics_count == 0) {
            $rules['topic_suffix'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        if ($topic->subtopics_count == 0 && isset($validated['topic_suffix'])) {
            $topic->topic_code = $topic->subject->subject_code . '-' . $validated['topic_suffix'];
        }
        $topic->topic_name = $validated['topic_name'];
        $topic->description = $validated['description'] ?? $topic->description;
        $topic->save();

        return redirect()->route('admin.roadmaps.manage-topics', $topic->subject_id)->with('success', 'Topic updated.');
    }

    // delete topic (with confirmation in UI). Delete subtopics first.
    public function deleteTopic(Request $request, $id)
    {
        $topic = Topic::with('subtopics')->findOrFail($id);

        // delete subtopic related data
        foreach ($topic->subtopics as $s) {
            $s->resources()->delete();
            $s->learningOutcomes()->delete();
            $s->delete();
        }

        // delete resources attached to topic if any
        $topic->resources()->delete();
        $topic->delete();

        return redirect()->route('admin.roadmaps.manage-topics', $topic->subject_id)->with('success', 'Topic and its subtopics deleted.');
    }

    /**
     * Store uploaded file under public disk with folder structure:
     *   resources/{grade-slug}/{subject-code-slug}/topic-{n}/subtopic-{m}/{resourceId}.{ext}
     * Returns storage-relative path (e.g. resources/grade-1/math/topic-1/subtopic-1/123.mp4)
     */
    private function storeUploadedFileForResource($file, ResourceModel $resource, Subtopic $subtopic)
    {
        // load only topic to avoid RelationNotFoundException when Topic lacks custom relations
        $subtopic->loadMissing('topic');
        $topic = $subtopic->topic ?? null;
        $subject = null;

        // try safe ways to get subject
        if ($topic) {
            if (method_exists($topic, 'subject')) {
                try {
                    $subject = $topic->subject;
                } catch (\Throwable $e) {
                    $subject = null;
                }
            }
            if (! $subject && isset($topic->subject_id)) {
                $subject = \App\Models\Subject::find($topic->subject_id);
            }
        }

        // fallbacks and slugs
        $gradeSlug = \Illuminate\Support\Str::slug($subject->grade ?? 'grade-unknown');
        $subjectCodeBase = $subject->subject_code ?? ($subject->subject_name ?? 'subject');
        $subjectSlug = \Illuminate\Support\Str::slug(explode('-G', $subjectCodeBase)[0]);

        if (isset($topic->topic_code) && preg_match('/-T(\d+)/i', $topic->topic_code, $m)) {
            $topicNum = $m[1];
        } else {
            $topicNum = $topic->id ?? 'topic';
        }

        if (isset($subtopic->subtopic_code) && preg_match('/-S(\d+)/i', $subtopic->subtopic_code, $m2)) {
            $subNum = $m2[1];
        } else {
            $subNum = $subtopic->id ?? 'subtopic';
        }

        $dir = "resources/{$gradeSlug}/{$subjectSlug}/topic-{$topicNum}/subtopic-{$subNum}";

        // ensure directory exists and store file
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($dir);

        $ext = $file->getClientOriginalExtension() ?: $file->extension();
        $filename = $resource->id . '.' . $ext;
        $storedPath = $file->storeAs($dir, $filename, 'public');

        return $storedPath;
    }
}