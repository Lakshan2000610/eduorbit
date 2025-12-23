<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TeacherDashboard extends Controller
{
    public function index()
    {
        $teacher = Auth::user();

        // Replace with real data from your models later
        $data = [
            'activeGigs' => 4,
            'pendingRequests' => 6,
            'upcomingSessions' => 5,
            'monthlyEarnings' => 125000,
            'nextSessions' => [], // fetch from DB
            'recentMessages' => [],
            'recentNotifications' => []
        ];

        $unreadNotifications = 8;
        $pendingRequests = $data['pendingRequests'];

        return view('teacher.dashboard', compact('teacher', 'data', 'unreadNotifications', 'pendingRequests'));
    }
}