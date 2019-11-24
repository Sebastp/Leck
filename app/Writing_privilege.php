<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use leck\User;
use leck\Follow;


class Writing_privilege extends Model
{
    protected $fillable = [
        'user_id', 'writing_id', 'type', 'public', 'created_at'
    ];

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;


    public static function addAuthor($writing_id)
    {
      Writing_privilege::create([
          'user_id' => Auth::user()->id,
          'writing_id' => $writing_id,
          'type' => 'author',
          'public' => 1
      ]);
    }

    public static function isQualified($user_id, $writing_id)
    {
      return Writing_privilege::where([['user_id', '=', $user_id], ['writing_id', '=', $writing_id]])->exists();
    }


    public static function getByAuthor($user_id)
    {
      $quryWhr = Writing_privilege::where([['user_id', '=', $user_id], ['public', '=', 1]]);
      if ($quryWhr->exists()) {
        $wrIdsArr = [];
        foreach ($quryWhr->select('writing_id')->get() as $writing_id) {
          array_push($wrIdsArr, $writing_id->writing_id);
        }
        return $wrIdsArr;
      }else {
        return 0;
      }
    }


    public static function getWritingAuthors($writing_id, $public = 1)
    {
      if ($public) {
        $pbWhr = ['public', '=', 1];
      }else {
        $pbWhr = ['public', '!=', 1];
      }

      $quryWhr = Writing_privilege::where([['writing_id', '=', $writing_id], $pbWhr]);
      if ($quryWhr->exists()) {
        $authorsIds = [];

        foreach ($quryWhr->select('user_id')->orderBy('created_at', 'asc')->get() as $user_id) {
          array_push($authorsIds, $user_id->user_id);
        }

        $quryAuthors = User::whereIn('id', $authorsIds);
        if ($quryAuthors->exists()) {
          $authorsArr = [];
          foreach ($authorsIds as $aId) {
            $usr_obj = User::getById($aId, ['id', 'nickname', 'avatar', 'following']);
            array_push($authorsArr, $usr_obj);
          }


          return $authorsArr;
        }else {
          return NULL;
        }
      }else {
        return NULL;
      }
    }
}
