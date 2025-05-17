<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    // Show the settings page
    public function index()
    {
        $user = Auth::user(); // get currently logged-in user
        return view('settings.index', compact('user'));
    }

    // Update general settings
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate input fields - adjust as needed
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
        ]);

        // Update user profile info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('settings')->with('success', 'Settings updated successfully!');
    }

    // Update user password
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate password fields
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('settings')->with('success', 'Password updated successfully!');
    }
}
