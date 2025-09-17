<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Orders History - DENR</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
        }
        .container {
            max-width: 28rem;
            margin: 0 auto;
            padding: 3rem 1rem;
        }
        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 2.5rem;
            text-align: center;
        }
        .logo {
            margin: 0 auto 1.5rem;
            height: 6rem;
            width: auto;
        }
        .title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            color: #4b5563;
            font-size: 0.875rem;
            margin-bottom: 2rem;
        }
        .info-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            border-radius: 0.375rem;
            padding: 1rem;
            text-align: left;
            margin: 2rem 0;
        }
        .info-title {
            color: #1e40af;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .info-text {
            color: #1e40af;
            font-size: 0.875rem;
        }
        .back-link {
            color: #2563eb;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #1d4ed8;
        }
        .back-link i {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="container">
        <div class="card">
            <img src="{{ asset('images/denr-logo.png') }}" alt="DENR Logo" class="logo">
            <h1 class="title">Page Under Development</h1>
            <p class="subtitle">We're working hard to bring you this feature. Please check back soon!</p>
            
            <div class="info-box">
                <h3 class="info-title">
                    <i class="fas fa-info-circle"></i> What to expect next?
                </h3>
                <p class="info-text">
                    This page will display the complete history of all travel orders with advanced filtering and search capabilities.
                </p>
            </div>
            
            <a href="{{ url()->previous() }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to previous page
            </a>
        </div>
    </div>
</body>
</html>