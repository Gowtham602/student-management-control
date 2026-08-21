<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>500 - Server Error</title>

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background: #f8fafc;
            color: #1e293b;
        }

        .error-wrapper {
            width: 100%;
            padding: 30px 20px;
        }

        .error-card {
            width: 100%;
            max-width: 650px;
            margin: auto;
            text-align: center;
            background: #ffffff;
            border-radius: 20px;
            padding: 50px 35px;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08);
        }

        .college-name {
            font-size: 14px;
            font-weight: 700;
            line-height: 1.6;
            color: #475569;
            text-transform: uppercase;
        }

        .error-code {
            margin: 25px 0 5px;
            font-size: clamp(70px, 15vw, 120px);
            line-height: 1;
            font-weight: 800;
            color: #dc2626;
        }

        .error-title {
            margin: 15px 0 10px;
            font-size: clamp(24px, 5vw, 34px);
            font-weight: 700;
            color: #0f172a;
        }

        .error-message {
            max-width: 500px;
            margin: 0 auto;
            font-size: 16px;
            line-height: 1.7;
            color: #64748b;
        }

        .home-button {
            display: inline-block;
            margin-top: 28px;
            padding: 12px 25px;
            border-radius: 10px;

            background: #4f46e5;
            color: #ffffff;

            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
        }

        .home-button:hover {
            background: #4338ca;
        }

        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #94a3b8;
        }

        @media (max-width: 576px) {
            .error-wrapper {
                padding: 20px 15px;
            }

            .error-card {
                padding: 40px 20px;
            }

            .college-name {
                font-size: 12px;
            }

            .error-message {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

<div class="error-wrapper">

    <div class="error-card">

        <div class="college-name">
            THE SALVATION ARMY<br>
            CATHEINE BOOTH COLLEGE OF NURSING
        </div>

        <div class="error-code">
            500
        </div>

        <h1 class="error-title">
            Something Went Wrong
        </h1>

        <p class="error-message">
            We're sorry, but something went wrong on our server.
            Please try again later.
        </p>

        <a href="{{ url('/') }}" class="home-button">
            Go to Home
        </a>

        <div class="footer">
            Nursing College Management System
        </div>

    </div>

</div>

</body>
</html>