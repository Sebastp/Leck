<?php

namespace leck\Http\Controllers\algo;

use Illuminate\Http\Request;
use leck\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use leck\Tag;
use leck\Writing_tag;

class searchAlgo extends Controller
{

  public static function tagsByText(Request $request, $limit = 8)
  {
    $tagTitle = strtolower($request->searchTag);
    $tagTitle = trim(preg_replace('/\s+/', ' ', $tagTitle));

    $rules = array(
      'tagTitle' => 'string|max:25',
    );

    $vaildator = Validator::make([
      'tagTitle' => $tagTitle
    ], $rules);

    if ($vaildator->fails()) {
      return response()->json([
        'success' => false,
      ]);
    }

    $simillarTags = Tag::where('title', 'like', '%'.$tagTitle.'%')->select('id', 'title', 'verified as ver')->get();
    foreach ($simillarTags as &$tObj) {
      $tObj->title = ucwords($tObj->title);
      $tObj->linked = Writing_tag::where('tag_id', '=', $tObj->id)->count();
      unset($tObj->id);
    }

    $sortedTags = $simillarTags->sortBy('linked')->toArray();

    $finalArr = array_slice($sortedTags, 0, $limit, true);


    return response()->json([
      'success' => true,
      'forTag' => $tagTitle,
      'resultArray' => $finalArr
    ]);
  }
}
