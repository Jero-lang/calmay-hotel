<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - {{ $hotel_name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #0a0e17;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #e0e0e0;
            padding: 30px 0;
        }
        .wrapper { max-width: 620px; margin: 0 auto; }

        /* Header */
        .header {
            background: linear-gradient(135deg, #0d2137 0%, #0a4f3e 100%);
            border-radius: 16px 16px 0 0;
            padding: 36px 40px;
            text-align: center;
        }
        .header-icon { font-size: 2.6rem; color: #4fc3f7; margin-bottom: 10px; }
        .header h1 { color: #fff; font-size: 1.6rem; font-weight: 700; margin-bottom: 4px; }
        .header p  { color: #90caf9; font-size: 0.9rem; }
        .booking-id-badge {
            display: inline-block; margin-top: 14px;
            background: rgba(79,195,247,0.2); border: 1px solid rgba(79,195,247,0.4);
            color: #4fc3f7; border-radius: 30px; padding: 5px 20px;
            font-size: 0.85rem; font-weight: 700; letter-spacing: 1px;
        }

        /* Status banner */
        .status-banner {
            background: rgba(79,195,247,0.12); border-left: 4px solid #4fc3f7;
            padding: 14px 24px; font-size: 0.88rem; color: #90caf9;
            display: flex; align-items: center; gap: 10px;
        }
        .status-banner.confirmed {
            background: rgba(102,187,106,0.12); border-left-color: #66bb6a; color: #a5d6a7;
        }
        .status-banner.checked_in {
            background: rgba(102,187,106,0.12); border-left-color: #66bb6a; color: #a5d6a7;
        }
        .status-banner.checked_out {
            background: rgba(120,144,156,0.12); border-left-color: #90a4ae; color: #b0bec5;
        }
        .status-banner.cancelled {
            background: rgba(239,83,80,0.12); border-left-color: #ef5350; color: #ef9a9a;
        }

        /* Card */
        .card {
            background: rgba(26,26,46,0.95);
            border-radius: 0;
            padding: 0;
            border: 1px solid rgba(255,255,255,0.07);
        }
        .card-section { padding: 28px 40px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .card-section:last-child { border-bottom: none; }
        .section-title {
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; color: #90a4ae; margin-bottom: 16px;
            display: flex; align-items: center; gap: 7px;
        }
        .section-title::after {
            content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.1);
        }

        /* Detail rows */
        .detail-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 14px;
        }
        .detail-item {}
        .detail-label { font-size: 0.75rem; color: #90a4ae; margin-bottom: 3px; }
        .detail-value { font-size: 0.95rem; font-weight: 600; color: #e0e0e0; }
        .detail-value.accent { color: #4fc3f7; }
        .detail-full { grid-column: 1 / -1; }

        /* Dates row */
        .dates-row {
            display: flex; align-items: center; gap: 0;
            background: rgba(255,255,255,0.03); border-radius: 12px; overflow: hidden;
            border: 1px solid rgba(255,255,255,0.07);
        }
        .date-box { flex: 1; padding: 16px 20px; text-align: center; }
        .date-box .d-label { font-size: 0.72rem; color: #90a4ae; text-transform: uppercase; letter-spacing: .5px; }
        .date-box .d-value { font-size: 1.05rem; font-weight: 700; color: #e0e0e0; margin-top: 4px; }
        .date-box .d-time  { font-size: 0.78rem; color: #4fc3f7; margin-top: 2px; }
        .date-arrow {
            width: 50px; text-align: center; color: #78909c;
            font-size: 1.4rem; flex-shrink: 0;
        }
        .nights-badge {
            background: rgba(79,195,247,0.12); border: 1px solid rgba(79,195,247,0.25);
            color: #4fc3f7; border-radius: 20px; padding: 3px 12px;
            font-size: 0.78rem; font-weight: 700; white-space: nowrap;
        }

        /* Total */
        .total-box {
            background: rgba(102,187,106,0.08);
            border: 1px solid rgba(102,187,106,0.2); border-radius: 12px;
            padding: 20px 24px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .total-box .label { font-size: 0.88rem; color: #a5d6a7; font-weight: 600; }
        .total-box .amount { font-size: 1.8rem; font-weight: 800; color: #66bb6a; }

        /* Amenities */
        .amenity-tags { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 8px; }
        .amenity-tag {
            background: rgba(79,195,247,0.12); color: #4fc3f7;
            border: 1px solid rgba(79,195,247,0.25);
            padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;
        }

        /* Notice */
        .notice {
            background: rgba(255,167,38,0.08); border: 1px solid rgba(255,167,38,0.15);
            border-radius: 10px; padding: 14px 18px;
            font-size: 0.83rem; color: #ffa726; line-height: 1.6;
        }
        .notice strong { color: #ffb74d; }
        .notice p { color: #e0e0e0; margin: 0; }

        /* Footer */
        .footer {
            background: rgba(26,26,46,0.95); border-radius: 0 0 16px 16px;
            padding: 24px 40px; text-align: center;
            border: 1px solid rgba(255,255,255,0.07); border-top: none;
        }
        .footer p { color: #b0bec5; font-size: 0.8rem; line-height: 1.7; }
        .footer .hotel-name { color: #4fc3f7; font-weight: 700; }
        .footer .divider { margin: 0 10px; color: #78909c; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="header-icon">🏨</div>
        <h1>{{ $hotel_name }}</h1>
        @php
            $statusMessages = [
                'pending' => 'Your booking has been received and is pending confirmation.',
                'confirmed' => 'Your booking has been confirmed!',
                'checked_in' => 'You have checked in to our hotel.',
                'checked_out' => 'Thank you for staying with us!',
                'cancelled' => 'Your booking has been cancelled.',
            ];
            $headerMessage = $statusMessages[$booking->status] ?? 'Your booking status has been updated.';
        @endphp
        <p>{{ $headerMessage }}</p>
        <div class="booking-id-badge">Booking ID: {{ $booking->booking_id }}</div>
    </div>

    {{-- Status --}}
    @php
        $statusBanners = [
            'pending' => ['icon' => '⏳', 'text' => 'Status: Pending Review — Our team will confirm your booking shortly.'],
            'confirmed' => ['icon' => '✅', 'text' => 'Status: Confirmed — Your booking has been confirmed. Check-in details will be sent separately.'],
            'checked_in' => ['icon' => '🔑', 'text' => 'Status: Checked In — Welcome to ' . $hotel_name . '!'],
            'checked_out' => ['icon' => '👋', 'text' => 'Status: Checked Out — Thank you for your stay!'],
            'cancelled' => ['icon' => '❌', 'text' => 'Status: Cancelled — Your booking has been cancelled.'],
        ];
        $bannerInfo = $statusBanners[$booking->status] ?? $statusBanners['pending'];
    @endphp
    <div class="status-banner {{ $booking->status }}">
        {{ $bannerInfo['icon'] }} <span><strong>{{ $bannerInfo['text'] }}</strong></span>
    </div>

    <div class="card">

        {{-- Guest info --}}
        <div class="card-section">
            <div class="section-title">Guest Information</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Guest Name</div>
                    <div class="detail-value">{{ $booking->user->name ?? 'Guest' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div class="detail-value accent">{{ $booking->user->email ?? '' }}</div>
                </div>
                @if($booking->user->phone ?? false)
                <div class="detail-item">
                    <div class="detail-label">Phone</div>
                    <div class="detail-value">{{ $booking->user->phone }}</div>
                </div>
                @endif
                <div class="detail-item">
                    <div class="detail-label">Number of Guests</div>
                    <div class="detail-value">{{ $booking->number_of_guests }} person{{ $booking->number_of_guests > 1 ? 's' : '' }}</div>
                </div>
            </div>
        </div>

        {{-- Room info --}}
        <div class="card-section">
            <div class="section-title">Room Details</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Room Type</div>
                    <div class="detail-value">{{ $booking->room->name ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Room Number</div>
                    <div class="detail-value accent">Room {{ $booking->room->room_number ?? '' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Rate</div>
                    <div class="detail-value">₱{{ number_format($booking->room->price_per_night ?? 0, 2) }} / 24 hours</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Capacity</div>
                    <div class="detail-value">Up to {{ $booking->room->capacity ?? '' }} guests</div>
                </div>
            </div>
            @if($booking->room && $booking->room->amenities)
            <div class="amenity-tags">
                @foreach($booking->room->amenities as $am)
                    <span class="amenity-tag">{{ $am }}</span>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Dates --}}
        <div class="card-section">
            <div class="section-title">Stay Period</div>
            @php
                $ci    = \Carbon\Carbon::parse($booking->check_in_date);
                $co    = \Carbon\Carbon::parse($booking->check_out_date);
                $nights = $ci->diffInDays($co);
            @endphp
            <div class="dates-row">
                <div class="date-box">
                    <div class="d-label">Check-In</div>
                    <div class="d-value">{{ $ci->format('M d, Y') }}</div>
                    @if($booking->check_in_time)
                        <div class="d-time">🕐 {{ \Carbon\Carbon::createFromFormat('H:i:s', $booking->check_in_time)->format('h:i A') }}</div>
                    @endif
                </div>
                <div class="date-arrow">→</div>
                <div class="date-box">
                    <div class="d-label">Check-Out</div>
                    <div class="d-value">{{ $co->format('M d, Y') }}</div>
                    @if($booking->check_out_time)
                        <div class="d-time">🕐 {{ \Carbon\Carbon::createFromFormat('H:i:s', $booking->check_out_time)->format('h:i A') }}</div>
                    @else
                        <div class="d-time">🕐 12:00 PM</div>
                    @endif
                </div>
                <div class="date-arrow">
                    <span class="nights-badge">{{ $nights }} night{{ $nights > 1 ? 's' : '' }}</span>
                </div>
            </div>
        </div>

        {{-- Special requests --}}
        @if($booking->special_requests)
        <div class="card-section">
            <div class="section-title">Special Requests</div>
            <p style="color:#4a5568;font-size:.9rem;line-height:1.6;">{{ $booking->special_requests }}</p>
        </div>
        @endif

        {{-- Total --}}
        <div class="card-section">
            <div class="section-title">Payment Summary</div>
            <div class="total-box">
                <div>
                    <div class="label">Total Amount Due</div>
                    <div style="font-size:.78rem;color:#68d391;margin-top:2px;">{{ $nights }} night{{ $nights > 1 ? 's' : '' }} × ₱{{ number_format($booking->room->price_per_night ?? 0, 2) }}</div>
                </div>
                <div class="amount">₱{{ number_format($booking->total_price, 2) }}</div>
            </div>
        </div>

        {{-- Notice --}}
        <div class="card-section">
            <div class="notice">
                <p><strong>📋 What happens next?</strong></p>
                <p>
                    @if($booking->status === 'pending')
                        Our team will review your booking and uploaded payment confirmation. You will receive
                        a follow-up email once your booking is <strong>confirmed</strong>.
                    @elseif($booking->status === 'confirmed')
                        Your booking is confirmed! Check-in details and final instructions will be sent to you shortly. 
                        For inquiries before your arrival, please contact us.
                    @elseif($booking->status === 'checked_in')
                        Thank you for checking in! If you need anything during your stay, please don't hesitate to contact our front desk.
                    @elseif($booking->status === 'checked_out')
                        We hope you had a wonderful stay. We'd love to hear your feedback! Please consider leaving a review.
                    @else
                        For any inquiries about your booking, please contact us.
                    @endif
                </p>
                <p style="margin-top: 8px;">Contact us at <strong>{{ $hotel_email }}</strong> or call <strong>{{ $hotel_phone }}</strong>.</p>
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>
            <span class="hotel-name">{{ $hotel_name }}</span><br>
            {{ $hotel_address }}<span class="divider">|</span>{{ $hotel_phone }}<br>
            {{ $hotel_email }}
        </p>
        <p style="margin-top:12px;font-size:.72rem;color:#718096;">
            This email was sent automatically. Receipt generated on {{ $generated_at }}.
        </p>
    </div>

</div>
</body>
</html>
