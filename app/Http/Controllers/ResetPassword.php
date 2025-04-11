<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Notifications\ForgotPassword;

class ResetPassword extends Controller
{
    use Notifiable;

    public function show()
    {
        return view('auth.reset-password');
    }

    public function routeNotificationForMail()
    {
        return request()->email;
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->notify(new ForgotPassword($user->id));
            return back()->with('success', 'An email was sent to your email address');
        }

        return back()->withErrors([
            'email' => 'We could not find a user with that email address'
        ]);
    }
}
