@extends('layouts.app')

@section('title', 'Reset Password - Bennylovescoffee')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-coffee"></i>
                <span>Bennylovescoffee</span>
            </div>
            <h1>Reset Password</h1>
            <p>Enter your new password below</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ $email ?? old('email') }}" 
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
                <label for="password" class="form-label">New Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input @error('password') error @enderror"
                           placeholder="Enter new password"
                           required>
                </div>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-input"
                           placeholder="Confirm new password"
                           required>
                </div>
            </div>

            <button type="submit" class="auth-button">
                <i class="fas fa-key"></i>
                Reset Password
            </button>
        </form>

        <div class="auth-footer">
            <p>Remember your password? 
                <a href="{{ route('login') }}" class="auth-link">Back to Login</a>
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

