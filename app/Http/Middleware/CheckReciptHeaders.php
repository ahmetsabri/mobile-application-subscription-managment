<?php

namespace App\Http\Middleware;

use App\Exceptions\UnHandledRequestException;
use Closure;
use Illuminate\Http\Request;

class CheckReciptHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->header('username') || !$request->header('password')) {
            throw new UnHandledRequestException('Missing username or password in header');
        }
        return $next($request);
    }
}
