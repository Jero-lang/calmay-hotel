<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Calmay River Hotel - Booking System')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #071a2e;
            min-height: 100vh;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #e0e0e0;
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

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .navbar-custom {
            background: linear-gradient(135deg, rgba(16, 20, 40, 0.97) 0%, rgba(26, 26, 56, 0.95) 100%) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(79, 195, 247, 0.15);
            padding: 14px 0;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.4), 0 1px 0 rgba(79, 195, 247, 0.05);
            transition: all 0.3s ease;
            width: 100%;
        }

        .navbar-custom:hover {
            box-shadow: 0 6px 40px rgba(0, 0, 0, 0.5), 0 1px 0 rgba(79, 195, 247, 0.08);
        }

        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
        }

        .navbar-brand {
            font-weight: 800;
            color: #ffffff !important;
            font-size: 1.5rem;
            letter-spacing: -0.3px;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: translateY(-1px);
            text-shadow: 0 0 20px rgba(79, 195, 247, 0.3);
        }

        .navbar-brand i {
            margin-right: 10px;
            color: #4fc3f7;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover i {
            transform: rotate(-5deg);
        }

        .navbar-logo-img {
            height: 48px;
            width: auto;
            margin-right: 10px;
            vertical-align: middle;
        }

        /* ── Navbar Action Buttons ── */
        .nav-btn {
            padding: 7px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.2px;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
        }

        .nav-btn-login {
            background: linear-gradient(135deg, rgba(79, 195, 247, 0.15), rgba(2, 136, 209, 0.1));
            color: #4fc3f7;
            border: 1px solid rgba(79, 195, 247, 0.25);
        }

        .nav-btn-login:hover {
            background: linear-gradient(135deg, rgba(79, 195, 247, 0.25), rgba(2, 136, 209, 0.15));
            color: #81d4fa;
            box-shadow: 0 4px 15px rgba(79, 195, 247, 0.2);
            border-color: rgba(79, 195, 247, 0.4);
        }

        .nav-btn-register {
            background: linear-gradient(135deg, #4fc3f7, #0288d1);
            color: #0a0e17;
            border: 1px solid transparent;
        }

        .nav-btn-register:hover {
            background: linear-gradient(135deg, #64d0fc, #039be5);
            color: #0a0e17;
            box-shadow: 0 4px 20px rgba(79, 195, 247, 0.35);
        }

        .nav-btn-admin {
            background: linear-gradient(135deg, rgba(239, 83, 80, 0.15), rgba(198, 40, 40, 0.1));
            color: #ef9a9a;
            border: 1px solid rgba(239, 83, 80, 0.25);
        }

        .nav-btn-admin:hover {
            background: linear-gradient(135deg, rgba(239, 83, 80, 0.25), rgba(198, 40, 40, 0.15));
            color: #ffcdd2;
            box-shadow: 0 4px 15px rgba(239, 83, 80, 0.2);
            border-color: rgba(239, 83, 80, 0.4);
        }

        .nav-btn-logout {
            background: linear-gradient(135deg, rgba(239, 83, 80, 0.1), rgba(198, 40, 40, 0.05));
            color: #ef9a9a;
            border: 1px solid rgba(239, 83, 80, 0.15);
        }

        .nav-btn-logout:hover {
            background: linear-gradient(135deg, rgba(239, 83, 80, 0.2), rgba(198, 40, 40, 0.1));
            color: #ffcdd2;
            box-shadow: 0 4px 15px rgba(239, 83, 80, 0.15);
            border-color: rgba(239, 83, 80, 0.3);
        }

        .nav-user-info {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #b0bec5;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 5px 12px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .nav-user-info i {
            color: #4fc3f7;
        }

        .hotel-name {
            color: #4fc3f7;
            font-weight: 700;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            gap: 0;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 22px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, rgba(79, 195, 247, 0.2) 0%, rgba(79, 195, 247, 0.2) 100%);
            z-index: 0;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
            flex: 1;
            position: relative;
        }

        .step-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            color: #90a4ae;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            border: 3px solid rgba(255, 255, 255, 0.12);
            position: relative;
        }

        .step-circle.active {
            background: linear-gradient(135deg, #4fc3f7, #0288d1);
            color: #ffffff;
            border-color: #4fc3f7;
            box-shadow: 0 0 20px rgba(79, 195, 247, 0.5);
        }

        .step-circle.completed {
            background: linear-gradient(135deg, #66bb6a, #388e3c);
            color: #ffffff;
            border-color: #66bb6a;
        }

        .step-label {
            font-size: 0.85rem;
            color: #90a4ae;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
        }

        .step-label.active {
            color: #4fc3f7;
            font-weight: 700;
        }

        .step-label.completed {
            color: #66bb6a;
        }

        .progress-bar-custom {
            height: 4px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #4fc3f7, #0288d1);
            transition: width 0.6s ease;
            border-radius: 2px;
        }

        .card-custom {
            border: 1px solid rgba(79, 195, 247, 0.15);
            border-radius: 20px;
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.05);
            overflow: hidden;
            background: linear-gradient(135deg, rgba(26, 26, 46, 0.9) 0%, rgba(22, 33, 62, 0.8) 100%);
            backdrop-filter: blur(20px);
        }

        .card-header-custom {
            background: linear-gradient(135deg, rgba(79, 195, 247, 0.1) 0%, rgba(2, 136, 209, 0.05) 100%);
            border-bottom: 1px solid rgba(79, 195, 247, 0.2);
            padding: 24px 30px;
            color: #ffffff;
            border-radius: 20px 20px 0 0;
        }

        .card-header-custom h4 {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .card-body-custom {
            padding: 32px;
        }

        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }

        .alert-custom.alert-info {
            background: rgba(79, 195, 247, 0.15);
            color: #b3e5fc;
            border-left: 4px solid #4fc3f7;
        }

        .alert-custom.alert-success {
            background: rgba(102, 187, 106, 0.15);
            color: #a5d6a7;
            border-left: 4px solid #66bb6a;
        }

        .alert-custom.alert-danger {
            background: rgba(239, 83, 80, 0.15);
            color: #ef9a9a;
            border-left: 4px solid #ef5350;
        }

        .btn-custom-primary {
            background: linear-gradient(135deg, #4fc3f7 0%, #0288d1 100%);
            border: none;
            color: #0a0e17;
            padding: 12px 40px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-custom-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(79, 195, 247, 0.3);
            color: #0a0e17;
        }

        .btn-custom-secondary {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #b0bec5;
            padding: 12px 40px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-custom-secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }

        .form-control-custom {
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.08);
            padding: 10px 15px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.04);
            color: #e0e0e0;
        }

        .form-control-custom:focus {
            border-color: #4fc3f7;
            box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.15);
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
        }

        .form-control-custom.is-invalid {
            border-color: #ef5350;
        }

        .form-control-custom[readonly] {
            background: rgba(255, 255, 255, 0.02);
            color: #90a4ae;
        }

        .form-label {
            color: #b0bec5;
        }

        .hotel-footer {
            background: linear-gradient(180deg, rgba(26, 26, 46, 0.95) 0%, rgba(10, 14, 23, 0.98) 100%);
            border-top: 2px solid rgba(79, 195, 247, 0.2);
            padding: 40px 0 20px;
            margin-top: 60px;
            color: #78909c;
            width: 100%;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 25px;
        }

        .hotel-footer .hotel-name {
            color: #4fc3f7;
            font-weight: 700;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-section h6 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 16px;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section li {
            margin-bottom: 10px;
        }

        .footer-section a {
            color: #90a4ae;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .footer-section a:hover {
            color: #4fc3f7;
            transform: translateX(4px);
        }

        .footer-section a i {
            font-size: 0.75rem;
        }

        .footer-contact {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.85rem;
            color: #90a4ae;
        }

        .footer-contact-item i {
            color: #4fc3f7;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .footer-social {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .footer-social a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(79, 195, 247, 0.1);
            border: 1px solid rgba(79, 195, 247, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4fc3f7;
            transition: all 0.3s ease;
            transform: none;
        }

        .footer-social a:hover {
            background: linear-gradient(135deg, rgba(79, 195, 247, 0.3), rgba(2, 136, 209, 0.2));
            border-color: #4fc3f7;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(79, 195, 247, 0.2);
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: #455a64;
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-bottom-links {
            display: flex;
            gap: 20px;
            justify-content: flex-end;
            flex: 1;
        }

        .footer-bottom-links a {
            color: #90a4ae;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.8rem;
        }

        .footer-bottom-links a:hover {
            color: #4fc3f7;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .footer-logo-img {
            height: 40px;
            width: auto;
        }

        .footer-tagline {
            font-size: 0.8rem;
            color: #78909c;
            margin-bottom: 14px;
            line-height: 1.4;
        }

        @media (max-width: 1024px) {
            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .hotel-footer {
                padding: 30px 0 15px;
                margin-top: 40px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .footer-bottom-links {
                justify-content: center;
                flex: none;
                width: 100%;
            }
        }

        .table {
            color: #e0e0e0;
        }
        .table thead th {
            background: rgba(255, 255, 255, 0.03);
            color: #b0bec5;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        .table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }
        .table-hover tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }
        .table-bordered {
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .summary-table td {
            padding: 12px 15px;
        }
        .summary-table tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.02);
        }

        .pagination .page-item .page-link {
            color: #4fc3f7;
            border: 1px solid rgba(255, 255, 255, 0.06);
            padding: 6px 16px;
            font-size: 14px;
            border-radius: 4px;
            transition: all 0.3s ease;
            background: transparent;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #4fc3f7, #0288d1);
            border-color: #4fc3f7;
            color: #0a0e17;
        }
        .pagination .page-item.disabled .page-link {
            color: #455a64;
            background: rgba(255, 255, 255, 0.02);
        }
        .pagination .page-item .page-link:hover:not(.disabled) {
            background: rgba(79, 195, 247, 0.1);
            border-color: rgba(79, 195, 247, 0.3);
        }

        .badge {
            padding: 5px 12px;
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .step-indicator {
                padding: 15px;
                flex-wrap: nowrap;
                overflow-x: auto;
            }
            .step-item {
                min-width: 80px;
            }
            .step-label {
                font-size: 0.7rem;
            }
            .step-circle {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
            .navbar-custom .nav-inner {
                flex-wrap: wrap;
                gap: 8px;
                padding: 0 15px;
            }
            .navbar-custom .d-flex {
                flex-wrap: wrap;
                gap: 6px;
            }
            .nav-btn {
                padding: 6px 14px;
                font-size: 0.8rem;
            }
            .nav-user-info {
                font-size: 0.8rem;
                padding: 4px 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar (Full Width) -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="nav-inner">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Calmay River Hotel" class="navbar-logo-img"> <span class="hotel-name">Calmay River Hotel</span>
            </a>
            <div class="d-flex align-items-center gap-2">
                @auth
                    <span class="nav-user-info">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </span>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-btn nav-btn-admin">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-btn nav-btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-btn nav-btn-login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="nav-btn nav-btn-register">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                    <a href="{{ route('admin.login') }}" class="nav-btn nav-btn-admin">
                        Admin
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="main-container">

        <!-- Step Indicator (Only on booking pages) -->
        @if(request()->routeIs('booking.start') || request()->routeIs('booking.confirmation') || request()->routeIs('booking.summary'))
            @php
                $currentStep = Session::get('booking.step', 1);
                $steps = [
                    ['label' => 'Details', 'icon' => 'fa-pen'],
                    ['label' => 'Upload', 'icon' => 'fa-upload'],
                    ['label' => 'Summary', 'icon' => 'fa-check-circle']
                ];
                $progress = (($currentStep - 1) / 2) * 100;
            @endphp

            <div class="step-indicator">
                @foreach($steps as $index => $step)
                    <div class="step-item">
                        <div class="step-circle 
                            @if($currentStep == $index+1) active
                            @elseif($currentStep > $index+1) completed
                            @endif">
                            <i class="fas {{ $step['icon'] }}"></i>
                        </div>
                        <span class="step-label 
                            @if($currentStep == $index+1) active
                            @elseif($currentStep > $index+1) completed
                            @endif">
                            Step {{ $index+1 }}
                        </span>
                        <span class="step-label 
                            @if($currentStep == $index+1) active
                            @elseif($currentStep > $index+1) completed
                            @endif" style="font-size: 0.7rem;">
                            {{ $step['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="progress-bar-custom">
                <div class="progress-bar-fill" style="width: {{ $progress }}%;"></div>
            </div>
        @endif

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-custom alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Content -->
        <div class="card card-custom mt-4">
            @yield('content')
        </div>

    </div>

    <!-- Footer (Full Width) -->
    <footer class="hotel-footer">
        <div class="footer-inner">
            <div class="footer-content">
                <!-- About Section -->
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="{{ asset('images/logo.png') }}" alt="Calmay River Hotel" class="footer-logo-img">
                    </div>
                    <p class="footer-tagline">Experience luxury and comfort by the river with world-class hospitality and exceptional service.</p>
                    <div class="footer-social">
                        <a href="#" title="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Twitter" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="LinkedIn" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h6><i class="fas fa-link me-2"></i>Quick Links</h6>
                    <ul>
                        <li><a href="{{ route('home') }}"><i class="fas fa-home"></i>Home</a></li>
                        <li><a href="{{ route('booking.start') }}"><i class="fas fa-calendar-check"></i>Book a Room</a></li>
                        @auth
                            <li><a href="{{ route('my.bookings') }}"><i class="fas fa-list"></i>My Bookings</a></li>
                        @endauth
                        <li><a href="{{ route('faq') }}"><i class="fas fa-question-circle"></i>FAQs</a></li>
                        <li><a href="{{ route('home') }}#amenities"><i class="fas fa-spa"></i>Amenities</a></li>
                    </ul>
                </div>

                <!-- Booking Info -->
                <div class="footer-section">
                    <h6><i class="fas fa-info-circle me-2"></i>Information</h6>
                    <ul>
                        <li><a href="{{ route('faq') }}"><i class="fas fa-question-circle"></i>FAQs</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-section">
                    <h6><i class="fas fa-phone me-2"></i>Contact Us</h6>
                    <div class="footer-contact">
                        <div class="footer-contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 River Road, City, State 12345</span>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+1 (555) 123-4567</span>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>info@calmayriver.com</span>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-clock"></i>
                            <span>24/7 Customer Support</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div>
                    <small>&copy; {{ date('Y') }} <span class="hotel-name">Calmay River Hotel</span>. All rights reserved.</small>
                </div>
                
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>