<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Service Unavailable</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #1f2937;
        }

        .error-page {
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        .error-code {
            font-size: clamp(80px, 20vw, 150px);
            font-weight: 800;
            color: #0891b2;
        }

        h1 {
            font-size: clamp(24px, 5vw, 36px);
        }

        p {
            color: #6b7280;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background: #0891b2;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .brand {
            margin-top: 40px;
            color: #9ca3af;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="error-page">

    <div class="error-code">503</div>

    <h1>Service Unavailable</h1>

    <p>
        The system is temporarily unavailable.
        Please try again shortly.
    </p>

    <a href="{{ url('/') }}" class="btn">
        Try Again
    </a>

    <div class="brand">
        Nursing College Management System
    </div>

</div>

</body>
</html>