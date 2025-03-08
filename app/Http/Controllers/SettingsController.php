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
            'theme' => 'nullable|string|in:light,dark',
            'notifications_enabled' => 'nullable|boolean',
            'language' => 'nullable|string|in:en,fr',
        ]);

        $user = auth()->user();
        $user->settings = array_merge($user->settings ?? [], $validated);
        $user->save();

        return back()->with('success', 'Settings updated successfully.');
    }
}
