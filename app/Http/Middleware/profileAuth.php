<?php

namespace leck\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
use leck\User;

class profileAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next)
     {
       $usr_qury = User::where('str_id', '=', $request->str_id);
       if (Auth::user()->id == $usr_qury->select('id')->get()[0]->id) {
         return $next($request);
       }else {
         return response()->json([
           'success' => false,
           'msg' => 403
         ]);
       }
     }
}
