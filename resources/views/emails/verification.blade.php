<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Work Sans', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #FFD359, #FFE066);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #282828;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #282828;
        }
        .message {
            font-size: 16px;
            margin-bottom: 30px;
            color: #666;
        }
        .verification-code {
            background: #f8f9fa;
            border: 2px dashed #FFD359;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .code {
            font-size: 32px;
            font-weight: 700;
            color: #282828;
            letter-spacing: 4px;
            margin: 10px 0;
        }
        .code-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .instructions {
            background: #f0f8ff;
            border-left: 4px solid #76E5B7;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .instructions h3 {
            margin: 0 0 10px 0;
            color: #282828;
            font-size: 16px;
        }
        .instructions ol {
            margin: 0;
            padding-left: 20px;
        }
        .instructions li {
            margin-bottom: 8px;
            color: #666;
        }
        .expiry {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .expiry-text {
            color: #856404;
            font-size: 14px;
            margin: 0;
        }
        .footer {
            background: #282828;
            color: white;
            padding: 20px 30px;
            text-align: center;
        }
        .footer p {
            margin: 0;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #FF5122, #e63946);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Email Verification</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello {{ $user_name }}! 👋
            </div>
            
            <div class="message">
                Thank you for registering with bennylovescoffe! To complete your registration and start shopping, please verify your email address using the code below.
            </div>
            
            <div class="verification-code">
                <div class="code-label">Your verification code is:</div>
                <div class="code">{{ $verification_code }}</div>
            </div>
            
            <div class="instructions">
                <h3>📋 How to verify your email:</h3>
                <ol>
                    <li>Copy the verification code above</li>
                    <li>Go back to the verification page on our website</li>
                    <li>Paste or type the code in the verification field</li>
                    <li>Click "Verify Email" to complete the process</li>
                </ol>
            </div>
            
            <div class="expiry">
                <p class="expiry-text">
                    ⏰ This code will expire on {{ $expires_at->format('F j, Y \a\t g:i A') }}
                </p>
            </div>
            
            <div class="divider"></div>
            
            <div class="message">
                <strong>Security Note:</strong> If you didn't create an account with us, please ignore this email. Your account is safe and no action is required.
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} bennylovescoffe. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
