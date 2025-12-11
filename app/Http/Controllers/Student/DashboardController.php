<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentSubjectSelection;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('student.dashboard');
    }

    public function showProgress($subjectId)
    {
        $user = Auth::user();
        
        $selection = StudentSubjectSelection::where('student_id', $user->id)
            ->where('subject_id', $subjectId)
            ->first();

        if (!$selection) {
            abort(403, 'You have not selected this subject.');
        }

        // Eager load topics with subtopics, contents, resources, and learning outcomes
        $subject = Subject::with([
            'topics' => function ($q) {
                $q->with([
                    'subtopics' => function ($sq) {
                        $sq->with('contents', 'resources', 'learningOutcomes');
                    }
                ]);
            }
        ])->find($subjectId);

        // Calculate progress
        $overallProgress = $selection->progress ?? 0;
        $completedTopics = $selection->completed_topics ?? 0;
        $totalTopics = $subject->topics->count();

        // Count total resources per subtopic for progress tracking
        $resourceCounts = [];
        foreach ($subject->topics as $topic) {
            foreach ($topic->subtopics as $subtopic) {
                $resourceCounts[$subtopic->id] = $subtopic->resources->count();
            }
        }

        return view('student.progress', [
            'subject' => $subject,
            'overallProgress' => $overallProgress,
            'completedTopics' => $completedTopics,
            'totalTopics' => $totalTopics,
            'subjectProgress' => collect(),
            'resourceCounts' => $resourceCounts,
        ]);
    }
}
