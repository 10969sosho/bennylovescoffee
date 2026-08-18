@extends('layouts.app')

@section('title', 'Login - Bennylovescoffee')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-coffee"></i>
                <span>Bennylovescoffee</span>
            </div>
            <h1>Welcome Back</h1>
            <p>Sign in to your account to continue shopping</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           class="form-input @error('email') error @enderror"
                           placeholder="Enter your email"
                           required 
                           autofocus>
                </div>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input @error('password') error @enderror"
                           placeholder="Enter your password"
                           required>
                </div>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                    Remember me
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                @endif
            </div>

            <button type="submit" class="auth-button">
                <i class="fas fa-sign-in-alt"></i>
                Sign In
            </button>
        </form>

        <!-- Google OAuth Button -->
        <div class="social-auth">
            <div class="divider">
                <span>or</span>
            </div>
            <a href="{{ route('auth.google') }}" class="google-button">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
            </a>
        </div>

        <div class="auth-footer">
            <p>Don't have an account? 
                <a href="{{ route('register') }}" class="auth-link">Create Account</a>
            </p>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f8f8 0%, #ffffff 50%, #f8f8f8 100%);
    padding: 2rem;
    margin-top: 2rem;
}

.auth-card {
    background: linear-gradient(135deg, #ffffff, #f8f8f8);
    border: 2px solid #000000;
    border-radius: 20px;
    padding: 3rem;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.auth-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #d4af37, #b8941f);
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1.8rem;
    font-weight: bold;
    color: #000000;
    margin-bottom: 1rem;
}

.auth-logo i {
    color: #d4af37;
}

.auth-header h1 {
    font-size: 2rem;
    font-weight: 900;
    color: #000000;
    margin-bottom: 0.5rem;
}

.auth-header p {
    color: #666666;
    font-size: 1rem;
}

.auth-form {
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #000000;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 15px;
    color: #666666;
    z-index: 2;
}

.form-input {
    width: 100%;
    padding: 15px 15px 15px 45px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 1rem;
    background: #ffffff;
    transition: all 0.3s ease;
    outline: none;
}

.form-input:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}

.form-input.error {
    border-color: #ff4757;
    box-shadow: 0 0 0 3px rgba(255, 71, 87, 0.1);
}

.error-message {
    color: #ff4757;
    font-size: 0.8rem;
    margin-top: 0.5rem;
    display: block;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 0.9rem;
    color: #666666;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid #e0e0e0;
    border-radius: 4px;
    margin-right: 8px;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: #d4af37;
    border-color: #d4af37;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #ffffff;
    font-size: 12px;
    font-weight: bold;
}

.forgot-link {
    color: #d4af37;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: color 0.3s ease;
}

.forgot-link:hover {
    color: #b8941f;
}

.auth-button {
    width: 100%;
    background: linear-gradient(135deg, #000000, #333333);
    color: #ffffff;
    border: 2px solid #000000;
    padding: 15px;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.auth-button:hover {
    background: linear-gradient(135deg, #333333, #000000);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.auth-footer {
    text-align: center;
    padding-top: 1.5rem;
    border-top: 1px solid #e0e0e0;
}

.auth-footer p {
    color: #666666;
    margin: 0;
}

.auth-link {
    color: #d4af37;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.auth-link:hover {
    color: #b8941f;
}

/* Social Auth Styles */
.social-auth {
    margin: 2rem 0;
}

.divider {
    text-align: center;
    position: relative;
    margin: 1.5rem 0;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e0e0e0;
}

.divider span {
    background: #ffffff;
    padding: 0 1rem;
    color: #666666;
    font-size: 0.9rem;
    position: relative;
    z-index: 1;
}

.google-button {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 15px;
    background: #ffffff;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    color: #333333;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.google-button:hover {
    background: #f8f9fa;
    border-color: #d4af37;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    color: #000000;
}

.google-button svg {
    flex-shrink: 0;
}

@media (max-width: 480px) {
    .auth-container {
        padding: 1rem;
    }
    
    .auth-card {
        padding: 2rem;
    }
    
    .auth-header h1 {
        font-size: 1.5rem;
    }
}
</style>
@endsection

