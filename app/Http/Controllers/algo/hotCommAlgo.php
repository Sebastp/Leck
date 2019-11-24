<?php

namespace leck\Http\Controllers\algo;

use Illuminate\Http\Request;
use leck\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use leck\Writing_comment;
use leck\Writing;

class hotCommAlgo extends Controller
{

  public static function getAll($date = 1, $limit = null)
  {
    $trend_ids = self::getAll__Ids($date, $limit);
    if (empty($trend_ids)) {
      return $trend_ids;
    }


    $finallArr = [];
    foreach ($trend_ids as $id) {
      $wrInfo = Writing::getWritingInfo($id, ['title', 'str_id', 'cover', 'description'], ['authors', 'cover', 'published_at', 'label']);
      if (!empty($wrInfo)) {
        array_push($finallArr, $wrInfo);
      }
    }

    return $finallArr;
  }




  public static function getAll__Ids($date, $limit)
  {
    $ltstQury = Writing_comment::where('created_at', '>', Carbon::now()->subDays($date)->toDateTimeString());

    if (!$ltstQury->exists()) {
      if ($date < 360) {
        $newRange = $date*4;
        return self::getAll__Ids($newRange, $limit);
      }else {
        return null;
      }
    }else {
      if ($ltstQury->count() < $limit && $date < 30) {
        $newRange = $date*4;
        return self::getAll__Ids($newRange, $limit);
      }
      $trendRes = $ltstQury->select('writing_id', DB::raw('COUNT(writing_id) as occur'))
              ->groupBy('writing_id')
              ->orderBy('occur', 'DESC')
              ->limit($limit)->get();

      $trend_ids = [];
      foreach ($trendRes as $trend) {
        array_push($trend_ids, $trend->writing_id);
      }
      return $trend_ids;
    }
  }
}
