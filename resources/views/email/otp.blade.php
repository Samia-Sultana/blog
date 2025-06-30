<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            font-size: 24px;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        .otp-code {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            font-size: 24px;
            color: #ffffff;
            background-color: #007bff;
            border-radius: 5px;
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your OTP Code from VISER X</h1>
        <p>Hi {{ $user->name }},</p>
        <p>Your One-Time Password (OTP) is:</p>
        <div class="otp-code">{{ $otpCode }}</div>
        <p>Please enter this code to complete your verification.</p>
        <p>If you didn't request this code, you can ignore this email.</p>
        <div class="footer">
            <p>Thank you,<br>VISER X</p>
        </div>
    </div>
</body>
</html>
