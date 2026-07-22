@extends('layouts.app')

@section('title', 'Book a Room - Calmay River Hotel')

@section('content')
<div class="card-header-custom">
    <h4 class="mb-0">
        <i class="fas fa-bed me-2"></i>
        Book a Room - Calmay River Hotel
    </h4>
</div>
<div class="card-body-custom">
    <div class="welcome-banner">
        <div class="welcome-icon"><img src="{{ asset('images/logo.png') }}" alt="Calmay River Hotel" class="welcome-logo-img"></div>
        <div class="welcome-text">
            <h5>Welcome, {{ Auth::user()->name }}!</h5>
            <p>Select a room type and fill in your stay details to complete your booking.</p>
        </div>
    </div>

    <form action="{{ route('booking.details') }}" method="POST" id="bookingForm">
        @csrf

        <input type="hidden" name="room_id" id="room_id" value="{{ old('room_id') }}">
        @error('room_id')
            <div class="error-message"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
        @enderror

        <div class="section-container mb-5">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-bed me-2"></i>
                    <span>Select Your Room</span>
                    <span class="required-indicator">*</span>
                </div>
                <p class="section-subtitle">Choose from our selection of comfortable and well-appointed rooms</p>
            </div>

            @php
                $typeLabels = [
                    'single_bed'  => 'Single Bed',
                    'double_bed'  => 'Double Bed',
                    'king_bed'    => 'King Bed',
                    'majesty_bed' => 'Majesty Bed',
                ];
                $typeIcons = [
                    'single_bed'  => 'fa-bed',
                    'double_bed'  => 'fa-bed',
                    'king_bed'    => 'fa-crown',
                    'majesty_bed' => 'fa-star',
                ];
                $roomsByType = $rooms->groupBy('type');
                $oldRoomId   = old('room_id');
            @endphp

            <div class="room-cards-grid">
                @foreach($roomsByType as $type => $typeRooms)
                    @php
                        $rep      = $typeRooms->first();
                        $label    = $typeLabels[$type]  ?? ucwords(str_replace('_',' ',$type));
                        $icon     = $typeIcons[$type]   ?? 'fa-bed';
                        $isOldSel = $typeRooms->contains('id', $oldRoomId);
                        $availNum = $typeRooms->count();
                    @endphp

                    <div class="room-card {{ $isOldSel ? 'active' : '' }}"
                         data-type="{{ $type }}"
                         onclick="selectRoomType(this)">

                        <div class="room-card-image">
                            <img src="{{ $rep->image_url }}"
                                 alt="{{ $label }}"
                                 onerror="this.src='https://placehold.co/600x400/1a1a2e/4fc3f7?text={{ urlencode($label) }}'">
                            <div class="room-card-badge">
                                <i class="fas {{ $icon }} me-1"></i>{{ $label }}
                            </div>
                            <div class="room-card-check-mark">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>

                        <div class="room-card-content">
                            <div class="room-card-meta">
                                <div class="meta-item">
                                    <i class="fas fa-users me-1"></i>
                                    <span>Up to {{ $rep->capacity }} guest{{ $rep->capacity > 1 ? 's' : '' }}</span>
                                </div>
                                <div class="meta-item availability-badge">
                                    <i class="fas fa-door-open me-1"></i>
                                    <span>{{ $availNum }} available</span>
                                </div>
                            </div>

                            @if($rep->amenities)
                            <div class="room-amenities">
                                @foreach(array_slice($rep->amenities, 0, 3) as $am)
                                    <span class="amenity-tag">{{ $am }}</span>
                                @endforeach
                                @if(count($rep->amenities) > 3)
                                    <span class="amenity-tag more-tag">+{{ count($rep->amenities) - 3 }} more</span>
                                @endif
                            </div>
                            @endif

                            <div class="room-card-price">
                                <span class="price-amount">₱{{ number_format($rep->price_per_night, 2) }}</span>
                                <span class="price-period">per night</span>
                            </div>

                            <div class="room-picker" id="picker-{{ $type }}" style="display:none;">
                                <label class="picker-label">Select a room number:</label>
                                <div class="room-selector">
                                    @foreach($typeRooms as $r)
                                        <button type="button"
                                                class="room-option {{ $oldRoomId == $r->id ? 'active' : '' }}"
                                                data-room-id="{{ $r->id }}"
                                                data-room-number="{{ $r->room_number }}"
                                                onclick="pickRoom(event, {{ $r->id }}, '{{ $r->room_number }}')">
                                            {{ $r->room_number }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="selected-room-label" id="selected-label-{{ $type }}" style="display:none;">
                                <i class="fas fa-check-circle me-1"></i>
                                Room <span id="selected-num-{{ $type }}"></span> selected
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="stay-section">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-calendar-check me-2"></i>
                    <span>Your Stay Details</span>
                    <span class="required-indicator">*</span>
                </div>
                <p class="section-subtitle">Provide your stay information and preferences</p>
            </div>

            <div class="details-grid">
                <div class="form-group-wrapper">
                    <label for="customer_name" class="form-label-custom">
                        <i class="fas fa-user me-2"></i> Customer Name
                    </label>
                    <input type="text" class="form-input-custom"
                           id="customer_name" name="customer_name"
                           value="{{ Auth::user()->name }}" readonly>
                </div>

                <div class="form-group-wrapper">
                    <label for="booking_id" class="form-label-custom">
                        <i class="fas fa-hashtag me-2"></i> Booking ID
                    </label>
                    <div class="input-group-custom">
                        <input type="text" class="form-input-custom @error('booking_id') is-invalid @enderror"
                               id="booking_id" name="booking_id"
                               value="{{ $generatedBookingId }}" readonly>
                        <button type="button" class="btn-refresh" onclick="generateBookingId()" title="Generate new ID">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <small class="form-hint"><i class="fas fa-info-circle me-1"></i> Auto-generated Booking ID.</small>
                    @error('booking_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ── Modern Date & Time Picker ─────────────────────── --}}
                <div class="date-range-card">
                    <div class="date-range-header">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Choose Your Stay Dates</span>
                    </div>
                    <div class="date-range-body">
                        {{-- Check-In --}}
                        <div class="date-range-side">
                            <div class="range-label">
                                <i class="fas fa-sign-in-alt"></i> Check-In
                            </div>
                            <div class="datetime-group">
                                <div class="dt-field date-field">
                                    <div class="dt-icon"><i class="fas fa-calendar-day"></i></div>
                                    <div class="dt-input-wrap">
                                        <input type="date"
                                               class="dt-input @error('check_in_date') is-invalid @enderror"
                                               id="check_in_date" name="check_in_date"
                                               value="{{ old('check_in_date', date('Y-m-d', strtotime('+1 day'))) }}"
                                               min="{{ date('Y-m-d') }}"
                                               onchange="onDateChange()">
                                    </div>
                                </div>
                                <div class="dt-field time-field">
                                    <div class="dt-icon"><i class="fas fa-clock"></i></div>
                                    <div class="dt-input-wrap">
                                        <input type="time"
                                               class="dt-input @error('check_in_time') is-invalid @enderror"
                                               id="check_in_time" name="check_in_time"
                                               value="{{ old('check_in_time', '14:00') }}">
                                    </div>
                                </div>
                            </div>
                            <small class="dt-hint"><i class="fas fa-info-circle"></i> Standard check-in is 2:00 PM</small>
                            @error('check_in_date')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                            @error('check_in_time')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Connector Arrow --}}
                        <div class="date-range-connector">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <div class="date-range-connector-mobile">
                            <i class="fas fa-arrow-down"></i>
                        </div>

                        {{-- Check-Out --}}
                        <div class="date-range-side">
                            <div class="range-label">
                                <i class="fas fa-sign-out-alt"></i> Check-Out
                            </div>
                            <div class="datetime-group">
                                <div class="dt-field date-field">
                                    <div class="dt-icon"><i class="fas fa-calendar-times"></i></div>
                                    <div class="dt-input-wrap">
                                        <input type="date"
                                               class="dt-input @error('check_out_date') is-invalid @enderror"
                                               id="check_out_date" name="check_out_date"
                                               value="{{ old('check_out_date', date('Y-m-d', strtotime('+2 days'))) }}"
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                               onchange="onDateChange()">
                                    </div>
                                </div>
                                <div class="dt-field time-field">
                                    <div class="dt-icon"><i class="fas fa-clock"></i></div>
                                    <div class="dt-input-wrap">
                                        <input type="time"
                                               class="dt-input @error('check_out_time') is-invalid @enderror"
                                               id="check_out_time" name="check_out_time"
                                               value="{{ old('check_out_time', '12:00') }}">
                                    </div>
                                </div>
                            </div>
                            <small class="dt-hint"><i class="fas fa-info-circle"></i> Standard check-out is 12:00 PM</small>
                            @error('check_out_date')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                            @error('check_out_time')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group-wrapper">
                    <label for="number_of_guests" class="form-label-custom">
                        <i class="fas fa-users me-2"></i> Number of Guests
                    </label>
                    <input type="number" class="form-input-custom @error('number_of_guests') is-invalid @enderror"
                           id="number_of_guests" name="number_of_guests"
                           value="{{ old('number_of_guests', 1) }}" min="1">
                    @error('number_of_guests')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="price-estimate-card" id="priceEstimate" style="display:none;">
                <div class="estimate-content">
                    <div class="estimate-label">Estimated Total Price</div>
                    <div class="estimate-value" id="estimatedTotal">₱0.00</div>
                    <div class="estimate-breakdown" id="estimateDays"></div>
                </div>
            </div>

            <div class="calendar-section">
                <div class="calendar-header">
                    <div>
                        <i class="fas fa-calendar-alt me-2"></i> Availability Calendar
                        <span class="calendar-badge">View Only</span>
                    </div>
                    <div class="calendar-legend">
                        <span class="legend-item">
                            <span class="legend-dot" style="background:#ffa726;"></span>Pending
                        </span>
                        <span class="legend-item">
                            <span class="legend-dot" style="background:#4fc3f7;"></span>Confirmed
                        </span>
                        <span class="legend-item">
                            <span class="legend-dot" style="background:#66bb6a;"></span>Checked In
                        </span>
                        <span class="legend-item">
                            <span class="legend-dot" style="background:rgba(79,195,247,0.4);border:2px solid #4fc3f7;"></span>Your Selection
                        </span>
                    </div>
                </div>
                <div class="calendar-container" id="bookingCalendar"></div>
            </div>

            <div class="form-group-wrapper">
                <label for="special_requests" class="form-label-custom">
                    <i class="fas fa-comment me-2"></i> Special Requests
                </label>
                <textarea class="form-input-custom textarea-input @error('special_requests') is-invalid @enderror"
                          id="special_requests" name="special_requests"
                          rows="3" placeholder="Any special requests or preferences?">{{ old('special_requests') }}</textarea>
                <small class="form-hint">Let us know if you have any special requests</small>
                @error('special_requests')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="action-buttons">
                <a href="{{ route('booking.reset') }}" class="btn btn-secondary-modern">
                    <i class="fas fa-times me-2"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary-modern" id="submitBtn" disabled>
                    <span>Next Step</span>
                    <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<style>
