<?php

namespace leck\Http\Middleware;

use Closure;
use leck\User;

class UserInDb
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
      if ( !User::where('str_id', '=', $request->str_id)->exists() ) {
        return abort(404);
      }

      return $next($request);
    }
}
