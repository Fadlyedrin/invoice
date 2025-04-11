<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f6f8fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 40px;
        }
        .button {
            display: inline-block;
            background-color: #3490dc;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Hello 👋</h2>
        <p>You are receiving this email because we received a request to reset the password for your account.</p>

        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" class="button">Reset Password</a>
        </p>

        <p>If you did not request a password reset, no further action is required.</p>

        <p>Thanks,<br></p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }}. All rights reserved.
    </div>
</body>
</html>
