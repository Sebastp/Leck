<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;

use leck\Tag;
use Illuminate\Support\Facades\DB;

class Writing_tag extends Model
{
  protected $fillable = [
      'writing_id', 'tag_id'
  ];

  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = null;


  public static function asignAndRemove($writing_id, $tags_bytext = [], $tags_byid = [])
  {
    $asignedIds = self::getByWritingId($writing_id);
    $idsToAsign = [];
    $insertData = [];

    if (count($tags_bytext) > 6) {
      $tags_bytext = array_slice($tags_bytext, 0, 6, true);
    }

    foreach ($tags_bytext as $tagText) {
      $tagText = strtolower($tagText);
      if (is_string($tagText) && strlen($tagText)>1) {
        if (Tag::where('title', '=', $tagText)->exists()) {
          $tag_id2Asign = Tag::where('title', '=', $tagText)->get()[0]->id;
        }else {
          $tag_id2Asign = Tag::insertGetId(['title' => $tagText]);
        }

        if (!in_array($tag_id2Asign, $asignedIds)) {
          array_push($insertData, array('writing_id'=> $writing_id, 'tag_id'=> $tag_id2Asign));
        }
        array_push($idsToAsign, $tag_id2Asign);
      }
    }


    $tags2remove = array_diff($asignedIds, $idsToAsign);
    if (count($tags2remove) > 0) {
      self::unasignTags($writing_id, $tags2remove);
    }

    return self::insert($insertData);
  }



  public static function getByWritingId($writing_id)
  {
    $tgQury = self::where('writing_id', '=', $writing_id);
    if ($tgQury->exists()) {
      $tgIds = $tgQury->select('tag_id')->get();
      $idsArr = [];
      foreach ($tgIds as $id) {
        array_push($idsArr, $id->tag_id);
      }
      return $idsArr;
    }else {
      return [];
    }
  }



  public static function unasignTags($writing_id, array $ids)
  {
    $tagOccur = self::whereIn('tag_id', $ids)->where('verified', '=', 0)->select('tag_id', DB::raw('COUNT(tag_id) as occur'))->groupBy('tag_id')->orderBy('occur', 'DESC')->get();

    foreach ($tagOccur as $tagObj) {
      if ($tagObj->occur == 1) {
        Tag::where('id', $tagObj->tag_id)->delete();
      }
    }

    self::whereIn('tag_id', $ids)->delete();
  }
}