/* ──────────────────────────────────────────────────────────────
   MODERN BOOKING PAGE STYLES - V2
   Modern colors, gradients, glassmorphism, and smooth transitions
   ────────────────────────────────────────────────────────────── */

/* Welcome banner */
.welcome-banner {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 32px;
    padding: 20px 24px;
    background: linear-gradient(135deg, rgba(79, 195, 247, 0.1) 0%, rgba(102, 187, 106, 0.05) 100%);
    border: 1px solid rgba(79, 195, 247, 0.2);
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.welcome-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.welcome-logo-img {
    height: 50px;
    width: auto;
}

.welcome-text h5 {
    color: #ffffff;
    font-weight: 700;
    margin-bottom: 4px;
    font-size: 1.1rem;
}

.welcome-text p {
    color: #90a4ae;
    font-size: 0.9rem;
    margin-bottom: 0;
}

/* Section containers */
.section-container {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 28px;
    backdrop-filter: blur(10px);
}

.section-header {
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgba(79, 195, 247, 0.15);
}

.section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.3rem;
    font-weight: 700;
    color: #ffffff;
}

.section-title i {
    color: #4fc3f7;
}

.required-indicator {
    color: #ef5350;
    font-weight: 700;
}

.section-subtitle {
    color: #90a4ae;
    font-size: 0.9rem;
    margin-top: 8px;
    margin-bottom: 0;
}

