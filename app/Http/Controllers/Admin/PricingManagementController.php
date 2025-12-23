<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\PlatformFee;
use App\Models\SubtopicPricing;
use Illuminate\Support\Facades\Validator;

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
                            'id' => $subtopic->id, // ADD THIS LINE: Pass the subtopic ID for AJAX updates
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

    public function updateSubtopicPricing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subtopic_id' => 'required|exists:subtopics,id',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|gt:min_price',
            'currency' => 'nullable|string|size:3|in:LKR,USD,EUR', // Adjust allowed currencies as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pricing = SubtopicPricing::updateOrCreate(
                ['subtopic_id' => $request->subtopic_id],
                [
                    'min_price' => $request->min_price,
                    'max_price' => $request->max_price,
                    'currency' => $request->currency ?? 'LKR',
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Subtopic pricing updated successfully',
                'data' => $pricing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update pricing: ' . $e->getMessage()
            ], 500);
        }
    }

   
    public function updatePlatformFee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fee_percentage' => 'required|numeric|between:0,100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $fee = PlatformFee::updateOrCreate(
                ['id' => 1], // Assuming single record; adjust if multiple
                [
                    'fee_percentage' => $request->fee_percentage,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Platform fee updated successfully',
                'data' => $fee
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update platform fee: ' . $e->getMessage()
            ], 500);
        }
    }

}