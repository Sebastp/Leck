<?php

namespace leck\Http\Controllers\algo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use leck\Http\Controllers\Controller;

use leck\Follow;
use leck\User;
use leck\Writing_traffic;

class popularAlgo extends Controller
{
  public static function getAll_Profiles($limit)
  {
    $pop_ids = Follow::getMostPopular_ids($limit);
    $users_obj = [];

    foreach ($pop_ids as $id) {
      $usrObj = User::getById($id, ['nickname', 'avatar', 'following', 'followers']);

      array_push($users_obj, $usrObj);
    }

    return $users_obj;
  }

  public static function mostPopularFrom($writing_ids, $bestNr = 3)
  {
    $mostPop =  Writing_traffic::whereIn('writing_id', $writing_ids)->select('writing_id', DB::raw('COUNT(writing_id) as occur'))->groupBy('writing_id')->orderBy('occur', 'DESC')->limit($bestNr)->get();
    if (!count($mostPop)) {
      return [];
    }else {
      return $mostPop->pluck('writing_id');
    }
  }
}
