<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('admin_logged_in')) {
            Session::put('url.intended', $request->url());

            return redirect('/admin-login')
                ->with('error', 'Please login to access the admin panel.');
        }

        return $next($request);
    }
}