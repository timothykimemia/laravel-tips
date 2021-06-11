<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Editor
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
        if (!auth()->check() || !$request->user()->isEditor()){
            return redirect('/');
        }
        return $next($request);
    }
}
