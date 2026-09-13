<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if(!session('user_id')){
            return redirect('/login')->with('error','login first');
        }
        if(!in_array(session('role'), $roles)){
            abort(403, 'Unauthorized access');
        }
        return $next($request);
    }
}
