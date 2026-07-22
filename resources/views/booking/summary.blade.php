@extends('layouts.app')

@section('title', 'Booking Summary - Calmay River Hotel')

@section('content')
<div class="card-header-custom">
    <h4 class="mb-0">
        <i class="fas fa-check-circle me-2" style="color:#66bb6a;"></i>
        Booking Summary
    </h4>
</div>
<div class="card-body-custom">

    @if(session('email_sent'))
    <div class="alert alert-success alert-custom mb-4 alert-dismissible fade show" role="alert">
        <i class="fas fa-envelope me-2"></i>
        <strong>Email sent!</strong> {{ session('email_sent') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-custom mb-4 alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="success-banner">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="success-content">
            <h5>Booking Complete!</h5>
            <p>Your reservation has been received and is pending review.</p>
        </div>
    </div>

    @php
        $room    = \App\Models\Room::find($details['room_id']);
        $checkIn = \Carbon\Carbon::parse($details['check_in_date']);
        $checkOut= \Carbon\Carbon::parse($details['check_out_date']);
        $nights  = $checkIn->diffInDays($checkOut);
    @endphp

    <div class="summary-grid">

        <div class="details-card">
            <div class="card-header">
                <i class="fas fa-receipt me-2"></i> Booking Details
            </div>
            <div class="card-body">
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-hashtag me-2"></i>Booking ID</span>
                    <span class="detail-value mono">{{ $details['booking_id'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-user me-2"></i>Guest Name</span>
                    <span class="detail-value">{{ $details['customer_name'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-bed me-2"></i>Room</span>
                    <span class="detail-value">
                        {{ $room->name ?? 'N/A' }}
                        @if($room)
                            <span class="detail-badge">Room {{ $room->room_number }}</span>
                        @endif
                    </span>
                </div>
                @if($room)
                <div class="room-image-section">
                    <img src="{{ $room->image_url }}" alt="{{ $room->name }}" class="room-preview-img">
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-calendar-check me-2"></i>Check In</span>
                    <span class="detail-value">
                        {{ $checkIn->format('F d, Y') }}
                        @if(!empty($details['check_in_time']))
                            <span class="detail-badge time-badge">{{ \Carbon\Carbon::createFromFormat('H:i', $details['check_in_time'])->format('h:i A') }}</span>
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-calendar-times me-2"></i>Check Out</span>
                    <span class="detail-value">
                        {{ $checkOut->format('F d, Y') }}
                        @if(!empty($details['check_out_time']))
                            <span class="detail-badge time-badge">{{ \Carbon\Carbon::createFromFormat('H:i', $details['check_out_time'])->format('h:i A') }}</span>
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-moon me-2"></i>Duration</span>
                    <span class="detail-value">{{ $nights }} night{{ $nights > 1 ? 's' : '' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-users me-2"></i>Guests</span>
                    <span class="detail-value">{{ $details['number_of_guests'] }} person{{ $details['number_of_guests'] > 1 ? 's' : '' }}</span>
                </div>
                @if(!empty($details['special_requests']))
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-comment me-2"></i>Requests</span>
                    <span class="detail-value sub">{{ $details['special_requests'] }}</span>
                </div>
                @endif
                <div class="detail-total">
                    <span>Total Amount</span>
                    <span class="total-value">₱{{ number_format($details['total_price'], 2) }}</span>
                </div>
            </div>
        </div>

        <div class="file-card">
            <div class="card-header" style="color: #66bb6a; border-bottom-color: rgba(102, 187, 106, 0.25); background: rgba(102, 187, 106, 0.08);">
                <i class="fas fa-file-upload me-2"></i> Payment Confirmation
            </div>
            <div class="card-body file-content">
                @php $ext = strtolower($file['extension'] ?? ''); @endphp
                <div class="file-icon">
                    @if(in_array($ext, ['jpg','jpeg','png']))
                        <i class="fas fa-file-image"></i>
                    @elseif($ext === 'pdf')
                        <i class="fas fa-file-pdf"></i>
                    @else
                        <i class="fas fa-file"></i>
                    @endif
                </div>
                <div class="file-info">
                    <div class="file-name">{{ $file['name'] }}</div>
                    <div class="file-size">{{ number_format(($file['size'] ?? 0) / 1024, 1) }} KB</div>
                </div>
                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="file-view-btn">
                    <i class="fas fa-eye me-2"></i> View File
                </a>
                <div class="file-status">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Status: Pending Review</strong><br>
                        <small>Our team will verify your payment and confirm your booking shortly.</small>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="action-footer">
        <a href="{{ route('home') }}" class="btn btn-primary-modern">
            <i class="fas fa-home me-2"></i> Back to Home
        </a>
        <a href="{{ route('my.bookings') }}" class="btn btn-secondary-modern">
            <i class="fas fa-list me-2"></i> My Bookings
        </a>
    </div>
</div>

<style>
    /* Success banner */
    .success-banner {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 24px 28px;
        background: linear-gradient(135deg, rgba(102, 187, 106, 0.12), rgba(56, 142, 60, 0.08));
        border: 2px solid rgba(102, 187, 106, 0.25);
        border-radius: 14px;
        margin-bottom: 32px;
        backdrop-filter: blur(10px);
    }

    .success-icon {
        font-size: 2.4rem;
        color: #66bb6a;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: rgba(102, 187, 106, 0.15);
        border-radius: 12px;
        flex-shrink: 0;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .success-content h5 {
        color: #ffffff;
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 4px;
    }

    .success-content p {
        color: #90a4ae;
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* Summary grid */
    .summary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    .details-card,
    .file-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        display: flex;
        flex-direction: column;
    }

    .card-header {
        background: rgba(79, 195, 247, 0.08);
        border-bottom: 1px solid rgba(79, 195, 247, 0.2);
        padding: 16px 22px;
        color: #4fc3f7;
        font-weight: 700;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-header i {
        font-size: 1.1rem;
    }

    .card-body {
        padding: 22px;
        flex: 1;
    }

    /* Detail rows */
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        gap: 12px;
    }

    .detail-row:last-of-type {
        border-bottom: none;
    }

    .detail-label {
        color: #78909c;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
        display: flex;
        align-items: center;
    }

    .detail-label i {
        color: #4fc3f7;
        width: 18px;
    }

    .detail-value {
        color: #e0e0e0;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: right;
        flex: 1;
    }

    .detail-value.mono {
        font-family: 'Courier New', monospace;
        color: #4fc3f7;
        font-size: 0.95rem;
    }

    .detail-value.sub {
        font-size: 0.85rem;
        color: #90a4ae;
        text-align: left;
    }

    .detail-badge {
        display: inline-block;
        margin-left: 8px;
        background: rgba(79, 195, 247, 0.12);
        color: #4fc3f7;
        border: 1px solid rgba(79, 195, 247, 0.25);
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Room image */
    .room-image-section {
        margin-top: 16px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(0, 0, 0, 0.2);
    }

    .room-preview-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .room-preview-img:hover {
        transform: scale(1.03);
    }

    .time-badge {
        background: rgba(102, 187, 106, 0.12);
        color: #66bb6a;
        border-color: rgba(102, 187, 106, 0.25);
    }

    /* Detail total */
    .detail-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        padding: 16px 18px;
        background: linear-gradient(135deg, rgba(102, 187, 106, 0.12), rgba(56, 142, 60, 0.08));
        border: 1px solid rgba(102, 187, 106, 0.2);
        border-radius: 10px;
        color: #b0bec5;
        font-weight: 600;
    }

    .total-value {
        color: #66bb6a;
        font-size: 1.6rem;
        font-weight: 800;
    }

    /* File content */
    .file-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        justify-content: space-between;
        gap: 16px;
    }

    .file-icon {
        font-size: 3rem;
        color: #4fc3f7;
        opacity: 0.9;
    }

    .file-info {
        flex: 1;
    }

    .file-name {
        color: #e0e0e0;
        font-weight: 700;
        font-size: 0.95rem;
        word-break: break-all;
        margin-bottom: 4px;
    }

    .file-size {
        color: #78909c;
        font-size: 0.8rem;
    }

    .file-view-btn {
        background: rgba(79, 195, 247, 0.12);
        border: 2px solid rgba(79, 195, 247, 0.3);
        color: #4fc3f7;
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .file-view-btn:hover {
        background: rgba(79, 195, 247, 0.22);
        color: #ffffff;
        border-color: #4fc3f7;
    }

    .file-status {
        background: rgba(255, 167, 38, 0.08);
        border: 1px solid rgba(255, 167, 106, 0.2);
        border-left: 3px solid #ffa726;
        border-radius: 8px;
        padding: 14px 16px;
        font-size: 0.8rem;
        color: #90a4ae;
        line-height: 1.6;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
    }

    .file-status i {
        color: #ffa726;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .file-status strong {
        color: #ffa726;
    }

    /* Action footer */
    .action-footer {
        display: flex;
        justify-content: center;
        gap: 16px;
        flex-wrap: wrap;
        padding-top: 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .btn-primary-modern,
    .btn-secondary-modern {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #4fc3f7 0%, #0288d1 100%);
        color: #0a0e17;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 36px rgba(79, 195, 247, 0.3);
    }

    .btn-secondary-modern {
        background: rgba(255, 255, 255, 0.06);
        border: 2px solid rgba(255, 255, 255, 0.1);
        color: #b0bec5;
    }

    .btn-secondary-modern:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .success-banner {
            flex-direction: column;
            text-align: center;
            padding: 20px 24px;
        }

        .summary-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .action-footer {
            flex-direction: column;
            gap: 12px;
        }

        .btn-primary-modern,
        .btn-secondary-modern {
            width: 100%;
        }

        .detail-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-value {
            text-align: left;
        }

        .detail-total {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .total-value {
            font-size: 1.4rem;
        }

        .file-content {
            padding: 20px 0;
        }

        .file-status {
            margin: 0;
        }
    }

    @media (max-width: 480px) {
        .success-banner {
            gap: 16px;
            padding: 16px 20px;
        }

        .success-icon {
            width: 50px;
            height: 50px;
            font-size: 2rem;
        }

        .success-content h5 {
            font-size: 1.05rem;
        }

        .success-content p {
            font-size: 0.85rem;
        }

        .card-header {
            font-size: 0.9rem;
            padding: 14px 16px;
        }
    }
</style>
@endsection
