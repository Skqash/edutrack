<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if email exists in the unified users table or legacy account tables
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            $legacyAccount = $this->findLegacyAccountByEmail($request->email);
            if (! $legacyAccount) {
                return back()->with('error', 'Email not found in our system.');
            }

            $user = $this->migrateLegacyAccountToUser($legacyAccount);
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

    private function findLegacyAccountByEmail(string $email)
    {
        return \App\Models\SuperAdmin::where('email', $email)->first()
            ?? \App\Models\Admin::where('email', $email)->first()
            ?? \App\Models\Teacher::where('email', $email)->first()
            ?? \App\Models\Student::where('email', $email)->first();
    }

    private function migrateLegacyAccountToUser($legacyAccount)
    {
        $role = strtolower($legacyAccount->role ?? $legacyAccount->getRoleAttribute() ?? 'student');
        $name = trim(($legacyAccount->first_name ?? '') . ' ' . ($legacyAccount->last_name ?? '')) ?: $legacyAccount->email;

        $user = User::updateOrCreate(
            ['email' => $legacyAccount->email],
            [
                'name' => $name,
                'password' => $legacyAccount->password,
                'phone' => $legacyAccount->phone ?? null,
                'role' => $role,
                'status' => $legacyAccount->status ?? 'Active',
                'school_id' => $legacyAccount->school_id ?? null,
                'campus' => $legacyAccount->campus ?? null,
            ]
        );

        if (property_exists($legacyAccount, 'user_id') && empty($legacyAccount->user_id)) {
            $legacyAccount->user_id = $user->id;
            $legacyAccount->save();
        }

        return $user;
    }
}
