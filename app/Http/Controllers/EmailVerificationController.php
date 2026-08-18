<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\EmailVerification;
use App\Models\User;
use App\Mail\EmailVerificationMail;

class EmailVerificationController extends Controller
{
    public function show(Request $request)
    {
        // Check if user is logged in
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->is_verified) {
                return redirect()->route('home');
            }
            return view('auth.verify-email', compact('user'));
        }
        
        // If not logged in, redirect to login
        return redirect()->route('login')->with('error', 'Please login first to verify your email.');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);

        // Find verification by code (works for both logged in and not logged in users)
        $verification = EmailVerification::where('verification_code', $request->verification_code)
            ->first();

        if (!$verification) {
            return back()->withErrors(['verification_code' => 'Invalid verification code.']);
        }

        if ($verification->isExpired()) {
            return back()->withErrors(['verification_code' => 'Verification code has expired. Please request a new one.']);
        }

        if ($verification->isVerified()) {
            return back()->withErrors(['verification_code' => 'This code has already been used.']);
        }

        // Get user from verification
        $user = $verification->user;
        
        if (!$user) {
            return back()->withErrors(['verification_code' => 'User not found.']);
        }

        if ($user->is_verified) {
            return redirect()->route('home');
        }

        // Mark as verified
        $verification->markAsVerified();
        $user->update([
            'email_verified_at' => now(),
            'is_verified' => true
        ]);

        // Auto-login user after successful verification
        Auth::login($user);

        return redirect()->route('verification.success');
    }

    public function resend(Request $request)
    {
        // User must be logged in to resend
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first to resend verification email.');
        }
        
        $user = Auth::user();
        if ($user->is_verified) {
            return redirect()->route('home');
        }

        // Rate limiting: max 3 resends per hour
        $key = 'email-verification-resend:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['resend' => "Too many attempts. Please try again in {$seconds} seconds."]);
        }

        RateLimiter::hit($key, 3600); // 1 hour

        // Create new verification
        $verification = EmailVerification::createForUser($user->id, $user->email);

        // Send email
        Mail::to($user->email)->send(new EmailVerificationMail($verification));

        return back()->with('success', 'Verification email sent successfully!');
    }

    public function success()
    {
        return view('auth.verification-success');
    }
}
