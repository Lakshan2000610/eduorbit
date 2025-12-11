<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\StudentSubjectSelection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectProgressController extends Controller
{
    public function update(Request $request, $subjectId)
    {
        $data = $request->validate([
            'completed' => 'required|integer|min:0',
            'total'     => 'required|integer|min:1',
        ]);

        $progress = (int) round(100 * ($data['completed'] / $data['total']));

        $updated = DB::table('student_subject_selections')
            ->where('student_id', Auth::id())
            ->where('subject_id', $subjectId)
            ->update([
                'progress' => $progress,
                'completed_topics' => $data['completed'],
                'updated_at' => now(),
            ]);

        if (! $updated) {
            return response()->json(['message' => 'Selection not found for this user/subject'], 404);
        }

        return response()->json(['progress' => $progress], 200);
    }

    public function markResourceComplete(Request $request, $resourceId)
    {
        $resource = Resource::findOrFail($resourceId);
        
        // Get the subtopic from polymorphic relationship
        if ($resource->resourceable_type !== 'App\Models\Subtopic') {
            return response()->json(['message' => 'Invalid resource type'], 400);
        }

        $subtopic = $resource->resourceable;
        $topic = $subtopic->topic;
        $subject = $topic->subject;

        $user = Auth::user();
        $selection = StudentSubjectSelection::where('student_id', $user->id)
            ->where('subject_id', $subject->id)
            ->firstOrFail();

        // Count total resources in this subject
        $totalResources = DB::table('resources')
            ->whereIn('resourceable_id', 
                $subtopic->topic->subject->topics()
                    ->with('subtopics')
                    ->get()
                    ->flatMap->subtopics
                    ->pluck('id')
                    ->toArray()
            )
            ->where('resourceable_type', 'App\Models\Subtopic')
            ->count();

        // Count completed resources (store in a tracking table or use cache)
        $completedCount = session("completed_resources_{$subject->id}", 0) + 1;
        session(["completed_resources_{$subject->id}" => $completedCount]);

        // Calculate progress
        $progress = $totalResources > 0 ? (int) round(100 * ($completedCount / $totalResources)) : 0;

        // Update selection
        $selection->update([
            'progress' => min($progress, 100),
            'updated_at' => now(),
        ]);

        return response()->json([
            'progress' => $selection->progress,
            'completed' => $completedCount,
            'total' => $totalResources,
            'message' => 'Resource marked as completed',
        ], 200);
    }
}
