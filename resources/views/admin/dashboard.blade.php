<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Calmay River Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        :root {
            --bg:       #0a0e17;
            --surface:  rgba(26,26,46,0.95);
            --border:   rgba(255,255,255,0.07);
            --accent:   #4fc3f7;
            --green:    #66bb6a;
            --orange:   #ffa726;
            --red:      #ef5350;
            --purple:   #ab47bc;
            --text:     #e0e0e0;
            --muted:    #78909c;
            --sidebar-w: 260px;
        }
        body {
            background: var(--bg); color: var(--text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh; display: flex; margin: 0;
        }
        body::before {
            content: ''; position: fixed; inset: 0;
            background: linear-gradient(145deg,#0a0e17 0%,#1a1a2e 30%,#16213e 60%,#0f3460 100%);
            z-index: -1;
        }

        /* ── Sidebar ───────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w); flex-shrink: 0;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 100; overflow-y: auto;
        }
        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand .brand-icon { color: var(--accent); font-size: 1.6rem; }
        .sidebar-brand .brand-name { color: #fff; font-weight: 700; font-size: 1.05rem; line-height: 1.2; }
        .sidebar-brand .brand-sub  { color: var(--muted); font-size: .75rem; }
        .admin-badge-sidebar {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(239,83,80,.12); border: 1px solid rgba(239,83,80,.25);
            color: #ef9a9a; border-radius: 20px; padding: 2px 10px;
            font-size: .7rem; font-weight: 700; margin-top: 8px;
        }
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .nav-section-label {
            color: var(--muted); font-size: .68rem; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            padding: 0 8px; margin: 16px 0 6px;
        }
        .nav-link-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px; color: #b0bec5;
            text-decoration: none; font-size: .9rem; font-weight: 500;
            transition: all .2s; margin-bottom: 2px;
        }
        .nav-link-item i { width: 18px; text-align: center; font-size: .95rem; }
        .nav-link-item:hover { background: rgba(255,255,255,.05); color: #fff; }
        .nav-link-item.active { background: rgba(79,195,247,.12); color: var(--accent); font-weight: 600; }
        .nav-link-item.active i { color: var(--accent); }
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }
        .admin-user-info {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            background: rgba(255,255,255,.03); margin-bottom: 8px;
        }
        .admin-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg,#ef5350,#c62828);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: .9rem; flex-shrink: 0;
        }
        .admin-name  { color: #fff; font-weight: 600; font-size: .88rem; }
        .admin-role  { color: var(--muted); font-size: .72rem; }
        .btn-logout-sidebar {
            display: flex; align-items: center; gap: 8px;
            width: 100%; padding: 9px 12px; border-radius: 10px;
            background: rgba(239,83,80,.1); border: 1px solid rgba(239,83,80,.2);
            color: #ef9a9a; font-size: .88rem; font-weight: 600;
            cursor: pointer; transition: all .2s; text-decoration: none;
            justify-content: center;
        }
        .btn-logout-sidebar:hover { background: rgba(239,83,80,.2); color: #ef5350; }

        /* ── Main content ──────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1; min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 16px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { color: #fff; font-weight: 700; font-size: 1.2rem; }
        .topbar-title span { color: var(--accent); }
        .topbar-meta { color: var(--muted); font-size: .82rem; }
        .content-area { padding: 28px; flex: 1; }

        /* ── Stat cards ────────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px; margin-bottom: 28px;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px; padding: 22px 20px;
            position: relative; overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(0,0,0,.3); }
        .stat-card::before {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
        }
        .stat-card.blue::before   { background: linear-gradient(90deg,var(--accent),#0288d1); }
        .stat-card.green::before  { background: linear-gradient(90deg,var(--green),#388e3c); }
        .stat-card.orange::before { background: linear-gradient(90deg,var(--orange),#e65100); }
        .stat-card.purple::before { background: linear-gradient(90deg,var(--purple),#6a1b9a); }
        .stat-card.red::before    { background: linear-gradient(90deg,var(--red),#b71c1c); }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; margin-bottom: 14px;
        }
        .stat-icon.blue   { background: rgba(79,195,247,.12);  color: var(--accent); }
        .stat-icon.green  { background: rgba(102,187,106,.12); color: var(--green); }
        .stat-icon.orange { background: rgba(255,167,38,.12);  color: var(--orange); }
        .stat-icon.purple { background: rgba(171,71,188,.12);  color: var(--purple); }
        .stat-icon.red    { background: rgba(239,83,80,.12);   color: var(--red); }
        .stat-value { font-size: 2rem; font-weight: 700; color: #fff; line-height: 1; }
        .stat-label { color: var(--muted); font-size: .82rem; margin-top: 4px; }
        .stat-sub   { font-size: .75rem; margin-top: 8px; font-weight: 600; }
        .stat-sub.up   { color: var(--green); }
        .stat-sub.warn { color: var(--orange); }

        /* ── Section panels ─────────────────────────────── */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
            margin-bottom: 24px;
        }
        .panel-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-title { color: #fff; font-weight: 700; font-size: 1rem; }
        .panel-title i { color: var(--accent); margin-right: 8px; }
        .panel-body { padding: 20px 24px; }

        /* ── Table ──────────────────────────────────────── */
        .tbl { width: 100%; border-collapse: collapse; }
        .tbl th {
            background: rgba(255,255,255,.03);
            color: var(--muted); font-size: .78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .5px;
            padding: 12px 14px; border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .tbl td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(255,255,255,.03);
            font-size: .88rem; color: var(--text); vertical-align: middle;
        }
        .tbl tbody tr:hover { background: rgba(255,255,255,.025); }
        .tbl tbody tr:last-child td { border-bottom: none; }
        .booking-id { font-family: monospace; color: var(--accent); font-weight: 700; font-size: .85rem; }
        .badge-status {
            padding: 4px 10px; border-radius: 20px; font-size: .74rem; font-weight: 700;
            display: inline-block; white-space: nowrap;
        }
        .badge-pending    { background: rgba(255,167,38,.15); color: var(--orange); border: 1px solid rgba(255,167,38,.3); }
        .badge-confirmed  { background: rgba(79,195,247,.15);  color: var(--accent); border: 1px solid rgba(79,195,247,.3); }
        .badge-checked_in { background: rgba(102,187,106,.15); color: var(--green);  border: 1px solid rgba(102,187,106,.3); }
        .badge-checked_out{ background: rgba(120,144,156,.15); color: #b0bec5;       border: 1px solid rgba(120,144,156,.3); }
        .badge-cancelled  { background: rgba(239,83,80,.15);   color: var(--red);    border: 1px solid rgba(239,83,80,.3); }

        /* Status form inline */
        .status-form select {
            background: linear-gradient(135deg, rgba(79, 195, 247, 0.06), rgba(2, 136, 209, 0.04)) !important;
            border: 1px solid rgba(79, 195, 247, 0.2) !important;
            color: var(--text); border-radius: 7px; padding: 6px 10px;
            font-size: .8rem; cursor: pointer; transition: all 0.2s;
        }
        .status-form select:hover {
            background: linear-gradient(135deg, rgba(79, 195, 247, 0.1), rgba(2, 136, 209, 0.08)) !important;
            border-color: rgba(79, 195, 247, 0.35) !important;
        }
        .status-form select:focus { 
            outline: none; 
            border-color: var(--accent) !important;
            background: linear-gradient(135deg, rgba(79, 195, 247, 0.12), rgba(2, 136, 209, 0.1)) !important;
            box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.15) !important;
        }
        /* Dropdown options styling */
        .status-form select option {
            background: var(--surface);
            color: var(--text);
            padding: 8px;
        }
        .status-form select option:checked {
            background: linear-gradient(135deg, rgba(79, 195, 247, 0.3), rgba(2, 136, 209, 0.2));
            color: #fff;
        }
        .btn-save-status {
            background: rgba(79,195,247,.15); border: 1px solid rgba(79,195,247,.3);
            color: var(--accent); border-radius: 7px; padding: 4px 10px;
            font-size: .78rem; cursor: pointer; font-weight: 600; transition: all .2s;
        }
        .btn-save-status:hover { background: rgba(79,195,247,.25); }

        /* ── Mini chart bar ─────────────────────────────── */
        .chart-bars {
            display: flex; align-items: flex-end; gap: 6px;
            height: 80px; padding: 0 4px;
        }
        .chart-bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .chart-bar {
            width: 100%; border-radius: 4px 4px 0 0;
            background: linear-gradient(180deg, var(--accent), #0288d1);
            min-height: 4px; transition: height .3s ease;
        }
        .chart-month { color: var(--muted); font-size: .65rem; }

        /* ── Pagination ─────────────────────────────────── */
        .pagination .page-link {
            background: transparent; border: 1px solid var(--border);
            color: var(--accent); border-radius: 6px !important;
            padding: 5px 12px; font-size: .82rem;
        }
        .pagination .page-item.active .page-link {
            background: var(--accent); border-color: var(--accent); color: #0a0e17;
        }
        .pagination .page-item.disabled .page-link { color: #455a64; }
        .pagination .page-link:hover:not([aria-disabled]) {
            background: rgba(79,195,247,.1); border-color: rgba(79,195,247,.3);
        }

        /* ── Admin Calendar ─────────────────────────────── */
        #adminCalendar .fc { color: var(--text); }
        #adminCalendar .fc-toolbar-title { color: #fff; font-size: 1rem; font-weight: 700; }
        #adminCalendar .fc-button {
            background: rgba(79,195,247,.1) !important;
            border: 1px solid rgba(79,195,247,.25) !important;
            color: var(--accent) !important;
            border-radius: 7px !important; font-size: .8rem !important;
            padding: 4px 12px !important; box-shadow: none !important;
        }
        #adminCalendar .fc-button:hover { background: rgba(79,195,247,.2) !important; }
        #adminCalendar .fc-col-header-cell-cushion { color: var(--muted); font-size: .75rem; font-weight: 700; text-transform: uppercase; text-decoration: none; }
        #adminCalendar .fc-daygrid-day-number { color: #b0bec5; font-size: .82rem; text-decoration: none; }
        #adminCalendar .fc-day-today { background: rgba(79,195,247,.06) !important; }
        #adminCalendar .fc-day-today .fc-daygrid-day-number { color: var(--accent); font-weight: 700; }
        #adminCalendar .fc-scrollgrid { border-color: var(--border) !important; }
        #adminCalendar .fc-scrollgrid td,
        #adminCalendar .fc-scrollgrid th { border-color: rgba(255,255,255,.04) !important; }
        #adminCalendar .fc-event { border-radius: 5px; font-size: .72rem; padding: 2px 6px; border: none; cursor: pointer; }
        #adminCalendar .fc-event-title { font-weight: 700; }
        #adminCalendar .fc-more-link { color: var(--accent); font-size: .72rem; }

        /* ── Responsive ─────────────────────────────────── */
        @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

{{-- ── Sidebar ─────────────────────────────────────────────────── --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fas fa-hotel brand-icon"></i>
            <div>
                <div class="brand-name">Calmay River Hotel</div>
                <div class="brand-sub">Management System</div>
            </div>
        </div>
        <div class="admin-badge-sidebar">
            <i class="fas fa-shield-alt"></i> Admin Panel
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link-item active">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('admin.users') }}" class="nav-link-item">
            <i class="fas fa-users"></i> Guests
        </a>

        <div class="nav-section-label">Hotel</div>
        <a href="{{ route('home') }}" class="nav-link-item" target="_blank">
            <i class="fas fa-globe"></i> View Site
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="admin-user-info">
            <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <div class="admin-name">{{ Auth::user()->name }}</div>
                <div class="admin-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout-sidebar">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </button>
        </form>
    </div>
</aside>

{{-- ── Main ─────────────────────────────────────────────────────── --}}
<div class="main-content">
    <div class="topbar">
        <div>
            <div class="topbar-title"><i class="fas fa-tachometer-alt me-2" style="color:var(--accent)"></i>Admin <span>Dashboard</span></div>
            <div class="topbar-meta">{{ now()->format('l, F j, Y') }}</div>
        </div>
        <div class="d-flex align-items-center gap-3">
            @if(session('success'))
                <span style="color:var(--green);font-size:.85rem;">
                    <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
                </span>
            @endif
        </div>
    </div>

    <div class="content-area">

        {{-- ── Stats row ───────────────────────────────── --}}
        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-value">{{ $totalBookings }}</div>
                <div class="stat-label">Total Bookings</div>
                <div class="stat-sub up"><i class="fas fa-arrow-up me-1"></i>All time</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon green"><i class="fas fa-users"></i></div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-label">Registered Guests</div>
                <div class="stat-sub up"><i class="fas fa-user-plus me-1"></i>Verified accounts</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
                <div class="stat-value">{{ $pendingBookings }}</div>
                <div class="stat-label">Pending Bookings</div>
                <div class="stat-sub warn"><i class="fas fa-exclamation-circle me-1"></i>Awaiting review</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon purple"><i class="fas fa-peso-sign"></i></div>
                <div class="stat-value">₱{{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-sub up"><i class="fas fa-chart-line me-1"></i>Confirmed + Checked In</div>
            </div>
        </div>

        {{-- ── Second stats row ────────────────────────── --}}
        <div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:28px;">
            <div class="stat-card green" style="padding:16px 18px;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-check-circle" style="color:var(--accent)"></i>
                    <span style="color:var(--muted);font-size:.78rem;">Confirmed</span>
                </div>
                <div style="font-size:1.6rem;font-weight:700;color:#fff;">{{ $confirmedBookings }}</div>
            </div>
            <div class="stat-card blue" style="padding:16px 18px;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-door-open" style="color:var(--green)"></i>
                    <span style="color:var(--muted);font-size:.78rem;">Checked In</span>
                </div>
                <div style="font-size:1.6rem;font-weight:700;color:#fff;">{{ $checkedInBookings }}</div>
            </div>
            <div class="stat-card red" style="padding:16px 18px;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-times-circle" style="color:var(--red)"></i>
                    <span style="color:var(--muted);font-size:.78rem;">Cancelled</span>
                </div>
                <div style="font-size:1.6rem;font-weight:700;color:#fff;">{{ $cancelledBookings }}</div>
            </div>
            <div class="stat-card orange" style="padding:16px 18px;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-bed" style="color:var(--orange)"></i>
                    <span style="color:var(--muted);font-size:.78rem;">Total Rooms</span>
                </div>
                <div style="font-size:1.6rem;font-weight:700;color:#fff;">{{ $totalRooms }}</div>
            </div>
        </div>

        {{-- ── Monthly chart ────────────────────────────── --}}
        <div class="panel mb-4">
            <div class="panel-header">
                <span class="panel-title"><i class="fas fa-chart-bar"></i>Bookings This Year — {{ now()->year }}</span>
            </div>
            <div class="panel-body">
                @php
                    $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    $maxVal = max(max($monthlyData), 1);
                @endphp
                <div class="chart-bars">
                    @foreach($monthlyData as $i => $val)
                        @php $pct = round(($val / $maxVal) * 100); @endphp
                        <div class="chart-bar-wrap">
                            <span style="color:var(--muted);font-size:.7rem;margin-bottom:2px;">{{ $val ?: '' }}</span>
                            <div class="chart-bar" style="height:{{ max($pct, 2) }}%;"
                                 title="{{ $months[$i] }}: {{ $val }} bookings"></div>
                            <span class="chart-month">{{ $months[$i] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Expiring Checked-In Bookings ───────────────────── --}}
        <div class="panel mb-4">
            <div class="panel-header">
                <span class="panel-title"><i class="fas fa-exclamation-circle" style="color:var(--orange)"></i>Expiring Checked-In Bookings</span>
                <span style="color:var(--muted);font-size:.82rem;">
                    <i class="fas fa-clock me-1"></i>Check-out today or tomorrow
                </span>
            </div>
            <div class="panel-body" style="padding:20px;">
                @if($expiringCheckIns->count() > 0)
                    <div class="row g-3">
                        @foreach($expiringCheckIns as $booking)
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded p-3" style="background:rgba(255,167,38,.05);border-color:rgba(255,167,38,.2)!important;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="booking-id">{{ $booking->booking_id }}</span>
                                            <div class="badge-status badge-checked_in mt-1">
                                                {{ ucfirst(str_replace('_',' ',$booking->status)) }}
                                            </div>
                                        </div>
                                        @php
                                            $checkOutDate = \Carbon\Carbon::parse($booking->check_out_date);
                                            $isToday = $checkOutDate->isToday();
                                            $isTomorrow = $checkOutDate->isTomorrow();
                                        @endphp
                                        <span style="color:{{ $isToday ? 'var(--red)' : 'var(--orange)' }};font-weight:700;font-size:.9rem;">
                                            <i class="fas fa-door-closed me-1"></i>
                                            {{ $checkOutDate->format('M d') }}
                                            @if($isToday)
                                                <br><small style="font-size:.7rem;">TODAY</small>
                                            @elseif($isTomorrow)
                                                <br><small style="font-size:.7rem;">TOMORROW</small>
                                            @endif
                                        </span>
                                    </div>
                                    <div style="color:#fff;font-weight:600;margin-bottom:4px;">
                                        {{ $booking->user->name ?? '—' }}
                                    </div>
                                    <div style="color:var(--muted);font-size:.8rem;margin-bottom:8px;">
                                        {{ $booking->room->name ?? '—' }} • Room {{ $booking->room->room_number ?? '' }}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span style="color:var(--text);font-size:.85rem;">
                                                <i class="fas fa-calendar-day me-1"></i>
                                                {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d') }} - 
                                                {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                                                @if($booking->check_in_time)
                                                    <br><i class="fas fa-sign-in-alt me-1" style="color:var(--green)"></i>
                                                    {{ \Carbon\Carbon::parse($booking->check_in_time)->format('g:i A') }}
                                                @endif
                                                @if($booking->check_out_time)
                                                    <br><i class="fas fa-sign-out-alt me-1" style="color:var(--orange)"></i>
                                                    {{ \Carbon\Carbon::parse($booking->check_out_time)->format('g:i A') }}
                                                @endif
                                            </span>
                                        </div>
                                        <span style="color:var(--green);font-weight:700;">
                                            ₱{{ number_format($booking->total_price, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center;color:var(--muted);padding:30px;">
                        <i class="fas fa-check-circle fa-2x mb-3" style="color:var(--green)"></i>
                        <div>No checked-in bookings expiring soon.</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Bookings Calendar ────────────────────────── --}}
        <div class="panel mb-4">
            <div class="panel-header">
                <span class="panel-title"><i class="fas fa-calendar-alt"></i>Bookings Calendar</span>
                <div style="display:flex;gap:10px;flex-wrap:wrap;font-size:.75rem;">
                    <span style="display:flex;align-items:center;gap:5px;"><span style="width:12px;height:12px;border-radius:3px;background:#ffa726;display:inline-block;"></span>Pending</span>
                    <span style="display:flex;align-items:center;gap:5px;"><span style="width:12px;height:12px;border-radius:3px;background:#4fc3f7;display:inline-block;"></span>Confirmed</span>
                    <span style="display:flex;align-items:center;gap:5px;"><span style="width:12px;height:12px;border-radius:3px;background:#66bb6a;display:inline-block;"></span>Checked In</span>
                    <span style="display:flex;align-items:center;gap:5px;"><span style="width:12px;height:12px;border-radius:3px;background:#78909c;display:inline-block;"></span>Checked Out</span>
                </div>
            </div>
            <div class="panel-body" style="padding:20px;">
                <div id="adminCalendar"></div>
            </div>
        </div>

        {{-- ── Bookings table ───────────────────────────── --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title"><i class="fas fa-list"></i>All Bookings</span>
                <span style="color:var(--muted);font-size:.82rem;">{{ $bookings->total() }} total</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Times</th>
                            <th>Guests</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td><span class="booking-id">{{ $booking->booking_id }}</span></td>
                            <td>
                                <div style="font-weight:600;color:#fff;">{{ $booking->user->name ?? '—' }}</div>
                                <div style="color:var(--muted);font-size:.75rem;">{{ $booking->user->email ?? '' }}</div>
                            </td>
                            <td>
                                <div style="color:#fff;font-weight:600;">{{ $booking->room->name ?? '—' }}</div>
                                <div style="color:var(--muted);font-size:.75rem;">Room {{ $booking->room->room_number ?? '' }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                            <td style="font-size:.8rem;white-space:nowrap;">
                                @if($booking->check_in_time)
                                    <i class="fas fa-sign-in-alt me-1" style="color:var(--green)"></i>
                                    {{ \Carbon\Carbon::parse($booking->check_in_time)->format('g:i A') }}
                                @endif
                                @if($booking->check_in_time && $booking->check_out_time)
                                    <br>
                                @endif
                                @if($booking->check_out_time)
                                    <i class="fas fa-sign-out-alt me-1" style="color:var(--orange)"></i>
                                    {{ \Carbon\Carbon::parse($booking->check_out_time)->format('g:i A') }}
                                @endif
                                @if(!$booking->check_in_time && !$booking->check_out_time)
                                    <span style="color:var(--muted)">—</span>
                                @endif
                            </td>
                            <td style="text-align:center;">{{ $booking->number_of_guests }}</td>
                            <td style="color:var(--green);font-weight:700;">₱{{ number_format($booking->total_price, 2) }}</td>
                            <td>
                                <span class="badge-status badge-{{ $booking->status }}">
                                    {{ ucfirst(str_replace('_',' ',$booking->status)) }}
                                </span>
                            </td>
                            <td>
                                <form method="POST"
                                      action="{{ route('admin.bookings.status', $booking->booking_id) }}"
                                      class="status-form d-flex gap-1 align-items-center">
                                    @csrf
                                    <select name="status">
                                        @foreach(['pending','confirmed','checked_in','checked_out','cancelled'] as $s)
                                            <option value="{{ $s }}" {{ $booking->status === $s ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_',' ',$s)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn-save-status">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" style="text-align:center;color:var(--muted);padding:40px;">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>No bookings yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bookings->hasPages())
            <div class="panel-body d-flex justify-content-center">
                {{ $bookings->links() }}
            </div>
            @endif
        </div>

    </div>{{-- end content-area --}}
</div>{{-- end main-content --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cal = new FullCalendar.Calendar(document.getElementById('adminCalendar'), {
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listMonth' },
        height: 'auto',
        firstDay: 0,
        editable: false,
        selectable: false,
        dayMaxEvents: 4,
        events: {
            url: '{{ route('api.booked-dates') }}',
            failure: () => console.warn('Could not load booked dates.')
        },
        eventDidMount: function(info) {
            const p = info.event.extendedProps;
            info.el.title =
                (p.bookingId ? '📋 ' + p.bookingId + '\n' : '')
                + (p.room    ? '🏨 ' + p.room    + '\n' : '')
                + (p.nights  ? '🌙 ' + p.nights  + '\n' : '')
                + (p.status  ? '📌 ' + p.status         : '');
        },
        // List view nice text color
        eventContent: function(arg) {
            return { html: '<span style="font-weight:700;color:#0a0e17;">' + arg.event.title + '</span>' };
        },
    });
    cal.render();
});
</script>
</body>
</html>
