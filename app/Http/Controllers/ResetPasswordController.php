<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showForm($token)
    {
        // Verify token exists and is not expired (24 hours)
        $record = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if (!$record) {
            return redirect('/login')->with('error', 'Invalid or expired token.');
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $record->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required'
        ]);

        // Verify token exists and is not expired
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if (!$record) {
            return back()->with('error', 'Invalid or expired token.');
        }

        // Ensure the user exists in the unified users table, migrating legacy accounts if needed
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            $legacyAccount = $this->findLegacyAccountByEmail($request->email);
            if (! $legacyAccount) {
                return back()->with('error', 'Email not found in our system.');
            }

            $user = $this->migrateLegacyAccountToUser($legacyAccount);
        }

        $hashedPassword = Hash::make($request->password);
        $user->update(['password' => $hashedPassword]);

        // Keep legacy record in sync when available
        if (isset($legacyAccount) && $legacyAccount) {
            $legacyAccount->update(['password' => $hashedPassword]);
        }

        // Delete the token after successful reset
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect('/login')->with('success', 'Password reset successful. Please login with your new password.');
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
