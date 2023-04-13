<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
            // return $request->user();
        }
    }
}
// class Authenticate// extends Middleware
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \Closure  $next
//      * @return mixed
//      */
//     public function handle(Request $request, Closure $next)
//     {
//         // if (!$request->expectsJson()) {
//         //     return route('login');
//         //     // return $request->user();
//         // }

//         return $next($request);

//         // $isAuthenticated = (\Illuminate\Support\Facades\Auth::check());

//         // //This will be excecuted if the new authentication fails.
//         // if ($isAuthenticated) {

//         //     return $next($request);
//         // }
//         // // if (!$isAuthenticated) {

//         // //     return back()->with('message', 'Authentication Error.');
//         // // }

//         // return $next($request);
//         // // return null;// back();
//     }
// }
