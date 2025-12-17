<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #C28A56; margin: 0; }
        .credentials-box { background-color: #f9f9f9; border: 1px solid #e0e0e0; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .label { font-weight: bold; color: #555; }
        .value { color: #333; font-family: monospace; font-size: 16px; }
        .btn { display: inline-block; background-color: #C28A56; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to TURO-MOKO!</h1>
        </div>
        
        <p>Hi {{ $name }},</p>
        
        <p>An implementor account has been created for you. You can now access the dashboard to manage courses and students.</p>
        
        <div class="credentials-box">
            <p><span class="label">Username:</span> <span class="value">{{ $username }}</span></p>
            <p><span class="label">Email:</span> <span class="value">{{ $email }}</span></p>
            <p><span class="label">Password:</span> <span class="value">{{ $password }}</span></p>
        </div>

        <p style="text-align: center;">
            <a href="{{ route('auth.login') }}" class="btn" style="color: white;">Log In to Dashboard</a>
        </p>

        <p><em>For security reasons, we recommend changing your password after your first login. Your verification code shall be sent in a seperate email</em></p>

        <div class="footer">
            &copy; {{ date('Y') }} Turo-Moko. All rights reserved.
        </div>
    </div>
</body>
</html>