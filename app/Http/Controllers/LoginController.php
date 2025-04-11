<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

public function login(Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);
    
    $user = \App\Models\User::where('email', $request->email)->first();
    
    if (!$user) {
        return back()->withErrors(['email' => 'Email not found.']);
    }
    
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Incorrect password.']);
    }
    
    // Login tanpa Auth::attempt
    Auth::login($user);
    $request->session()->regenerate();
    return redirect()->intended('dashboard');
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
