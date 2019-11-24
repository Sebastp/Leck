<?php

namespace leck\Http\Controllers\algo;

use Illuminate\Http\Request;
use leck\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


use leck\Follow;
use leck\Writing;
use leck\Writing_traffic;


class feedAlgo extends Controller
{
  public static function getAll($limit = null, $user_id = null)
  {
    if (empty($user_id)) {
      $user_id = Auth::id();
    }

    $feedIds = Follow::getUserFeed($user_id, $limit);

    if (empty($feedIds)) {
      return $feedIds;
    }

    $finallArr = [];
    foreach ($feedIds as $id) {
      $wrInfo = Writing::getWritingInfo($id, ['title', 'description', 'str_id', 'cover'], ['authors', 'cover', 'published_at', 'visited']);
      if (!empty($wrInfo)) {
        array_push($finallArr, $wrInfo);
      }
    }
    return $finallArr;
  }

  public static function getHistory($user_id = null, $limit = 2, $writingObjs = false)
  {
    if (empty($user_id)) {
      $user_id = Auth::id();
    }
    $writingIds = Writing_traffic::where('user_id', '=', $user_id)->select('writing_id')->limit($limit)->latest()->get()->pluck('writing_id');
    if (!$writingObjs) {
      return $writingIds;
    }else {
      $finallArr = [];
      foreach ($writingIds as $id) {
        $wrInfo = Writing::getWritingInfo($id, ['title', 'description', 'str_id', 'cover'], ['authors', 'cover', 'published_at']);
        if (!empty($wrInfo)) {
          array_push($finallArr, $wrInfo);
        }
      }
      return $finallArr;
    }
  }
}
