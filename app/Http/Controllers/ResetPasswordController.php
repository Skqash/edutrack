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

        // Update password in the unified users table
        $hashedPassword = Hash::make($request->password);
        $updated = User::where('email', $request->email)->update(['password' => $hashedPassword]);

        if (!$updated) {
            return back()->with('error', 'Email not found in our system.');
        }

        // Delete the token after successful reset
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect('/login')->with('success', 'Password reset successful. Please login with your new password.');
    }
}
