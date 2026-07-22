<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ── Login ────────────────────────────────────────────────────────

    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->isAdmin()) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
            // Logged in but not admin — log them back out
            Auth::logout();
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Invalid admin credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('status', 'Logged out successfully.');
    }

    // ── Dashboard ─────────────────────────────────────────────────────

    public function dashboard(Request $request)
    {
        // Summary stats
        $totalBookings   = Booking::count();
        $totalUsers      = User::where('is_admin', false)->count();
        $totalRooms      = Room::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings  = Booking::where('status', 'confirmed')->count();
        $checkedInBookings  = Booking::where('status', 'checked_in')->count();
        $cancelledBookings  = Booking::where('status', 'cancelled')->count();
        $totalRevenue    = Booking::whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
                                  ->sum('total_price');

        // Monthly bookings for the current year (for chart)
        $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = $monthlyBookings[$m] ?? 0;
        }

        // Expiring checked-in bookings (check-out is today or tomorrow)
        $expiringCheckIns = Booking::with(['user', 'room'])
            ->where('status', 'checked_in')
            ->whereDate('check_out_date', '<=', now()->addDay())
            ->whereDate('check_out_date', '>=', now())
            ->orderBy('check_out_date')
            ->get();

        // Recent bookings
        $recentBookings = Booking::with(['user', 'room'])
            ->latest()
            ->take(10)
            ->get();

        // All bookings (paginated) with search
        $search = $request->query('search');
        $bookings = Booking::with(['user', 'room'])
            ->when($search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('booking_id', 'like', "%{$search}%")
                      ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")
                                                      ->orWhere('email', 'like', "%{$search}%"))
                      ->orWhereHas('room', fn($q) => $q->where('name', 'like', "%{$search}%")
                                                      ->orWhere('room_number', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.dashboard', compact(
            'totalBookings', 'totalUsers', 'totalRooms',
            'pendingBookings', 'confirmedBookings', 'checkedInBookings',
            'cancelledBookings', 'totalRevenue',
            'monthlyData', 'expiringCheckIns', 'recentBookings', 'bookings'
        ));
    }

    // ── Booking management ────────────────────────────────────────────

    public function updateBookingStatus(Request $request, $bookingId)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
        ]);

        $booking = Booking::where('booking_id', $bookingId)->firstOrFail();
        $oldStatus = $booking->status;
        $booking->update(['status' => $request->status]);

        // Send email receipt when admin confirms booking
        if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
            try {
                $mailgun = new \App\Services\MailgunService();
                $html = view('emails.booking-receipt', [
                    'booking'       => $booking,
                    'hotel_name'    => 'Calmay River Hotel',
                    'hotel_address' => '123 River St, Calmay City',
                    'hotel_phone'   => '(02) 8123-4567',
                    'hotel_email'   => 'info@calmayriverhotel.com',
                    'generated_at'  => now()->format('F d, Y h:i A'),
                ])->render();

                $mailgun->send(
                    $booking->user->email,
                    'Booking Confirmed #' . $bookingId . ' - Calmay River Hotel',
                    $html
                );
            } catch (\Throwable $e) {
                \Log::warning('Confirmation email failed for booking ' . $bookingId . ': ' . $e->getMessage());
            }
        }

        return back()->with('success', "Booking {$bookingId} status updated to " . ucfirst($request->status) . '.');
    }

    // ── Users list ────────────────────────────────────────────────────

    public function users()
    {
        $users = User::where('is_admin', false)
            ->withCount('bookings')
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    // ── Booked dates API (for FullCalendar) ───────────────────────────

    public function bookedDates()
    {
        $bookings = Booking::whereNotIn('status', ['cancelled'])
            ->with(['room'])
            ->get(['booking_id', 'user_id', 'room_id', 'check_in_date', 'check_out_date', 'status']);

        $events = $bookings->map(function ($b) {
            $colors = [
                'pending'     => '#ffa726',
                'confirmed'   => '#4fc3f7',
                'checked_in'  => '#66bb6a',
                'checked_out' => '#78909c',
            ];
            $guestName = $b->user->name ?? 'Guest';
            $roomNo    = $b->room->room_number ?? '?';
            $nights    = $b->check_in_date->diffInDays($b->check_out_date);

            return [
                'id'              => $b->booking_id,
                'title'           => 'Room ' . $roomNo,
                'start'           => $b->check_in_date->format('Y-m-d'),
                'end'             => $b->check_out_date->copy()->addDay()->format('Y-m-d'),
                'backgroundColor' => $colors[$b->status] ?? '#78909c',
                'borderColor'     => $colors[$b->status] ?? '#78909c',
                'textColor'       => '#0a0e17',
                'extendedProps'   => [
                    'status'     => ucfirst(str_replace('_', ' ', $b->status)),
                    'bookingId'  => $b->booking_id,
                    'room'       => 'Room ' . $roomNo,
                    'nights'     => $nights . ' night' . ($nights != 1 ? 's' : ''),
                ],
            ];
        });

        return response()->json($events);
    }
}
