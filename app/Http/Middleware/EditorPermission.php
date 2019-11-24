<?php

namespace leck\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
use leck\Writing_privilege;
use leck\Writing;
class EditorPermission
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
      if (!Writing::where('id', '=', $request->writing_id)->exists()) {
        return response()->json([
          'success' => false,
          'msg' => "ERROR 404: page doesn't exist"
        ]);
      }else{
        if ( Writing_privilege::isQualified(Auth::user()->id, $request->writing_id) ) {
          return $next($request);
        }else {
          return response()->json([
            'success' => false,
            'msg' => 'Access Denyed'
          ]);
        }
      }
    }
}
