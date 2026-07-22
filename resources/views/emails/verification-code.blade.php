<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #0a0e17, #1a1a2e);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #4fc3f7;
            margin: 0;
            font-size: 24px;
        }
        .header .hotel-name {
            color: #4fc3f7;
        }
        .body {
            padding: 30px;
        }
        .code-box {
            background: #f0f4f8;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 8px;
            background: #ffffff;
            padding: 15px 30px;
            border-radius: 8px;
            display: inline-block;
            border: 2px dashed #4fc3f7;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        .footer .hotel-name {
            color: #4fc3f7;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><span class="hotel-name">Calmay River Hotel</span></h1>
        </div>
        <div class="body">
            <h2>Hello {{ $name }}!</h2>
            <p>Thank you for registering at Calmay River Hotel. Please use the verification code below to verify your email address:</p>
            <div class="code-box">
                <span class="code">{{ $code }}</span>
            </div>
            <p>This code will expire in 15 minutes.</p>
            <p>If you didn't request this, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} <span class="hotel-name">Calmay River Hotel</span>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>