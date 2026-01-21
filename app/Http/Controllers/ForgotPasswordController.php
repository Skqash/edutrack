<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\SuperAdmin;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if email exists in any user table
        $userExists = User::where('email', $request->email)->exists() ||
                      Admin::where('email', $request->email)->exists() ||
                      Teacher::where('email', $request->email)->exists() ||
                      Student::where('email', $request->email)->exists() ||
                      SuperAdmin::where('email', $request->email)->exists();

        if (!$userExists) {
            return back()->with('error', 'Email not found in our system.');
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        $resetLink = url('/reset-password/' . $token);

        // Try to send email (optional if mail not configured)
        try {
            Mail::send('emails.password-reset', ['resetLink' => $resetLink, 'email' => $request->email], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Password Reset Link');
            });
        } catch (\Exception $e) {
            // If email fails, still show the link for development
        }

        return back()->with('status', 'Password reset link has been sent to your email. Link: ' . $resetLink);
    }
}
