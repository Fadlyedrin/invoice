<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function show()
    {
        return view('pages.user-profile');
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'username' => ['required','max:255', 'min:2'],
            'email' => ['required', 'email', 'max:255',  Rule::unique('users')->ignore(auth()->user()->id),],
            'address' => ['max:100'],
            'city' => ['max:100'],
            'phone' => ['regex:/^(\+62|62|08)[1-9][0-9]{7,11}$/']
        ]);

        auth()->user()->update([
            'username' => $request->get('username'),
            'email' => $request->get('email') ,
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'phone' => $request->get('phone')
        ]);
        return back()->with('succes', 'Profile succesfully updated');
    }
}
