@extends('layouts.app')

@section('title', 'Welcome to Calmay River Hotel')

@section('content')
<div class="home-page">
    {{-- ── Modern Hero Section ────────────────────────────────── --}}
    <div class="modern-hero" style="background-image: url('{{ asset('images/rooms/hotel-bg.jpg') }}');">
        <div class="hero-overlay"></div>
        <div class="hero-gradient"></div>
        <div class="hero-content">
            <h1 class="hero-title">
                Your Perfect Getaway
                <span class="title-highlight">by the River</span>
            </h1>
            <p class="hero-subtitle">Experience world-class comfort and hospitality at Calmay River Hotel. Book your ideal room and create unforgettable memories.</p>
            <div class="hero-buttons">
                @auth
                    @unless(Auth::user()->isAdmin())
                        <a href="{{ route('booking.start') }}" class="btn-modern-primary">
                            <i class="fas fa-calendar-check me-2"></i> Book a Room
                        </a>
                        <a href="{{ route('my.bookings') }}" class="btn-modern-secondary">
                            <i class="fas fa-clipboard-list me-2"></i> My Bookings
                        </a>
                    @endunless
                @else
                    <a href="{{ route('booking.start') }}" class="btn-modern-primary">
                        <i class="fas fa-calendar-check me-2"></i> Book a Room
                    </a>
                    <a href="{{ route('login') }}" class="btn-modern-secondary">
                        <i class="fas fa-sign-in-alt me-2"></i> Sign In
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- ── Stats Banner ────────────────────────────────── --}}
    <div class="modern-stats">
        <div class="stat-box">
            <div class="stat-icon"><i class="fas fa-door-open"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalRooms ?? 0 }}</div>
                <div class="stat-label">Premium Rooms</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div class="stat-content">
                <div class="stat-value">4.8/5</div>
                <div class="stat-label">Guest Rating</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <div class="stat-value">1000+</div>
                <div class="stat-label">Happy Guests</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon"><i class="fas fa-award"></i></div>
            <div class="stat-content">
                <div class="stat-value">15+</div>
                <div class="stat-label">Years Experience</div>
            </div>
        </div>
    </div>

    {{-- ── Featured Rooms Section ────────────────────── --}}
    <div class="modern-section">
        <div class="section-header">
            <span class="section-badge">ACCOMMODATIONS</span>
            <h2 class="section-title">Luxurious Room Types</h2>
            <p class="section-subtitle">Choose from our carefully designed rooms, each offering unique comfort and modern amenities for your stay.</p>
        </div>

        <div class="modern-rooms-grid">
            @foreach($featuredRooms ?? [] as $room)
                @php
                    $badgeLabels = [
                        'single_bed'  => 'Single Bed',
                        'double_bed'  => 'Double Bed',
                        'king_bed'    => 'King Bed',
                        'majesty_bed' => 'Majesty Suite',
                    ];
                    $badgeColors = [
                        'single_bed'  => 'color-single',
                        'double_bed'  => 'color-double',
                        'king_bed'    => 'color-king',
                        'majesty_bed' => 'color-majesty',
                    ];
                    $badge = $badgeLabels[$room->type] ?? ucwords(str_replace('_',' ',$room->type));
                    $color = $badgeColors[$room->type] ?? '';
                @endphp
                <div class="modern-room-card">
                    <div class="room-card-image">
                        <img src="{{ $room->image_url }}" alt="{{ $room->name }}"
                             onerror="this.src='https://placehold.co/600x400/1a1a2e/4fc3f7?text={{ urlencode($room->name) }}'">
                        <div class="room-card-overlay">
                            <span class="room-type-badge {{ $color }}">{{ $badge }}</span>
                        </div>
                    </div>
                    <div class="room-card-content">
                        <h3 class="room-card-title">{{ $room->name }}</h3>
                        <p class="room-card-desc">{{ $room->description ?? 'Elegant and comfortable accommodation' }}</p>
                        
                        <div class="room-features">
                            <div class="feature-item">
                                <i class="fas fa-user"></i>
                                <span>Up to {{ $room->capacity }} guest{{ $room->capacity > 1 ? 's' : '' }}</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-wifi"></i>
                                <span>Free WiFi</span>
                            </div>
                            @if($room->amenities && count($room->amenities) > 0)
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>+{{ count($room->amenities) }} amenities</span>
                            </div>
                            @endif
                        </div>

                                                <div class="room-amenities-display">
                            @if($room->amenities)
                                @foreach(array_slice($room->amenities, 0, 2) as $amenity)
                                    <span class="amenity-badge">{{ $amenity }}</span>
                                @endforeach
                            @endif
                        </div>

                        {{-- ── Extra Benefits (not for single_bed) ── --}}
                        @if($room->type !== 'single_bed')
                        <div class="room-benefits">
                            @php
                                $benefits = [
                                    'double_bed'  => ['Mini Fridge', 'More Space'],
                                    'king_bed'    => ['Mini Fridge', 'Bathtub', 'Room Service', 'Smart TV'],
                                    'majesty_bed' => ['Mini Bar', 'Jacuzzi', 'Balcony', 'River View', 'Complimentary Breakfast'],
                                ];
                                $roomBenefits = $benefits[$room->type] ?? [];
                            @endphp
                            @foreach($roomBenefits as $benefit)
                                <span class="benefit-badge">
                                    <i class="fas fa-check-circle"></i> {{ $benefit }}
                                </span>
                            @endforeach
                        </div>
                        @endif

                        <div class="room-card-footer">
                            <div class="room-price">
                                <span class="price-label">from</span>
                                <span class="price-amount">₱{{ number_format($room->price_per_night, 0) }}</span>
                                <span class="price-period">per night</span>
                            </div>
                            <a href="{{ route('booking.start') }}" class="btn-room-book">
                                Book Now <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── Amenities Section ────────────────────────── --}}
    <div class="modern-section amenities-section" id="amenities">
        <div class="section-header">
            <span class="section-badge">FACILITIES</span>
            <h2 class="section-title">World-Class Amenities</h2>
            <p class="section-subtitle">Enjoy premium facilities designed to make your stay unforgettable.</p>
        </div>

        <div class="amenities-showcase">
            <div class="amenity-showcase-item">
                <div class="amenity-icon"><i class="fas fa-swimming-pool"></i></div>
                <h4>Swimming Pool</h4>
                <p>Olympic-size pool with heated water</p>
            </div>
            <div class="amenity-showcase-item">
                <div class="amenity-icon"><i class="fas fa-utensils"></i></div>
                <h4>Fine Dining</h4>
                <p>5-star restaurant and bar</p>
            </div>
            <div class="amenity-showcase-item">
                <div class="amenity-icon"><i class="fas fa-spa"></i></div>
                <h4>Spa & Wellness</h4>
                <p>Premium spa treatments</p>
            </div>
            <div class="amenity-showcase-item">
                <div class="amenity-icon"><i class="fas fa-dumbbell"></i></div>
                <h4>Fitness Center</h4>
                <p>State-of-the-art gym equipment</p>
            </div>
        </div>
    </div>

    {{-- ── CTA Section ────────────────────────────── --}}
    <div class="modern-cta">
        <div class="cta-content">
            <h2>Ready to Book Your Stay?</h2>
            <p>Don't miss out on your perfect vacation. Reserve your room today and enjoy exclusive benefits.</p>
            <a href="{{ route('booking.start') }}" class="btn-modern-primary">
                <i class="fas fa-calendar-check me-2"></i> Start Booking Now
            </a>
        </div>
    </div>
