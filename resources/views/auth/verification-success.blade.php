@extends('layouts.app')

@section('title', 'Email Verified Successfully')

@section('content')
<div class="success-container">
    <div class="success-card">
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Email Verified!</h1>
            <p class="success-subtitle">
                Your email has been successfully verified
            </p>
        </div>

        <div class="success-content">
            <div class="success-message">
                <h2>🎉 Welcome to bennylovescoffe!</h2>
                <p>Your account is now fully activated and you can start shopping for the best coffee products.</p>
            </div>

            <div class="success-features">
                <div class="feature-item">
                    <i class="fas fa-shopping-cart"></i>
                    <div>
                        <h3>Shop Products</h3>
                        <p>Browse our premium coffee grinders and accessories</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <i class="fas fa-bolt"></i>
                    <div>
                        <h3>Flash Sales</h3>
                        <p>Get exclusive discounts on limited-time offers</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <i class="fas fa-truck"></i>
                    <div>
                        <h3>Fast Shipping</h3>
                        <p>Quick and reliable delivery to your doorstep</p>
                    </div>
                </div>
            </div>

            <div class="success-actions">
                <a href="{{ route('home') }}" class="btn-primary">
                    <i class="fas fa-home"></i>
                    Go to Homepage
                </a>
                
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    <i class="fas fa-shopping-bag"></i>
                    Start Shopping
                </a>
            </div>

            <div class="success-info">
                <div class="info-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Your account is secure and verified</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <span>You'll receive order updates via email</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f5f5f5, #e8e8e8);
    padding: 20px;
}

.success-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    max-width: 600px;
    width: 100%;
}

.success-header {
    background: linear-gradient(135deg, #76E5B7, #4ECDC4);
    padding: 40px 30px;
    text-align: center;
    color: white;
}

.success-icon {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.9;
}

.success-header h1 {
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 10px 0;
}

.success-subtitle {
    font-size: 18px;
    margin: 0;
    opacity: 0.9;
}

.success-content {
    padding: 40px 30px;
}

.success-message {
    text-align: center;
    margin-bottom: 40px;
}

.success-message h2 {
    color: #282828;
    font-size: 24px;
    margin: 0 0 15px 0;
}

.success-message p {
    color: #666;
    font-size: 16px;
    margin: 0;
}

.success-features {
    margin-bottom: 40px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.feature-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.feature-item i {
    font-size: 24px;
    color: #FFD359;
    width: 30px;
    text-align: center;
}

.feature-item h3 {
    margin: 0 0 5px 0;
    color: #282828;
    font-size: 16px;
    font-weight: 600;
}

.feature-item p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.success-actions {
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.btn-primary, .btn-secondary {
    flex: 1;
    min-width: 150px;
    padding: 15px 25px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-primary {
    background: linear-gradient(135deg, #FF5122, #e63946);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 81, 34, 0.3);
}

.btn-secondary {
    background: transparent;
    color: #FF5122;
    border: 2px solid #FF5122;
}

.btn-secondary:hover {
    background: #FF5122;
    color: white;
}

.success-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
    padding: 20px;
    background: #f0f8ff;
    border-radius: 10px;
    border-left: 4px solid #76E5B7;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #666;
    font-size: 14px;
}

.info-item i {
    color: #76E5B7;
    width: 16px;
}

@media (max-width: 480px) {
    .success-container {
        padding: 10px;
    }
    
    .success-header {
        padding: 30px 20px;
    }
    
    .success-content {
        padding: 30px 20px;
    }
    
    .success-header h1 {
        font-size: 28px;
    }
    
    .success-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary {
        min-width: auto;
    }
}
</style>
@endsection
