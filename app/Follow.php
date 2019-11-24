<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


use leck\Writing_privilege;


class Follow extends Model
{
  protected $fillable = [
      'user_id', 'followed_id', 'track', 'created_at'
  ];

  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = null;



  public static function createNew($f_id, $track)
  {
    self::create([
        'user_id' => Auth::id(),
        'followed_id' => $f_id,
        'track' => $track
    ]);
  }

  public static function unFollow($f_id)
  {
    self::where([['user_id', '=', Auth::id()], ['followed_id', '=', $f_id]])->delete();
  }


  public static function getMostPopular_ids($limit = null)
  {
    $queryResp = self::select('followed_id')
        ->groupBy('followed_id')
        ->orderByRaw('COUNT(*) DESC')
        ->limit($limit)
        ->get();
    $ids_arr = [];
    foreach ($queryResp as $obj) {
      array_push($ids_arr, $obj->followed_id);
    }
    return $ids_arr;
  }



  public static function getUserFollows($user_id, $limit = Null)
  {
    if (!self::where('user_id', '=', $user_id)->exists()) {
      return [];
    }

    $followedReq = self::where('user_id', '=', $user_id)->select('followed_id')->limit($limit)->get();

    $followedArr = $followedReq->pluck('followed_id')->all();

    return $followedArr;
  }


  public static function getUserFeed($user_id, $limit = null)
  {
    $followIds = self::getUserFollows($user_id);
    if (empty($followIds)) {
      return Null;
    }

    $WPqury = Writing_privilege::whereIn('user_id', $followIds)->where('public', '=', 1);
    if (!$WPqury->exists()) {
      return Null;
    }

    $wrIdsArr = [];
    foreach ($WPqury->select('writing_id')->limit($limit)->get() as $writing_id) {
      array_push($wrIdsArr, $writing_id->writing_id);
    }
    return $wrIdsArr;
  }
}
