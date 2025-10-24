<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/?verified=1'); // Redirect to welcome page if already verified
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Redirect based on the user's role after verification
        $user = $request->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }

        return redirect('/?verified=1'); // Fallback to welcome page
    }
}