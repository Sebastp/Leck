<?php

namespace leck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


use leck\Follow;
use leck\User;

class followings extends Controller
{
    public static function followUnfollow(Request $request)
    {
      $foled = $request->followed;
      $track = parse_url($request->header('referer'))['path'];

      if (User::where('id', '=', $foled)->exists()){
        $rules = array(
          'id' => 'required|string'
        );

        $vaildator = Validator::make([
          'id' => $foled
        ], $rules);


        if ($vaildator->fails() || Auth::id() == $foled) {
          return response()->json([
            'success' => false
          ]);
        }else {
          if (Follow::where([['user_id', '=', Auth::id()], ['followed_id', '=', $foled]])->exists()) {
            Follow::unFollow($foled, $track);
          }else {
            Follow::createNew($foled, $track);
          }
          return response()->json([
            'success' => true
          ]);
        }
      }else {
        return response()->json([
          'success' => false
        ]);
      }
    }

    public static function getUsersFollows($user_id, $limit = Null, $more_info = [])
    {
      $followIds = Follow::getUserFollows($user_id, $limit);
      $basic_info = ['nickname', 'str_id', 'avatar'];

      $users_obj = [];
      foreach ($followIds as $id) {
        $usrObj = User::getById($id, array_merge($basic_info, $more_info));
        array_push($users_obj, $usrObj);
      }

      return $users_obj;
    }

}
