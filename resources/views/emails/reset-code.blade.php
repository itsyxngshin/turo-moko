<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; padding: 20px; margin: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #f97316; padding: 30px; text-align: center; } /* Orange-500 */
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: bold; }
        .content { padding: 40px 30px; text-align: center; color: #374151; }
        .code-box { 
            background-color: #f3f4f6; 
            border: 2px dashed #f97316; 
            color: #1f2937;
            font-size: 32px; 
            font-weight: bold; 
            letter-spacing: 4px; 
            padding: 20px; 
            margin: 30px 0; 
            border-radius: 8px; 
            display: inline-block;
            font-family: monospace;
        }
        .text { font-size: 16px; line-height: 1.5; margin-bottom: 20px; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Recovery</h1>
        </div>

        <div class="content">
            <p class="text">Hello,</p>
            <p class="text">You requested a password reset. Please use the following code to complete the process:</p>
            
            <div class="code-box">
                {{ $code }}
            </div>

            <p class="text" style="font-size: 14px; color: #6b7280;">
                This code will expire in 60 minutes.<br>
                If you did not request this, you can safely ignore this email.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>