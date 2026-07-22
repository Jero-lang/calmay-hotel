@extends('layouts.app')

@section('title', 'Confirm Booking - Calmay River Hotel')

@section('content')
<div class="card-header-custom">
    <h4 class="mb-0">
        <i class="fas fa-upload me-2"></i>
        Confirm Your Booking
    </h4>
</div>
<div class="card-body-custom">

    @if(session('success'))
        <div class="alert alert-success alert-custom alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-custom alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="confirmation-grid">

        <div class="summary-card">
            <div class="card-header">
                <i class="fas fa-receipt me-2"></i> Booking Summary
            </div>
            <div class="card-body">
                @php
                    $room = \App\Models\Room::find($details['room_id']);
                    $checkIn  = \Carbon\Carbon::parse($details['check_in_date']);
                    $checkOut = \Carbon\Carbon::parse($details['check_out_date']);
                    $nights   = $checkIn->diffInDays($checkOut);
                @endphp

                <div class="summary-item">
                    <span class="item-label"><i class="fas fa-hashtag me-2"></i>Booking ID</span>
                    <span class="item-value mono">{{ $details['booking_id'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="item-label"><i class="fas fa-user me-2"></i>Guest Name</span>
                    <span class="item-value">{{ $details['customer_name'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="item-label"><i class="fas fa-bed me-2"></i>Room</span>
                    <span class="item-value">
                        {{ $room->name ?? 'N/A' }}
                        @if($room)
                            <span class="value-badge">Room {{ $room->room_number }}</span>
                        @endif
                    </span>
                </div>
                @if($room)
                <div class="room-image-section">
                    <img src="{{ $room->image_url }}" alt="{{ $room->name }}" class="room-preview-img">
                </div>
                @endif
                <div class="summary-item">
                    <span class="item-label"><i class="fas fa-calendar-check me-2"></i>Check In</span>
                    <span class="item-value">
                        {{ $checkIn->format('F d, Y') }}
                        @if(!empty($details['check_in_time']))
                            <span class="value-badge">{{ \Carbon\Carbon::createFromFormat('H:i', $details['check_in_time'])->format('h:i A') }}</span>
                        @endif
                    </span>
                </div>
                <div class="summary-item">
                    <span class="item-label"><i class="fas fa-calendar-times me-2"></i>Check Out</span>
                    <span class="item-value">
                        {{ $checkOut->format('F d, Y') }}
                        @if(!empty($details['check_out_time']))
                            <span class="value-badge">{{ \Carbon\Carbon::createFromFormat('H:i', $details['check_out_time'])->format('h:i A') }}</span>
                        @endif
                    </span>
                </div>
                <div class="summary-item">
                    <span class="item-label"><i class="fas fa-moon me-2"></i>Duration</span>
                    <span class="item-value">{{ $nights }} night{{ $nights > 1 ? 's' : '' }}</span>
                </div>
                <div class="summary-item">
                    <span class="item-label"><i class="fas fa-users me-2"></i>Guests</span>
                    <span class="item-value">{{ $details['number_of_guests'] }} person{{ $details['number_of_guests'] > 1 ? 's' : '' }}</span>
                </div>
                @if(!empty($details['special_requests']))
                <div class="summary-item">
                    <span class="item-label"><i class="fas fa-comment me-2"></i>Requests</span>
                    <span class="item-value details">{{ $details['special_requests'] }}</span>
                </div>
                @endif

                <div class="summary-total">
                    <span class="total-label">Total Amount</span>
                    <span class="total-amount">₱{{ number_format($details['total_price'], 2) }}</span>
                </div>
            </div>
        </div>

        <div class="upload-card">
            <div class="card-header" style="color: #66bb6a; border-bottom-color: rgba(102, 187, 106, 0.25); background: rgba(102, 187, 106, 0.08);">
                <i class="fas fa-file-upload me-2"></i> Upload Payment Confirmation
            </div>
            <div class="card-body">
                <p class="upload-hint">
                    <i class="fas fa-info-circle me-2"></i>
                    Please upload your proof of payment (screenshot, receipt, or bank slip).
                    Accepted formats: <strong>PDF, JPG, PNG</strong> — max 2MB.
                </p>

                <form action="{{ route('booking.confirmation.post') }}"
                      method="POST" enctype="multipart/form-data" id="confirmForm">
                    @csrf

                    <div class="dropzone-container" id="dropzone" onclick="document.getElementById('fileInput').click()">
                        <div class="dropzone-icon" id="dzIcon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="dropzone-text" id="dzText">
                            <span class="text-main">Click to browse or drag & drop</span>
                            <span class="text-sub">PDF, JPG, PNG up to 2MB</span>
                        </div>
                        <div class="dropzone-preview" id="dzPreview" style="display:none;">
                            <i class="fas fa-file-check me-2"></i>
                            <span id="dzFileName"></span>
                        </div>
                    </div>

                    <input type="file" id="fileInput" name="confirmation_file"
                           accept=".pdf,.jpg,.jpeg,.png"
                           style="display:none;" onchange="handleFile(this)">

                    @error('confirmation_file')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror

                    <div class="form-actions">
                        <a href="{{ route('booking.start') }}" class="btn btn-secondary-modern">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary-modern" id="uploadBtn" disabled>
                            <i class="fas fa-check-circle me-2"></i> Confirm Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
    /* Confirmation page styles */
    .confirmation-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 20px;
    }

    .summary-card,
    .upload-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        display: flex;
        flex-direction: column;
        height: 100%;
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
        display: flex;
        flex-direction: column;
    }

    /* Summary items */
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        gap: 12px;
    }

    .summary-item:last-of-type {
        border-bottom: none;
    }

    .item-label {
        color: #78909c;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
        display: flex;
        align-items: center;
    }

    .item-label i {
        color: #4fc3f7;
        width: 18px;
    }

    .item-value {
        color: #e0e0e0;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: right;
        flex: 1;
    }

    .item-value.mono {
        font-family: 'Courier New', monospace;
        color: #4fc3f7;
        font-size: 0.95rem;
    }

    .item-value.details {
        font-size: 0.85rem;
        color: #90a4ae;
        text-align: left;
    }

    .value-badge {
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

    /* Summary total */
    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        padding: 16px 18px;
        background: linear-gradient(135deg, rgba(102, 187, 106, 0.12), rgba(56, 142, 60, 0.08));
        border: 1px solid rgba(102, 187, 106, 0.2);
        border-radius: 10px;
    }

    .total-label {
        color: #b0bec5;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .total-amount {
        color: #66bb6a;
        font-size: 1.6rem;
        font-weight: 800;
    }

    /* Upload hint */
    .upload-hint {
        color: #90a4ae;
        font-size: 0.85rem;
        line-height: 1.6;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .upload-hint i {
        color: #4fc3f7;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* Dropzone */
    .dropzone-container {
        border: 2px dashed rgba(79, 195, 247, 0.25);
        border-radius: 14px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(79, 195, 247, 0.02);
        min-height: 180px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .dropzone-container:hover,
    .dropzone-container.dragover {
        border-color: #4fc3f7;
        background: rgba(79, 195, 247, 0.08);
    }

    .dropzone-container.has-file {
        border-color: #66bb6a;
        background: rgba(102, 187, 106, 0.08);
    }

    .dropzone-icon {
        font-size: 2.8rem;
        color: #546e7a;
        opacity: 0.7;
    }

    .dropzone-container.has-file .dropzone-icon {
        display: none;
    }

    .dropzone-text {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .text-main {
        color: #b0bec5;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .text-sub {
        color: #78909c;
        font-size: 0.8rem;
    }

    .dropzone-container.has-file .dropzone-text {
        display: none;
    }

    .dropzone-preview {
        color: #66bb6a;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Error message */
    .error-message {
        color: #ef5350;
        font-size: 0.8rem;
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
        background: rgba(239, 83, 80, 0.08);
        padding: 10px 12px;
        border-radius: 8px;
        border-left: 3px solid #ef5350;
    }

    /* Form actions */
    .form-actions {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    /* Buttons */
    .btn-primary-modern,
    .btn-secondary-modern {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 28px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
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

    .btn-primary-modern:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-secondary-modern {
        background: rgba(255, 255, 255, 0.06);
        border: 2px solid rgba(255, 255, 255, 0.1);
        color: #b0bec5;
        flex: 1;
    }

    .btn-primary-modern {
        flex: 1;
    }

    .btn-secondary-modern:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .confirmation-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-primary-modern,
        .btn-secondary-modern {
            width: 100%;
        }

        .card-body {
            padding: 18px;
        }

        .summary-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 10px 0;
        }

        .item-value {
            text-align: left;
        }

        .dropzone-container {
            padding: 30px 15px;
            min-height: 150px;
        }
    }

    @media (max-width: 480px) {
        .card-header {
            font-size: 0.9rem;
            padding: 14px 16px;
        }

        .summary-total {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .total-amount {
            font-size: 1.4rem;
        }
    }
</style>

<script>
    function handleFile(input) {
        const file = input.files[0];
        if (!file) return;

        const dz       = document.getElementById('dropzone');
        const preview  = document.getElementById('dzPreview');
        const fileName = document.getElementById('dzFileName');
        const btn      = document.getElementById('uploadBtn');

        if (file.size > 2 * 1024 * 1024) {
            alert('File is too large. Maximum size is 2MB.');
            input.value = '';
            return;
        }

        fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        preview.style.display = 'flex';
        dz.classList.add('has-file');
        btn.disabled = false;
    }

    const dz = document.getElementById('dropzone');
    dz.addEventListener('dragover',  e => { e.preventDefault(); dz.classList.add('dragover'); });
    dz.addEventListener('dragleave', () => dz.classList.remove('dragover'));
    dz.addEventListener('drop', e => {
        e.preventDefault();
        dz.classList.remove('dragover');
        const dt = e.dataTransfer;
        if (dt.files.length) {
            document.getElementById('fileInput').files = dt.files;
            handleFile(document.getElementById('fileInput'));
        }
    });
</script>
@endsection
