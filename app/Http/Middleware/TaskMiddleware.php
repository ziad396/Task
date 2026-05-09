<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Task;
use App\Models\User;
class TaskMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
  
       
        if (!Auth::user()) {
            # code...
            return response()->json([
                'message'=>'user not authentication'
            ]);
        }
        


        // if () {
        //     # code...
        // }
        return $next($request);
    }
}
