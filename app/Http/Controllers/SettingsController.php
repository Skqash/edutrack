<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Show user settings page
     */
    public function index()
    {
        $user = Auth::user();
        $themes = [
            'light' => 'Light',
            'dark' => 'Dark',
            'ocean' => 'Ocean Blue',
            'forest' => 'Forest Green',
            'sunset' => 'Sunset Orange',
        ];

        return view('settings.index', compact('user', 'themes'));
    }

    /**
     * Update user settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,ocean,forest,sunset',
        ]);

        $user = User::find(Auth::id());
        if ($user) {
            $user->fill($validated);
            $user->save();
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    /**
     * Change theme via AJAX
     */
    public function changeTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,ocean,forest,sunset',
        ]);

        $user = User::find(Auth::id());
        if ($user) {
            $user->theme = $validated['theme'];
            $user->save();
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'theme' => $validated['theme']]);
        }

        return redirect()->back()->with('success', 'Theme changed successfully');
    }
}
