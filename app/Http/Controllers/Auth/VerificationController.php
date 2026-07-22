<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use App\Services\MailgunService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function showVerifyForm($email)
    {
        return view('auth.verify', compact('email'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'name' => 'required|string|max:255',
            'password' => 'required|confirmed|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        // Check if code is valid and not expired
        $verification = EmailVerification::where('email', $request->email)
                                         ->where('code', $request->code)
                                         ->where('expires_at', '>', now())
                                         ->first();

        if (!$verification) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        // Create user - NOW the account is saved
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'email_verified_at' => now(),
        ]);

        // Mark as verified and delete code
        $verification->update(['is_verified' => true]);
        $verification->delete();

        // Login the user
        auth()->login($user);

        return redirect()->route('home')
            ->with('success', 'Email verified successfully! Welcome to Calmay River Hotel.');
    }

    public function resendCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $verification = EmailVerification::where('email', $request->email)->first();

        if (!$verification) {
            return back()->withErrors(['email' => 'No verification request found.']);
        }

        // Generate new code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $verification->update([
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Resend email
        $name = session('register_name', 'Guest');
        $mailgun = new MailgunService();
        $html = view('emails.verification-code', ['code' => $code, 'name' => $name])->render();
        $mailgun->send($request->email, 'Email Verification - Calmay River Hotel', $html);

        return back()->with('success', 'New verification code sent to your email.');
    }
}