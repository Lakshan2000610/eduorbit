<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentSubjectSelection;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // 🔒 Regenerate session to prevent fixation attacks
        $request->session()->regenerate();

        // ✅ Get the logged-in user
        $user = Auth::user();

        // If student role, redirect to selection page when no current selections
        if ($user->role === 'student') {
            $hasCurrent = StudentSubjectSelection::where('student_id', $user->id)
                ->where('is_current', true)
                ->exists();

            if (! $hasCurrent) {
                return redirect()->route('student.select-subjects');
            }

            return redirect()->route('student.dashboard');
        }

        // If teacher role, go to teacher dashboard when available
        if ($user->role === 'teacher' && Route::has('teacher.dashboard')) {
            return redirect()->intended(route('teacher.dashboard'));
        }

        // fallback for other roles:
        // Prefer role-specific dashboard routes when available, otherwise fall back to home '/'
        $fallback = url('/');
        if ($user && $user->role === 'admin' && Route::has('admin.dashboard')) {
            $fallback = route('admin.dashboard');
        } elseif (Route::has('dashboard')) {
            $fallback = route('dashboard');
        }

        return redirect()->intended($fallback);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}