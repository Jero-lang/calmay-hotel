<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Calmay River Hotel</title>
    
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

        .register-container {
            max-width: 480px;
            width: 100%;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .register-card {
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 45px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .register-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .register-header .hotel-icon {
            margin-bottom: 15px;
            display: block;
        }

        .register-header .register-logo-img {
            height: 60px;
            width: auto;
        }

        .register-header h3 {
            color: #ffffff;
            font-weight: 700;
            margin: 0;
            font-size: 1.8rem;
        }

        .register-header p {
            color: #90a4ae;
            font-size: 0.95rem;
            margin: 8px 0 0;
        }

        .register-header .hotel-name {
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

        .btn-register {
            background: linear-gradient(135deg, #66bb6a 0%, #2e7d32 100%);
            border: none;
            color: #ffffff;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.05rem;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 25px rgba(102, 187, 106, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(102, 187, 106, 0.4);
            color: #ffffff;
        }

        .btn-register i {
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

        .password-hint {
            color: #78909c;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 30px 25px;
            }
            .register-header h3 {
                font-size: 1.5rem;
            }
            .register-header .hotel-icon .register-logo-img {
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <span class="hotel-icon">
                    <img src="{{ asset('images/logo.png') }}" alt="Calmay River Hotel" class="register-logo-img">
                </span>
                <h3>Calmay River Hotel</h3>
                <p>Create your <span class="hotel-name">Guest</span> Account</p>
            </div>

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

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="fas fa-user me-2"></i> Full Name
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="Enter your full name" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i> Email Address
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control form-control-custom @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">
                        <i class="fas fa-phone me-2"></i> Phone Number
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" class="form-control form-control-custom @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" 
                               placeholder="Enter your phone number">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i> Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control form-control-custom @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Create a password" required>
                    </div>
                    <div class="password-hint">
                        <i class="fas fa-info-circle me-1"></i> Minimum 8 characters
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-check-circle me-2"></i> Confirm Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                        <input type="password" class="form-control form-control-custom" 
                               id="password_confirmation" name="password_confirmation" 
                               placeholder="Confirm your password" required>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="auth-links">
                <span style="color: #78909c;">Already have an account?</span>
                <a href="{{ route('login') }}" style="color: #4fc3f7; font-weight: 600;">Login here</a>
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