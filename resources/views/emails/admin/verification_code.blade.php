<!DOCTYPE html>
<html>
<head>
    <title>Verification Code</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #667eea;">Password Change Verification</h2>
        <p>Hello,</p>
        <p>You have requested to change your admin password. Please use the following verification code to proceed:</p>
        
        <div style="background-color: #f4f4f4; padding: 15px; text-align: center; border-radius: 5px; margin: 20px 0;">
            <span style="font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #333;">{{ $code }}</span>
        </div>
        
        <p>This code will expire in 15 minutes.</p>
        <p>If you did not request this change, please ignore this email and secure your account.</p>
        
        <p>Best regards,<br>Bennylovescoffee Admin Team</p>
    </div>
</body>
</html>
