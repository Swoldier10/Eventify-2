<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventify Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f4f4f4;
            font-size: 16px;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #ebca7e;
            padding: 30px 20px;
            text-align: center;
        }

        .header img {
            max-width: 200px;
            height: auto;
            margin-bottom: 10px;
        }

        .content {
            padding: 30px 20px;
            text-align: left;
        }

        .content p {
            margin: 16px 0;
            color: #333;
        }

        .footer {
            background-color: #ebca7e;
            padding: 20px;
            text-align: center;
            color: #ffffff;
            font-size: 14px;
        }

        .footer a {
            color: #ffffff;
            text-decoration: underline;
        }

        .footer a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ asset('images/Logo_Eventify_Yellow_BG.png') }}" alt="Eventify Logo">
    </div>
    <div class="content">
        @yield('content')
    </div>
    <div class="footer">
        © {{ now()->format('Y') }} Eventify. @lang('translations.All rights reserved.')
    </div>
</div>
</body>
</html>
