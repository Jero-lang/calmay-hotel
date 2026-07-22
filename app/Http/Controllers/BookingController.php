<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Mail\BookingReceiptMail;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function start()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first to make a booking.');
        }

        $customerName = Auth::user()->name;
        Session::put('booking.customer_name', $customerName);
        Session::forget('booking.step');
        
        if (!Session::has('booking.details')) {
            Session::forget('booking.details');
            Session::forget('booking.file');
        }
        
        $generatedBookingId = 'BK-' . strtoupper(Str::random(8));
        $rooms = Room::where('is_available', true)
            ->orderByRaw("FIELD(type, 'single_bed','double_bed','king_bed','majesty_bed')")
            ->orderBy('room_number')
            ->get();

        return view('booking.start', compact('customerName', 'generatedBookingId', 'rooms'));
    }

    public function processDetails(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'booking_id'       => 'required|regex:/^BK-[A-Za-z0-9]{8}$/|unique:bookings,booking_id',
            'check_in_date'    => 'required|date|after_or_equal:today',
            'check_out_date'   => 'required|date|after:check_in_date',
            'check_in_time'    => 'required|date_format:H:i',
            'check_out_time'   => 'nullable|date_format:H:i',
            'room_id'          => 'required|exists:rooms,id',
            'number_of_guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:500',
        ]);

        $room     = Room::find($request->room_id);
        $checkIn  = new \DateTime($request->check_in_date);
        $checkOut = new \DateTime($request->check_out_date);
        $days     = $checkIn->diff($checkOut)->days;
        $totalPrice = $days * $room->price_per_night;

        Session::put('booking.details', [
            'customer_name'    => $request->customer_name,
            'booking_id'       => $request->booking_id,
            'check_in_date'    => $request->check_in_date,
            'check_in_time'    => $request->check_in_time,
            'check_out_date'   => $request->check_out_date,
            'check_out_time'   => $request->check_out_time,
            'room_id'          => $request->room_id,
            'number_of_guests' => (int) $request->number_of_guests,
            'special_requests' => $request->special_requests,
            'total_price'      => $totalPrice,
        ]);

        Session::put('booking.step', 2);

        return redirect()->route('booking.confirmation')
            ->with('success', 'Booking details saved successfully!');
    }

    public function showConfirmation()
    {
        if (!Session::has('booking.details') || Session::get('booking.step') < 2) {
            return redirect()->route('booking.start')
                ->with('error', 'Please complete the booking details first.');
        }

        $details = Session::get('booking.details');
        return view('booking.confirmation', compact('details'));
    }

    public function processConfirmation(Request $request)
    {
        if (!Session::has('booking.details') || Session::get('booking.step') < 2) {
            return redirect()->route('booking.start')
                ->with('error', 'Please complete the booking details first.');
        }

        $request->validate([
            'confirmation_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $details = Session::get('booking.details');
        
        $checkIn = new \DateTime($details['check_in_date']);
        $checkOut = new \DateTime($details['check_out_date']);
        $today = new \DateTime(date('Y-m-d'));
        
        if ($checkIn < $today) {
            return redirect()->route('booking.start')
                ->with('error', 'Check-in date cannot be in the past.');
        }

        if ($checkOut <= $checkIn) {
            return redirect()->route('booking.start')
                ->with('error', 'Check-out date must be after check-in date.');
        }
        
        $file = $request->file('confirmation_file');
        $originalName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('booking_confirmations', $fileName, 'public');

        Session::put('booking.file', [
            'name' => $originalName,
            'path' => $filePath,
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize()
        ]);
        
        Session::put('booking.step', 3);

        $room = Room::find($details['room_id']);
        $days = $checkIn->diff($checkOut)->days;
        $totalPrice = $days * $room->price_per_night;

        $bookingId = $details['booking_id'];

        if (Booking::where('booking_id', $bookingId)->exists()) {
            $bookingId = 'BK-' . strtoupper(Str::random(8));
            while (Booking::where('booking_id', $bookingId)->exists()) {
                $bookingId = 'BK-' . strtoupper(Str::random(8));
            }
            $details['booking_id'] = $bookingId;
            Session::put('booking.details', $details);
        }

        $booking = Booking::create([
            'booking_id'       => $bookingId,
            'user_id'          => Auth::id(),
            'room_id'          => $details['room_id'],
            'check_in_date'    => $details['check_in_date'],
            'check_in_time'    => $details['check_in_time'] ?? null,
            'check_out_date'   => $details['check_out_date'],
            'check_out_time'   => $details['check_out_time'] ?? null,
            'number_of_guests' => $details['number_of_guests'],
            'total_price'      => $totalPrice,
            'status'           => 'pending',
            'special_requests' => $details['special_requests'] ?? null,
            'confirmation_file' => $filePath,
        ]);

        // ── Email auto-send removed — user clicks "Send Summary" button instead ──

        return redirect()->route('booking.summary')
            ->with('success', 'Booking completed! Booking ID: ' . $bookingId);
    }

    public function summary()
    {
        if (!Session::has('booking.details') || !Session::has('booking.file') || Session::get('booking.step') < 3) {
            return redirect()->route('booking.start')
                ->with('error', 'Please complete all booking steps first.');
        }

        $details = Session::get('booking.details');
        $file = Session::get('booking.file');

        return view('booking.summary', compact('details', 'file'));
    }

    public function reset()
    {
        if (Session::has('booking.file')) {
            $file = Session::get('booking.file');
            if (Storage::disk('public')->exists($file['path'])) {
                Storage::disk('public')->delete($file['path']);
            }
        }
        
        Session::forget('booking');
        
        return redirect()->route('booking.start')
            ->with('info', 'Booking process has been reset.');
    }

    public function myBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
                           ->latest()
                           ->paginate(10);
        return view('bookings.my-bookings', compact('bookings'));
    }

    /**
     * Available dates for user-facing calendar — confirmed & checked_in only.
     * Pending bookings from OTHER users are hidden to protect privacy.
     * The logged-in user's own bookings are always shown.
     */
    public function availableDates()
    {
        $userId = Auth::id();

        $bookings = \App\Models\Booking::with('room')
            ->where(function ($q) use ($userId) {
                // Always show confirmed/checked_in from anyone (room is taken)
                $q->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
                  // Also show the current user's own pending bookings
                  ->orWhere(function ($q2) use ($userId) {
                      $q2->where('status', 'pending')
                         ->where('user_id', $userId);
                  });
            })
            ->whereNotIn('status', ['cancelled'])
            ->get(['booking_id', 'user_id', 'room_id', 'check_in_date', 'check_out_date', 'status']);

        $events = $bookings->map(function ($b) use ($userId) {
            $colors = [
                'pending'     => '#ffa726',
                'confirmed'   => '#4fc3f7',
                'checked_in'  => '#66bb6a',
                'checked_out' => '#78909c',
            ];
            $roomNo = $b->room->room_number ?? '?';
            $nights = $b->check_in_date->diffInDays($b->check_out_date);
            $isOwn  = $b->user_id == $userId;

            return [
                'id'              => $b->booking_id,
                'title'           => 'Room ' . $roomNo . ($isOwn ? ' (Yours)' : ''),
                'start'           => $b->check_in_date->format('Y-m-d'),
                'end'             => $b->check_out_date->copy()->addDay()->format('Y-m-d'),
                'backgroundColor' => $isOwn && $b->status === 'pending' ? '#ffa726' : ($colors[$b->status] ?? '#78909c'),
                'borderColor'     => $isOwn ? '#fff' : ($colors[$b->status] ?? '#78909c'),
                'textColor'       => '#0a0e17',
                'extendedProps'   => [
                    'status' => ucfirst(str_replace('_', ' ', $b->status)),
                    'room'   => 'Room ' . $roomNo,
                    'nights' => $nights . ' night' . ($nights != 1 ? 's' : ''),
                    'own'    => $isOwn,
                ],
            ];
        });

        return response()->json($events);
    }

    /**
     * Send booking summary email on demand (button click from summary page).
     */
    public function sendSummaryEmail(Request $request)
    {
        $bookingId = $request->input('booking_id');

        $booking = \App\Models\Booking::with(['user', 'room'])
            ->where('booking_id', $bookingId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$booking) {
            return back()->with('error', 'Booking not found.');
        }

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
                Auth::user()->email,
                'Booking Summary #' . $bookingId . ' - Calmay River Hotel',
                $html
            );

            return back()->with('email_sent', 'Summary sent to ' . Auth::user()->email);
        } catch (\Throwable $e) {
            \Log::warning('Summary email failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email. Please try again.');
        }
    }

    public function downloadReceipt($bookingId)
    {
        $booking = Booking::with(['user', 'room'])->where('booking_id', $bookingId)->first();
        
        if (!$booking) {
            return redirect()->route('booking.start')
                ->with('error', 'Booking not found.');
        }

        $data = [
            'booking' => $booking,
            'user' => $booking->user,
            'room' => $booking->room,
            'hotel_name' => 'Calmay River Hotel',
            'hotel_address' => '123 River St, Calmay City',
            'hotel_phone' => '(02) 8123-4567',
            'hotel_email' => 'info@calmayriverhotel.com',
            'generated_at' => now()->format('F d, Y h:i A'),
        ];

        $pdf = Pdf::loadView('booking.receipt', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('Booking_Receipt_' . $booking->booking_id . '.pdf');
    }
}