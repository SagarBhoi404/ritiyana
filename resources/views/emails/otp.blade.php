<!-- resources/views/emails/otp.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Ritiyana - Your Login OTP</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #e91e63;">🕉️ Ritiyana</h1>
        <h2 style="color: #333;">Your Login OTP</h2>
    </div>
    
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 10px; text-align: center;">
        <p style="color: #666; margin-bottom: 20px;">Your One-Time Password (OTP) for logging into Ritiyana is:</p>
        
        <div style="background-color: #e91e63; color: white; padding: 15px 30px; border-radius: 5px; font-size: 32px; font-weight: bold; letter-spacing: 5px; margin: 20px 0;">
            {{ $otp }}
        </div>
        
        <p style="color: #666; font-size: 14px; margin-top: 20px;">
            This OTP will expire in 10 minutes. Please do not share this code with anyone.
        </p>
    </div>
    
    <div style="text-align: center; margin-top: 30px; color: #999; font-size: 12px;">
        <p>This email was sent from Ritiyana. If you didn't request this OTP, please ignore this email.</p>
        <p>&copy; {{ date('Y') }} Ritiyana. All rights reserved.</p>
    </div>
</body>
</html>
