<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use leck\Split;
use leck\Writing_section;
use leck\SysMsg_writing;

class Inook_progress extends Model
{
  protected $fillable = [
      'user_id', 'writing_id', 'split_id', 'created_at'
  ];

  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = null;



  public static function newProg($writing_id, $split_id)
  {
    $rules = array(
      'split_id' => 'required|integer'
    );


    $vaildator = Validator::make([
    'split_id' => $split_id
    ], $rules);


    $wrSects = Writing_section::getWritingSectionIds($writing_id);

    if ($vaildator->fails() || !Split::where('id', '=', $split_id)->whereIn('section_id', $wrSects)->exists()) {
      return false;
    }else {
      $currSection_id = Split::where('id', '=', $split_id)->select('section_id')->get()[0]->section_id;
      $currSecSplitIds = [];
      foreach (Split::where('section_id', '=', $currSection_id)->select('id')->get() as $splitId) {
        array_push($currSecSplitIds, $splitId->id);
      }




      //check if user has progress from previous possible
      if (!Section::where('id', $currSection_id)->select('first')->get()[0]->first) {
        $posiblePrevious = [];
        foreach (Split::where('next_id', '=', $currSection_id)->select('id')->get() as $splitId) {
          array_push($posiblePrevious, $splitId->id);
        }
        $prevValid = self::where('user_id', '=', Auth::user()->id)->whereIn('split_id', $posiblePrevious)->exists();
      }else {
        //first section
        $prevValid = true;
      }

      //check if user doesn't have any progress on this section
      if (!self::where('user_id', '=', Auth::user()->id)
                           ->whereIn('split_id', $currSecSplitIds)->exists() && $prevValid) {
        self::create([
          'user_id' => Auth::user()->id,
          'writing_id' => $writing_id,
          'split_id' => $split_id,
        ]);
      }else {
        return false;
      }

      return true;
    }
  }


  public static function getLastOfUser($writing_id, $userId)
  {
    $qury = self::where([['writing_id', '=', $writing_id], ['user_id', '=', $userId]]);
    if ($qury->exists()) {
      $ltstSplId = $qury->select('split_id')->orderBy('created_at', 'desc')->get()[0]->split_id;
      if (!Split::where('id', $ltstSplId)->exists()) {
        self::where('split_id', $ltstSplId)->delete();
        return self::getLastOfUser($writing_id, $userId);
      }
      return $ltstSplId;
    }else {
      return false;
    }
  }


  public static function getPrevSection($section_id, $userId)
  {
    if (!Split::where('next_id', '=', $section_id)->exists()) {
      return NULL;
    }
    $splIds = Split::where('next_id', '=', $section_id)->select('id')->get();
    $sIdsArr = [];
    foreach ($splIds as $splId) {
      array_push($sIdsArr, $splId->id);
    }

    if (self::whereIn('split_id', $sIdsArr)->where('user_id', '=', $userId)->exists()) {
      $prevSplitId = self::whereIn('split_id', $sIdsArr)->where('user_id', '=', $userId)->select('split_id')->get()[0]->split_id;
      return Split::where('id', '=', $prevSplitId)->select('section_id')->get()[0]->section_id;
    }else {
      return false;
    }
  }


  public static function countUsersOnPath($writing_id, $split_id)
  {
    $qury = self::where([['writing_id', '=', $writing_id], ['split_id', '=', $split_id]]);
    return $qury->count();
  }



  public static function removePathHistry($writ_id, $split_id){
    self::where([['writing_id', '=', $writ_id], ['split_id', '=', $split_id]]);
    $to_check_Id = [Split::where('id', '=', $split_id)->select('next_id')->get()[0]->next_id];
    $splits_toRmve = [$split_id];

    while(!empty($to_check_Id)) {
      $newToChck = Split::whereIn('section_id', $to_check_Id)->select('id', 'next_id')->get();
      $to_check_Id = [];
      foreach ($newToChck as $newOne) {
        if (!empty($newOne->next_id)) {
          array_push($to_check_Id, $newOne->next_id);
        }
        array_push($splits_toRmve, $newOne->id);
      }
    }

    $uIdsObj = self::where('split_id', '=', $split_id)->select('user_id')->get();
    $usrsToMsg = [];
    foreach ($uIdsObj as $uObj) {
      array_push($usrsToMsg, $uObj->user_id);
    }

    SysMsg_writing::w_pathHistory($writ_id, $usrsToMsg);
    self::whereIn('split_id', $splits_toRmve)->delete();
  }
}
