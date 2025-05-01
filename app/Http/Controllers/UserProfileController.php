<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function show()
    {
        return view('pages.user-profile');
    }

    // public function update(Request $request)
    // {
    //     $attributes = $request->validate([
    //         'username' => ['required','max:255', 'min:2'],
    //         'email' => ['required', 'email', 'max:255',  Rule::unique('users')->ignore(auth()->user()->id),],
    //         'address' => ['max:100'],
    //         'city' => ['max:100'],
    //         'phone' => ['regex:/^(\+62|62|08)[1-9][0-9]{7,11}$/'],
    //         'password' => ['nullable', 'string', 'min:5', 'max:255'],
    //     ]);

    //     auth()->user()->update([
    //         'username' => $request->get('username'),
    //         'email' => $request->get('email') ,
    //         'address' => $request->get('address'),
    //         'city' => $request->get('city'),
    //         'phone' => $request->get('phone'),
    //     ]);
    //     return back()->with('success', 'Profile succesfully updated');
    // }

        public function update(Request $request)
{
    $user = auth()->user();
    
    // Validate the basic profile information
    $attributes = $request->validate([
        'username' => ['required', 'max:255', 'min:2'],
        'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'address' => ['max:100'],
        'city' => ['max:100'],
        'phone' => ['nullable', 'regex:/^(\+62|62|08)[1-9][0-9]{7,11}$/'],
    ]);
    
    // Update the user profile information
    $user->update([
        'username' => $request->get('username'),
        'email' => $request->get('email'),
        'address' => $request->get('address'),
        'city' => $request->get('city'),
        'phone' => $request->get('phone'),
    ]);
    
    // Check if password change was requested
    if ($request->filled('current_password')) {
        // Validate password fields
        $passwordAttributes = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);
        
        // Update password
        $user->update([
            'password' => Hash::make($request->get('password')),
        ]);
        
        return back()->with('success', 'Profile and password successfully updated');
    }
    
    return back()->with('success', 'Profile successfully updated');
}
}