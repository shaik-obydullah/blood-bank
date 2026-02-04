<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodGroup;
use Illuminate\Support\Facades\Auth;

class DonorAuthController extends Controller
{
    /**
     * Show donor login form
     */
    public function showLoginForm()
    {


        $all_blood_groups = BloodGroup::pluck('code')->toArray();
        return view('website.donor.login', compact('all_blood_groups'));
    }

    /**
     * Handle donor login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (
            Auth::guard('donor')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ])
        ) {
            return redirect()->intended('/donor-dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }
}