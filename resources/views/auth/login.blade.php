<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Calmay River Hotel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #071a2e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('/images/rooms/hotel-bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }

        body::after {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(5, 15, 30, 0.75);
            z-index: -1;
        }

        .login-container {
            max-width: 440px;
            width: 100%;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 45px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header .hotel-icon {
            margin-bottom: 15px;
            display: block;
        }

        .login-header .login-logo-img {
            height: 60px;
            width: auto;
        }

        .login-header h3 {
            color: #ffffff;
            font-weight: 700;
            margin: 0;
            font-size: 1.8rem;
        }

        .login-header p {
            color: #90a4ae;
            font-size: 0.95rem;
            margin: 8px 0 0;
        }

        .login-header .hotel-name {
            color: #4fc3f7;
        }

        .form-control-custom {
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, 0.08);
            padding: 14px 18px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.04);
            color: #e0e0e0;
            font-size: 1rem;
        }

        .form-control-custom:focus {
            border-color: #4fc3f7;
            box-shadow: 0 0 0 4px rgba(79, 195, 247, 0.15);
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
        }

        .form-control-custom.is-invalid {
            border-color: #ef5350;
        }

        .form-control-custom::placeholder {
            color: #78909c;
        }

        .form-label {
            color: #b0bec5;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .btn-login {
            background: linear-gradient(135deg, #4fc3f7 0%, #0288d1 100%);
            border: none;
            color: #0a0e17;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.05rem;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 25px rgba(79, 195, 247, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(79, 195, 247, 0.4);
            color: #0a0e17;
        }

        .btn-login i {
            margin-right: 8px;
        }

        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 14px 18px;
        }

        .alert-custom.alert-danger {
            background: rgba(239, 83, 80, 0.15);
            color: #ef9a9a;
            border-left: 4px solid #ef5350;
        }

        .alert-custom.alert-success {
            background: rgba(102, 187, 106, 0.15);
            color: #a5d6a7;
            border-left: 4px solid #66bb6a;
        }

        .auth-links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .auth-links a {
            color: #90a4ae;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .auth-links a:hover {
            color: #4fc3f7;
        }

        .auth-links .divider {
            color: #455a64;
            margin: 0 10px;
        }

        .input-group-text {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.08);
            border-right: none;
            color: #78909c;
            border-radius: 12px 0 0 12px;
        }

        .input-group .form-control-custom {
            border-radius: 0 12px 12px 0;
        }

        .input-group .form-control-custom:focus {
            border-left: none;
        }

        .checkbox-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #90a4ae;
        }

        .checkbox-custom input[type="checkbox"] {
            accent-color: #4fc3f7;
            width: 18px;
            height: 18px;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 25px;
            }
            .login-header h3 {
                font-size: 1.5rem;
            }
            .login-header .hotel-icon .login-logo-img {
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <span class="hotel-icon">
                    <img src="{{ asset('images/logo.png') }}" alt="Calmay River Hotel" class="login-logo-img">
                </span>
                <h3>Calmay River Hotel</h3>
                <p><span class="hotel-name">Guest</span> Login</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i> Email Address
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control form-control-custom @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="Enter your email" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i> Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control form-control-custom @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <label class="checkbox-custom">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span style="font-size: 0.9rem;">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color: #4fc3f7; text-decoration: none; font-size: 0.9rem;">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="auth-links">
                <span style="color: #78909c;">Don't have an account?</span>
                <a href="{{ route('register') }}" style="color: #4fc3f7; font-weight: 600;">Register here</a>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('home') }}" style="color: #455a64; text-decoration: none; font-size: 0.85rem;">
                    <i class="fas fa-arrow-left me-1"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>