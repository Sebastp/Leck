<?php

namespace leck\Http\Controllers\algo;

use Illuminate\Http\Request;
use leck\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use leck\Writing_traffic;
use leck\Writing;

class trendingAlgo extends Controller
{

  public static function getAll($date = 1, $limit = null)
  {
    $trend_ids = trendingAlgo::getAll__Ids($date, $limit);
    if (empty($trend_ids)) {
      return $trend_ids;
    }


    $finallArr = [];
    foreach ($trend_ids as $id) {
      $wrInfo = Writing::getWritingInfo($id, ['title', 'description', 'str_id', 'cover'], ['authors', 'cover', 'published_at', 'label']);
      if (!empty($wrInfo)) {
        array_push($finallArr, $wrInfo);
      }
    }
    return $finallArr;
  }




  public static function getAll__Ids($date, $limit)
  {
    $ltstQury = Writing_traffic::where('created_at', '>', Carbon::now()->subDays($date)->toDateTimeString());

    if (!$ltstQury->exists()) {
      if ($date < 27) {
        $newRange = $date*3;
        return trendingAlgo::getAll__Ids($newRange, $limit);
      }else {
        return null;
      }
    }else {
      $trendRes = $ltstQury->select('writing_id', DB::raw('COUNT(writing_id) as occur'))
              ->groupBy('writing_id')
              ->orderBy('occur', 'DESC')
              ->limit($limit)->get();

      $trend_ids = $trendRes->pluck('writing_id')->all();
      if (count($trend_ids) < $limit && $date < 256) {
        $newRange = $date*4;
        return trendingAlgo::getAll__Ids($newRange, $limit);
      }
      return $trend_ids;
    }
  }
}
