<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'notifications_enabled' => 'nullable|boolean',
            'language' => 'nullable|string|in:en,fr,ar',
        ]);

        // Store settings in session temporarily until database column is properly set up
        session(['user_settings' => $validated]);
        
        // Try database storage as a fallback, but don't rely on it
        try {
            $user = auth()->user();
            // Only attempt to save if the settings column exists
            if (\Schema::hasColumn('users', 'settings')) {
                $user->settings = $validated;
                $user->save();
            }
        } catch (\Exception $e) {
            // Silently fail and rely on session storage
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
