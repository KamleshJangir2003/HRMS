<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.7;
            color: #2c3e50;
            background: #f4f6f9;
            padding: 20px;
        }
        .email-container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 20px solid #764ba2;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .header p {
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 50px 40px;
        }
        .message {
            background: #f8f9ff;
            padding: 30px;
            border-radius: 12px;
            border-left: 5px solid #667eea;
            font-size: 16px;
            line-height: 1.8;
        }
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .company-info {
            color: #495057;
            font-size: 13px;
            font-weight: 500;
        }
        @media (max-width: 600px) {
            .email-container { margin: 10px; border-radius: 12px; }
            .header { padding: 30px 20px; }
            .content { padding: 30px 25px; }
            .message { padding: 20px; }
            .footer { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Professional Communication</p>
        </div>
        
        <div class="content">
            <div class="message">
                {!! nl2br(e($messageContent)) !!}
            </div>
        </div>
        
        <div class="footer">
            <p>This email was sent from {{ config('app.name') }} Admin Panel</p>
            <div class="company-info">
                <strong>{{ config('app.name') }}</strong><br>
                Professional Services & Solutions
            </div>
        </div>
    </div>
</body>
</html>