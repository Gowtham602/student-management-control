<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>403 - Access Denied</title>

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
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #1f2937;
            padding: 20px;
        }

        .error-page {
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        .error-code {
            font-size: clamp(80px, 20vw, 160px);
            font-weight: 800;
            line-height: 1;
            margin-bottom: 20px;
            color: #dc2626;
        }

        h1 {
            margin: 0 0 12px;
            font-size: clamp(24px, 5vw, 36px);
        }

        p {
            margin: 0 auto 30px;
            max-width: 450px;
            color: #6b7280;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            background: #111827;
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        .btn:hover {
            background: #374151;
        }

        .brand {
            margin-top: 40px;
            font-size: 14px;
            color: #9ca3af;
        }
    </style>
</head>

<body>

<div class="error-page">

    <div class="error-code">403</div>

    <h1>Access Denied</h1>

    <p>
        Sorry, you don't have permission to access this page.
    </p>

    <a href="{{ url('/') }}" class="btn">
        Go to Home
    </a>

    <div class="brand">
        Nursing College Management System
    </div>

</div>

</body>
</html>