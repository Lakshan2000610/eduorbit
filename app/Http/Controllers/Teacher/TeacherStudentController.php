<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TeacherStudentController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();

        $students = collect([]);

        $stats = [
            'active' => $students->count(),
            'totalSessions' => 142,
            'upcoming' => 8,
            'rating' => 4.9
        ];

        return view('teacher.students', compact('students', 'stats'));
    }
}