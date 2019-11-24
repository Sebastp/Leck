<?php

namespace leck\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use leck\Http\Controllers\followings;
use leck\Http\Controllers\algo\recommendedAlgo;

use leck\User;
use leck\Writing;
use leck\Writing_privilege;


class profileController extends Controller
{
    public static function show($str_id, $more_info = [])
    {
      $usrInfo = User::getByStr_id($str_id, ['id', 'nickname', 'bio', 'following', 'followers', 'prof_following']);
      if (Auth::check() && $usrInfo->id == Auth::user()->id) {
        $usrInfo->auth_profile = true;
      }else {
        $usrInfo->auth_profile = false;
      }

      $recommProfileIds = recommendedAlgo::getProfiles(6);
      $recommProfileArr = [];
      foreach ($recommProfileIds as $recId) {
        $profObj = User::getById($recId, ['id', 'nickname', 'avatar', 'str_id']);
        array_push($recommProfileArr, $profObj);
      }

      $data = array(
        'user' => $usrInfo,
        'u_writings' => Writing::getByUser($usrInfo->id),
        'recom_prof' => $recommProfileArr
      );

      foreach ($more_info as $info) {
        switch ($info) {
          case 'followings':
            $aditData = followings::getUsersFollows($usrInfo->id, $limit = Null, ['bio', 'followers', 'following']);
            $data['usr_followings'] = $aditData;
            $data['show_sect'] = 'followings';
          break;
          case 'auth_stories':
        $aditData = followings::getUsersFollows($usrInfo->id, $limit = Null, ['bio', 'followers', 'following']);
        $data['usr_followings'] = $aditData;
        $data['show_sect'] = 'auth_stories';
          break;
        }
      }

      if (empty($data['show_sect'])) {
        $data['show_sect'] = '';
      }

      if ($usrInfo->auth_profile) {
        // $data['u_writings__private'] = Writing::getUserPrivate($usrInfo->id, null, [0]);
        $data['u_writings__drafts'] = Writing::getUserDrafts($usrInfo->id, null, 'draft');
      }

      return view('layouts.profile')->with($data);
    }


    public static function show_following($str_id)
    {
      return self::show($str_id, ['followings']);
    }

    public static function show_auth_stories($str_id)
    {
      return self::show($str_id, ['auth_stories']);
    }



   public static function ProfileRedirector($profile_str_id, $_2_path)
   {
     $usr_id = User::select('id')->where('str_id', '=', $profile_str_id)->get()[0]->id;

     if (Writing::where('str_id', '=', $_2_path)->exists())
     {
       $writing_id = Writing::where('str_id', '=', $_2_path)->select('id')->get()[0]->id;

       if (Writing_privilege::where([['user_id', '=', $usr_id], ['writing_id', '=', $writing_id],
         ['type', '=', 'author'], ['public', '=', 1]])->exists())
       {
         if (Auth::check()) {
           $usr_id = Auth::user()->id;
         }else {
           $usr_id = null;
         }
         return Writing::show($writing_id);
       }else {
         abort(404);
       }
     }else {
       abort(404);
     }
   }


   public static function edit(Request $request, $profile_str_id)
   {
     if ($request->hasFile('new_avatar')) {
       return fileUpload::profileAvatar($request);
     }
   }
}
