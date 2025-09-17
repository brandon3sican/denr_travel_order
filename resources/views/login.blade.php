<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DENR Travel Order Information System</title>
    <link rel="icon" type="image/png" href="{{ asset('images/denr-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        .login-container {
            background-image: url("{{ asset('images/bg.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            color: #ffffff6e ;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
        }
        .denr-logo {
            width: 120px;
            height: auto;
            margin: 0 auto 1.5rem;
            display: block;
        }
        .login-title {
            color: #1a365d;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .login-subtitle {
            color: #4a5568;
            margin-bottom: 2rem;
            text-align: center;
            font-size: 0.95rem;
        }
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            font-size: 0.95rem;
            color: #000000;
            transition: all 0.2s;
        }
        .form-input:focus {
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
            outline: none;
        }
        .btn-login {
            width: 100%;
            background-color: #2b6cb0;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-login:hover {
            background-color: #2c5282;
        }
        .error-message {
            background-color: #fff5f5;
            border-left: 4px solid #e53e3e;
            color: #c53030;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }
    </style>
</head>

<body class="login-container">
    <div class="login-box">
        <div class="text-center">
            <img src="{{ asset('images/denr-logo.png') }}" alt="DENR Logo" class="denr-logo">
            <h1 class="login-title">TRAVEL ORDER INFORMATION SYSTEM</h1>
            <p class="login-subtitle">Sign in to start your session</p>
        </div>

        @if ($errors->any())
            <div class="error-message">
                <p class="font-bold">Login Failed</p>
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div class="input-group">
                <span class="input-icon">
                    <i class="fas fa-envelope"></i>
                </span>
                <input id="email" name="email" type="email" value="{{ old('email') }}" 
                    required autofocus
                    class="form-input"
                    placeholder="Email">
            </div>

            <div class="input-group">
                <span class="input-icon">
                    <i class="fas fa-lock"></i>
                </span>
                <input id="password" name="password" type="password" 
                    autocomplete="current-password" required
                    class="form-input pr-10"
                    placeholder="Password">
                <button type="button" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                    onclick="togglePassword('password')">
                    <i class="fas fa-eye" id="togglePassword"></i>
                </button>
            </div>
            
            <script>
                function togglePassword(inputId) {
                    const input = document.getElementById(inputId);
                    const icon = document.getElementById('togglePassword');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            </script>

            <button type="submit" class="btn-login">
                SIGN IN
            </button>
        </form>
    </div>
    </div>

    <script src="js/auth.js"></script>
</body>

</html>
