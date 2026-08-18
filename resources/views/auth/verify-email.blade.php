@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
<div class="verification-container">
    <div class="verification-card">
        <div class="verification-header">
            <div class="verification-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h1>Verify Your Email</h1>
            <p class="verification-subtitle">
                We've sent a verification code to <strong>{{ $user->email }}</strong>
            </p>
        </div>

        <div class="verification-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify') }}" class="verification-form">
                @csrf
                
                <div class="form-group">
                    <label for="verification_code" class="form-label">
                        <i class="fas fa-key"></i>
                        Enter Verification Code
                    </label>
                    <input type="text" 
                           name="verification_code" 
                           id="verification_code" 
                           class="form-input @error('verification_code') error @enderror"
                           placeholder="000000"
                           maxlength="6"
                           required
                           autocomplete="off">
                    <div class="form-help">
                        Enter the 6-digit code sent to your email
                    </div>
                </div>

                <button type="submit" class="verify-btn">
                    <i class="fas fa-check"></i>
                    Verify Email
                </button>
            </form>

            <div class="verification-actions">
                <p class="resend-text">Didn't receive the code?</p>
                
                <form method="POST" action="{{ route('verification.resend') }}" class="resend-form">
                    @csrf
                    <button type="submit" class="resend-btn">
                        <i class="fas fa-paper-plane"></i>
                        Resend Code
                    </button>
                </form>
            </div>

            <div class="verification-info">
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span>Code expires in 24 hours</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Secure verification process</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.verification-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f5f5f5, #e8e8e8);
    padding: 20px;
}

.verification-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    max-width: 500px;
    width: 100%;
}

.verification-header {
    background: linear-gradient(135deg, #FFD359, #FFE066);
    padding: 40px 30px;
    text-align: center;
    color: #282828;
}

.verification-icon {
    font-size: 48px;
    margin-bottom: 20px;
    opacity: 0.9;
}

.verification-header h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 10px 0;
}

.verification-subtitle {
    font-size: 16px;
    margin: 0;
    opacity: 0.8;
}

.verification-content {
    padding: 40px 30px;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

.verification-form {
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #282828;
    margin-bottom: 10px;
    font-size: 16px;
}

.form-input {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 18px;
    text-align: center;
    letter-spacing: 2px;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: #FFD359;
    box-shadow: 0 0 0 3px rgba(255, 211, 89, 0.1);
}

.form-input.error {
    border-color: #e63946;
}

.form-help {
    font-size: 14px;
    color: #666;
    margin-top: 8px;
    text-align: center;
}

.verify-btn {
    width: 100%;
    background: linear-gradient(135deg, #FF5122, #e63946);
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.verify-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 81, 34, 0.3);
}

.verification-actions {
    text-align: center;
    margin: 30px 0;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.resend-text {
    margin: 0 0 15px 0;
    color: #666;
    font-size: 14px;
}

.resend-form {
    display: inline-block;
}

.resend-btn {
    background: transparent;
    color: #FF5122;
    border: 2px solid #FF5122;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.resend-btn:hover {
    background: #FF5122;
    color: white;
}

.verification-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 30px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #666;
    font-size: 14px;
}

.info-item i {
    color: #FFD359;
    width: 16px;
}

@media (max-width: 480px) {
    .verification-container {
        padding: 10px;
    }
    
    .verification-header {
        padding: 30px 20px;
    }
    
    .verification-content {
        padding: 30px 20px;
    }
    
    .verification-header h1 {
        font-size: 24px;
    }
    
    .form-input {
        font-size: 16px;
        padding: 12px 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('verification_code');
    
    // Auto-format input (only numbers)
    codeInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 6) {
            value = value.substring(0, 6);
        }
        e.target.value = value;
    });
    
    // Auto-submit when 6 digits entered
    codeInput.addEventListener('input', function(e) {
        if (e.target.value.length === 6) {
            setTimeout(() => {
                document.querySelector('.verification-form').submit();
            }, 500);
        }
    });
    
    // Focus on input
    codeInput.focus();
});
</script>
@endsection
