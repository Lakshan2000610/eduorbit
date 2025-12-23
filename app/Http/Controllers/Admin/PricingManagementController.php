<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\PlatformFee;

class PricingManagementController extends Controller
{
    public function index()
    {
        $platformFee = PlatformFee::getCurrentFee();

        // Fetch active main subjects (non-subsubjects), eager load hierarchy
        $subjectsQuery = Subject::where('status', 'active')
            ->where('is_subsubject', false)
            ->with(['topics.subtopics.pricing']);

        $subjectsByGrade = $subjectsQuery->get()->groupBy('grade');

        // Build hierarchical data structure matching the frontend expectations
        $grades = [];

        foreach ($subjectsByGrade as $gradeCode => $gradeSubjects) {
            $grade = [
                'code' => $gradeCode,
                'name' => 'Grade ' . $gradeCode, // Adjust naming logic as needed (e.g., map "9" to "Grade 9")
                'subjects' => []
            ];

            foreach ($gradeSubjects as $subject) {
                $subjectData = [
                    'code' => $subject->subject_code,
                    'name' => $subject->subject_name,
                    'topics' => []
                ];

                foreach ($subject->topics as $topic) {
                    $topicData = [
                        'code' => $topic->topic_code,
                        'name' => $topic->topic_name,
                        'subtopics' => []
                    ];

                    foreach ($topic->subtopics as $subtopic) {
                        $pricing = $subtopic->pricing;
                        $subData = [
                            'code' => $subtopic->subtopic_code,
                            'name' => $subtopic->subtopic_name,
                            'minPrice' => $pricing ? $pricing->min_price : 0,
                            'maxPrice' => $pricing ? $pricing->max_price : 0,
                            'status' => 'Active' // Hardcoded; add a status field to subtopics if needed
                        ];

                        $topicData['subtopics'][] = $subData;
                    }

                    $subjectData['topics'][] = $topicData;
                }

                $grade['subjects'][] = $subjectData;
            }

            $grades[] = $grade;
        }

        return view('admin.PricingManagement.index', compact('grades', 'platformFee'));
    }
}