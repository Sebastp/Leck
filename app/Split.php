<?php

namespace leck;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


use leck\Writing_section;
use leck\Inook_progress;

class Split extends Model
{
  protected $fillable = [
      'id', 'title', 'position', 'section_id', 'next_id', 'created_at'
  ];


  public $timestamps = false;
  public $incrementing = false;
  protected $primaryKey = null;



  public static function updateInfo($writing_id, $split)
  {
    $split_id = $split->id;
    $split_title = self::sanitizeTitle($split->title);
    $split_next_id = $split->next_id;
    $split_position = $split->position;
    $split_section = self::where('id', '=', $split_id)->select('section_id')->get()[0]->section_id;




    if (empty($split_title)) {
      return response()->json([
        'success' => true,
        // 'msg' => "Title can't be empty if split was used before"
      ]);
    }

    $rules = array(
      'id' => 'integer|exists:splits,id',
      'title' => 'string|nullable|max:160',
      'position' => 'integer|max:6|min:1'
    );



    $vaildator = Validator::make([
    'id' => $split_id, 'title' => $split_title, 'position' => $split_position
    ], $rules);



    $updateArr = [
          'title' => $split_title,
          'position' => $split_position
        ];


    if (!empty($split_next_id)) {
      $rules['next_id'] = $split_next_id;
      $updateArr['next_id'] = $split_next_id;
    }

    if ($vaildator->fails()) {
      return response()->json([
        'success' => false,
        'msg' => $vaildator->errors()->all()
      ]);
    }

    if (self::where([['id', '!=', $split_id], ['position', '=', $split_position], ['section_id', '=', $split_section]])->exists()) {
      self::where([['position', '>=', $split_position], ['section_id', '=', $split_section]])->increment('position');
    }


    $currNext_id = self::where('id', '=', $split_id)->select('next_id')->get()[0]->next_id;
    if ($currNext_id != $split_next_id) {
      Inook_progress::removePathHistry($writing_id, $split_id);
    }

    self::where('id', '=', $split_id)->update($updateArr);

    return response()->json([
      'success' => true
    ]);
  }



  public static function CreateNew($split)
  {
    $split_position = $split->position;
    $split_section = $split->section_id;

    if (self::where([['position', '=', $split_position], ['section_id', '=', $split_section]])->exists() || Split::where('section_id', '=', $split_section)->count() > 5) {
      return response()->json([
        'success' => false
      ]);
    }

    $new_id = self::uid(9, 'id');


    $rules = array(
      'id' => 'required|integer',
      'position' => 'integer|max:6|min:1',
      'section_id' => 'required|integer'
    );


    $vaildator = Validator::make([
    'id' => $new_id, 'position' => $split_position, 'section_id' => $split_section
    ], $rules);


    if ($vaildator->fails()) {
      return response()->json([
        'success' => false,
      ]);
    }



    self::create([
      'id' => $new_id,
      'position' => $split_position,
      'section_id' => $split_section,
    ]);


    return response()->json([
      'success' => true,
      'new_id' => $new_id
    ]);

  }


  public static function removeSplit($writ_id, $split_id){

    if (!Writing_section::where([['writing_id', $writ_id], ['section_id', $split_id]])->exists()) {
      $splitqury = Split::where('id', '=', $split_id);

      if ($splitqury->exists()) {
        Inook_progress::removePathHistry($writ_id, $split_id);

        $splitpos = $splitqury->select('position')->get()[0]->position;
        self::where('position', '>', $splitpos)->decrement('position');
        $splitqury->delete();
      }
      return response()->json([
        'success' => true
      ]);
    }else {
      return response()->json([
        'success' => false
      ]);
    }
  }





  public static function getBySectionId($ids, $withNoPath = false)
  {
    if (!is_array($ids)) {
      $sec_ids = array($ids);
    }else {
      $sec_ids = $ids;
    }

    $lblArr = ['A', 'B', 'C', 'D', 'E', 'F'];


    $splitInSecQury = self::whereIn('section_id', $sec_ids);
    if ($withNoPath) {
      $splitInSecQury = $splitInSecQury->where([['next_id', '!=', NULL], ['title', '!=', NULL], ['title', '!=', '']]);
    }

    if (!$splitInSecQury->exists()) {
      return NULL;
    }

    $splitqury = $splitInSecQury->select('id', 'title', 'position', 'section_id', 'next_id')->orderBy('position', 'asc')->limit(6)->get();
    foreach ($splitqury as $index => $splitObj) {
      if (!empty($splitObj->next_id)) {
        $splitObj->next_title = Section::where('id', '=', $splitObj->next_id)->select('title')->get()[0]->title;
      }
      $splitObj->label = $lblArr[$index];
    }
    return $splitqury;
  }




  public static function sanitizeTitle($tstring)
  {
    str_replace(['{', '}'], '', $tstring);
    $tstring = preg_replace('/\s+/', ' ', $tstring);
    $posOfBrake = strpos($tstring, '<br>');
    $stipedTitle = strip_tags($tstring);
    if ($posOfBrake !== false && substr($stipedTitle,$posOfBrake) != '') {
      $firstLine = trim(substr($stipedTitle,0,$posOfBrake));
      $secLine = trim(substr($stipedTitle,$posOfBrake));
      return $firstLine.'<br>'.$secLine;
    }else {
      return $stipedTitle;
    }
  }
}
