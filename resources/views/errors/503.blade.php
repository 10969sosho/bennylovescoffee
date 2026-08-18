<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Service Unavailable | Bennylovescoffee</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            padding: 20px;
        }
        .error-content {
            text-align: center;
            background: white;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #a8edea;
            margin: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 32px;
            color: #333;
            margin: 20px 0 10px;
        }
        .error-message {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
        }
        .error-icon {
            font-size: 80px;
            color: #a8edea;
            margin-bottom: 20px;
        }
        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-error {
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary {
            background: #a8edea;
            color: #333;
        }
        .btn-primary:hover {
            background: #8ddbd8;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        .btn-secondary:hover {
            background: #e0e0e0;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-tools"></i>
            </div>
            <h1 class="error-code">503</h1>
            <h2 class="error-title">Service Unavailable</h2>
            <p class="error-message">
                Layanan sedang dalam pemeliharaan. Kami akan segera kembali. Terima kasih atas kesabaran Anda.
            </p>
            <div class="error-actions">
                <button onclick="window.location.reload()" class="btn-error btn-primary">
                    <i class="fas fa-redo"></i>
                    Coba Lagi
                </button>
                <a href="{{ route('home') }}" class="btn-error btn-secondary">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>

