<?php

namespace leck\Http\Controllers\algo;

use Illuminate\Http\Request;
use leck\Http\Controllers\Controller;

use leck\Tag;
use leck\Writing_tag;
use leck\Writing;
use leck\Http\Controllers\algo\popularAlgo;

class categoriesAlgo extends Controller
{
  public static function getBestIn($writings_limit = 3, $bannedTags = [])
  {
    $verTagObj = Tag::where('verified', '=', 1)->whereNotIn('id', $bannedTags)->select('id', 'title')->inRandomOrder()->limit(1)->get();
    if (!count($verTagObj)) {
      return null;
    }
    $finalObj = $verTagObj[0];
    $verTagId = $finalObj->id;
    $finalObj->title = ucwords($finalObj->title);

    $tagWritings = Writing_tag::where('tag_id', $verTagId)->select('writing_id')->get();
    if (count($tagWritings) < $writings_limit) {
      return self::getBestIn($writings_limit, array_push($bannedTags, $verTagId));
    }
    $allWrtIds = $tagWritings->pluck('writing_id');

    $WPqury = Writing::whereIn('id', $allWrtIds)->where('public', '=', 1);
    if (!$WPqury->exists()) {
      return Null;
    }
    $publicWrtIds = $WPqury->pluck('id');

    $getBestWrits = popularAlgo::mostPopularFrom($publicWrtIds, $writings_limit);
    $writingsArr = [];
    foreach ($getBestWrits as $wr_id) {
      $wrInfo = Writing::getWritingInfo($wr_id, ['title', 'description', 'str_id', 'cover'], ['authors', 'cover', 'published_at']);
      if (!empty($wrInfo)) {
        array_push($writingsArr, $wrInfo);
      }
    }

    $finalObj->writings = $writingsArr;
    return $finalObj;
  }
}
