<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ChangePassword extends Controller
{
    protected $user;

    public function __construct()
    {
        Auth::logout();

        $id = intval(request()->id);
        $this->user = User::find($id);
    }

    public function show()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:5', 'confirmed']
        ]);

        $existingUser = User::where('email', $attributes['email'])->first();

        if ($existingUser) {
            $existingUser->update([
                'password' => Hash::make($attributes['password'])
            ]);
            return redirect('login')->with('success', 'Password updated successfully. You may now log in.');
        } else {
            return back()->withErrors([
                'email' => 'Your email does not match the email that requested the password change.'
            ]);
        }
    }
}