/* Room cards grid */
.room-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 16px;
}

.room-card {
    background: linear-gradient(135deg, rgba(26, 26, 46, 0.5) 0%, rgba(22, 33, 62, 0.4) 100%);
    border: 2px solid rgba(79, 195, 247, 0.15);
    border-radius: 16px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
    backdrop-filter: blur(10px);
}

.room-card:hover {
    border-color: rgba(79, 195, 247, 0.4);
    transform: translateY(-8px);
    box-shadow: 0 16px 48px rgba(79, 195, 247, 0.15), inset 0 1px 1px rgba(255, 255, 255, 0.1);
}

.room-card.active {
    border-color: #4fc3f7;
    background: linear-gradient(135deg, rgba(79, 195, 247, 0.12) 0%, rgba(2, 136, 209, 0.08) 100%);
    box-shadow: 0 0 0 4px rgba(79, 195, 247, 0.2), 0 16px 48px rgba(79, 195, 247, 0.2);
}

/* Room card image */
.room-card-image {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.room-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.room-card:hover .room-card-image img {
    transform: scale(1.08);
}

/* Badge on image */
.room-card-badge {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(10, 14, 23, 0.9), transparent);
    color: #ffffff;
    padding: 20px 16px 12px;
    font-size: 0.9rem;
    font-weight: 600;
}