</div>

<style>
    .home-page { padding: 0; }

    /* ── Modern Hero ─────────────────────────────────── */
    .modern-hero {
        position: relative;
        height: 480px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #1a1a2e;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(79, 195, 247, 0.15);
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: rgba(10, 14, 23, 0.85);
        z-index: 1;
    }

    .hero-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.1) 0%, rgba(102, 187, 106, 0.05) 100%);
        z-index: 2;
    }

    .hero-content {
        position: relative;
        z-index: 3;
        text-align: center;
        max-width: 900px;
        padding: 30px 40px;
    }

    .hero-badge {
        display: inline-block;
        background: rgba(79, 195, 247, 0.2);
        border: 1px solid rgba(79, 195, 247, 0.4);
        color: #4fc3f7;
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 15px;
        line-height: 1.2;
        letter-spacing: -1px;
    }

    .title-highlight {
        background: linear-gradient(135deg, #4fc3f7, #0288d1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: #b0bec5;
        margin-bottom: 40px;
        line-height: 1.6;
    }

    .hero-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-modern-primary {
        background: linear-gradient(135deg, #4fc3f7 0%, #0288d1 100%);
        border: none;
        color: #0a0e17;
        padding: 16px 50px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 24px rgba(79, 195, 247, 0.3);
    }

    .btn-modern-primary:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(79, 195, 247, 0.4);
        color: #0a0e17;
        text-decoration: none;
    }

    .btn-modern-secondary {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
        padding: 14px 48px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-modern-secondary:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-4px);
        color: #ffffff;
        text-decoration: none;
    }

    /* ── Modern Stats ─────────────────────────────────── */
    .modern-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 60px;
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.08) 0%, rgba(102, 187, 106, 0.04) 100%);
        padding: 40px;
        border-radius: 20px;
        border: 1px solid rgba(79, 195, 247, 0.15);
    }

    .stat-box {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(79, 195, 247, 0.15);
        border-radius: 16px;
        font-size: 1.8rem;
        color: #4fc3f7;
        flex-shrink: 0;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #ffffff;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #78909c;
        margin-top: 4px;
        font-weight: 500;
    }

    /* ── Modern Sections ─────────────────────────────── */
    .modern-section {
        margin-bottom: 70px;
    }

    .section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .section-badge {
        display: inline-block;
        background: rgba(79, 195, 247, 0.15);
        border: 1px solid rgba(79, 195, 247, 0.3);
        color: #4fc3f7;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 15px;
    }

    .section-title {
        font-size: 2.8rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 15px;
        letter-spacing: -0.5px;
    }

    .section-subtitle {
        font-size: 1.05rem;
        color: #90a4ae;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ── Modern Rooms Grid ────────────────────────── */
    .modern-rooms-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
    }

    .modern-room-card {
        background: rgba(26, 26, 46, 0.6);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(79, 195, 247, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .modern-room-card:hover {
        transform: translateY(-12px);
        border-color: rgba(79, 195, 247, 0.3);
        box-shadow: 0 20px 60px rgba(79, 195, 247, 0.2);
    }

    .room-card-image {
        position: relative;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, #1a1a2e, #0f3460);
    }

    .room-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .modern-room-card:hover .room-card-image img {
        transform: scale(1.08);
    }

    .room-card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.4) 100%);
        display: flex;
        align-items: flex-start;
        padding: 16px;
        z-index: 2;
    }

    .room-type-badge {
        background: rgba(79, 195, 247, 0.9);
        color: #0a0e17;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .room-type-badge.color-single { background: rgba(79, 195, 247, 0.9); }
    .room-type-badge.color-double { background: rgba(102, 187, 106, 0.9); }
    .room-type-badge.color-king { background: rgba(255, 167, 38, 0.9); }
    .room-type-badge.color-majesty { background: rgba(171, 71, 188, 0.9); }

    .room-card-content {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .room-card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 8px;
    }

    .room-card-desc {
        font-size: 0.85rem;
        color: #90a4ae;
        line-height: 1.5;
        margin-bottom: 16px;
    }

    .room-features {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 16px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
        color: #b0bec5;
    }

    .feature-item i {
        color: #4fc3f7;
        width: 16px;
    }

    .room-amenities-display {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }

    .amenity-badge {
        background: rgba(79, 195, 247, 0.15);
        color: #4fc3f7;
        border: 1px solid rgba(79, 195, 247, 0.2);
        padding: 4px 10px;
        border-radius: 16px;
        font-size: 0.7rem;
        font-weight: 600;
    }








        .room-benefits {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 14px;
    }

    .benefit-badge {
        background: rgba(102, 187, 106, 0.15);
        color: #66bb6a;
        border: 1px solid rgba(102, 187, 106, 0.25);
        padding: 4px 10px;
        border-radius: 16px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .benefit-badge i {
        font-size: 0.6rem;
    }

    .room-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .room-price {
        display: flex;
        flex-direction: column;
    }

    .price-label {
        font-size: 0.7rem;
        color: #90a4ae;
        font-weight: 600;
    }

    .price-amount {
        font-size: 1.5rem;
        font-weight: 800;
        color: #66bb6a;
    }

    .price-period {
        font-size: 0.7rem;
        color: #78909c;
    }

    .btn-room-book {
        background: linear-gradient(135deg, #4fc3f7, #0288d1);
        color: #0a0e17;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.85rem;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .btn-room-book:hover {
        transform: translateY(-2px);
        color: #0a0e17;
        text-decoration: none;
    }

    /* ── Amenities Showcase ──────────────────────── */
    .amenities-showcase {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
    }

    .amenity-showcase-item {
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.08) 0%, rgba(102, 187, 106, 0.04) 100%);
        border: 1px solid rgba(79, 195, 247, 0.15);
        padding: 30px 24px;
        border-radius: 16px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .amenity-showcase-item:hover {
        transform: translateY(-8px);
        border-color: rgba(79, 195, 247, 0.3);
        box-shadow: 0 12px 40px rgba(79, 195, 247, 0.15);
    }

    .amenity-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.2), rgba(102, 187, 106, 0.1));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: #4fc3f7;
    }

    .amenity-showcase-item h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 8px;
    }

    .amenity-showcase-item p {
        font-size: 0.9rem;
        color: #90a4ae;
        margin: 0;
    }

    /* ── CTA Section ─────────────────────────────── */
    .modern-cta {
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.15) 0%, rgba(102, 187, 106, 0.08) 100%);
        border: 1px solid rgba(79, 195, 247, 0.25);
        border-radius: 20px;
        padding: 60px;
        text-align: center;
        margin-top: 70px;
    }

    .cta-content h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 15px;
    }

    .cta-content p {
        font-size: 1.1rem;
        color: #b0bec5;
        margin-bottom: 30px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ── Responsive ──────────────────────────────── */
    @media (max-width: 1200px) {
        .modern-stats { grid-template-columns: repeat(2, 1fr); }
        .modern-rooms-grid { grid-template-columns: repeat(2, 1fr); }
        .amenities-showcase { grid-template-columns: repeat(2, 1fr); }
        .hero-title { font-size: 3rem; }
    }

    @media (max-width: 768px) {
        .modern-hero { height: 400px; padding: 30px 20px; }
        .hero-title { font-size: 2rem; }
        .hero-subtitle { font-size: 1rem; }
        .hero-buttons { flex-direction: column; }
        .btn-modern-primary, .btn-modern-secondary { width: 100%; }
        .modern-stats { grid-template-columns: 1fr; gap: 15px; padding: 25px; }
        .stat-box { flex-direction: column; text-align: center; }
        .modern-rooms-grid { grid-template-columns: 1fr; }
        .amenities-showcase { grid-template-columns: 1fr; }
        .section-title { font-size: 2rem; }
        .modern-cta { padding: 40px 25px; }
        .cta-content h2 { font-size: 1.8rem; }
    }
</style>

{{-- Chatbot ────────────────────────────────────────── --}}
@push('scripts')
<script async type="module" src="https://interfaces.zapier.com/assets/web-components/zapier-interfaces/zapier-interfaces.esm.js"></script>
<zapier-interfaces-chatbot-embed is-popup="true" chatbot-id="cmrlp57yt003wav2dxvdf1u2o" style="position:fixed;bottom:24px;right:24px;z-index:99999;"></zapier-interfaces-chatbot-embed>
@endpush

@endsection
