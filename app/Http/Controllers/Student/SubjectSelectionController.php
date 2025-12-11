<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\StudentSubjectSelection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectSelectionController extends Controller
{
    // Step 1: show grade + language form
    public function showGradeForm()
    {
        $userId = Auth::id();
        $hasCurrent = StudentSubjectSelection::where('student_id', $userId)
            ->where('is_current', true)
            ->exists();

        if ($hasCurrent) {
            return redirect()->route('student.dashboard');
        }

        return view('student.select-subjects-step1');
    }

    // Step 2: preview subjects for chosen grade+language
    public function previewSubjects(Request $request)
    {
        $request->validate([
            'grade' => 'required|string',
            'language' => 'required|string',
        ]);

        $grade = $request->input('grade');
        $language = $request->input('language');

        $mainSubjects = Subject::where(function ($q) {
                $q->where('is_subsubject', false)->orWhereNull('is_subsubject');
            })
            ->when($grade, fn($q) => $q->where('grade', $grade))
            ->when($language, fn($q) => $q->where('language', $language))
            ->with('children')
            ->orderBy('subject_name')
            ->get();

        $userId = Auth::id();
        $current = StudentSubjectSelection::where('student_id', $userId)
            ->where('is_current', true)
            ->pluck('subject_id')
            ->toArray();

        return view('student.select-subjects', compact('mainSubjects', 'grade', 'language', 'current'));
    }

    // Final store: sanitize, validate and persist selections (keeps history)
    public function storeSelection(Request $request)
    {
        $request->validate([
            'grade' => 'required|string',
            'language' => 'required|string',
        ]);

        $userId = Auth::id();
        $grade = $request->input('grade');
        $language = $request->input('language');

        $raw = $request->input('subject_ids', []);
        $subjectIds = array_values(array_filter(array_map(function ($v) {
            return $v === '' || $v === null ? null : intval($v);
        }, (array) $raw)));

        if (empty($subjectIds)) {
            return back()->withInput()->withErrors(['subject_ids' => 'Please select at least one subject.']);
        }

        $validCount = Subject::whereIn('id', $subjectIds)->count();
        if ($validCount !== count($subjectIds)) {
            return back()->withInput()->withErrors(['subject_ids' => 'One or more selected subjects are invalid.']);
        }

        DB::transaction(function () use ($userId, $grade, $language, $subjectIds) {
            StudentSubjectSelection::where('student_id', $userId)
                ->where('is_current', true)
                ->update(['is_current' => false]);

            $now = now();
            $rows = [];
            foreach ($subjectIds as $sid) {
                $rows[] = [
                    'student_id' => $userId,
                    'grade' => $grade,
                    'language' => $language,
                    'subject_id' => $sid,
                    'is_current' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            StudentSubjectSelection::insert($rows);
        });

        return redirect()->route('student.dashboard')->with('success', 'Subjects confirmed.');
    }

    // show student's current selected subjects (roadmap)
    public function showCurrentSelection()
    {
        $userId = Auth::id();
        $selections = StudentSubjectSelection::with('subject')
            ->where('student_id', $userId)
            ->where('is_current', true)
            ->get();

        $grade = $selections->first()->grade ?? null;
        $language = $selections->first()->language ?? null;

        return view('student.selected-subjects-Roadmap', compact('selections', 'grade', 'language'));
    }

    // optional: history
    public function mySelections()
    {
        $userId = Auth::id();
        $selections = StudentSubjectSelection::with('subject')
            ->where('student_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        return view('student.my-selections', compact('selections'));
    }
}
