<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class DashboardLoginController extends Controller
{
    public function index()
    {
        return view('dashboard.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Session::put('admin_id', $admin->id);
            Session::put('admin_logged_in', true);
            Session::put('admin_name', $admin->name);
            Session::put('admin_username', $admin->username);

            return redirect(Session::get('url.intended', '/admin-dashboard'));
        }

        return back()->withErrors([
            'username' => 'Invalid username or password'
        ])->withInput();
    }
}