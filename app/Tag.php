<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use leck\Writing_tag;

class Tag extends Model
{
  protected $fillable = [
      'id', 'title', 'verified'
  ];

  public $timestamps = false;
  public $incrementing = false;



  public static function getWritingTags($writing_id)
  {
    $tagIds = Writing_tag::getByWritingId($writing_id);

    if (empty($tagIds)) {
      return Null;
    }

    $tagTitles = self::whereIn('id', $tagIds)->select('title')->get();
    $tagTitlesArr = [];
    foreach ($tagTitles as $title) {
      array_push($tagTitlesArr, ucwords($title->title));
    }

    return $tagTitlesArr;
  }

  public static function getWritingLabel($writing_id)
  {
    $tagIds = Writing_tag::getByWritingId($writing_id);
    if (empty($tagIds)) {
      return null;
    }
    $mostPop =  Writing_tag::whereIn('tag_id', $tagIds)->select('tag_id', DB::raw('COUNT(tag_id) as occur'))->groupBy('tag_id')->orderBy('occur', 'DESC')->get();
    foreach ($mostPop as $tagObj) {
      $qury = self::where('id', '=', $tagObj->tag_id)->select('title');
      if ($qury->exists()) {
        $labelTitle = ucwords($qury->get()[0]->title);
        break;
      }
    }
    return $labelTitle;
  }
}
