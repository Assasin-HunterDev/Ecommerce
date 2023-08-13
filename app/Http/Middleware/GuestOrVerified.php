<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestOrVerified extends \Illuminate\Auth\Middleware\EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle($request, Closure $next, $redirectToRoute = null): Response
    {
        if (!$request->user()) {
            return $next($request);
        }
        return parent::handle($request, $next, $redirectToRoute);
    }
}
