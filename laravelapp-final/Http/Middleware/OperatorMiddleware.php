<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OperatorMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (! auth()->check()) {

            return redirect('/login');
        }

        if (

            ! auth()->user()->isAdmin()

            &&

            ! auth()->user()->isOperator()

        ) {

            abort(403);
        }

        return $next($request);
    }
}
