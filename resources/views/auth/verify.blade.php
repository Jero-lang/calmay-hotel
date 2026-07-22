<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - Calmay River Hotel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #0a0e17;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(145deg, #0a0e17 0%, #1a1a2e 30%, #16213e 60%, #0f3460 100%);
            z-index: -1;
        }

        .verify-container {
            max-width: 480px;
            width: 100%;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .verify-card {
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 45px 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .verify-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .verify-header .verify-icon {
            font-size: 3.5rem;
            color: #4fc3f7;
            margin-bottom: 15px;
            display: block;
        }

        .verify-header h3 {
            color: #ffffff;
            font-weight: 700;
            margin: 0;
            font-size: 1.8rem;
        }

        .verify-header p {
            color: #90a4ae;
            font-size: 0.95rem;
            margin: 8px 0 0;
        }

        .form-control-custom {
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, 0.08);
            padding: 14px 18px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.04);
            color: #e0e0e0;
            font-size: 1.5rem;
            text-align: center;
            letter-spacing: 8px;
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
            letter-spacing: 0;
            font-size: 1rem;
            color: #78909c;
        }

        .form-label {
            color: #b0bec5;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .btn-verify {
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

        .btn-verify:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(79, 195, 247, 0.4);
            color: #0a0e17;
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

        .resend-btn {
            background: transparent;
            border: none;
            color: #4fc3f7;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .resend-btn:hover {
            color: #0288d1;
            text-decoration: underline;
        }

        .code-hint {
            color: #78909c;
            font-size: 0.85rem;
            margin-top: 8px;
        }

        @media (max-width: 480px) {
            .verify-card {
                padding: 30px 25px;
            }
            .verify-header h3 {
                font-size: 1.5rem;
            }
            .verify-header .verify-icon {
                font-size: 2.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card">
            <div class="verify-header">
                <span class="verify-icon">
                    <i class="fas fa-envelope"></i>
                </span>
                <h3>Verify Your Email</h3>
                <p>We sent a verification code to <strong style="color: #4fc3f7;">{{ $email }}</strong></p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
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

            <form method="POST" action="{{ route('verify.code') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="name" value="{{ session('register_name') }}">
                <input type="hidden" name="password" value="{{ session('register_password') }}">
                <input type="hidden" name="password_confirmation" value="{{ session('register_password') }}">
                <input type="hidden" name="phone" value="{{ session('register_phone') }}">

                <div class="mb-4">
                    <label for="code" class="form-label">
                        <i class="fas fa-key me-2"></i> Enter 6-Digit Code
                    </label>
                    <input type="text" class="form-control form-control-custom @error('code') is-invalid @enderror" 
                           id="code" name="code" placeholder="· · · · · ·" 
                           maxlength="6" required autofocus>
                    <div class="code-hint">
                        <i class="fas fa-info-circle me-1"></i> Enter the 6-digit code sent to your email
                    </div>
                </div>

                <button type="submit" class="btn-verify">
                    <i class="fas fa-check-circle me-2"></i> Verify Email
                </button>
            </form>

            <div class="auth-links">
                <form action="{{ route('verify.resend') }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="resend-btn">
                        <i class="fas fa-redo me-1"></i> Resend Code
                    </button>
                </form>
                <span style="color: #455a64; margin: 0 10px;">|</span>
                <a href="{{ route('register') }}">
                    <i class="fas fa-arrow-left me-1"></i> Back to Register
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>