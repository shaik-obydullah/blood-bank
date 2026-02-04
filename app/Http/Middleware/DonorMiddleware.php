<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DonorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('donor')->check()) {
            Session::put('url.intended', $request->url());
            
            return redirect('/donor-login')
                ->with('error', 'Please login to access the donor dashboard.');
        }

        return $next($request);
    }
}