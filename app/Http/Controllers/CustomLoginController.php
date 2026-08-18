<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\EmailVerification;
use App\Mail\EmailVerificationMail;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is verified
            if (!$user->is_verified) {
                // Create email verification if not exists
                $verification = EmailVerification::where('user_id', $user->id)
                    ->where('verified_at', null)
                    ->first();
                
                if (!$verification) {
                    $verification = EmailVerification::createForUser($user->id, $user->email);
                }
                
                // Send verification email
                Mail::to($user->email)->send(new EmailVerificationMail($verification));
                
                // Redirect to verification page
                return redirect()->route('verification.show')->with('info', 'Please verify your email to continue.');
            }
            
            // Email is verified, redirect to intended page
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
