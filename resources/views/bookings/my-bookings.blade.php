@extends('layouts.app')

@section('title', 'My Bookings - Calmay River Hotel')

@section('content')
<div class="card-header-custom">
    <h4 class="mb-0">
        <i class="fas fa-list me-2"></i>
        My Bookings
    </h4>
</div>
<div class="card-body-custom">
    @if($bookings->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <h5>No Bookings Yet</h5>
            <p>You haven't made any bookings yet. Let's get started!</p>
            <a href="{{ route('booking.start') }}" class="btn btn-primary-modern">
                <i class="fas fa-plus me-2"></i> Make a Booking
            </a>
        </div>
    @else
        <div class="bookings-container">
            <!-- Desktop Table View -->
            <div class="table-view">
                <div class="table-responsive">
                    <table class="bookings-table">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Room</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Times</th>
                                <th>Guests</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                            <tr class="booking-row">
                                <td>
                                    <span class="booking-id">{{ $booking->booking_id }}</span>
                                </td>
                                <td>
                                    <span class="room-info">
                                        <i class="fas fa-door-open me-2"></i>
                                        {{ $booking->room->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="date-cell">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="date-cell">
                                        <i class="fas fa-calendar-times me-2"></i>
                                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                                    </div>
                                </td>
                                <td style="font-size:.82rem;white-space:nowrap;">
                                    @if($booking->check_in_time)
                                        <div><i class="fas fa-sign-in-alt me-1" style="color:#66bb6a"></i> {{ \Carbon\Carbon::parse($booking->check_in_time)->format('g:i A') }}</div>
                                    @endif
                                    @if($booking->check_out_time)
                                        <div><i class="fas fa-sign-out-alt me-1" style="color:#ffa726"></i> {{ \Carbon\Carbon::parse($booking->check_out_time)->format('g:i A') }}</div>
                                    @endif
                                    @if(!$booking->check_in_time && !$booking->check_out_time)
                                        <span style="color:#78909c">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="guests-cell">
                                        <i class="fas fa-users me-2"></i>{{ $booking->number_of_guests }}
                                    </span>
                                </td>
                                <td>
                                    <span class="price-cell">₱{{ number_format($booking->total_price, 2) }}</span>
                                </td>
                                <td>
                                    <span class="status-badge
                                        @if($booking->status == 'pending') status-pending
                                        @elseif($booking->status == 'confirmed') status-confirmed
                                        @elseif($booking->status == 'checked_in') status-checked-in
                                        @elseif($booking->status == 'checked_out') status-checked-out
                                        @else status-cancelled
                                        @endif">
                                        <i class="fas 
                                            @if($booking->status == 'pending') fa-clock
                                            @elseif($booking->status == 'confirmed') fa-check
                                            @elseif($booking->status == 'checked_in') fa-door-open
                                            @elseif($booking->status == 'checked_out') fa-sign-out-alt
                                            @else fa-times
                                            @endif me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </td>
                                <td class="action-cell">
                                    <a href="{{ route('booking.receipt', $booking->booking_id) }}" 
                                       class="btn-action" 
                                       target="_blank"
                                       title="Download Receipt">
                                        <i class="fas fa-file-pdf me-1"></i> Receipt
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="card-view">
                @foreach($bookings as $booking)
                <div class="booking-card">
                    <div class="card-header">
                        <div class="header-left">
                            <span class="booking-id">{{ $booking->booking_id }}</span>
                            <span class="status-badge
                                @if($booking->status == 'pending') status-pending
                                @elseif($booking->status == 'confirmed') status-confirmed
                                @elseif($booking->status == 'checked_in') status-checked-in
                                @elseif($booking->status == 'checked_out') status-checked-out
                                @else status-cancelled
                                @endif">
                                <i class="fas 
                                    @if($booking->status == 'pending') fa-clock
                                    @elseif($booking->status == 'confirmed') fa-check
                                    @elseif($booking->status == 'checked_in') fa-door-open
                                    @elseif($booking->status == 'checked_out') fa-sign-out-alt
                                    @else fa-times
                                    @endif me-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-info-row">
                            <span class="info-label"><i class="fas fa-bed me-2"></i>Room</span>
                            <span class="info-value">{{ $booking->room->name ?? 'N/A' }}</span>
                        </div>
                        <div class="card-info-row">
                            <span class="info-label"><i class="fas fa-calendar-check me-2"></i>Check In</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</span>
                        </div>
                        <div class="card-info-row">
                            <span class="info-label"><i class="fas fa-calendar-times me-2"></i>Check Out</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</span>
                        </div>
                        <div class="card-info-row">
                            <span class="info-label"><i class="fas fa-users me-2"></i>Guests</span>
                            <span class="info-value">{{ $booking->number_of_guests }}</span>
                        </div>
                        <div class="card-info-row price-row">
                            <span class="info-label">Total</span>
                            <span class="info-value amount">₱{{ number_format($booking->total_price, 2) }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('booking.receipt', $booking->booking_id) }}" 
                           class="btn btn-primary-modern"
                           target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Receipt
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @if($bookings->hasPages())
        <div class="pagination-wrapper">
            {{ $bookings->links() }}
        </div>
        @endif
    @endif
</div>

<style>
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 30px;
        background: rgba(255, 255, 255, 0.01);
        border: 2px dashed rgba(79, 195, 247, 0.2);
        border-radius: 16px;
    }

    .empty-icon {
        font-size: 3rem;
        color: #4fc3f7;
        margin-bottom: 16px;
        opacity: 0.7;
    }

    .empty-state h5 {
        color: #ffffff;
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #90a4ae;
        font-size: 0.95rem;
        margin-bottom: 24px;
    }

    .btn-primary-modern {
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
        background: linear-gradient(135deg, #4fc3f7 0%, #0288d1 100%);
        color: #0a0e17;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 36px rgba(79, 195, 247, 0.3);
    }

    /* Table view */
    .table-view {
        display: block;
    }

    .bookings-table {
        width: 100%;
        border-collapse: collapse;
    }

    .bookings-table thead {
        background: rgba(79, 195, 247, 0.08);
    }

    .bookings-table thead th {
        border-bottom: 2px solid rgba(79, 195, 247, 0.25);
        color: #4fc3f7;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 14px 16px;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bookings-table tbody tr {
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        transition: all 0.3s ease;
    }

    .bookings-table tbody tr:hover {
        background: rgba(79, 195, 247, 0.08);
        box-shadow: inset 0 0 8px rgba(79, 195, 247, 0.1);
    }

    .bookings-table tbody td {
        padding: 14px 16px;
        color: #b0bec5;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .booking-id {
        display: inline-block;
        background: rgba(79, 195, 247, 0.12);
        color: #4fc3f7;
        padding: 4px 12px;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .room-info,
    .date-cell,
    .guests-cell {
        display: flex;
        align-items: center;
        color: #b0bec5;
    }

    .room-info i,
    .date-cell i,
    .guests-cell i {
        color: #4fc3f7;
    }

    .price-cell {
        color: #66bb6a;
        font-weight: 700;
        font-size: 0.95rem;
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
        gap: 4px;
    }

    .status-pending {
        background: linear-gradient(135deg, rgba(255, 167, 38, 0.2), rgba(245, 127, 23, 0.1));
        color: #ffb74d;
        border: 1px solid rgba(255, 167, 38, 0.3);
    }

    .status-confirmed {
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.2), rgba(2, 136, 209, 0.1));
        color: #81d4fa;
        border: 1px solid rgba(79, 195, 247, 0.3);
    }

    .status-checked-in {
        background: linear-gradient(135deg, rgba(102, 187, 106, 0.2), rgba(56, 142, 60, 0.1));
        color: #81c784;
        border: 1px solid rgba(102, 187, 106, 0.3);
    }

    .status-checked-out {
        background: linear-gradient(135deg, rgba(144, 164, 174, 0.2), rgba(120, 144, 156, 0.1));
        color: #b0bec5;
        border: 1px solid rgba(144, 164, 174, 0.3);
    }

    .status-cancelled {
        background: linear-gradient(135deg, rgba(239, 83, 80, 0.2), rgba(211, 47, 47, 0.1));
        color: #ef9a9a;
        border: 1px solid rgba(239, 83, 80, 0.3);
    }

    /* Action cell */
    .action-cell {
        text-align: center;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(79, 195, 247, 0.12);
        border: 2px solid rgba(79, 195, 247, 0.25);
        color: #4fc3f7;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        background: rgba(79, 195, 247, 0.22);
        border-color: #4fc3f7;
        color: #ffffff;
    }

    /* Card view (mobile) */
    .card-view {
        display: none;
        gap: 16px;
    }

    .booking-card {
        background: linear-gradient(135deg, rgba(26, 26, 46, 0.5) 0%, rgba(22, 33, 62, 0.4) 100%);
        border: 1px solid rgba(79, 195, 247, 0.15);
        border-radius: 14px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .booking-card:hover {
        border-color: rgba(79, 195, 247, 0.3);
        box-shadow: 0 8px 24px rgba(79, 195, 247, 0.1);
    }

    .booking-card .card-header {
        background: rgba(79, 195, 247, 0.08);
        border-bottom: 1px solid rgba(79, 195, 247, 0.15);
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .booking-card .card-body {
        padding: 16px;
    }

    .card-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        gap: 12px;
    }

    .info-label {
        color: #78909c;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        white-space: nowrap;
    }

    .info-label i {
        color: #4fc3f7;
        width: 16px;
    }

    .info-value {
        color: #b0bec5;
        font-size: 0.9rem;
        text-align: right;
        flex: 1;
    }

    .price-row {
        padding: 12px 0;
        margin-top: 8px;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }

    .info-value.amount {
        color: #66bb6a;
        font-weight: 700;
        font-size: 1.05rem;
    }

    .booking-card .card-footer {
        padding: 12px 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }

    .booking-card .btn-primary-modern {
        width: 100%;
        padding: 10px 16px;
        font-size: 0.9rem;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 32px;
        display: flex;
        justify-content: center;
    }

    .pagination .page-item .page-link {
        color: #4fc3f7;
        border: 1px solid rgba(79, 195, 247, 0.25);
        padding: 8px 14px;
        font-size: 0.85rem;
        border-radius: 6px;
        transition: all 0.2s ease;
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
        border-color: rgba(255, 255, 255, 0.05);
    }

    .pagination .page-item .page-link:hover:not(.disabled) {
        background: rgba(79, 195, 247, 0.12);
        border-color: rgba(79, 195, 247, 0.4);
    }

    /* Responsive design */
    @media (max-width: 1024px) {
        .bookings-table thead th,
        .bookings-table tbody td {
            padding: 12px 12px;
            font-size: 0.85rem;
        }

        .booking-id {
            font-size: 0.75rem;
            padding: 3px 10px;
        }
    }

    @media (max-width: 768px) {
        .table-view {
            display: none;
        }

        .card-view {
            display: flex;
            flex-direction: column;
        }

        .booking-card {
            margin-bottom: 8px;
        }

        .empty-state {
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 2.5rem;
        }

        .empty-state h5 {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 480px) {
        .empty-state {
            padding: 30px 16px;
            border-radius: 12px;
        }

        .empty-icon {
            font-size: 2rem;
            margin-bottom: 12px;
        }

        .empty-state h5 {
            font-size: 1rem;
        }

        .empty-state p {
            font-size: 0.85rem;
        }

        .btn-primary-modern {
            padding: 10px 24px;
            font-size: 0.85rem;
        }

        .booking-card .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .header-left {
            width: 100%;
            justify-content: space-between;
        }

        .booking-id {
            font-size: 0.7rem;
        }

        .status-badge {
            font-size: 0.7rem;
            padding: 4px 10px;
        }

        .card-info-row {
            padding: 6px 0;
        }

        .info-label {
            font-size: 0.8rem;
        }

        .info-value {
            font-size: 0.85rem;
        }
    }
</style>
@endsection
