<?php

namespace leck;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use leck\Follow;

use leck\Http\Controllers\fileServe;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'str_id', 'nickname', 'bio', 'avatar', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];




    public static function getAvatarPath($value)
    {
      $filesArr = fileServe::searchByName(storage_path('app/users'), $value);
      if (!empty($value) && count($filesArr)) {
        return Storage::url('users/'.$value.'.jpg');
      }else {
        return Storage::url('def/avatar.jpg');
      }
    }


    public static function getByStr_id($str_id, $selectArr = ['id', 'nickname', 'avatar']){
      if (!in_array('avatar', $selectArr)) {
        array_push($selectArr, 'avatar');
      }
      if (!in_array('id', $selectArr)) {
        array_push($selectArr, 'id');
      }

      $adit_info = [];
      foreach ($selectArr as $select_info) {
        if (!in_array($select_info, ['id', 'nickname', 'bio', 'avatar', 'email'])) {
          array_push($adit_info, $select_info);
          $selectArr = array_diff( $selectArr, [$select_info]);
        }
      }


      $reqObj = User::select($selectArr)->where('str_id', '=', $str_id)->get()[0];
      $reqObj->str_id = $str_id;
      $reqObj->avatar_path = self::getAvatarPath($reqObj->avatar);


      foreach ($adit_info as $ed_value) {
        switch ($ed_value) {
          case 'following':
            if (Auth::check()) {
              $reqObj->u_following = Follow::where([['user_id', '=', Auth::id()], ['followed_id', '=', $reqObj->id]])->exists();
            }else {
              $reqObj->u_following = false;
            }
            break;
          case 'followers':
            $reqObj->followers = self::shortNum(Follow::where('followed_id', '=', $reqObj->id)->count());
            break;
          case 'prof_following':
            $reqObj->following = self::shortNum(Follow::where('user_id', '=', $reqObj->id)->count());
            break;
        }
      }

      return $reqObj;
    }


    public static function getById($user_id, $selectArr = ['id', 'nickname', 'avatar']){
       $reqStr_id = self::select('str_id')->where('id', '=', $user_id)->get()[0]->str_id;

       return User::getByStr_id($reqStr_id, $selectArr);
    }




    public static function avatarUpdate($file_name)
    {
      $oldAv = self::where('id', '=', Auth::user()->id)->select('avatar')->get()[0]->avatar;
      if(!empty($oldAv)) {
        $filesArr = fileServe::searchByName(storage_path('app/users'), $oldAv);
        foreach ($filesArr as $fileId) {
          Storage::delete('users/'.$fileId.'.jpg');
        }
      }

      User::where('id', '=', Auth::user()->id)->update([
        'avatar' => $file_name
      ]);
    }

}
