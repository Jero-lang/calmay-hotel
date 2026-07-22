<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use App\Services\MailgunService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete old verification codes for this email
        EmailVerification::where('email', $request->email)->delete();

        // Store verification code (User is NOT created yet)
        EmailVerification::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
            'is_verified' => false,
        ]);

        // Send email with verification code
        try {
            $mailgun = new MailgunService();
            $html = view('emails.verification-code', ['code' => $code, 'name' => $request->name])->render();
            $mailgun->send(
                $request->email,
                'Email Verification - Calmay River Hotel',
                $html
            );
        } catch (\Throwable $e) {
            \Log::error('Verification email failed: ' . $e->getMessage());
            // Don't block registration — still redirect to verify page
        }

        // Store user data in session temporarily (not saved yet)
        session([
            'register_name' => $request->name,
            'register_password' => $request->password,
            'register_phone' => $request->phone,
        ]);

        return redirect()->route('verify.form', ['email' => $request->email])
            ->with('success', 'Verification code sent to your email!');
    }
}