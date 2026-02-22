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

        $schemes = config('grade_schemes.schemes') ?? [];
        $userScheme = $user->grading_scheme ?? null;
        $userWeights = $user->grading_weights ?? null;

        return view('settings.index', compact('user', 'themes', 'schemes', 'userScheme', 'userWeights'));
    }

    /**
     * Update user settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,ocean,forest,sunset',
            'grading_scheme' => 'nullable|string',
            'grading_weights.knowledge' => 'nullable|numeric|min:0|max:100',
            'grading_weights.skills' => 'nullable|numeric|min:0|max:100',
            'grading_weights.attitude' => 'nullable|numeric|min:0|max:100',
        ]);

        $user = User::find(Auth::id());
        if ($user) {
            $user->theme = $validated['theme'];
            if ($request->has('grading_scheme')) {
                $user->grading_scheme = $request->input('grading_scheme');
            }
            if ($request->has('grading_weights')) {
                $user->grading_weights = $request->input('grading_weights');
            }
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
