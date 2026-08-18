<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use App\Mail\AdminVerificationCodeMail;
use Illuminate\Support\Facades\Session;

class AdminProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => Auth::guard('admin')->user()
        ]);
    }

    public function sendVerificationCode(Request $request)
    {
        $user = Auth::guard('admin')->user();
        
        // Generate 6 digit code
        $code = rand(100000, 999999);
        
        // Store in session with expiration (15 mins)
        Session::put('admin_password_reset_code', $code);
        Session::put('admin_password_reset_expires', now()->addMinutes(15));
        
        // Send email
        Mail::to($user->email)->send(new AdminVerificationCodeMail($code));
        
        return back()->with('code_sent', 'Verification code has been sent to your email.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::guard('admin')->user()->password)) {
                    $fail('The provided password does not match your current password.');
                }
            }],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Verify code
        $sessionCode = Session::get('admin_password_reset_code');
        $expiresAt = Session::get('admin_password_reset_expires');
        
        if (!$sessionCode || !$expiresAt || now()->gt($expiresAt)) {
            return back()->withErrors(['verification_code' => 'Verification code expired or invalid. Please request a new one.']);
        }
        
        if ($validated['verification_code'] != $sessionCode) {
            return back()->withErrors(['verification_code' => 'Invalid verification code.']);
        }

        $request->user('admin')->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Clear session
        Session::forget(['admin_password_reset_code', 'admin_password_reset_expires']);

        return back()->with('success', 'Password updated successfully.');
    }
}