/* Check mark for active card */
.room-card-check-mark {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4fc3f7, #0288d1);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(79, 195, 247, 0.3);
}

.room-card.active .room-card-check-mark {
    opacity: 1;
    transform: scale(1);
}

/* Room card content */
.room-card-content {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.room-card-meta {
    display: flex;
    flex-direction: column;
    gap: 10px;
    font-size: 0.85rem;
    color: #90a4ae;
    margin-bottom: 14px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.meta-item i {
    color: #4fc3f7;
    width: 16px;
}

.availability-badge {
    color: #66bb6a;
    font-weight: 600;
}

/* Amenities */
.room-amenities {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 14px;
}

.amenity-tag {
    background: rgba(79, 195, 247, 0.12);
    color: #4fc3f7;
    border: 1px solid rgba(79, 195, 247, 0.25);
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.amenity-tag:hover {
    background: rgba(79, 195, 247, 0.2);
    border-color: rgba(79, 195, 247, 0.4);
}

.more-tag {
    background: rgba(255, 255, 255, 0.06);
    color: #78909c;
    border-color: rgba(255, 255, 255, 0.1);
}

/* Room price */
.room-card-price {
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.price-amount {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #66bb6a;
}

.price-period {
    font-size: 0.8rem;
    color: #78909c;
    font-weight: 500;
}

/* Room picker */
.room-picker {
    margin-top: 12px;
}

.picker-label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #90a4ae;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.room-selector {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.room-option {
    background: rgba(79, 195, 247, 0.08);
    border: 2px solid rgba(79, 195, 247, 0.2);
    color: #90a4ae;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 48px;
    text-align: center;
}

.room-option:hover {
    border-color: #4fc3f7;
    color: #4fc3f7;
    background: rgba(79, 195, 247, 0.15);
}

.room-option.active {
    background: linear-gradient(135deg, rgba(79, 195, 247, 0.25), rgba(2, 136, 209, 0.15));
    border-color: #4fc3f7;
    color: #ffffff;
    font-weight: 700;
}

.selected-room-label {
    margin-top: 10px;
    font-size: 0.85rem;
    color: #66bb6a;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

.selected-room-label i {
    font-size: 0.9rem;
}

/* Error messages */
.error-message {
    color: #ef5350;
    font-size: 0.8rem;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Stay section */
.stay-section {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 28px;
    backdrop-filter: blur(10px);
    margin-top: 24px;
}

/* Details grid */
.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.form-group-wrapper {
    display: flex;
    flex-direction: column;
}

.form-label-custom {
    display: flex;
    align-items: center;
    color: #b0bec5;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.form-label-custom i {
    color: #4fc3f7;
    width: 18px;
}

/* Form inputs */
.form-input-custom,
.textarea-input {
    background: linear-gradient(135deg, rgba(79, 195, 247, 0.04), rgba(2, 136, 209, 0.04));
    border: 2px solid rgba(79, 195, 247, 0.15);
    border-radius: 10px;
    padding: 12px 16px;
    color: #e0e0e0;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: inherit;
}

.form-input-custom::placeholder,
.textarea-input::placeholder {
    color: #546e7a;
}

.form-input-custom:focus,
.textarea-input:focus {
    outline: none;
    background: linear-gradient(135deg, rgba(79, 195, 247, 0.08), rgba(2, 136, 209, 0.08));
    border-color: #4fc3f7;
    box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.25), inset 0 0 0 1px rgba(79, 195, 247, 0.2);
}

.form-input-custom:read-only,
.form-input-custom[readonly] {
    background: rgba(255, 255, 255, 0.02);
    border-color: rgba(255, 255, 255, 0.08);
    color: #90a4ae;
    cursor: not-allowed;
}

.form-input-custom.is-invalid,
.textarea-input.is-invalid {
    border-color: #ef5350 !important;
    background: rgba(239, 83, 80, 0.04);
}

/* ══════════════════════════════════════════════════════════
   MODERN DATE RANGE PICKER
   ══════════════════════════════════════════════════════════ */
.date-range-card {
    background: linear-gradient(135deg, rgba(16, 22, 40, 0.8), rgba(26, 32, 56, 0.7));
    border: 1px solid rgba(79, 195, 247, 0.12);
    border-radius: 16px;
    overflow: hidden;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    grid-column: 1 / -1;
}

.date-range-card:hover {
    border-color: rgba(79, 195, 247, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

.date-range-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 24px;
    background: rgba(79, 195, 247, 0.06);
    border-bottom: 1px solid rgba(79, 195, 247, 0.08);
    color: #e0e0e0;
    font-weight: 700;
    font-size: 0.95rem;
    letter-spacing: 0.3px;
}

.date-range-header i {
    color: #4fc3f7;
    font-size: 1.1rem;
}

.date-range-body {
    display: flex;
    align-items: flex-start;
    gap: 0;
    padding: 20px 24px;
}

.date-range-side {
    flex: 1;
    min-width: 0;
}

.range-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #90a4ae;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 14px;
}

.range-label i {
    color: #4fc3f7;
    font-size: 0.85rem;
}

/* Date & Time input group */
.datetime-group {
    display: flex;
    gap: 10px;
}

.dt-field {
    flex: 1;
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.03);
    border: 2px solid rgba(79, 195, 247, 0.12);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.dt-field:hover {
    border-color: rgba(79, 195, 247, 0.25);
    background: rgba(255, 255, 255, 0.05);
}

.dt-field:focus-within {
    border-color: #4fc3f7;
    background: rgba(79, 195, 247, 0.06);
    box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.15), inset 0 0 0 1px rgba(79, 195, 247, 0.1);
}

.dt-field.is-invalid {
    border-color: #ef5350 !important;
}

.dt-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    min-height: 46px;
    background: rgba(79, 195, 247, 0.06);
    color: #4fc3f7;
    font-size: 0.9rem;
    flex-shrink: 0;
    border-right: 1px solid rgba(79, 195, 247, 0.08);
}

.dt-input-wrap {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
}

.dt-input {
    width: 100%;
    padding: 12px 14px;
    background: transparent;
    border: none;
    outline: none;
    color: #e0e0e0;
    font-size: 0.92rem;
    font-family: inherit;
    position: relative;
    z-index: 1;
}

/* Hide default calendar/time icon in some browsers */
.dt-input::-webkit-datetime-edit { padding: 0; }
.dt-input::-webkit-inner-spin-button { display: none; }
.dt-input::-webkit-clear-button { display: none; }

.dt-input::-webkit-calendar-picker-indicator {
    cursor: pointer;
    opacity: 0.6;
    transition: all 0.2s ease;
    filter: invert(0.7) brightness(1.2);
    padding: 4px;
    border-radius: 4px;
}

.dt-input::-webkit-calendar-picker-indicator:hover {
    opacity: 1;
    filter: invert(0.85) brightness(1.4);
    background: rgba(79, 195, 247, 0.15);
}

.dt-hint {
    display: block;
    color: #546e7a;
    font-size: 0.75rem;
    margin-top: 8px;
    padding-left: 2px;
}

.dt-hint i {
    color: #4fc3f7;
    margin-right: 4px;
    font-size: 0.7rem;
}

/* Connector Arrow (Desktop) */
.date-range-connector {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 16px 0;
    flex-shrink: 0;
}

.date-range-connector i {
    font-size: 1.3rem;
    color: #4fc3f7;
    opacity: 0.6;
    animation: pulseArrow 2s ease-in-out infinite;
}

.date-range-connector-mobile {
    display: none;
}

@keyframes pulseArrow {
    0%, 100% { opacity: 0.4; transform: translateX(0); }
    50%      { opacity: 0.8; transform: translateX(4px); }
}

/* Responsive */
@media (max-width: 768px) {
    .date-range-body {
        flex-direction: column;
        gap: 16px;
        padding: 16px;
    }
    .date-range-connector {
        display: none;
    }
    .date-range-connector-mobile {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0;
    }
    .date-range-connector-mobile i {
        font-size: 1.1rem;
        color: #4fc3f7;
        opacity: 0.5;
    }
    .datetime-group {
        flex-direction: column;
        gap: 8px;
    }
    .dt-field {
        min-width: 0;
    }
}

/* Input group */
.input-group-custom {
    display: flex;
    align-items: center;
    gap: 8px;
}

.input-group-custom .form-input-custom {
    flex: 1;
}

.btn-refresh {
    background: rgba(79, 195, 247, 0.12);
    border: 2px solid rgba(79, 195, 247, 0.2);
    color: #4fc3f7;
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.btn-refresh:hover {
    background: rgba(79, 195, 247, 0.2);
    border-color: #4fc3f7;
    transform: rotate(180deg);
}

.form-hint {
    color: #78909c;
    font-size: 0.8rem;
    margin-top: 6px;
    display: block;
}

/* Price estimate card */
.price-estimate-card {
    background: linear-gradient(135deg, rgba(102, 187, 106, 0.12), rgba(56, 142, 60, 0.08));
    border: 2px solid rgba(102, 187, 106, 0.25);
    border-radius: 12px;
    padding: 18px 24px;
    margin-bottom: 24px;
    backdrop-filter: blur(10px);
    animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.estimate-content {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.estimate-label {
    color: #90a4ae;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.estimate-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #66bb6a;
}

.estimate-breakdown {
    color: #78909c;
    font-size: 0.8rem;
}

/* Calendar section */
.calendar-section {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 24px;
}

.calendar-header {
    margin-bottom: 16px;
}

.calendar-header > div:first-child {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 12px;
}

.calendar-header i {
    color: #4fc3f7;
}

.calendar-badge {
    display: inline-block;
    background: rgba(120, 144, 156, 0.15);
    color: #90a4ae;
    border: 1px solid rgba(120, 144, 156, 0.25);
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.calendar-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    font-size: 0.8rem;
    color: #90a4ae;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 3px;
    flex-shrink: 0;
}

.calendar-container {
    background: rgba(255, 255, 255, 0.01);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 12px;
    padding: 16px;
    pointer-events: none;
}

.calendar-container .fc {
    color: #e0e0e0;
}

.calendar-container .fc-toolbar-title {
    color: #ffffff;
    font-size: 1rem;
    font-weight: 700;
}

.calendar-container .fc-button {
    background: rgba(79, 195, 247, 0.1) !important;
    border: 1px solid rgba(79, 195, 247, 0.2) !important;
    color: #4fc3f7 !important;
    border-radius: 6px !important;
    font-size: 0.8rem !important;
    padding: 4px 12px !important;
    box-shadow: none !important;
    text-transform: capitalize !important;
    transition: all 0.2s ease !important;
}

.calendar-container .fc-button:hover {
    background: rgba(79, 195, 247, 0.18) !important;
    border-color: rgba(79, 195, 247, 0.35) !important;
}

.calendar-container .fc-button-active {
    background: rgba(79, 195, 247, 0.25) !important;
    border-color: #4fc3f7 !important;
}

.calendar-container .fc-col-header-cell {
    background: rgba(79, 195, 247, 0.05) !important;
    border-color: rgba(255, 255, 255, 0.05) !important;
}

.calendar-container .fc-col-header-cell-cushion {
    color: #90a4ae;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    text-decoration: none;
    letter-spacing: 0.3px;
}

.calendar-container .fc-daygrid-day-number {
    color: #b0bec5;
    font-size: 0.8rem;
    text-decoration: none;
    padding: 4px 6px;
}

.calendar-container .fc-day-today {
    background: rgba(79, 195, 247, 0.08) !important;
}

.calendar-container .fc-day-today .fc-daygrid-day-number {
    color: #4fc3f7;
    font-weight: 700;
}

.calendar-container .fc-daygrid-day:hover {
    background: rgba(79, 195, 247, 0.1) !important;
}

.calendar-container .fc-day-past .fc-daygrid-day-number {
    color: #455a64;
}

.calendar-container .fc-scrollgrid {
    border-color: rgba(255, 255, 255, 0.05) !important;
}

.calendar-container .fc-scrollgrid td,
.calendar-container .fc-scrollgrid th {
    border-color: rgba(255, 255, 255, 0.04) !important;
}

.calendar-container .sel-start,
.calendar-container .sel-end {
    background: rgba(79, 195, 247, 0.25) !important;
}

.calendar-container .sel-start .fc-daygrid-day-number,
.calendar-container .sel-end .fc-daygrid-day-number {
    color: #ffffff;
    font-weight: 700;
}

.calendar-container .sel-range {
    background: rgba(79, 195, 247, 0.1) !important;
}

.calendar-container .fc-event {
    border-radius: 4px;
    font-size: 0.7rem;
    padding: 1px 5px;
    border: none !important;
}

.calendar-container .fc-event-title {
    font-weight: 600;
}

/* Action buttons */
.action-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-top: 28px;
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

.btn-primary-modern:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 12px 36px rgba(79, 195, 247, 0.3);
}

.btn-primary-modern:active:not(:disabled) {
    transform: translateY(0);
}

.btn-primary-modern:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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

.btn-secondary-modern:active {
    transform: translateY(0);
}

/* Responsive design */
@media (max-width: 1024px) {
    .details-grid {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }

    .calendar-legend {
        gap: 12px;
    }
}

@media (max-width: 768px) {
    .room-cards-grid {
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
    }

    .room-card-image {
        height: 160px;
    }

    .welcome-banner {
        flex-direction: column;
        text-align: center;
    }

    .section-container,
    .stay-section {
        padding: 20px;
    }

    .section-header {
        padding-bottom: 16px;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 1.1rem;
    }

    .details-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .action-buttons {
        flex-direction: column-reverse;
        gap: 12px;
    }

    .btn-primary-modern,
    .btn-secondary-modern {
        width: 100%;
    }

    .calendar-legend {
        flex-direction: column;
        gap: 10px;
    }

    .calendar-container {
        padding: 12px;
    }
}

@media (max-width: 480px) {
    .room-cards-grid {
        grid-template-columns: 1fr;
    }

    .room-card {
        margin-bottom: 8px;
    }

    .section-title {
        font-size: 1rem;
    }

    .form-input-custom {
        padding: 11px 14px;
        font-size: 0.9rem;
    }

    .btn-refresh {
        width: 40px;
        height: 40px;
        font-size: 0.85rem;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
    const roomPrices = {
        @foreach($rooms as $r)
            {{ $r->id }}: {{ $r->price_per_night }},
        @endforeach
    };

    let selectedRoomId   = {{ old('room_id') ? old('room_id') : 'null' }};
    let selectedRoomType = null;
    let calendar         = null;

    function selectRoomType(card) {
        document.querySelectorAll('.room-card').forEach(c => {
            c.classList.remove('active');
            document.getElementById('picker-' + c.dataset.type).style.display = 'none';
        });
        card.classList.add('active');
        selectedRoomType = card.dataset.type;
        document.getElementById('picker-' + selectedRoomType).style.display = 'block';
        selectedRoomId = null;
        document.getElementById('room_id').value = '';
        checkSubmit();
        updateEstimate();
    }

    function pickRoom(event, roomId, roomNumber) {
        event.stopPropagation();
        const picker = event.target.closest('.room-picker');
        picker.querySelectorAll('.room-option').forEach(b => b.classList.remove('active'));
        event.target.classList.add('active');
        selectedRoomId = roomId;
        document.getElementById('room_id').value = roomId;
        const labelEl = document.getElementById('selected-label-' + selectedRoomType);
        const numEl   = document.getElementById('selected-num-' + selectedRoomType);
        if (labelEl && numEl) { numEl.textContent = roomNumber; labelEl.style.display = 'flex'; }
        checkSubmit();
        updateEstimate();
    }

    function checkSubmit() {
        const ci = document.getElementById('check_in_date').value;
        const co = document.getElementById('check_out_date').value;
        document.getElementById('submitBtn').disabled = !(selectedRoomId && ci && co);
    }

    function updateEstimate() {
        const ci = document.getElementById('check_in_date').value;
        const co = document.getElementById('check_out_date').value;
        const el = document.getElementById('priceEstimate');
        if (!selectedRoomId || !ci || !co) { el.style.display = 'none'; return; }
        const days = Math.round((new Date(co) - new Date(ci)) / 86400000);
        if (days <= 0) { el.style.display = 'none'; return; }
        const price = roomPrices[selectedRoomId] || 0;
        const total = price * days;
        document.getElementById('estimatedTotal').textContent =
            '₱' + total.toLocaleString('en-PH', {minimumFractionDigits: 2});
        document.getElementById('estimateDays').textContent =
            ' (' + days + ' night' + (days > 1 ? 's' : '') + ' × ₱' + price.toLocaleString('en-PH') + ')';
        el.style.display = 'block';
    }

    function highlightSelected() {
        const ci = document.getElementById('check_in_date').value;
        const co = document.getElementById('check_out_date').value;
        document.querySelectorAll('.calendar-container .fc-daygrid-day').forEach(cell => {
            cell.classList.remove('sel-start','sel-end','sel-range');
        });
        if (!ci) return;
        const s = new Date(ci + 'T00:00:00');
        const e = co ? new Date(co + 'T00:00:00') : null;
        document.querySelectorAll('.calendar-container .fc-daygrid-day').forEach(cell => {
            const d = new Date(cell.dataset.date + 'T00:00:00');
            if (cell.dataset.date === ci)       { cell.classList.add('sel-start'); return; }
            if (co && cell.dataset.date === co) { cell.classList.add('sel-end');   return; }
            if (e && d > s && d < e)            { cell.classList.add('sel-range'); }
        });
    }

    function onDateChange() {
        const ci = document.getElementById('check_in_date').value;
        const co = document.getElementById('check_out_date').value;
        if (ci) {
            document.getElementById('check_out_date').min =
                new Date(new Date(ci).getTime() + 86400000).toISOString().split('T')[0];
        }
        if (ci && calendar) {
            calendar.gotoDate(ci);
        }
        highlightSelected();
        checkSubmit();
        updateEstimate();
    }

    document.addEventListener('DOMContentLoaded', function () {
        calendar = new FullCalendar.Calendar(document.getElementById('bookingCalendar'), {
            initialView:   'dayGridMonth',
            headerToolbar: { left: 'prev,next today', center: 'title', right: '' },
            height:        'auto',
            firstDay:      0,
            editable:      false,
            selectable:    false,
            events: {
                url: '{{ route('api.available-dates') }}',
                failure: () => console.warn('Could not load booked dates.')
            },
            datesSet: () => setTimeout(highlightSelected, 50),
            eventDidMount: function(info) {
                info.el.title = info.event.title + ' (' + (info.event.extendedProps.status || '') + ')';
            },
        });
        calendar.render();

        @if(old('room_id'))
            document.querySelectorAll('.room-card').forEach(card => {
                if (card.querySelector('.room-option.active')) {
                    card.classList.add('active');
                    document.getElementById('picker-' + card.dataset.type).style.display = 'block';
                    selectedRoomType = card.dataset.type;
                }
            });
        @endif

        highlightSelected();
        checkSubmit();
        updateEstimate();
    });

    document.getElementById('check_in_date').addEventListener('change', onDateChange);
    document.getElementById('check_out_date').addEventListener('change', onDateChange);

    function generateBookingId() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = 'BK-';
        for (let i = 0; i < 8; i++) result += chars.charAt(Math.floor(Math.random() * chars.length));
        document.getElementById('booking_id').value = result;
    }
</script>
@endsection
