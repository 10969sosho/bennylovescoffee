<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // User exists, check if they have Google ID
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
                
                // Auto-login existing user
                Auth::login($user);
                
                // Check if email is verified
                if (!$user->is_verified) {
                    return redirect()->route('verification.show')
                        ->with('info', 'Please verify your email to continue.');
                }
                
                return redirect()->intended(route('home'))
                    ->with('success', 'Welcome back!');
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)), // Random password
                    'role' => 'client',
                    'email_verified_at' => now(), // Google emails are pre-verified
                    'is_verified' => true, // Google emails are pre-verified
                ]);
                
                // Auto-login new user
                Auth::login($user);
                
                return redirect()->route('home')
                    ->with('success', 'Registration successful! Welcome to bennylovescoffe.');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Something went wrong with Google authentication. Please try again.');
        }
    }
}
