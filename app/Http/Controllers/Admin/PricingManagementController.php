<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\PlatformFee;
use Illuminate\Http\Request;

class PricingManagementController extends Controller
{
    public function index()
    {
        // Group subjects by grade
        $grades = Subject::where('is_subsubject', false)
            ->whereNull('parent_subject_id')
            ->with(['topics.subtopics.pricing'])
            ->orderBy('grade')
            ->get()
            ->groupBy('grade')
            ->map(function ($subjects, $grade) {
                return [
                    'code' => 'G' . substr($grade, -2), // e.g., Grade 9 -> G9
                    'name' => $grade,
                    'subjects' => $subjects->map(function ($subject) {
                        return [
                            'code' => $subject->subject_code,
                            'name' => $subject->subject_name,
                            'topics' => $subject->topics->map(function ($topic) {
                                return [
                                    'code' => $topic->topic_code,
                                    'name' => $topic->topic_name,
                                    'subtopics' => $topic->subtopics->map(function ($subtopic) {
                                        $pricing = $subtopic->pricing ?? (object)['min_price' => 0, 'max_price' => 0];
                                        return [
                                            'code' => $subtopic->subtopic_code,
                                            'name' => $subtopic->subtopic_name,
                                            'minPrice' => (float)$pricing->min_price,
                                            'maxPrice' => (float)$pricing->max_price,
                                            'status' => $subtopic->status ?? 'Active',
                                        ];
                                    })->toArray(),
                                ];
                            })->toArray(),
                        ];
                    })->toArray(),
                ];
            })->values()->toArray();

        $platformFee = PlatformFee::first()?->fee_percentage ?? 10;

        return view('admin.pricing-management.index', compact('grades', 'platformFee'));
    }
}