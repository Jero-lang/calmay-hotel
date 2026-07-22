<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Calmay River Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #071a2e;
        }
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url('/images/rooms/hotel-bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }
        body::after {
            content: '';
            position: fixed; inset: 0;
            background: rgba(5, 15, 30, 0.72);
            z-index: -1;
        }
        .login-wrap { width: 100%; max-width: 420px; padding: 20px; }
        .login-card {
            background: rgba(26,26,46,0.97);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 20px;
            padding: 45px 40px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.6);
        }
        .login-icon { margin-bottom: 10px; }
        .admin-login-logo-img { height: 60px; width: auto; }
        .login-title { color: #fff; font-weight: 700; font-size: 1.7rem; margin: 0; }
        .login-sub { color: #78909c; font-size: .9rem; margin-top: 6px; }
        .hotel-name { color: #4fc3f7; }
        .form-label { color: #b0bec5; font-weight: 600; font-size: .88rem; margin-bottom: 7px; }
        .input-group-text {
            background: rgba(255,255,255,.03);
            border: 2px solid rgba(255,255,255,.08);
            border-right: none; color: #78909c;
            border-radius: 10px 0 0 10px;
        }
        .fc {
            background: rgba(255,255,255,.04) !important;
            border: 2px solid rgba(255,255,255,.08) !important;
            border-left: none !important;
            border-radius: 0 10px 10px 0 !important;
            color: #e0e0e0 !important; padding: 12px 15px !important;
            font-size: 1rem !important;
        }
        .fc:focus {
            border-color: #ef5350 !important;
            box-shadow: 0 0 0 3px rgba(239,83,80,.15) !important;
            background: rgba(255,255,255,.06) !important; color: #fff !important;
        }
        .fc::placeholder { color: #546e7a; }
        .fc.is-invalid { border-color: #ef5350 !important; }
        .btn-admin-login {
            background: linear-gradient(135deg, #ef5350 0%, #c62828 100%);
            border: none; color: #fff; padding: 13px;
            border-radius: 10px; font-weight: 700; font-size: 1rem;
            width: 100%; transition: all .3s ease;
            box-shadow: 0 4px 20px rgba(239,83,80,.3);
        }
        .btn-admin-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(239,83,80,.45); color: #fff;
        }
        .alert-custom {
            border-radius: 10px; border: none; padding: 13px 16px;
            background: rgba(239,83,80,.12); color: #ef9a9a;
            border-left: 4px solid #ef5350; font-size: .9rem;
        }
        .back-link { color: #455a64; font-size: .85rem; text-decoration: none; transition: color .2s; }
        .back-link:hover { color: #4fc3f7; }
        .divider { border-top: 1px solid rgba(255,255,255,.06); margin: 22px 0; }
        .remember-row { display: flex; align-items: center; gap: 8px; color: #90a4ae; font-size: .88rem; }
        .remember-row input { accent-color: #ef5350; width: 16px; height: 16px; }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="login-icon"><img src="{{ asset('images/logo.png') }}" alt="Calmay River Hotel" class="admin-login-logo-img"></div>
            <h2 class="login-title">Admin Portal</h2>
            <p class="login-sub"><span class="hotel-name">Calmay River Hotel</span> Management</p>
        </div>

        @if(session('status'))
            <div class="alert alert-custom mb-3">
                <i class="fas fa-info-circle me-2"></i>{{ session('status') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-custom mb-3">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-custom mb-3">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-envelope me-1"></i> Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" value="{{ old('email', 'admin@calmay.com') }}"
                           class="form-control fc @error('email') is-invalid @enderror"
                           placeholder="admin@calmay.com" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-lock me-1"></i> Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password"
                           class="form-control fc @error('password') is-invalid @enderror"
                           placeholder="••••••••" required>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <label class="remember-row">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
            </div>
            <button type="submit" class="btn-admin-login">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>
        </form>

        <div class="divider"></div>
        <div class="text-center">
            <a href="{{ route('home') }}" class="back-link">
                <i class="fas fa-arrow-left me-1"></i> Back to Hotel Home
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
