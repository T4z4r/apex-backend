<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $tenant = Auth::user()->tenant;

            if ($tenant && !$tenant->hasActiveSubscription()) {
                // Redirect to subscription page or show error
                return redirect()->route('subscription.expired')->with('error', 'Your subscription has expired. Please renew to continue.');
            }
        }

        return $next($request);
    }
}
