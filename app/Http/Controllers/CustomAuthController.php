<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\EmailVerification;
use App\Mail\EmailVerificationMail;

class CustomAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user with unverified email
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'email_verified_at' => null, // Explicitly set as null
            'is_verified' => false, // Default to false
        ]);

        // Auto-login user after registration
        Auth::login($user);

        // Redirect to home after successful registration
        return redirect()->route('home')->with('success', 'Registration successful! Welcome to bennylovescoffe.');
    }
}
