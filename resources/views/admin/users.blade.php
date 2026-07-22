<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guests - Calmay River Hotel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reuse dashboard base styles */
        *, *::before, *::after { box-sizing: border-box; }
        :root {
            --bg:#0a0e17; --surface:rgba(26,26,46,0.95); --border:rgba(255,255,255,0.07);
            --accent:#4fc3f7; --green:#66bb6a; --muted:#78909c; --text:#e0e0e0;
            --sidebar-w:260px;
        }
        body { background:var(--bg); color:var(--text); font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; min-height:100vh; display:flex; margin:0; }
        body::before { content:''; position:fixed; inset:0; background:linear-gradient(145deg,#0a0e17 0%,#1a1a2e 30%,#16213e 60%,#0f3460 100%); z-index:-1; }
        .sidebar { width:var(--sidebar-w); flex-shrink:0; background:var(--surface); border-right:1px solid var(--border); display:flex; flex-direction:column; position:fixed; top:0; left:0; bottom:0; z-index:100; overflow-y:auto; }
        .sidebar-brand { padding:24px 20px 20px; border-bottom:1px solid var(--border); }
        .brand-icon { color:var(--accent); font-size:1.6rem; }
        .brand-name { color:#fff; font-weight:700; font-size:1.05rem; }
        .brand-sub  { color:var(--muted); font-size:.75rem; }
        .sidebar-nav { padding:16px 12px; flex:1; }
        .nav-section-label { color:var(--muted); font-size:.68rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; padding:0 8px; margin:16px 0 6px; }
        .nav-link-item { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; color:#b0bec5; text-decoration:none; font-size:.9rem; font-weight:500; transition:all .2s; margin-bottom:2px; }
        .nav-link-item i { width:18px; text-align:center; }
        .nav-link-item:hover { background:rgba(255,255,255,.05); color:#fff; }
        .nav-link-item.active { background:rgba(79,195,247,.12); color:var(--accent); font-weight:600; }
        .sidebar-footer { padding:16px 12px; border-top:1px solid var(--border); }
        .admin-user-info { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; background:rgba(255,255,255,.03); margin-bottom:8px; }
        .admin-avatar { width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#ef5350,#c62828); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:.9rem; flex-shrink:0; }
        .admin-name { color:#fff; font-weight:600; font-size:.88rem; }
        .admin-role { color:var(--muted); font-size:.72rem; }
        .btn-logout-sidebar { display:flex; align-items:center; gap:8px; width:100%; padding:9px 12px; border-radius:10px; background:rgba(239,83,80,.1); border:1px solid rgba(239,83,80,.2); color:#ef9a9a; font-size:.88rem; font-weight:600; cursor:pointer; transition:all .2s; text-decoration:none; justify-content:center; }
        .btn-logout-sidebar:hover { background:rgba(239,83,80,.2); color:#ef5350; }
        .main-content { margin-left:var(--sidebar-w); flex:1; display:flex; flex-direction:column; }
        .topbar { background:var(--surface); border-bottom:1px solid var(--border); padding:16px 28px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:50; }
        .topbar-title { color:#fff; font-weight:700; font-size:1.2rem; }
        .topbar-title span { color:var(--accent); }
        .topbar-meta { color:var(--muted); font-size:.82rem; }
        .content-area { padding:28px; flex:1; }
        .panel { background:var(--surface); border:1px solid var(--border); border-radius:16px; overflow:hidden; }
        .panel-header { padding:18px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
        .panel-title { color:#fff; font-weight:700; font-size:1rem; }
        .panel-title i { color:var(--accent); margin-right:8px; }
        .tbl { width:100%; border-collapse:collapse; }
        .tbl th { background:rgba(255,255,255,.03); color:var(--muted); font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; padding:12px 14px; border-bottom:1px solid var(--border); }
        .tbl td { padding:12px 14px; border-bottom:1px solid rgba(255,255,255,.03); font-size:.88rem; color:var(--text); vertical-align:middle; }
        .tbl tbody tr:hover { background:rgba(255,255,255,.025); }
        .tbl tbody tr:last-child td { border-bottom:none; }
        .user-avatar-sm { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--accent),#0288d1); display:inline-flex; align-items:center; justify-content:center; color:#0a0e17; font-weight:700; font-size:.82rem; flex-shrink:0; }
        .badge-verified { background:rgba(102,187,106,.12); color:var(--green); border:1px solid rgba(102,187,106,.25); padding:3px 9px; border-radius:20px; font-size:.72rem; font-weight:700; }
        .pagination .page-link { background:transparent; border:1px solid var(--border); color:var(--accent); border-radius:6px !important; padding:5px 12px; font-size:.82rem; }
        .pagination .page-item.active .page-link { background:var(--accent); border-color:var(--accent); color:#0a0e17; }
        .pagination .page-item.disabled .page-link { color:#455a64; }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-hotel brand-icon"></i>
            <div>
                <div class="brand-name">Calmay River Hotel</div>
                <div class="brand-sub">Management System</div>
            </div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link-item">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('admin.users') }}" class="nav-link-item active">
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

<div class="main-content">
    <div class="topbar">
        <div>
            <div class="topbar-title"><i class="fas fa-users me-2" style="color:var(--accent)"></i>Registered <span>Guests</span></div>
            <div class="topbar-meta">{{ $users->total() }} total guests</div>
        </div>
    </div>
    <div class="content-area">
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title"><i class="fas fa-users"></i>Guest Accounts</span>
                <span style="color:var(--muted);font-size:.82rem;">{{ $users->total() }} registered</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Bookings</th>
                            <th>Verified</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td style="color:var(--muted);">{{ $loop->iteration + ($users->currentPage()-1) * $users->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar-sm">{{ strtoupper(substr($user->name,0,1)) }}</div>
                                    <span style="font-weight:600;color:#fff;">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td style="color:var(--muted);">{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '—' }}</td>
                            <td style="text-align:center;font-weight:700;color:var(--accent);">{{ $user->bookings_count }}</td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge-verified"><i class="fas fa-check me-1"></i>Verified</span>
                                @else
                                    <span style="color:var(--muted);font-size:.8rem;">Not verified</span>
                                @endif
                            </td>
                            <td style="color:var(--muted);">{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center;color:var(--muted);padding:40px;">
                                <i class="fas fa-users fa-2x mb-2 d-block"></i>No guests registered yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
            <div class="p-4 d-flex justify-content-center">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
